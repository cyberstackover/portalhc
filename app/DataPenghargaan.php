<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataPenghargaan extends Model
{
    protected $table = 'data_penghargaan';

    protected $fillable = [
        'id_talenta',
        'jenis_penghargaan',
        'tingkat',
        'pemberi_penghargaan',
        'tahun',
    ];

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }

}
