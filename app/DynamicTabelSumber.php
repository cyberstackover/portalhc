<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DynamicTabelSumber extends Model
{
    protected $table = 'dynamic_tabel_sumber';

    protected $fillable = [
        'tabel','field','alias','query','keterangan'
    ];
}
