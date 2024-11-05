<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PengalamanLain extends Model
{
    protected $table = 'pengalaman_lain';

    protected $fillable = [
        'id_talenta',
        'acara',
        'penyelenggara',
        'periode',
        'lokasi',
        'peserta'
    ];

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
