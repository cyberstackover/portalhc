<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriNonTalent extends Model
{
    protected $table = 'kategori_non_talent';

    protected $fillable = [
        'nama', 'keterangan', 'id_kategori_jabatan_talent', 'hak_akses'
    ];

    public function kategori_jabatan_talent()
    {
    	return $this->belongsTo('App\KategoriJabatanTalent', 'id_kategori_jabatan_talent');
    }
}
