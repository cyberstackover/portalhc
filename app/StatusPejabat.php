<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusPejabat extends Model
{
    protected $table = 'status_pejabat';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
