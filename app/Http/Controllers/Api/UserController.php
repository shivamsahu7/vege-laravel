<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;

class UserController extends Controller
{
    //
    public function userDetail(){
        $user = Auth::guard('api')->user();
        return response()->json(['success'=>true,'user'=>$user]);
    }

    // public function userDetailUpdate(Request $request){
    //     $user = Auth::guard('api')->user();
    //     if($request->name){
    //         $user->name = $request->name;
    //     }
    //     return response()->json(['success'=>true,'user'=>$user]);
    // }
}
