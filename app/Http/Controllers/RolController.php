<?php

namespace App\Http\Controllers;

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
        $this->middleware('permission:add-rol', ['only' => ['create','store']]);
        $this->middleware('permission:update-rol', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-rol', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        
        $roles = Role::select('id', 'name')->orderBy('name')->get();
        return response()->json($roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::select('id', 'name')->orderBy('name')->get();
        return response()->json($permission);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
    {
        // Validar los datos
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:roles,name',
                'permission' => 'required|array',
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        }
        
        // Decodificar el JSON y obtener los datos
        $data = $request->json()->all();

        // Acceder a las variables
        $name = $data['name'];
        $permission = $data['permission'];

        // Crear el rol y asignar los permisos
        $role = Role::create(['name' => $name]);
        $role->syncPermissions($permission);

        return response()->json(['message' => 'Exitoso']);  
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id, ['id', 'name']);
        $permission = Permission::select('id', 'name')->orderBy('name')->get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        return response()->json([
            'role' => $role,
            'permission' => $permission,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
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
                        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return response()->json(['message' => 'Exitoso']);
                        
    }
}