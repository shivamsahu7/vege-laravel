<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\Admin;

class AuthController extends Controller
{
    //
    public function login(Request $request){
        $input = $request->all();
        $validation = Validator::make($input,[
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validation->fails()){
            return response()->json(['error'=> $validation->errors()->all()],422);
        }

        $admin = Admin::where('email',$request->email)->first();
        if($admin){
            if(Hash::check($request->password, $admin->password)){
                $token = $admin->createToken('My Token')->accessToken;
                return response()->json(['success'=>true,'token'=>$token]); 
            }else{
                return response()->json(['success'=>false]);  
            }
        }else{
            return response()->json(['success'=>false]); 
        }
        
    }
}
