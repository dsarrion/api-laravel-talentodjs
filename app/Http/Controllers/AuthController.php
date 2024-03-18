<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
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

        if($validate->fails()){   //Si falla la validación
            return response()->json([
                'status' => false,
                'message' => 'El usuario no se ha creado',
                'errors' => $validate->errors()->all()
            ],400);
        }    
            
        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'nick' => $request->nick,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'ROLE_USER',
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Usuario CREADO correctamente',
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ],200);

        //Guardar el usuario
        $user->save();
    }

    public function login(Request $request){

        //Reglas de logueo
        $rules = [
            'email' => 'required|email',
            'password' => 'required|string|min:8'
        ];

        // Validar datos
        $validate = Validator::make($request->input(), $rules);

        if($validate->fails()){   //Si falla la validación
            return response()->json([
                'status' => false,
                'errors' => $validate->errors()->all()
            ],400);
        }
        if(!Auth::attempt($request->only('email','password'))){
            return response()->json([
                'status' => false,
                'errors' => ['No autorizado']
            ],401);
        }
        //Sacar info de usuario cuando coincida con el correo
        $user = User::where('email',$request->email)->first();
        return response()->json([
            'status' => true,
            'message' => 'Usuario LOGUEADO correctamente',
            'data' => $user,
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ],200);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Sesion CERRADA correctamente',
        ],200);
    }
}
