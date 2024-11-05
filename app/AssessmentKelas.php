<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentKelas extends Model
{
    protected $table = 'assessment_kelas';

    protected $fillable = [
        'id_assessment_nilai', 'id_kelas_bumn', 'rating'
    ];

    public function kelas()
    {
        return $this->belongsTo('App\KelasBumn', 'id_kelas_bumn');
    }
}
