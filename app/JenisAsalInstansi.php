<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisAsalInstansi extends Model
{
    protected $table = 'jenis_asal_instansi';

    protected $fillable = [
        'nama', 'keterangan', 'tablename'
    ];
}
