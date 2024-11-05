<?php

/**
 * @Author: zalfrie
 * @Date:   2021-08-24 04:11:19
 * @Last Modified by:   zalfrie
 * @Last Modified time: 2021-08-24 04:33:10
 */
namespace App;


use Illuminate\Database\Eloquent\Model;
use App\Perusahaan;

class PerusahaanRelasi extends Model
{
    protected $table = 'perusahaan_relasi';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function hasperusahaan(){
	   return $this->belongsTo(Perusahaan::class,'perusahaan_id','id');
	}

    public function parent(){
	   return $this->hasOne(PerusahaanRelasi::class,'perusahaan_id','perusahaan_induk_id');
	}

	public function children(){ 
	   return $this->hasMany(PerusahaanRelasi::class,'perusahaan_induk_id','perusahaan_id');
	}

}