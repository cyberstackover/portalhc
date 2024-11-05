<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FilePendukung extends Model
{
    protected $table = 'file_pendukung';

    protected $guarded = ['id'];

    public function jenis_file_pendukung()
    {
    	return $this->belongsTo('App\JenisFilePendukung', 'id_jenis_file_pendukung');
    }

    public function talenta()
    {
    	return $this->belongsTo('App\Talenta', 'id_talenta');
    }
}
