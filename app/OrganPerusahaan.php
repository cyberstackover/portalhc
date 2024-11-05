<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrganPerusahaan extends Model
{
    protected $table = 'organ_perusahaan';

    protected $fillable = [
        'id_struktur_organ', 'id_talenta', 'id_surat_keputusan', 'tanggal_awal', 'tanggal_akan_berakhir', 'tanggal_akhir', 'plt','aktif', 'komisaris_independen', 'nomenklatur', 'id_periode_jabatan'
    ];

    public function talenta()
    {
    	return $this->belongsTo('App\Talenta', 'id_talenta');
    }

    public function struktur_organ()
    {
    	return $this->belongsTo('App\StrukturOrgan', 'id_struktur_organ');
    }

}
