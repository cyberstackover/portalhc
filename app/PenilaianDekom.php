<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenilaianDekom extends Model
{
    protected $table = 'penilaian_dekom';

    protected $guarded = [
        'id'
    ];

    public function talenta()
    {
    	return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
