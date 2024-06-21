<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrackController extends Controller
{
    public function index()
    {
        $tracks = Track::all();
        return response()->json($tracks);
    }
    
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|min:1|max:255',
            'dj' => 'string|max:100',
            'description' => 'string',
            'url' => 'string|max:255',
            'category_id' => 'required|numeric',
            'likes' => 0
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }
        $track = new Track($request->input());
        $track->save();
        return response()->json([
            'status' => true,
            'message' => 'Track CREADO correctamente'
        ], 200);
    }

    public function show(Track $track)
    {
        return response()->json(['status' => true, 'data' => $track]);
    }

    public function update(Request $request, Track $track)
    {
        $rules = [
            'title' => 'required|string|min:1|max:255',
            'dj' => 'string|max:100',
            'description' => 'string',
            'url' => 'string|max:255',
            'category_id' => 'required|numeric'
        ];
        $validator = Validator::make($request->input(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }
        $track->update($request->input());
        return response()->json([
            'status' => true,
            'message' => 'Track ACTUALIZADO correctamente'
        ], 200);
    }

    public function destroy(Track $track)
    {
        $track->delete();
        return response()->json([
            'status' => true,
            'message' => 'Track BORRADO correctamente'
        ], 200);
    }

    public function getByCategory($categoryId)
    {
        $tracks = Track::select('tracks.*', 'categories.name as category')
            ->join('categories', 'categories.id', '=', 'tracks.category_id')
            ->where('tracks.category_id', $categoryId)
            ->paginate(5);

        return response()->json($tracks);
    }
}
