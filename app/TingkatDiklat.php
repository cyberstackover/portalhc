<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TingkatDiklat extends Model
{
    protected $table = 'tingkat_diklat';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
