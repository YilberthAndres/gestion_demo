<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Modulo extends Model
{
    protected $table = 'modulos';

    protected $fillable = [
        'name', 'path', 'icon', 'order', 'parent_id', 'created_by_id', 'updated_by_id'
    ];

    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'modulos_roles', 'modulo_id', 'rol_id');
    }

    public function children()
    {
        return $this->hasMany(Modulo::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Modulo::class, 'parent_id');
    }


    public static function allModulos()
    {
        return static::with(['children' => function ($query) {
            $query->select('id', 'name', 'path', 'icon', 'order', 'parent_id')
                ->with(['children' => function ($query) {
                    $query->select('id', 'name', 'path', 'icon', 'order', 'parent_id')
                        ->with(['children' => function ($query) {
                            $query->select('id', 'name', 'path', 'icon', 'order', 'parent_id');
                        }]);
                }]);
        }])->select('id', 'name', 'path', 'icon', 'order', 'parent_id')->get();
    }

    public static function findModulos($id = null)
    {
        return static::with(['children' => function($query) {
            $query->select('id', 'name', 'path', 'icon', 'order', 'parent_id')
                  ->with(['children' => function($query) {
                      $query->select('id', 'name', 'path', 'icon', 'order', 'parent_id')
                            ->with(['children' => function($query) {
                                $query->select('id', 'name', 'path', 'icon', 'order', 'parent_id');
                            }]);
                  }]);
        }])
        ->select('id', 'name', 'path', 'icon', 'order', 'parent_id')
        ->find($id);
    }
    
    public static function findRol($id = null)
    {
        return static::with(['children' => function($query) use ($id) {
            $query->whereHas('roles', function($query) use ($id) {
                $query->where('rol_id', $id);
            })->orderBy('order');
            $query->with(['children' => function($subQuery) use ($id) {
                $subQuery->whereHas('roles', function($subQuery) use ($id) {
                    $subQuery->where('rol_id', $id);
                })->orderBy('order');
            }]);
        }])
        ->whereNull('parent_id')
        ->whereHas('roles', function($query) use ($id) {
            $query->where('rol_id', $id);
        })
        ->orderBy('order')
        ->select(['id', 'name', 'path', 'icon', 'order']) 
        ->get();
    }
}

