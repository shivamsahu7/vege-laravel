<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderSubProduct;
use Auth;

class StripeController extends Controller
{
    //
    public function paymentForm(){
        // $user = Auth::user();
        // return view('stripe.form',compact('user'));
        return view('stripe.form');
    }

    public function payment(Request $request){
        $input = $request->all();
        $user = User::find($request->user_id);

        \Stripe\Stripe::setApiKey('sk_test_51LSgBYD0POSZFc8lXrwiW8MnVOxLgZ6QOOhE8G4WmwwsAdkYfc0z2auoactJQTsX5OllYonaSdWQvjKx9MWK3eS800IJD3PcfH');

        $charge = \Stripe\Charge::create([
          'source' => $_POST['stripeToken'],
          'description' => "10 cucumbers from Roger's Farm",
          'amount' => $user->cart->total_amount*100,
          'currency' => 'usd'
        ]);
        // dd($charge);
        if($charge->status == "succeeded"){
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
                        $order->stripe_id = $charge->id;
                        $order->stripe_status = $charge->status;
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
                                // product move cart table to order table
                                $cartSubProduct->delete();
                        }
                        }
                        $user->cart->amount = 0;
                        $user->cart->total_amount = 0;
                        $user->cart->quantity = 0;
                        $user->cart->delivery_charge = 0;
                        $user->cart->save();
                        // $user->cartSubProducts->delete();
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
        }else{
            return response()->json(["success"=>false]);
        }
    }
}
