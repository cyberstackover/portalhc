<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriPemberhentian extends Model
{
    protected $table = 'kategori_pemberhentian';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
