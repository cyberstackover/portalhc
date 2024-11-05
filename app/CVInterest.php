<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CVInterest extends Model
{
    protected $table = 'cv_interest';

    protected $fillable = [
        'id_talenta',
        'ekonomi',
        'leadership',
        'sosial',
        'interest'
    ];
}


