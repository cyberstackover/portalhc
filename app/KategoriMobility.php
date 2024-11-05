<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriMobility extends Model
{
    protected $table = 'kategori_mobility';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
