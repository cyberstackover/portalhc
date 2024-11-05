<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BidangJabatan extends Model
{
    protected $table = 'bidang_jabatan';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
