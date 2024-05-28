<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmSede extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'em_sedes';

    protected $fillable = [
        'nombre',
        'web',
        'direccion',
        'email',
        'telefono',
        'prestador',
        'nom_representante',
        'email_representante',
        'tel_representante',
        'representante_id',
        'logo',
        'deleted_by',
        'created_by_id',
        'estado_id',
        'updated_by_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function estado()
    {
        return $this->belongsTo(Maestra::class, 'estado_id');
    }
}
