<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisPerusahaan extends Model
{
    protected $table = 'jenis_perusahaan';

    protected $fillable = [
        'nama', 'keterangan'
    ];

    public function perusahaans()
    {
        return $this->hasMany('App\Perusahaan');
    }
}
