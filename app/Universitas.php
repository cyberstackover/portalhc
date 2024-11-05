<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Universitas extends Model
{
    protected $table = 'universitas';

    protected $fillable = [
        'nama', 'id_kota', 'id_provinsi', 'id_negara', 'unverified'
    ];

    public function refKota()
    {
        return $this->belongsTo('App\Kota', 'id_kota');
    }

    public function refNegara()
    {
        return $this->belongsTo('App\Provinsi', 'id_negara');
    }

    public function refProvinsi()
    {
        return $this->belongsTo('App\Provinsi', 'id_negara');
    }
}
