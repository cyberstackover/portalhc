<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentNilai extends Model
{
    protected $table = 'assessment_nilai';

    protected $guarded = ['id'];

    public function lembagaAssessment()
    {
        return $this->belongsTo('App\LembagaAssessment', 'id_lembaga_assessment');
    }

    public function assessmentKompetensi()
    {
        return $this->hasMany('App\AssessmentKompetensi', 'id_assessment_nilai');
    }

    public function assessmentKualifikasi()
    {
        return $this->hasMany('App\AssessmentKualifikasi', 'id_assessment_nilai');
    }

    public function assessmentKarakter()
    {
        return $this->hasMany('App\AssessmentKarakter', 'id_assessment_nilai');
    }

    public function assessmentKelas()
    {
        return $this->hasMany('App\AssessmentKelas', 'id_assessment_nilai');
    }

    public function assessmentCluster()
    {
        return $this->hasMany('App\AssessmentCluster', 'id_assessment_nilai');
    }

    public function assessmentKeahlian()
    {
        return $this->hasMany('App\AssessmentKeahlian', 'id_assessment_nilai');
    }

    public function assessmentOrganisasi()
    {
        return $this->hasMany('App\AssessmentOrganisasi', 'id_assessment_nilai');
    }
 }
