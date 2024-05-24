<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeederTablaPermisos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Definir los permisos
        $permisos = [
            // Roles
            'list-rol',
            'add-rol',
            'update-rol',
            'delete-rol',
            // User
            'list_rol_user',
            'add_rol_user',
            'update_rol_user',
            'delete_rol_user',
        ];

        // Crear los permisos
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Crear un rol y asignarle todos los permisos
        $rol = Role::firstOrCreate(['name' => 'admin']);
        $rol->syncPermissions(Permission::all());
    }
}
