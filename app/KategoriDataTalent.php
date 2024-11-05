<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriDataTalent extends Model
{
    protected $table = 'kategori_data_talent';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
