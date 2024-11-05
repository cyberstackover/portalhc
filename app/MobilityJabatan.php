<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobilityJabatan extends Model
{
    protected $table = 'jenis_mobility_jabatan';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
