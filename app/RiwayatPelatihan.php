<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatPelatihan extends Model
{
    protected $table = 'riwayat_pelatihan';

    protected $fillable = [
        'id_talenta',
        'jenis_diklat',
        'pengembangan_kompetensi',
        'id_kota',
        'kota',
        'penyelenggara',
        'lama_hari',
        'nomor_sertifikasi',
        'tahun_diklat',
        'id_tingkat',
        'id_jenis'
    ];

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }

    public function refKota()
    {
        return $this->belongsTo('App\Kota', 'id_kota');
    }

    public function refTingkat()
    {
        return $this->belongsTo('App\TingkatDiklat', 'id_tingkat');
    }

    public function refJenis()
    {
        return $this->belongsTo('App\JenisDiklat', 'id_jenis');
    }
}
