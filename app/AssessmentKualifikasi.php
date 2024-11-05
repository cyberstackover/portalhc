<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentKualifikasi extends Model
{
    protected $table = 'assessment_kualifikasi';

    protected $fillable = [
        'id_assessment_nilai', 'id_kualifikasi_personal', 'rating'
    ];

    public function kualifikasi()
    {
        return $this->belongsTo('App\KualifikasiPersonal', 'id_kualifikasi_personal');
    }
}
