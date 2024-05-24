<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'identificacion',
        'lugarexpedicion',
        'fechaexpedicion',
        'direccion',
        'telefono',
        'telefonomovil',
        'telefonowhatsapp',
        'email',
        'sendemail',
        'fechanacimiento',
        'nombre',
        'segundonombre',
        'apellido',
        'segundoapellido',
        'foto',
        'pais_id',
        'departamento_id',
        'ciudad_id',
        'zona_id',
        'barrio',
        'tipoidentificacion_id',
        'sexo_id',
        'ocupacion_id',
        'observaciones'
    ];
    

    static $rules = [
		'nombre' => 'required',
		'apellido' => 'required',
		'sexo_id' => 'required',
    ];
    

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->segundonombre} {$this->apellido} {$this->segundoapellido}";
    }

    public function getNombresAttribute()
    {
        return "{$this->nombre} {$this->segundonombre}";
    }

    public function getApellidosAttribute()
    {
        return "{$this->apellido} {$this->segundoapellido}";
    }
    
    public function user()
    {
        return $this->hasOne('App\Models\User', 'persona_id', 'id');
    }


    

}