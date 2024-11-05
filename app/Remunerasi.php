<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remunerasi extends Model
{
    protected $table = 'remunerasi';

    protected $guarded = ['id'];

    public function jenis_jabatan()
    {
        return $this->belongsTo('App\JenisJabatan', 'id_jenis_jabatan');
    }

    public function faktor_penghasilan()
    {
        return $this->belongsTo('App\FaktorPenghasilan', 'id_faktor_penghasilan');
    }

    public function mata_uang()
    {
        return $this->belongsTo('App\MataUang', 'id_mata_uang');
    }
    
}
