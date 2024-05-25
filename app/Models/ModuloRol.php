<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuloRol extends Model
{
    protected $table = 'modulos_roles';

    protected $fillable = [
        'modulo_id', 'rol_id', 'created_at', 'updated_at'
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
}
