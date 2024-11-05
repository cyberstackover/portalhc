<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    protected $table = 'instansi';

    protected $fillable = [
        'id_jenis_asal_instansi', 'nama', 'keterangan'
    ];
}
