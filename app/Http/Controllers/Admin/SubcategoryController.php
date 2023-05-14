<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;

class SubcategoryController extends Controller
{
    //
    public function add(Request $request){
        $input = $request->all();
        $validation = Validator::make($input,[
            'category_id' => 'required',
            'name' => 'required',
            'slug' => 'required',
        ]);
        if($validation->fails()){
            return response()->json(['error'=> $validation->errors()->all()],422);
        }
        $subcategory = new SubCategory;
        $subcategory->category_id = $request->category_id;
        $subcategory->name = $request->name;
        $subcategory->slug = $request->slug;
        $subcategory->save();
        if($subcategory){
            return response()->json(['success'=>true,'subcategory'=>$subcategory]); 
        }else{
            return response()->json(['success'=>false]);
        }
    }

    public function list(){
        $subcategories =  SubCategory::paginate(10);
        return response()->json(['success'=>true,'subcategories'=>$subcategories]); 
    }

    
}
