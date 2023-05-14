<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use Auth;
use Validator;

class AddressController extends Controller
{
    //
    public function add(Request $request){
        // return Address::all();
        $input = $request->all();
        $validation = Validator::make($input,[
            'area' => 'required',
            'name' => 'required',
            'flat' => 'required',
            'street' => 'required',
            'type' => 'required',
        ]);

        if($validation->fails()){
            return response()->json(['error'=> $validation->errors()->all()],422);
        }
        $user = Auth::guard('api')->user();
        // New Address add before all addresses default value is 0
        Address::where('user_id',$user->id)->update(['default'=>0]);
        $address = new Address;
        $address->area = $request->area;
        $address->name = $request->name;
        $address->flat = $request->flat;
        $address->street = $request->street;
        $address->type = $request->type;
        $address->user_id = $user->id;
        $address->default = '1';
        $address->save();
        return response()->json(['success'=>true,'address'=>$address]); 
    }

    public function list(){
        $user = Auth::guard('api')->user();
        $addresses = Address::where('user_id',$user->id)->get();
        return response()->json(['success'=>true,'addresses'=>$addresses]); 

    }
    
    public function update($id,Request $request){

            $user = Auth::guard('api')->user();
            $address = Address::find($id);
            if($address){
                if($user->id == $address->user_id){
                    if($request->area){
                        $address->area = $request->area;
                    }
                    if($request->name){
                        $address->name = $request->name;
                    }
                    if($request->flat){
                        $address->flat = $request->flat;
                    }
                    if($request->street){
                        $address->street = $request->street;
                    }
                    if($request->type){
                        $address->type = $request->type;
                    }
                    if($request->default == 1){
                        Address::where('user_id',$user->id)->update(['default'=>0]);
                        $address->default = $request->default;
                    }
                    $address->save();
                    return response()->json(['success'=>true,'address'=>$address]); 
                }else {
                    return response()->json(['success'=>false]); 
                }
            }
        return response()->json(['success'=>false]); 
    }

}
