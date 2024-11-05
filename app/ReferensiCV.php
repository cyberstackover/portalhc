<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferensiCV extends Model
{
    protected $table = 'referensi_cv';

    protected $fillable = [
        'id_talenta',
        'nama',
        'perusahaan',
        'jabatan',
        'nomor_handphone'
    ];

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
