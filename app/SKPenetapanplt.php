<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SKPenetapanplt extends Model
{
    protected $table = 'sk_penetapan_plt';

    protected $fillable = [
        'id_rincian_sk', 'id_organ_perusahaan', 'id_struktur_organ', 'keterangan'
    ];

}
