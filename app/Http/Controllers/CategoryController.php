<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $rules = ['name' => 'required|string|min:1|max:100'];
        $validator = Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ],400);
        }
        $category = new Category($request->input());
        $category->save();
        return response()->json([
            'status' => true,
            'message' =>'Categoria CREADA correctamente'
        ],200);
    }

    public function show(Category $category)
    {
        return response()->json(['status' => true, 'data' => $category]);    
    }

    public function update(Request $request, Category $category)
    {
        $rules = ['name' => 'required|string|min:1|max:100'];
        $validator = Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ],400);
        }
        $category->update($request->input());
        return response()->json([
            'status' => true,
            'message' =>'Categoria ACTUALIZADA correctamente'
        ],200);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'status' => true,
            'message' =>'Categoria BORRADA correctamente'
        ],200);
    }
}
