<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataKaryaIlmiah extends Model
{
    protected $table = 'data_karya_ilmiah';

    protected $fillable = [
        'id_talenta',
        'judul',
        'media_publikasi',
        'tahun',
    ];

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }

}
