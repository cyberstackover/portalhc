<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SKPemberhentian extends Model
{
    protected $table = 'sk_pemberhentian';

    protected $fillable = [
        'id_rincian_sk', 'id_talenta', 'id_struktur_organ', 'id_alasan_pemberhentian', 'tanggal_akhir_menjabat', 'keterangan', 'sk_id'
    ];

}
