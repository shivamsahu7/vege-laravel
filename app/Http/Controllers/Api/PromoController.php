<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promo;

class PromoController extends Controller
{
    //
    public function list(){
        $promos = Promo::all();
        return response()->json(['success'=>true,'promos'=>$promos]);
    }
}
