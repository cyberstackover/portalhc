<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Talenta extends Model
{
    protected $table = 'talenta';

    protected $guarded = ['id'];

    
    public function agama()
    {
        return $this->belongsTo('App\Agama', 'id_agama');
    }

    public function summary()
    {
        return $this->hasOne('App\CVSummary', 'id_talenta');
    }

    public function pribadi()
    {
        return $this->hasOne('App\CVNilai', 'id_talenta');
    }

    public function interest()
    {
        return $this->hasOne('App\CVInterest', 'id_talenta');
    }

    public function transactionTalentaKeahlian()
    {
        return $this->hasMany('App\TransactionTalentaKeahlian', 'id_talenta');
    }

    public function transactionTalentaKelas()
    {
        return $this->hasMany('App\TransactionTalentaKelas', 'id_talenta');
    }

    public function transactionTalentaCluster()
    {
        return $this->hasMany('App\TransactionTalentaCluster', 'id_talenta');
    }

    public function organ_perusahaans()
    {
        return $this->hasMany('App\OrganPerusahaan', 'id_talenta');
    }

    public function refKota()
    {
        return $this->belongsTo('App\Kota', 'id_kota');
    }

    public function statusKawin()
    {
        return $this->belongsTo('App\StatusKawin', 'id_status_kawin');
    }

    public function jenisAsalInstansi()
    {
        return $this->belongsTo('App\JenisAsalInstansi', 'id_jenis_asal_instansi');
    }
   
    public function asalInstansi()
    {
        return $this->belongsTo('App\AsalInstansiBaru', 'id_asal_instansi');
    }

    public function golonganDarah()
    {
        return $this->belongsTo('App\GolonganDarah', 'id_gol_darah');
    }

    public function suku()
    {
        return $this->belongsTo('App\Suku', 'id_suku');
    }

    public function statusTalenta()
    {
        return $this->belongsTo('App\StatusTalenta', 'id_status_talenta');
    }

    public function assessmentNilai()
    {
        return $this->hasMany('App\AssessmentNilai', 'id_talenta');
    }

    public function lembagaAssessment()
    {
        return $this->belongsTo('App\LembagaAssessment', 'id_lembaga_assessment');
    }

 }
