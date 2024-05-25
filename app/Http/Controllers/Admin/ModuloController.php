<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Modulo
};
use Spatie\Permission\Models\Role;

class ModuloController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:list-module', ['only' => ['index']]);
        $this->middleware('permission:add-module', ['only' => ['create','store']]);
        $this->middleware('permission:update-module', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-module', ['only' => ['destroy']]);
    }

    // Listar todos los módulos con sus hijos
    public function index(Request $request)
    {
        $rolId = $request->query('rol_id', 1); 

        $modulos = Modulo::with(['children' => function($query) use ($rolId) {
            $query->whereHas('roles', function($query) use ($rolId) {
                $query->where('rol_id', $rolId);
            })->orderBy('order');
            $query->with(['children' => function($subQuery) use ($rolId) {
                $subQuery->whereHas('roles', function($subQuery) use ($rolId) {
                    $subQuery->where('rol_id', $rolId);
                })->orderBy('order');
            }]);
        }])
        ->whereNull('children')
        ->whereHas('roles', function($query) use ($rolId) {
            $query->where('rol_id', $rolId);
        })
        ->orderBy('order')
        ->select(['id', 'name', 'path', 'icon', 'order']) 
        ->get();
    

        return response()->json($modulos);
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
            'children' => 'nullable|integer|exists:modulos,id',
            'order' => 'nullable|integer',
            'roles' => 'required',
        ]);

        $modulo = Modulo::create($validated);

        return response()->json($modulo, 201);
    }

    // Mostrar un módulo específico
    public function show($id)
    {
        $modulo = Modulo::with('children')->findOrFail($id);

        return response()->json($modulo);
    }

    // Actualizar un módulo
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:191',
            'path' => 'nullable|string|max:191',
            'icon' => 'nullable|string|max:191',
            'children' => 'nullable|integer|exists:modulos,id',
            'order' => 'nullable|integer',
        ]);

        $modulo = Modulo::findOrFail($id);
        $modulo->update($validated);

        return response()->json($modulo);
    }

    // Eliminar un módulo
    public function destroy($id)
    {
        $modulo = Modulo::findOrFail($id);
        $modulo->delete();

        return response()->json(null, 204);
    }
}
