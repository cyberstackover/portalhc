<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MataUang extends Model
{
    protected $table = 'mata_uang';

    protected $fillable = [
        'nama', 'kode'
    ];
}
