<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatKesehatan extends Model
{
    protected $table = 'riwayat_kesehatan';

    protected $fillable = [
        'id_talenta',
        'nilai_kesehatan',
        'tahun_kesehatan',
        'instansi_kesehatan',
    ];

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }

    public function skalasehat()
    {
        return $this->belongsTo('App\SkalaKesehatan', 'nilai_kesehatan');
    }
}
