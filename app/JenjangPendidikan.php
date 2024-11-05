<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenjangPendidikan extends Model
{
    protected $table = 'jenjang_pendidikan';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
