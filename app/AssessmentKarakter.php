<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssessmentKarakter extends Model
{
    protected $table = 'assessment_karakter';

    protected $fillable = [
        'id_assessment_nilai', 'id_karakter', 'rating'
    ];

    public function karakter()
    {
        return $this->belongsTo('App\Karakters', 'id_karakter');
    }
}
