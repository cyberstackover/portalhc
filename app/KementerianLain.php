<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KementerianLain extends Model
{
    protected $table = 'kementerian_lain';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
