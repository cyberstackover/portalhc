<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatJabatanDirkomwas extends Model
{
    protected $table = 'riwayat_jabatan_dirkomwas';

    protected $fillable = [
        'id_talenta',
        'jabatan',
        'tupoksi',
        'nama_perusahaan',
        'tanggal_awal',
        'tanggal_akhir',
        'masih_bekerja',
        'achievement',
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
