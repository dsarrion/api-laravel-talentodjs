<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    public function index()
    {
        $like = Like::all();
        return response()->json($like);
    }

    public function store(Request $request)
    {
        $rules = [
            'user_id' => 'required|numeric',
            'track_id' => 'required|numeric'
        ];
        $validator = Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ],400);
        }
        $like = new Like($request->input());
        $like->save();
        return response()->json([
            'status' => true,
            'message' => 'Like GUARDADO correctamente'
        ],200);
    }

    public function show(Like $like)
    {
        return response()->json(['status' => true, 'data' => $like]);
    }

    public function destroy(Like $like)
    {
        $like->delete();
        return response()->json([
            'status' => true,
            'message' => 'Like BORRADO correctamente'
        ],200);
    }
}
