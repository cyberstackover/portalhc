<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatJabatanLain extends Model
{
    protected $table = 'riwayat_jabatan_lain';

    protected $fillable = [
        'id_talenta',
        'penugasan',
        'tupoksi',
        'instansi',
        'tanggal_awal',
        'tanggal_akhir',
        'bidang_jabatan_id',
    ];

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }

    public function bidangJabatan(){

        return $this->belongsTo('App\BidangJabatan', 'bidang_jabatan_id');
    }
}
