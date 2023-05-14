<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\Coupon;
use App\Models\CouponProduct;
use App\Models\DeliveryCharge;

class CouponController extends Controller
{
    //
    public function list(){
        $coupons = Coupon::all();
        return response()->json(['success'=>true,'coupons'=>$coupons]);
    }
    public function apply(Request $request){
        $input = $request->all();
        $validation = Validator::make($input,[
            'code' => 'required|exists:coupons,code',
        ]);

        if($validation->fails()){
            return response()->json(['error'=> $validation->errors()->all()],422);
        }
        // get delivery charge
        $delivery_charge = new DeliveryCharge;
        
        $user = Auth::guard('api')->user();

        $coupon = Coupon::where('code',$request->code)->first();
        // cart price is greater than coupon amount
        if($user->cart->amount >= $coupon->min_amount){
            if($coupon->type->value == "all"){
                // coupon discount types 
                if($coupon->discount_type->value == "percent"){
                    $discount_value = ($user->cart->amount*$coupon->discount_value)/100;
                    $discount_value = (int)$discount_value;
                    if($discount_value >= $coupon->max_discount){
                        $discount_value = $coupon->max_discount;
                    }
                    $user->cart->total_amount = $user->cart->amount-$discount_value+(int)$delivery_charge->first()->charge;
                    $user->cart->coupon_code = $coupon->code;
                    $user->cart->discount_amount = $discount_value;
                    $user->cart->save();
                }elseif($coupon->discount_type == "fix"){
                    $user->cart->total_amount = $user->cart->amount - $coupon->discount_value+(int)$delivery_charge->first()->charge;
                    $user->cart->coupon_code = $coupon->code;
                    $user->cart->discount_amount = $coupon->discount_value;
                    $user->cart->save();
                }
            }elseif($coupon->type->value == "product"){
                $coupon_product_ids = $coupon->couponProducts->pluck('product_id');
                $coupon_product_ids_array = json_decode($coupon_product_ids, true);

                $cart_product_ids = $user->cartSubProducts->pluck('product_id');
                $cart_product_ids_array = json_decode($cart_product_ids, true);

                $result = array_intersect($coupon_product_ids_array, $cart_product_ids_array);
                
                if(!empty($result)){
                    // coupon discount type
                    if($coupon->discount_type->value == "percent"){
                        $discount_value = ($user->cart->amount*$coupon->discount_value)/100;
                        $discount_value = (int)$discount_value;
                        if($discount_value >= $coupon->max_discount){
                            $discount_value = $coupon->max_discount;
                        }
                        $user->cart->total_amount = $user->cart->amount-$discount_value+(int)$delivery_charge->first()->charge;
                        $user->cart->coupon_code = $coupon->code;
                        $user->cart->discount_amount = $discount_value;
                        $user->cart->save();
                    }elseif($coupon->discount_type == "fix"){
                        $user->cart->total_amount = $user->cart->amount - $coupon->discount_value+(int)$delivery_charge->first()->charge;
                        $user->cart->coupon_code = $coupon->code;
                        $user->cart->discount_amount = $coupon->discount_value;
                        $user->cart->save();
                    }
                }
            }
            return response()->json(['success'=>true,'cart'=>$user->cart]);
        }else{
            return response()->json(['success'=>false,'error' => 'Total Cart value is not greater than Coupon Minimum Amount Value'],422);
        }
    }

    public function remove(){
        // get delivery charge
        $delivery_charge = new DeliveryCharge;
        $user = Auth::guard('api')->user();
        $user->cart->coupon_code = null;
        $user->cart->total_amount = $user->cart->amount+(int)$delivery_charge->first()->charge;
        $user->cart->discount_amount = null;
        $user->cart->save();
        return response()->json(['success'=>true,'cart'=>$user->cart]);
    }

}
