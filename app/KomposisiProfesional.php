<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KomposisiProfesional extends Model
{
    protected $table = 'komposisi_profesional';

    protected $fillable = [
        'id_kelas_bumn', 'keterangan', 'jumlah_minimal', 'jumlah_maksimal'
    ];

    public function kelas_bumn()
    {
    	return $this->belongsTo('App\KelasBumn', 'id_kelas_bumn');
    }
}
