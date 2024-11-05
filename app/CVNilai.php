<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CVNilai extends Model
{
    protected $table = 'cv_nilai';

    protected $fillable = [
        'id_talenta',
        'nilai',
        'interest'
    ];
}


