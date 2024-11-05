<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\BidangJabatan;

class StrukturOrgan extends Model
{
    protected $table = 'struktur_organ';

    protected $fillable = [
        'id_perusahaan', 'id_jenis_jabatan', 'nomenklatur_jabatan', 'prosentase_gaji', 'aktif', 'parent_id', 'urut', 'level', 'keterangan', 'kosong'
    ];

    public function bidangjabatans()
    {
    	return $this->belongsToMany(BidangJabatan::class,'struktur_bidang_jabatan','id_struktur_organ','id_bidang_jabatan');
    }

    public function remunerasis()
    {
        return $this->hasMany('App\Remunerasi');
    }

    public function jenis_jabatan()
    {
        return $this->belongsTo('App\JenisJabatan', 'id_jenis_jabatan');
    }
}
