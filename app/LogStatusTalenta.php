<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogStatusTalenta extends Model
{
    protected $table = 'log_status_talenta';

    protected $fillable = [
        'id_user', 'id_talenta', 'id_status_talenta'
    ];

    public function statusTalenta()
    {
        return $this->belongsTo('App\StatusTalenta', 'id_status_talenta');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'id_user');
    }
}
