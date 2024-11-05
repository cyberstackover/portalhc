<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartaiPolitik extends Model
{
    protected $table = 'partai_politik';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
