<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Validator;
use Auth;

class CouponController extends Controller
{
    //
    public function add(Request $request){
        $input = $request->all();
        $validation = Validator::make($input,[
            'type' => 'required',
            'name' => 'required',
            'discount_type' => 'required',
            'discount_value' => 'required',
            'min_amount' => 'required',
            'max_discount' => 'required',
            'code' => 'required',
            'term_condition' => 'required',
            'image' => 'required',
        ]);
        if($validation->fails()){
            return response()->json(['error'=> $validation->errors()->all()],422);
        }
        $imageName = time().'.'.$request->image->extension();  
        $request->image->move(public_path('coupon_images'), $imageName);
        $coupon = new Coupon;
        $coupon->image = "coupon_images/".$imageName;
        $coupon->type = $request->type;
        $coupon->discount_type = $request->discount_type;
        $coupon->discount_value = $request->discount_value;
        $coupon->name = $request->name;
        $coupon->min_amount = $request->min_amount;
        $coupon->max_discount = $request->max_discount;
        $coupon->code = $request->code;
        $coupon->term_condition = $request->term_condition;
        $coupon->save();
        if($coupon){
            return response()->json(['success'=>true,'coupon'=>$coupon]); 
        }else{
            return response()->json(['success'=>false]); 
        }
    }

    public function list(){
        $coupons = Coupon::all();
        if($coupons){
            return response()->json(['success'=>true,'coupons'=>$coupons]); 
        }else{
            return response()->json(['success'=>false]); 
        }
    }

    public function edit($id,Request $request){
        $coupon = Coupon::find($id);
        if($coupon){
            if($request->image){
                $imageName = time().'.'.$request->image->extension();  
                $request->image->move(public_path('coupon_images'), $imageName);
                $coupon->image = "coupon_images/".$imageName;
            }
            if($request->type){
                $coupon->type = $request->type;
            }
            if($request->discount_type){
                $coupon->discount_type = $request->discount_type;
            }
            if($request->discount_value){
                $coupon->discount_value = $request->discount_value;
            }
            if($request->name){
                $coupon->name = $request->name;
            }
            if($request->min_amount){
                $coupon->min_amount = $request->min_amount;
            }
            if($request->max_discount){
                $coupon->max_discount = $request->max_discount;
            }
            if($request->code){
                $coupon->code = $request->code;
            }
            if($request->term_condition){
                $coupon->term_condition = $request->term_condition;
            }
            $coupon->save();
            return response()->json(['success'=>true,'coupon'=>$coupon]); 
        }else{
            return response()->json(['success'=>false]); 
        }
    }

    public function delete($id){
        $coupon = Coupon::find($id);
        if($coupon){
            $coupon->delete();
            return response()->json(['success'=>true]); 
        }else{
            return response()->json(['success'=>false]); 
        }
    }

    
}
