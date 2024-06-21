<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Track;
use App\Models\User;
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
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }
        $like = new Like($request->input());
        $like->save();
        return response()->json([
            'status' => true,
            'message' => 'Like GUARDADO correctamente',
            'data' => $like
        ], 200);
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
        ], 200);
    }

    public function hasLike($user_id, $track_id)
    {
        //Bucar el like
        $like = Like::where('user_id', $user_id)->where('track_id', $track_id)->first();

        //Si hay like
        if($like){
            return response()->json([
                'hasLike' => true,
                'like' => $like
            ]);
        }
        
        //Si no hay like
        return response()->json(['hasLike' => $like !== null]);
    }

    public function videosWithLikesByUser($user_id)
    {
        // Obtener el usuario
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Obtener los tracks que tienen likes del usuario
        $tracks = Track::whereHas('likes', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->get();

        // Devolver la respuesta
        return response()->json(['tracks' => $tracks]);
    }
}
