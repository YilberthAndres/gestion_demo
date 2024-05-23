<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maestra extends Model
{
    use SoftDeletes;
    protected $fillable = ['codigo','nombre','padre','jerarquia', 'orden', 'visible', 'observacion','created_by','created_at','updated_by', 'updated_at','deleted_at','deleted_by'];
    

    public function getSexos()
    {
        $sql = "SELECT * FROM `maestras` WHERE padre = '20'";

        return DB::select($sql);
    }
}