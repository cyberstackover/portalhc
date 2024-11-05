<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DynamicOperator extends Model
{
    protected $table = 'dynamic_operator';

    protected $fillable = [
        'nama','operator', 'is_number','aktif','is_sorting','keterangan'
    ];
}
