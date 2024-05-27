<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Modulo,
    ModuloRol
};
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class ModuloController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:list-module', ['only' => ['index']]);
        $this->middleware('permission:add-module', ['only' => ['create','store']]);
        $this->middleware('permission:update-module', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-module', ['only' => ['destroy']]);
    }

    // Listar todos los modulos con hijos y los campos especificados
    public function index(Request $request)
    {
        try {
            $modulos = Modulo::allModulos();
            
            return response()->json($modulos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Mostrar un módulo y sus hijos
    public function find($id)
    {
        try {
            $modulo = Modulo::findModulos($id);
            
            return response()->json($modulo);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Listar todos los módulos con sus hijos
    public function find_rol(Request $request, $id)
    {
        try{
            $modulos = Modulo::findRol($id);
        
            return response()->json($modulos);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create(Request $request)
    {
        $roles = Role::select('id', 'name')->orderBy('name')->get();
        $modulos = Modulo::select('id', 'name')->orderBy('name')->get();

        return response()->json([
            'roles' => $roles, 
            'modulos' => $modulos
        ]);
    }

    // Crear un nuevo módulo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'path' => 'nullable|string|max:191',
            'icon' => 'nullable|string|max:191',
            'parent_id' => 'nullable|integer|exists:modulos,id',
            'order' => 'nullable|integer',
            'roles' => 'required',
        ]);

        $user_id = Auth::id();
        $validated['created_by_id'] = $user_id;

        $modulo = Modulo::create($validated);

        $modulo_id = $modulo->id;

        foreach ($validated['roles'] as $key => $rol_id) {
            ModuloRol::create(['modulo_id' => $modulo_id , 'rol_id' => $rol_id, 'created_by_id' => $user_id]);
        }

        return response()->json(['message' => "success"]);
    }


    public function edit(Request $request, $id)
    {
        $modulo = Modulo::findModulos($id);
        $roles = Role::select('id', 'name')->orderBy('name')->get();
        $modulos = Modulo::select('id', 'name')->orderBy('name')->get();


        return response()->json([
            'modulo' => $modulo,
            'modulos' => $modulos,
            'roles' => $roles,
        ]);
    }


    // Actualizar un módulo
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:191',
            'path' => 'nullable|string|max:191',
            'icon' => 'nullable|string|max:191',
            'parent_id' => 'nullable|integer|exists:modulos,id',
            'order' => 'nullable|integer',
            'roles' => 'required',
        ]);

        $user_id = Auth::id();
        $validated['update_by_id'] = $user_id;

        $modulo = Modulo::findOrFail($id);
        $modulo->update($validated);
        $modulo_id = $modulo->id;

        foreach ($validated['roles'] as $key => $rol_id) {
            $modulo = Modulo::findOrFail($rol_id);
            $modulo->update(['parent_id' => $modulo_id]);
        }

        return response()->json(['message' => "success"]);
    }

    // Eliminar un módulo
    public function destroy($id)
    {
        $modulo = Modulo::findOrFail($id);
        $modulo->delete();

        return response()->json(null, 204);
    }
}
