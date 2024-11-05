<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlasanPemberhentian extends Model
{
    protected $table = 'alasan_pemberhentian';

    protected $fillable = [
        'keterangan', 'id_kategori_pemberhentian'
    ];

    public function kategori_pemberhentian()
    {
    	return $this->belongsTo('App\KategoriPemberhentian', 'id_kategori_pemberhentian');
    }
}
