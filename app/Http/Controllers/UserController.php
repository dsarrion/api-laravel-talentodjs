<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function detail($id){
        $user = User::find($id);

        if(is_object($user)){
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user
            );
        }else{
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no existe'
            );
        }
        return response()->json($data, $data['code']);
    }

    public function uploadAvatar(Request $request)
    {
        //Recoger datos
        $imagen = $request->file('avatar');

        //Validar imagen
        $validate = Validator::make($request->all(), [
            'avatar' => 'image|mimes:jpg,jpeg,png,gif'
        ]);

        //Si falla
        if (!$imagen || $validate->fails()) {

            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir la imagen'
            );
        } else {  //Si va todo bien
            $image_name = $imagen->hashName();

            //Guardar imagen
            Storage::disk('images')->put($image_name, File::get($imagen));

            $data = array(
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            );
        }

        return response()->json($data, $data['code']);
    }

    public function getImage($filename)
    {
        $isset = Storage::disk('images')->exists($filename);
        if ($isset) {
            $imagen = Storage::disk('images')->get($filename);
            return new Response($imagen, 200);
        }else{
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'La imagen no existe'
            );
        }
        return response()->json($data, $data['code']);
    }
}
