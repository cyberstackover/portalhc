<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Karakters extends Model
{
    protected $table = 'karakters';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
