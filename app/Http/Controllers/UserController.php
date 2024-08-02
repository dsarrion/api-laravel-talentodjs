<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
        Log::info('Solicitud de carga de imagen', ['files' => $request->file(), 'request' => $request->all()]);

        // Verificar si se recibió el archivo
        if (!$request->hasFile('avatar')) {
            Log::error('No se recibió ningún archivo');
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'No se recibió ninguna imagen',
            ], 400);
        }

        //Recoger datos
        $imagen = $request->file('avatar');

        // Loguear detalles del archivo recibido
        if ($imagen) {
            Log::info('Archivo recibido', [
                'name' => $imagen->getClientOriginalName(),
                'type' => $imagen->getClientMimeType(),
                'size' => $imagen->getSize(),
            ]);
        } else {
            Log::error('El archivo no se recibió correctamente', ['request' => $request->all()]);
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'El archivo no se recibió correctamente',
            ], 400);
        }

        //Validar imagen
        $validate = Validator::make($request->all(), [
            'avatar' => 'image|mimes:jpg,jpeg,png,gif|max:5120'
        ]);

        //Si falla
        if ($validate->fails()) {  
            Log::error('Error de validación', [
                'errors' => $validate->errors()
            ]);  
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir la imagen',
                'errors' => $validate->errors()
            ], 400);
        }  

        // Obtener el nombre de la imagen actual del usuario
        $user = $request->user();
        $currentImageName = $user->avatar;

        // Si hay una imagen actual, eliminarla
        if ($currentImageName && Storage::disk('images')->exists($currentImageName)) {
            Storage::disk('images')->delete($currentImageName);
        }

        $image_name = $imagen->hashName();

        //Guardar imagen
        Storage::disk('images')->put($image_name, File::get($imagen));
        
        // Actualizar el nombre de la imagen del usuario en la base de datos
        $user->avatar = $image_name;
        $user->save();

        $data = array(
            'code' => 200,
            'status' => 'success',
            'image' => $image_name
        );

        Log::info('Imagen subida correctamente', [
            'image' => $image_name
        ]);

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
