<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\{
    User, Persona
};


class UserController extends Controller
{

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:6',
                'nombre' => 'required',
                'apellido' => 'required',
            ]);
            
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(),400);
            }

            $data = $request->json()->all();
            $nombre = $data['nombre'];
            $apellido = $data['apellido'];
            $email = $data['email'];

            $persona = Persona::create([
                "nombre" => $nombre,
                "apellido" => $apellido,
                "email" => $email,
            ]);
            
            
            $user = User::create(array_merge(
                $validator->validate(),
                ['password' => bcrypt($request->password)],
                ['persona_id' => $persona->id],
            ));
    
            return response()->json([
                'message' => '¡Usuario registrado exitosamente!',
                // 'user' => $user
            ], 201);
        }catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
    

    
}
