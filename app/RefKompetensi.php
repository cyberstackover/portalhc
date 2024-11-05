<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RefKompetensi extends Model
{
    protected $table = 'kompetensis';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
