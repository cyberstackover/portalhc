<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KelasBumn extends Model
{
    protected $table = 'kelas_bumn';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
