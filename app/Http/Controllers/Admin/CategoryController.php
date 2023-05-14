<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //
    public function add(Request $request){
        $input = $request->all();
        $validation = Validator::make($input,[
            'image' => 'required',
            'name' => 'required',
            'slug' => 'required',
        ]);
        if($validation->fails()){
            return response()->json(['error'=> $validation->errors()->all()],422);
        }
        $imageName = time().'.'.$request->image->extension();  
        $request->image->move(public_path('category_images'), $imageName);
        $category = new Category;
        $category->image = "category_images/".$imageName;
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->save();
        return response()->json(['success'=>true,'category'=>$category]); 
    }
    public function list(){
        $categories =  Category::paginate(10);
        return response()->json(['success'=>true,'categories'=>$categories]); 
    }
    public function edit($id, Request $request){
        $category = Category::find($id);
        if($category){
            if($request->name){
                $category->name = $request->name;
            }
            if($request->slug){
                $category->slug = $request->slug;
            }
            if($request->image){
                $imageName = time().'.'.$request->image->extension();  
                $request->image->move(public_path('category_images'), $imageName);
                $category->image = "category_images/".$imageName;
                $category->image = $request->image;
            }
            $category->save();
            return response()->json(['success'=>true,'category'=>$category]); 
        }else{
            return response()->json(['success'=>false]); 
        }
    }
    public function delete($id){
        $category = Category::find($id);
        if($category){
            $category->delete();
            return response()->json(['success'=>true]); 
        }else{
            return response()->json(['success'=>false]); 
        }
    }
}
