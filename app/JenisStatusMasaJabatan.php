<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisStatusMasaJabatan extends Model
{
    protected $table = 'jenis_status_masa_jabatan';

    protected $fillable = [
        'nama', 'jumlah_hari_awal', 'jumlah_hari_akhir'
    ];
}
