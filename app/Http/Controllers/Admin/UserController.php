<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash,Auth,Validator,DB,Mail};
use App\Models\{
    User, Persona, Maestra
};


class UserController extends Controller
{

    protected function rules_create()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'estado_id' => 'required|integer',
            'rol_id' => 'required|integer',
            'persona.identificacion' => 'required|integer',
            'persona.lugarexpedicion' => 'nullable|string',
            'persona.fechaexpedicion' => 'nullable|date',
            'persona.direccion' => 'required|string',
            'persona.telefono' => 'nullable|integer',
            'persona.telefonomovil' => 'required|integer',
            'persona.telefonowhatsapp' => 'required|integer',
            'persona.email' => 'required|email',
            'persona.sendemail' => 'required|in:0,1',
            'persona.fechanacimiento' => 'nullable|date',
            'persona.nombre' => 'required|string|max:50',
            'persona.segundonombre' => 'nullable|string|max:50',
            'persona.apellido' => 'required|string|max:50',
            'persona.segundoapellido' => 'nullable|string|max:50',
            'persona.foto' => 'nullable|string',
            'persona.pais_id' => 'nullable|integer',
            'persona.departamento_id' => 'nullable|integer',
            'persona.ciudad_id' => 'nullable|integer',
            'persona.zona_id' => 'nullable|integer',
            'persona.barrio' => 'required|string',
            'persona.tipoidentificacion_id' => 'required|integer',
            'persona.sexo_id' => 'required|integer',
            'persona.ocupacion_id' => 'nullable|integer',
            'persona.observaciones' => 'nullable|string',
        ];
    }

    public function register(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate($this->rules_create());

            $persona = Persona::create($validatedData['persona']);

            $user = User::create(array_merge(
                $validatedData,
                ['password' => bcrypt($validatedData['password'])],
                ['persona_id' => $persona->id],
                ['estado_id' => $validatedData['estado_id']],
            ));

            $user->assignRole($validatedData['rol_id']);

            DB::commit();

            return response()->json([
                'message' => '¡Usuario registrado exitosamente!',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function me()
    {
        return response()->json(auth()->user()->load('persona'));
    }
    

    public function edit($id)
    {
        $tipo_identificaciones  = Maestra::where('padre', 8)->get(['id', 'codigo', 'nombre']);
        $sexos                  = Maestra::where('padre', 20)->get(['id', 'codigo', 'nombre']);
        $estados_civiles        = Maestra::where('padre', 24)->get(['id', 'codigo', 'nombre']);
        $estados                = Maestra::where('padre', 595)->get(['id', 'codigo', 'nombre']);
        $user                   = auth()->user()->load('persona');

        $data = [
            'sexos' => $sexos,
            'tipo_identificaciones' => $tipo_identificaciones,
            'estados_civiles' => $estados_civiles,
            'estados' => $estados,
            'user' => $user,
        ];
        
        return response()->json($data);
    }


    protected function rules_update()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'estado_id' => 'required|integer',
            'rol_id' => 'required|integer',
            'persona.id' => 'required|integer',
            'persona.identificacion' => 'required|integer',
            'persona.lugarexpedicion' => 'nullable|string',
            'persona.fechaexpedicion' => 'nullable|date',
            'persona.direccion' => 'required|string',
            'persona.telefono' => 'nullable|integer',
            'persona.telefonomovil' => 'required|integer',
            'persona.telefonowhatsapp' => 'required|integer',
            'persona.email' => 'required|email',
            'persona.sendemail' => 'required|in:0,1',
            'persona.fechanacimiento' => 'nullable|date',
            'persona.nombre' => 'required|string|max:50',
            'persona.segundonombre' => 'nullable|string|max:50',
            'persona.apellido' => 'required|string|max:50',
            'persona.segundoapellido' => 'nullable|string|max:50',
            'persona.foto' => 'nullable|string',
            'persona.pais_id' => 'nullable|integer',
            'persona.departamento_id' => 'nullable|integer',
            'persona.ciudad_id' => 'nullable|integer',
            'persona.zona_id' => 'nullable|integer',
            'persona.barrio' => 'required|string',
            'persona.tipoidentificacion_id' => 'required|integer',
            'persona.sexo_id' => 'required|integer',
            'persona.ocupacion_id' => 'nullable|integer',
            'persona.observaciones' => 'nullable|string',
        ];
    }

    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();
    
            $validatedData = $request->validate($this->rules_update());
        
            $user = User::find($id);
            $persona = Persona::find($user->persona->id);
            $persona->update($validatedData['persona']);

            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($validatedData['rol_id']);
        
            DB::commit();
        
            return response()->json([
                'message' => 'Datos actualizados correctamente'
            ], 200);
        
        } catch (ValidationException $e) {
            DB::rollback();
        
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        
        } catch (\Throwable $th) {
            DB::rollback();
        
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    
}
