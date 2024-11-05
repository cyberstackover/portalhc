<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SKAlihtugas extends Model
{
    protected $table = 'sk_alih_tugas';

    protected $fillable = [
        'id_rincian_sk', 'id_organ_perusahaan', 'id_struktur_organ', 'id_kategori_mobility', 'keterangan'
    ];

}
