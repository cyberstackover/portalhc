<?php

/**
 * Additional Laravel Helper
 *
 * @package  Laravel Helper
 * @author   Ali Alghozali <www.alghoza.li>
 */

namespace App\Helpers;

use Carbon\Carbon;
use File;
use App\CVInterest;
use App\CVPajak;
use App\CVSummary;
use App\CVNilai;
use App\DataKeluarga;
use App\DataKeluargaAnak;
use App\DataKaryaIlmiah;
use App\DataPenghargaan;
use App\TransactionTalentaKeahlian;
use App\TransactionTalentaKelas;
use App\TransactionTalentaCluster;
use App\Talenta;
use App\PengalamanLain;
use App\RiwayatPendidikan;
use App\RiwayatPelatihan;
use App\RiwayatJabatanLain;
use App\RiwayatOrganisasi;
use App\RiwayatLhkpn;
use App\RiwayatTani;
use App\RiwayatKesehatan;
use App\ReferensiCV;
use App\TidakMemilikiAnak;
use App\TidakMemilikiPenghargaan;
use App\TidakMemilikiKaryaIlmiah;
use App\TidakMemilikiOrganisasi;
use App\TidakMemilikiOrganisasiNonformal;
use App\TidakMemilikiReferensi;
use App\TidakMemilikiPengalamanLain;
use App\TransactionTalentaSocialMedia;

class CVHelper
{
    #fungsi untuk mengubah format tanggal
    public static function tglFormat($tgl, $format = 1)
    {   
    	$dt = @date_parse($tgl);
        if(!$dt['year'] || !$dt['month'] || !$dt['day']) return $tgl;

        if ( $format == 1 ) {
        	$return = Carbon::createFromFormat('Y-m-d', $tgl)->format('d/m/Y');
        	return $return;
            

        } else if ( $format == 2 ) {
            # 31 Desember 2017
            $hari   = $dt['day'];
            $bulan  = $dt['month'];
            $tahun  = $dt['year'];

            return $hari.' '.CVHelper::bulan($bulan).' '.$tahun;
        }
        
    }

    public static function bulan($bulan)
    {
        $aBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

        return $aBulan[$bulan-1];
    }

    public static function getFoto($value = null, $defaultImagePath = 'img/male.png')
    {
        if($value && File::exists('img/foto_talenta/'.$value)) {
            return asset('img/foto_talenta/'.$value);
        } else {
            return asset($defaultImagePath);
        }
    }

    public static function getFotoKtp($value = null, $defaultImagePath = 'img/male.png')
    {
        if($value && File::exists('img/ktp/'.$value)) {
            return asset('img/ktp/'.$value);
        } else {
            return asset($defaultImagePath);
        }
    }

    public static function fillPercentage($id_talenta)
    {
        $total_count = 21;
        $true_count = 0;

        if(CVHelper::biodataFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::nilaiFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::organisasiFillCheck($id_talenta) && CVHelper::organisasinonformalFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::pajakFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::lhkpnFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::taniFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::kesehatanFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::socialFillCheck($id_talenta)){
            $true_count++;
        }

        // if(CVHelper::summaryFillCheck($id_talenta)){
        //     $true_count++;
        // }

        if(CVHelper::pengalamanLainFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::karyaIlmiahFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::penghargaanFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::keluargaFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::pendidikanFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::pelatihanFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::jabatanFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::keahlianFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::kelasFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::clusterFillCheck($id_talenta)){
            $true_count++;
        }

        if(CVHelper::interestFillCheck($id_talenta)){
            $true_count++;
        }
        
        if(CVHelper::referensiCVFillCheck($id_talenta)){
            $true_count++;
        }
        
        if(CVHelper::sosmedFillCheck($id_talenta)){
            $true_count++;
        }

        $percentage = $true_count / $total_count * 100;
        
        return (int)$percentage;
    }

    public static function updatePercentage($id_talenta)
    {
        $param = [];
        $talenta = Talenta::find($id_talenta);
        $param['fill_percentage'] = CVHelper::fillPercentage($id_talenta);
        $talenta->update($param);
        return true;
    }

    public static function biodataFillCheck($id_talenta)
    {
        $talenta = Talenta::find($id_talenta);

        if($talenta->nama_lengkap == NULL){
            return false;
        }
        if($talenta->nik == NULL){
            return false;
        }
        if($talenta->npwp == NULL){
            return false;
        }
        if($talenta->email == NULL){
            return false;
        }
        if($talenta->nomor_hp == NULL){
            return false;
        }
        if($talenta->id_asal_instansi == NULL){
            return false;
        }
        if($talenta->id_jenis_asal_instansi == NULL){
            return false;
        }
        if($talenta->alamat == NULL){
            return false;
        }
        if($talenta->id_agama == NULL){
            return false;
        }
        if($talenta->tempat_lahir == NULL){
            return false;
        }
        if($talenta->tanggal_lahir == NULL){
            return false;
        }
        if($talenta->id_suku == NULL){
            return false;
        }
        if($talenta->id_gol_darah == NULL){
            return false;
        }
        if($talenta->id_status_kawin == NULL){
            return false;
        }
        if($talenta->id_kota == NULL){
            return false;
        }
        if($talenta->foto == NULL){
            return false;
        }
        if($talenta->kewarganegaraan == NULL){
            return false;
        }

        return true;
    }

