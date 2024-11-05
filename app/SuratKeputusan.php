<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\JenisSk;

class SuratKeputusan extends Model
{
    protected $table = 'surat_keputusan';

    protected $fillable = [
        'id_perusahaan', 'id_grup_jabatan', 'nomor', 'tanggal_sk', 'tanggal_serah_terima', 'keterangan', 'file_name', 'save', 'user_log'
    ];

    public function rinciansk()
    {
    	return $this->belongsToMany(JenisSk::class,'rincian_sk','id_surat_keputusan','id_jenis_sk');
    }

    public function filesk()
    {
        return $this->belongsToMany(JenisSk::class, 'file_pendukung', 'id_surat_keputusan', 'id_jenis_sk')->withPivot('filename');
    }
}
