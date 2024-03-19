<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::all();
        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $rules = [
            'content' => 'required|string',
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
        $comment = new Comment($request->input());
        $comment->save();
        return response()->json([
            'status' => true,
            'message' => 'Comentario GUARDADO correctamente' 
        ],200);
    }

    public function show(Comment $comment)
    {
        return response()->json(['status' => true, 'data' => $comment]);
    }

    public function update(Request $request, Comment $comment)
    {
        $rules = [
            'content' => 'required|string',
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
        $comment->update($request->input());
        return response()->json([
            'status' => true,
            'message' => 'Comentario ACTUALIZADO correctamente'
        ],200);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json([
            'status' => true,
            'message' => 'Comentario BORRADO correctamente'
        ],200);
    }
}
