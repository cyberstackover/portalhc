<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisDiklat extends Model
{
    protected $table = 'jenis_diklat';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