    public static function pajakFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = CVPajak::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function lhkpnFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = RiwayatLhkpn::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function taniFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = RiwayatTani::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function kesehatanFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = RiwayatKesehatan::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function socialFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = TransactionTalentaSocialMedia::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function summaryFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = CVSummary::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function nilaiFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = CVNilai::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function pengalamanLainFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = PengalamanLain::where("id_talenta", $id_talenta)->count();
        $tidak_memiliki = TidakMemilikiPengalamanLain::where('id_talenta', $id_talenta)->count();
        
        if($count > 0 || $tidak_memiliki > 0){
            $status_fill = true;
        }         

        return $status_fill;
    }

    public static function karyaIlmiahFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = DataKaryaIlmiah::where("id_talenta", $id_talenta)->count();
        $tidak_memiliki = TidakMemilikiKaryaIlmiah::where('id_talenta', $id_talenta)->count();
        
        if($count > 0 || $tidak_memiliki > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function penghargaanFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = DataPenghargaan::where("id_talenta", $id_talenta)->count();
        $tidak_memiliki = TidakMemilikiPenghargaan::where('id_talenta', $id_talenta)->count();
        
        if($count > 0 || $tidak_memiliki > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function keluargaFillCheck($id_talenta)
    {
        $status_fill = false;
        $talenta = Talenta::where("id", $id_talenta)->first();
        $count = DataKeluarga::where("id_talenta", $id_talenta)->count();
        if(($count > 0 && $talenta->id_status_kawin == 1) || ($talenta->id_status_kawin != 1)){     

            $count = DataKeluargaAnak::where("id_talenta", $id_talenta)->count();
            $tidak_memiliki = TidakMemilikiAnak::where('id_talenta', $id_talenta)->count();

            if($count>0 || $tidak_memiliki>0){
                $status_fill = true;
            }
        }

        return $status_fill;
    }

    public static function pendidikanFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = RiwayatPendidikan::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function pelatihanFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = RiwayatPelatihan::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function jabatanFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = RiwayatJabatanLain::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function keahlianFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = TransactionTalentaKeahlian::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function kelasFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = TransactionTalentaKelas::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function clusterFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = TransactionTalentaCluster::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function interestFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = CVInterest::where("id_talenta", $id_talenta)->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function organisasiFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = RiwayatOrganisasi::where("id_talenta", $id_talenta)->where('formal_flag', true)->count();
        $tidak_memiliki = TidakMemilikiOrganisasi::where('id_talenta', $id_talenta)->count();
        
        if($count > 0 || $tidak_memiliki > 0){
            $status_fill = true;
        }      

        return $status_fill;
    }

    public static function organisasinonformalFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = RiwayatOrganisasi::where("id_talenta", $id_talenta)->where('formal_flag', false)->count();
        $tidak_memiliki = TidakMemilikiOrganisasiNonformal::where('id_talenta', $id_talenta)->count();
        
        if($count > 0 || $tidak_memiliki > 0){
            $status_fill = true;
        }      

        return $status_fill;
    }

    public static function referensiCVFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = ReferensiCV::where("id_talenta", $id_talenta)->count();
        $tidak_memiliki = TidakMemilikiReferensi::where('id_talenta', $id_talenta)->count();
        
        if($count > 0 || $tidak_memiliki > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function sosmedFillCheck($id_talenta)
    {
        $status_fill = false;
        $count = Talenta::find($id_talenta)->transactionTalentaKeahlian()->count();
        
        if($count > 0){
            $status_fill = true;
        }        

        return $status_fill;
    }

    public static function updatePersentase($id)
    {
      $talenta = Talenta::find($id);
      $param['fill_biodata'] = CVHelper::biodataFillCheck($id);
      $param['fill_organisasi'] = CVHelper::organisasiFillCheck($id);
      $param['fill_organisasinonformal'] = CVHelper::organisasinonformalFillCheck($id);
      $param['fill_pajak'] = CVHelper::pajakFillCheck($id);
      $param['fill_summary'] = CVHelper::summaryFillCheck($id);
      $param['fill_pengalaman_lain'] = CVHelper::pengalamanLainFillCheck($id);
      $param['fill_karya_ilmiah'] = CVHelper::karyaIlmiahFillCheck($id);
      $param['fill_penghargaan'] = CVHelper::penghargaanFillCheck($id);
      $param['fill_keluarga'] = CVHelper::keluargaFillCheck($id);
      $param['fill_pendidikan'] = CVHelper::pendidikanFillCheck($id);
      $param['fill_pelatihan'] = CVHelper::pelatihanFillCheck($id);
      $param['fill_jabatan'] = CVHelper::jabatanFillCheck($id);
      $param['fill_keahlian'] = CVHelper::keahlianFillCheck($id);
      $param['fill_kelas'] = CVHelper::kelasFillCheck($id);
      $param['fill_cluster'] = CVHelper::clusterFillCheck($id);
      $param['fill_interest'] = CVHelper::interestFillCheck($id);
      $param['fill_referensi_cv'] = CVHelper::referensiCVFillCheck($id);
      $param['fill_lhkpn'] = CVHelper::lhkpnFillCheck($id);
      $param['fill_tani'] = CVHelper::taniFillCheck($id);
      $param['fill_kesehatan'] = CVHelper::kesehatanFillCheck($id);
      $param['fill_nilai'] = CVHelper::nilaiFillCheck($id);
      $param['fill_social'] = CVHelper::socialFillCheck($id);
      
      $param['persentase'] = CVHelper::fillPercentage($id);
      $status = $talenta->update($param);
      return "OK";
    }
}
