<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaktorPenghasilan extends Model
{
    protected $table = 'faktor_penghasilan';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
