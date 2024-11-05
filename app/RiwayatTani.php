<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiwayatTani extends Model
{
    protected $table = 'riwayat_tani';

    protected $fillable = [
        'id_talenta',
        'tani',
        'tahun',
        'tgl_awal',
        'tgl_akhir'
    ];

    
    public function talenta()
    {
        return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
