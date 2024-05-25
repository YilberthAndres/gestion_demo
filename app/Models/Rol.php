<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name', 'created_at', 'updated_at'
    ];

    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'modulos_roles', 'rol_id', 'modulo_id');
    }
}
