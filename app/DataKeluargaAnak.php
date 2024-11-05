<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataKeluargaAnak extends Model
{
    protected $table = 'data_keluarga_anak';

    protected $fillable = [
        'id_talenta',
        'nama',
        'id_kota',
        'tempat_lahir',
        'tanggal_lahir',
        'pekerjaan',
        'keterangan',
        'jenis_kelamin'
    ];

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }

    public function refKota()
    {
        return $this->belongsTo('App\Kota', 'id_kota');
    }
}
