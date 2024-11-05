<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DynamicStandarValue extends Model
{
    protected $table = 'dynamic_standar_value';

    protected $fillable = [
        'nama','opsi'
    ];
}
