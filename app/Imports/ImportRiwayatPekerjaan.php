<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use App\KeteranganPerorangan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\RiwayatJabatanLain ;
use App\BidangJabatan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportRiwayatPekerjaan implements ToCollection   , WithHeadingRow, WithMultipleSheets 
{

    public function __construct($row,$id ){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Jabatan/Pekerjaan')->keys()->first();
        ++$this->suffix;


    }
      public function sheets(): array
    {
        return [
            1 => $this,
        ];
    }
    public function collection(Collection $row)
    {
       
        $arr = collect();
     
        foreach($row as $r) {

            if($r['jabatanpekerjaan'] == null){
                break ;
            }

            $arr->push($r);
        }
        // if(is_int($arr[0]['awal_menjabat'])){
        //   dump($arr[0]['awal_menjabat']);
        //   dd('int');
        // } else{
        //   dump($arr[0]['awal_menjabat']);
        //   dd('str');

        // };
        // $UNIX_DATE = (43850 - 25569) * 86400;
        // $EXCEL_DATE = 25569 + ($UNIX_DATE / 86400);
        // $UNIX_DATE = ($EXCEL_DATE - 25569) * 86400;
        // echo gmdate("Y-m-d", $UNIX_DATE);
        // dd($arr);

        $saveid = collect();
        foreach ($arr as $ar) {
          if($ar['id']!= null)
            $saveid->push(rtrim(ltrim($ar['id'])));
        }
      
        
        $prim = RiwayatJabatanLain::where('id_talenta',$this->id)->get();
        $iddel = collect();
        foreach($prim as $p){
          $iddel->push($p->id);
        }
        $iddel = $iddel->diff($saveid);
       

        if ( $iddel != null )
          RiwayatJabatanLain::destroy($iddel);
       
$tez = collect();
       foreach ($arr as $ar) {

        /*$tanggal = explode('-',$ar['rentang_waktu']);
        for ( $i = 0  ; $i < count($tanggal) ; $i++ ){
            $tanggal[$i] =  str_replace(' ','',$tanggal[$i]);
        }
       if (count($tanggal) < 3) {
          return redirect(route('cv.board.index'))->with('error', 'Rentang Waktu Jabatan Invalid, Gunakan YYYY-MM-DD - YYYY-MM-DD');
       }
        $awal = $tanggal[0].'-'.$tanggal[1].'-'.$tanggal[2];
        $akhir = null ; 
        if ( count($tanggal) == 6 )
        $akhir = $tanggal[3].'-'.$tanggal[4].'-'.$tanggal[5];*/
        if(is_int($ar['awal_menjabat'])){
          $UNIX_DATE = ($ar['awal_menjabat'] - 25569) * 86400;
          $EXCEL_DATE = 25569 + ($UNIX_DATE / 86400);
          $UNIX_DATE = ($EXCEL_DATE - 25569) * 86400;
          $awal_menjabat =  gmdate("d/m/Y", $UNIX_DATE);
          $awal_menjabat = \Carbon\Carbon::createFromFormat('d/m/Y', $awal_menjabat)->format('Y-m-d');
        } else{
          $awal_menjabat = !empty($ar['awal_menjabat']) ? \Carbon\Carbon::createFromFormat('d/m/Y', $ar['awal_menjabat'])->format('Y-m-d') : NULL;
        }

        if(is_int($ar['akhir_menjabat'])){
          $UNIX_DATE = ($ar['akhir_menjabat'] - 25569) * 86400;
          $EXCEL_DATE = 25569 + ($UNIX_DATE / 86400);
          $UNIX_DATE = ($EXCEL_DATE - 25569) * 86400;
          $akhir_menjabat =  gmdate("d/m/Y", $UNIX_DATE);
          $akhir_menjabat = \Carbon\Carbon::createFromFormat('d/m/Y', $akhir_menjabat)->format('Y-m-d');
        } else{
          $akhir_menjabat = !empty($ar['akhir_menjabat']) ? \Carbon\Carbon::createFromFormat('d/m/Y', $ar['akhir_menjabat'])->format('Y-m-d') : NULL;
        }

        $bidang_jabatan = BidangJabatan::where('nama', rtrim($ar['bidang_jabatan']))->first();
        if($bidang_jabatan){
          $bidang_jabatan = $bidang_jabatan->id;
        } else{
          $bidang_jabatan = NULL;
        }
        
        if(ltrim(rtrim($ar['id'])) == null){
          try{
           RiwayatJabatanLain::create([
              'id_talenta' => $this->id ,
              'penugasan' => rtrim($ar['jabatanpekerjaan']) ,
              'tupoksi' => rtrim($ar['tupoksi']),
              'tanggal_awal' => $awal_menjabat,
              'tanggal_akhir' => $akhir_menjabat,
              'instansi' => rtrim($ar['instansi']),
              'bidang_jabatan_id' => $bidang_jabatan,
            ]); 
          
         }
         catch(\Exception $e){
          
          return redirect(route('cv.board.index'))->with('error', 'Isi Riwayat Jabatan Lain Gagal');
         }
        }
        else {
          try {

            RiwayatJabatanLain::updateOrCreate(
              [
                'id'=> rtrim(ltrim($ar['id'])),
                'id_talenta' => $this->id,
            ],[
                'id_talenta' => $this->id ,
                'penugasan' => rtrim($ar['jabatanpekerjaan']) ,
                'tupoksi' => rtrim($ar['tupoksi']),
                'tanggal_awal' => !empty($ar['awal_menjabat']) ? rtrim(\Carbon\Carbon::createFromFormat('d/m/Y', $ar['awal_menjabat'])->format('Y-m-d')) : '',
                'tanggal_akhir' => !empty($ar['akhir_menjabat']) ? rtrim(\Carbon\Carbon::createFromFormat('d/m/Y', $ar['akhir_menjabat'])->format('Y-m-d')) : '',
                'instansi' => rtrim($ar['instansi']),
                'bidang_jabatan_id' => $bidang_jabatan,
            ]);
            
          }
             catch(\Exception $e){
             
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', $e->getMessage());
         }
        }


       }

    
    
    }






    public function headingRow(): int
    {
        return $this->suffix;
    }




}
