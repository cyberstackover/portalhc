<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    protected $table = 'agamas';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
