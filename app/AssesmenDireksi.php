<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssesmenDireksi extends Model
{
    protected $table = 'assesmen_direksi';

    protected $guarded = [
        'id'
    ];

    public function talenta()
    {
    	return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
