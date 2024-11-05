<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ormas extends Model
{
    protected $table = 'ormas';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
