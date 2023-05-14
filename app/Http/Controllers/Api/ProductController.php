<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\SubProduct;

class ProductController extends Controller
{
    //
    public function productList($slug,$number){
        $subCategory = SubCategory::where('slug',$slug)->first();
        if($subCategory){
            $product = Product::first();

            $product = Product::where('sub_category_id',$subCategory->id)->with('subProducts','productImages')->select('id','sub_category_id','name','slug','image')->paginate($number);

            return response()->json(['success'=>true,'products'=>$product]);
        }else{
            return response()->json(['success'=>false,'message'=>"product does not exist"]); 
        }
    }

    public function productSearch($search){

        $products = Product::where('slug', 'REGEXP', $search)->with('subProducts','productImages')->get();

        return response()->json(['success'=>true,'products'=>$products]);
    }
}
