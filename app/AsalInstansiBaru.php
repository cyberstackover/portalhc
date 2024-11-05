<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsalInstansiBaru extends Model
{
    protected $table = 'instansi_baru';

    protected $fillable = [
        'nama', 'keterangan', 'id_jenis_asal_instansi'
    ];

    public function jenis_asal_instansi()
    {
    	return $this->belongsTo('App\JenisAsalInstansi', 'id_jenis_asal_instansi');
    }
}
