<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\Cart;
use App\Models\CartSubProduct;
use App\Models\SubProduct;
use App\Models\DeliveryCharge;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    //
    public function add(Request $request){
        $input = $request->all();
        $validation = Validator::make($input,[
            'sub_product_id' => 'required',
            'quantity' => 'required',
        ]);

        if($validation->fails()){
            return response()->json(['error'=> $validation->errors()->all()],422);
        }
        $user = Auth::guard('api')->user();

        $cart = $user->cart;
        // check product exist
        $subproduct = SubProduct::find($request->sub_product_id);

        // check product exist in cart
        if($subproduct){
            $cartSubProduct = CartSubProduct::where('user_id',$user->id)->where('sub_product_id',$subproduct->id)->first();
            
            if(!$cartSubProduct){
                $cartSubProduct = new CartSubProduct;
                $cartSubProduct->user_id = $user->id;
                $cartSubProduct->cart_id = $user->cart->id;
                $cartSubProduct->sub_product_id = $subproduct->id;
                $cartSubProduct->product_id = $subproduct->product_id;
                $cartSubProduct->quantity = $request->quantity;
                $cartSubProduct->save();
            }else{
                // Sub Product remove from Cart
                if($request->quantity == 0){
                    $cartSubProduct->delete();
                }else{
                    // sub product quantity update
                    $cartSubProduct->quantity = $request->quantity;
                    $cartSubProduct->save();
                }
            }
        }
        // get delivery charge
        $delivery_charge = new DeliveryCharge;

        // Total Cart value recalculate
        $cart->coupon_code = null;
        $cart->amount = $user->amount();
        $cart->total_amount = $user->amount()+(int)$delivery_charge->first()->charge;
        // $cart->quantity = $user->productCount();
        $cart->delivery_charge = (int)$delivery_charge->first()->charge;
        $cart->quantity = $user->cartSubProducts->sum('quantity');
        $cart->save();
        return response()->json(['success'=>true,'cart'=>$cart]);
    }

    public function detail(){
        $user = Auth::guard('api')->user();
        $cart = $user->cart;
        $cartSubProduct = CartSubProduct::where('cart_id',$cart->id)->with('subProduct.product')->get();
        return response()->json(['success'=>true,'cart'=>$cart,'cart_sub_products'=>$cartSubProduct]);
    }

    public function afterLoginAdd(Request $request){
        $user = Auth::guard('api')->user();
        foreach($request->sub_product_ids as $sub_product){
            $subProduct = SubProduct::where('id',$sub_product['sub_product_id'])->first();
            $cartSubProduct = CartSubProduct::where('user_id',$user->id)->where('sub_product_id',$subProduct->id)->first();
            if($cartSubProduct){
                $cartSubProduct->quantity = $sub_product['quantity'];
                $cartSubProduct->save();$cartSubProduct->save();
            }else{
                if($subProduct){
                    $cartSubProduct = new CartSubProduct;
                    $cartSubProduct->user_id = $user->id;
                    $cartSubProduct->cart_id = $user->cart->id;
                    $cartSubProduct->sub_product_id = $subProduct->id;
                    $cartSubProduct->product_id = $subProduct->product_id;
                    $cartSubProduct->quantity = $sub_product['quantity'];
                    $cartSubProduct->save();
                }
            }
        }
        $cart = $user->cart;
        // get delivery charge
        $delivery_charge = new DeliveryCharge;

        // Total Cart value recalculate
        $cart->coupon_code = null;
        $cart->amount = $user->amount();
        $cart->total_amount = $user->amount()+(int)$delivery_charge->first()->charge;
        // $cart->quantity = $user->productCount();
        $cart->delivery_charge = (int)$delivery_charge->first()->charge;
        $cart->quantity = $user->cartSubProducts->sum('quantity');
        $cart->save();
        return response()->json(['success'=>true,'cart'=>$cart]);
    }
}
