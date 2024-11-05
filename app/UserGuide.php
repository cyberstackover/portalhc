<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGuide extends Model
{
    protected $table = 'user_guides';

    protected $fillable = [
        'filename'
    ];
}
