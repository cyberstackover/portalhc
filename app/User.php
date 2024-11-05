<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\GeneralModel;
use App\Menu;
use App\Bumn;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'is_external', 'id_bumn', 'kategori_user_id', 'asal_instansi', 'activated', 'is_pejabat', 'id_assessment'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getmenuaccess()
    {
        try{
            return Menu::whereHas('roles', function($query){
                $query->whereIn('id', explode(',', $this->roles()->get()->implode('id', ',')));
            })->where('status', (bool)true)->orderBy('order')->get();
        }catch(Exception $e){
            return collect([]);
        }
    }

    public function hasBumn()
    {
        return $this->belongsTo('App\Perusahaan', 'id_bumn');
    }

    /*public function bumns()
    {
        return $this->belongsToMany(Bumn::class,'users_bumn','id_users','id_bumns');
    }*/
}
