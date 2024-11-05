<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentKompetensi extends Model
{
    protected $table = 'assessment_kompetensi';

    protected $fillable = [
        'id_assessment_nilai', 'id_kompetensi', 'rating'
    ];

    public function kompetensi()
    {
        return $this->belongsTo('App\RefKompetensi', 'id_kompetensi');
    }
}
