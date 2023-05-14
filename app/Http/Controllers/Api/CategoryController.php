<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;

class CategoryController extends Controller
{
    //
    public function category(){
        $categories = Category::all();
        return response()->json(['success'=>true,'catgories'=>$categories]);
    }

    public function subCategoryList($slug){
        $category = Category::where('slug',$slug)->first();
        if($category){
            $subCategory = SubCategory::where('category_id',$category->id)->select('id','category_id','name','slug')->get();
            return response()->json(['success'=>true,'sub_catgories'=>$subCategory]);
        }else{
            return response()->json(['success'=>false,'message'=>"Category not exist"]); 
        }
        // return SubCategory::
    }
}
