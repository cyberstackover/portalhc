<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatLhkpn extends Model
{
    protected $table = 'riwayat_lhkpn';

    protected $fillable = [
        'id_talenta',
        'tahun',
        'jml_kekayaan_rp',
        'jml_kekayaan_usd',
        'file_name',
        'tgl_pelaporan',
        'user'
    ];

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
