<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkalaKesehatan extends Model
{
    protected $table = 'skala_kesehatan';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
