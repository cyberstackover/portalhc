<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrupJabatan extends Model
{
    protected $table = 'grup_jabatan';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
