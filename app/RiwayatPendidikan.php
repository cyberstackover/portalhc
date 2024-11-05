<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatPendidikan extends Model
{
    protected $table = 'riwayat_pendidikan';

    protected $fillable = [
        'id_talenta',
        'id_jenjang_pendidikan',
        'perguruan_tinggi',
        'id_kota',
        'kota',
        'negara',
        'penghargaan',
        'tahun',
        'penjurusan',
        'id_universitas'
    ];

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }

    public function jenjangPendidikan()
    {
        return $this->belongsTo('App\JenjangPendidikan', 'id_jenjang_pendidikan');
    }

    public function refKota()
    {
        return $this->belongsTo('App\Kota', 'id_kota');
    }

}
