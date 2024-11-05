<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TargetAsalInstansi extends Model
{
    protected $table = 'target_asal_instansi';

    protected $fillable = [
        'keterangan', 'id_jenis_asal_instansi', 'jumlah_minimal', 'jumlah_maksimal'
    ];

    public function jenis_asal_instansi()
    {
    	return $this->belongsTo('App\JenisAsalInstansi', 'id_jenis_asal_instansi');
    }
}
