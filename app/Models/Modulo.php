<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modulo extends Model
{
    use SoftDeletes;

    protected $table = 'modulos';

    protected $fillable = [
        'name',
        'path',
        'icon',
        'children',
        'order',
        'created_by_id',
        'updated_by_id',
        'deleted_by'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
