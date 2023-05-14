<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderSubProduct;
use App\Models\Cart;
use App\Models\CartSubProduct;
use Auth;



class OrderController extends Controller
{
    //
    public function order(){
        $user = Auth::user();
        // Check user amount for order
        if($user->cart->amount >= 1){
            // check user product count
            if($user->cartSubProducts->count() >= 1){
                // check user addresses
                if($user->addresses->count()){
                    $transaction_id = 998877665544332211;
                    // Transaction successfully after Order
                    $order = new Order();
                    $order->user_id = $user->id;
                    $order->order_status = "success";
                    $order->amount = $user->cart->amount;
                    $order->delivery_charge = $user->cart->delivery_charge;
                    $order->coupon_code = $user->cart->coupon_code;
                    $order->quantity = $user->cart->quantity;
                    $order->total_amount = $user->cart->total_amount;
                    $order->transaction_id = $transaction_id;
                    $order->discount_amount = $user->cart->discount_amount ;
                    $order->save();
                    if($order){
                       foreach($user->cartSubProducts as $cartSubProduct){
                            $orderSubProduct = new OrderSubProduct;
                            $orderSubProduct->user_id = $user->id;
                            $orderSubProduct->order_id = $order->id;
                            $orderSubProduct->sub_product_id =  $cartSubProduct->sub_product_id;
                            $orderSubProduct->product_id =  $cartSubProduct->product_id;
                            $orderSubProduct->quantity =  $cartSubProduct->quantity;
                            $orderSubProduct->save();
                       }
                    }
                    $user->cart->amount = 0;
                    $user->cart->total_amount = 0;
                    $user->cart->quantity = 0;
                    $user->cart->delivery_charge = 0;
                    $user->cart->save();
                    $user->cartSubProducts->delete();
                    return response()->json(['success'=>true,'message'=>"order Successfully"]); 
                }else{
                    return response()->json(['success'=>false,'message'=>"please add address"]); 
                }
            }else{
                return response()->json(['success'=>false,'message'=>"please add product in cart"]); 
            }
        }else{
            return response()->json(['success'=>false,'message'=>"Amount value is greater then 1 rupees"]); 
        }

    }
}
