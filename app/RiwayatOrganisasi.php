<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatOrganisasi extends Model
{
    protected $table = 'riwayat_organisasi';

    protected $fillable = [
        'id_talenta',
        'nama_organisasi',
        'jabatan',
        'kegiatan_organisasi',
        'tanggal_awal',
        'tanggal_akhir',
        'masih_aktif',
        'formal_flag',
        'tahun_awal',
        'tahun_akhir'
    ];

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
