<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //REGISTRO USUARIO
    public function create(Request $request)
    {
        //Reglas de validación
        $rules = [
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:200',
            'nick' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8'
        ];

        // Validar datos
        $validate = Validator::make($request->input(), $rules);

        if ($validate->fails()) {   //Si falla la validación
            return response()->json([
                'status' => false,
                'message' => 'El usuario no se ha creado',
                'errors' => $validate->errors()->all()
            ], 400);
        }

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'nick' => $request->nick,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => 'picture-profile-icon.jpg',
            'role' => 'ROLE_USER',
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Usuario CREADO correctamente',
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);

        //Guardar el usuario
        $user->save();
    }

    public function update(Request $request){
        $user = User::find(Auth::user()->id);
    
        //Reglas de validación
        $rules = [
            'name' => 'required|alpha|max:100',
            'surname' => 'required|string|max:200',
            'nick' => 'required|string|max:100',
            'email' => 'required|unique:users,email,'.$user->id.'|email',
            'avatar' => 'nullable|string|max:255'
        ];

        // Validar datos
        $validate = Validator::make($request->all(), $rules);

        //Si falla la validación
        if ($validate->fails()) {   
            return response()->json([
                'status' => false,
                'message' => 'El usuario NO se ha actualizado',
                'errors' => $validate->errors()->all()
            ], 400);
        }

        // Actualizamos usuario
        $user->fill([
            $user->name = $request->has('name') ? $request->get('name') : $user->name,
            $user->surname = $request->has('surname') ? $request->get('surname') : $user->surname,
            $user->nick = $request->has('nick') ? $request->get('nick') : $user->nick,
            $user->email = $request->has('email') ? $request->get('email') : $user->email,
            $user->avatar = $request->has('avatar') ? $request->get('avatar') : $user->avatar
        ])->save();
        
        return response()->json([
            'status' => true,
            'message' => 'Usuario ACTUALIZADO correctamente',
            'user' => $user->only(['id', 'name', 'surname', 'nick', 'email', 'avatar']),
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }

    //lOGIN USUARIO
    public function login(Request $request)
    {

        // Definir reglas de validación
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ];

        // Validar datos
        $validator = Validator::make($request->all(), $rules);

        // Verificar si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        // Verificar si el usuario con el correo electrónico proporcionado existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'errors' => ['Correo electrónico no encontrado']
            ], 401);
        }

        // Intentar autenticar al usuario
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'status' => false,
                'errors' => ['Contraseña incorrecta']
            ], 401);
        }

        //Sacar info de usuario cuando coincida con el correo
        $user = User::where('email', $request->email)->first();
        
        // Autenticación exitosa
        return response()->json([
            'status' => true,
            'message' => 'Usuario LOGUEADO correctamente',
            'data' => $user,
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }

}
