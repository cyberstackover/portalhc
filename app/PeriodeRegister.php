<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeriodeRegister extends Model
{
    protected $table = 'periode_register';

    protected $fillable = [
        'nama', 'keterangan', 'tmt_awal', 'tmt_akhir', 'aktif'
    ];
}
