<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//agregamos
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;


class RolController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:list-rol', ['only' => ['index']]);
        $this->middleware('permission:list-rol', ['only' => ['find']]);
        $this->middleware('permission:add-rol', ['only' => ['create','store']]);
        $this->middleware('permission:update-rol', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-rol', ['only' => ['destroy']]);
    }

    // Funcion para listar todos los roles 
    public function index(Request $request)
    {
        try {
            $roles = Role::with('permissions')->get();
            return response()->json($roles);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Funcion para buscar un rol por id
    public function find($id)
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);
            return response()->json($role);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Funcion para traer los datos necesarios para crear un rol
    public function create()
    {
        try {
            $permission = Permission::select('id', 'name')->orderBy('name')->get();
            return response()->json($permission);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 500);
        }
    }


    // Funcion para crear un rol
    public function store(Request $request)
    {
        // Validar los datos recibidos
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:roles,name',
                'permission' => 'required|array',
            ]);

        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        }

        try {
            // Decodificar el JSON y obtener los datos
            $data = $request->json()->all();

            // Acceder a las variables
            $name = $data['name'];
            $permission = $data['permission'];

            // Crear el rol y asignar los permisos
            $role = Role::create(['name' => $name]);
            $role->syncPermissions($permission);

            return response()->json(['message' => 'Exitoso']);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 500);
        }
    }


    // Funcion para traer un rol y los datos necesairos para actualizarlo
    public function edit($id)
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);
            $permission = Permission::select('id', 'name')->orderBy('name')->get();
        
            return response()->json([
                'role' => $role,
                'permission' => $permission
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 500);
        }
    }

    // Funcion para actualizar un rol
    public function update(Request $request, $id)
    {
        try{
            $this->validate($request, [
                'name' => 'required',
                'permission' => 'required',
            ]);

            $data = $request->json()->all();
            $name = $data['name'];
            $permission = $data['permission'];
        
            $role = Role::find($id);
            $role->name = $name;
            $role->save();
        
            $role->syncPermissions($permission);
        
            return response()->json(['message' => 'Exitoso']);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 500);
        }
                        
    }

    // Funcion para eliminar un rol
    public function destroy($id)
    {
        // DB::table("roles")->where('id',$id)->delete();
        // return response()->json(['message' => 'Exitoso']);             
    }
}