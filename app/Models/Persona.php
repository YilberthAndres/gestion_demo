<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'nombre',
        'apellido',
        'email'
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