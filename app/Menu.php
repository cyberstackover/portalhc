<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Menu extends Model
{
    protected $guarded = [];

    public function roles()
    {
    	return $this->belongsToMany(Role::class,'role_menu','menu_id','role_id');
    }
}
