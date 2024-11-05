<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisJabatan extends Model
{
    protected $table = 'jenis_jabatan';

    protected $fillable = [
        'id_grup_jabatan', 'nama' , 'prosentase_gaji', 'parent_id', 'id_jns_jab_pengali', 'urut'
    ];

    public function grup_jabatan()
    {
    	return $this->belongsTo('App\GrupJabatan', 'id_grup_jabatan');
    }

    public function induk_pengali()
    {
        return $this->belongsTo('App\JenisJabatan', 'id_jns_jab_pengali');
    }

    public function anak_pengali()
    {
        return $this->hasMany('App\JenisJabatan', 'id_jns_jab_pengali');
    }
}
