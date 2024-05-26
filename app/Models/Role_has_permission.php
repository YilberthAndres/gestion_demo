<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role_has_permission extends Model
{
    protected $table = 'Role_has_permissions';

    protected $fillable = [
        'permission_id', 'role_id'
    ];
    

   
}
