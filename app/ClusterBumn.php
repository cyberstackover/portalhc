<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClusterBumn extends Model
{
    protected $table = 'cluster_bumn';

    protected $fillable = [
        'nama', 'keterangan'
    ];
}
