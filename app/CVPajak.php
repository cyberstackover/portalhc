<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CVPajak extends Model
{
    protected $table = 'cv_pajak';

    protected $fillable = [
        'id_talenta',
        'file_name',
        'tahun',
        'user'
    ];

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }

}
