<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionTalentaCluster extends Model
{
    protected $table = 'transaction_talenta_cluster';

    protected $fillable = [
        'id_talenta', 'id_cluster'
    ];

    public function cluster()
    {
        return $this->hasOne('App\ClusterBumn', 'id_cluster');
    }
}
