<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriTalenta extends Model
{
    protected $table = 'kategori_talenta';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
