<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SKKomIndependen extends Model
{
    protected $table = 'sk_kom_independen';

    protected $fillable = [
        'id_rincian_sk', 'id_struktur_organ', 'id_periode_jabatan', 'id_talenta', 'id_kategori_mobility', 'id_jenis_mobility', 'id_rekomendasi', 'tanggal_awal_menjabat', 'tanggal_akhir_menjabat', 'keterangan', 'id_organ_perusahaan'
    ];

}
