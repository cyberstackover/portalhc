<?php

/**
 * @Author: zalfrie
 * @Date:   2021-08-30 12:47:30
 * @Last Modified by:   zalfrie
 * @Last Modified time: 2021-08-30 12:47:50
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class Whitelist extends Model
{
    protected $table = 'whitelist';
    protected $primaryKey = 'id';
    public $timestamps = false;
}