<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentCluster extends Model
{
    protected $table = 'assessment_cluster';

    protected $fillable = [
        'id_assessment_nilai', 'id_cluster_bumn', 'rating'
    ];

    public function cluster()
    {
        return $this->belongsTo('App\ClusterBumn', 'id_cluster_bumn');
    }
}
