<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{
    Rol
};
class Modulo extends Model
{
    protected $table = 'modulos';

    protected $fillable = [
        'name', 'path', 'icon', 'children', 'order', 
        'created_at', 'updated_at', 'deleted_at', 
        'deleted_by', 'created_by_id', 'updated_by_id'
    ];

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'modulos_roles', 'modulo_id', 'rol_id');
    }

    public function children()
    {
        return $this->hasMany(Modulo::class, 'children');
    }

    public function parent()
    {
        return $this->belongsTo(Modulo::class, 'children');
    }
}

