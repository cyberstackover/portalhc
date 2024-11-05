<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisAsalInstansiBaru extends Model
{
    protected $table = 'jenis_asal_instansi';

    protected $fillable = [
        'nama', 'keterangan', 'tablename', 'urut', 'direksi_induk', 'dekom_induk', 'direksi_anak_cucu', 'dekom_anak_cucu'
    ];
}
