<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\User;
use App\Models\Cart;
use App\Models\UserOtp;

class AuthController extends Controller
{
    //
    public function login(Request $request){
        $input = $request->all();
        $validation = Validator::make($input,[
            'number' => 'required|numeric|digits:10'
            // 'email' => 'required|email',
            // 'password' => 'required'
        ]);
        if($validation->fails()){
            return response()->json(['error'=> $validation->errors()->all()],422);
        }

        // if(Auth::attempt(['email'=>$input['email'],'password'=>$input['password']])){
        //     $user = Auth::user();
        //     $token = $user->createToken('MyApp')->accessToken;
        //     return response()->json(['token'=>$token]);
        // }
        $user = User::where('number',$request->number)->first();

        if($user){
            // $token = $user->createToken('MyApp')->accessToken;
            // return response()->json(['token'=>$token]);
        }else{
            $user = new User();
            $user->number  = $request->number;
            $user->save();            
            $cart = new Cart;
            $cart->id = $user->id;
            $cart->user_id = $user->id;
            $cart->amount = 0;
            $cart->total_amount = 0;
            $cart->save();
            // $token = $user->createToken('MyApp')->accessToken;
            // return response()->json(['token'=>$token]);
        }
        $userOtp = new UserOtp;
        $userOtp->user_id = $user->id;
        $userOtp->type = "type";
        $userOtp->otp = 123456;
        $userOtp->save();
        return response([
            'success'=>true
        ]);
    }

    public function otpVerify(Request $request){
        $input = $request->all();
        $validation = Validator::make($input,[
            'number' => 'required|exists:users,number',
            'otp' => 'required'
        ]);
        if($validation->fails()){
            return response()->json(['error'=> $validation->errors()->all()],422);
        }
        $user = User::where('number',$request->number)->first();
        
        if($user->userOtp->otp == $request->otp){
            $token = $user->createToken('MyApp')->accessToken;
            // $user->userOtp->delete();
            return response()->json(['success'=>true,'token'=>$token]); 
        }
        return response()->json(['success'=>false]); 
    }

    public function logout(){
        $user = Auth::user()->token()->revoke();
        if($user){
            return response([
                'success'=>true
            ]);
        }
    }
}
