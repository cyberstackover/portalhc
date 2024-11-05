<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LembagaAssessment extends Model
{
    protected $table = 'lembaga_assessment';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
