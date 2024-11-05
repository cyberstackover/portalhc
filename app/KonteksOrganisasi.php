<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KonteksOrganisasi extends Model
{
    protected $table = 'konteks_organisasi';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
