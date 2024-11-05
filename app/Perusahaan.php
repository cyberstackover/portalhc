<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'perusahaan';

    protected $guarded = [];

    public function kategori_perusahaan()
    {
    	return $this->belongsTo('App\JenisPerusahaan', 'id_jenis_perusahaan');
    }

}
