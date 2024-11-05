<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penghasilan extends Model
{
    protected $table = 'penghasilan';

    protected $guarded = [
        'id'
    ];

    public function talenta()
    {
    	return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
