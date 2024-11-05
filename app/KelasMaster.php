<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Perusahaan;

class KelasMaster extends Model
{
    protected $table = 'kelas_master';

    protected $fillable = [
        'kelas_bumn_id', 'std_direksi', 'std_komwas', 'user', 'std_direksi_max', 'std_komwas_max'
    ];

    public function bumns()
    {
    	return $this->belongsToMany(Perusahaan::class,'kelas_has_bumn','kelas_master_id','perusahaan_id');
    }
}
