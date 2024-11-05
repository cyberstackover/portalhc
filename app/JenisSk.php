<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisSk extends Model
{
    protected $table = 'jenis_sk';

    protected $fillable = [
        'nama', 'keterangan', 'tablename', 'urut'
    ];
}
