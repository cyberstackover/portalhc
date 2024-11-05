<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusTalenta extends Model
{
    protected $table = 'status_talenta';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
