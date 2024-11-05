<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CVSummary extends Model
{
    protected $table = 'cv_summary';

    protected $fillable = [
        'id_talenta',
        'kompetensi',
        'kepribadian'
    ];
}


