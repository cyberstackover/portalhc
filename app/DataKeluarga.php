<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataKeluarga extends Model
{
    protected $table = 'data_keluarga';

    protected $fillable = [
        'id_talenta',
        'nama',
        'id_kota',
        'tempat_lahir',
        'tanggal_lahir',
        'tanggal_menikah',
        'pekerjaan',
        'keterangan',
        'hubungan_keluarga',
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
