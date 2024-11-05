<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriJabatanTalent extends Model
{
    protected $table = 'kategori_jabatan_talent';

    protected $fillable = [
        'nama', 'keterangan', 'id_kategori_data_talent', 'hak_akses'
    ];

    public function kategori_data_talent()
    {
    	return $this->belongsTo('App\KategoriDataTalent', 'id_kategori_data_talent');
    }
}
