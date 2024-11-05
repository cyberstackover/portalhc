<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SKNomenklatur extends Model
{
    protected $table = 'sk_perubahan_nomenklatur';

    protected $fillable = [
        'id_rincian_sk', 'id_struktur_organ', 'id_kategori_mobility', 'nomenklatur_baru', 'keterangan', 'aktif'
    ];

}
