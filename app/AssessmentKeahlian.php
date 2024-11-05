<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentKeahlian extends Model
{
    protected $table = 'assessment_keahlian';

    protected $fillable = [
        'id_assessment_nilai', 'id_keahlian', 'rating'
    ];

    public function keahlian()
    {
        return $this->belongsTo('App\Keahlian', 'id_keahlian');
    }
}
