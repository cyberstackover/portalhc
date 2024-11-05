<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KualifikasiPersonal extends Model
{
    protected $table = 'kualifikasi_personal';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
