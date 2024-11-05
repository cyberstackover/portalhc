<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentOrganisasi extends Model
{
    protected $table = 'assessment_organisasi';

    protected $fillable = [
        'id_assessment_nilai', 'id_konteks_organisasi', 'rating'
    ];

    public function konteks()
    {
        return $this->belongsTo('App\KonteksOrganisasi', 'id_konteks_organisasi');
    }
}
