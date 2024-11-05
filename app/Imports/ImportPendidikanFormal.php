<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use App\RiwayatPendidikan;
use App\Universitas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use App\JenjangPendidikan ;
use App\Provinsi;
use App\Kota ; 
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportPendidikanFormal implements ToCollection   , WithHeadingRow, WithMultipleSheets 
{

    public function __construct($row,$id ){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Jenjang')->keys()->first();
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
            
            if(rtrim($r['jenjang']) == null){
                break ;
            }
            $arr->push($r);
        }

         $saveid = collect();
        foreach ($arr as $ar) {
          if($ar['id']!= null)
            $saveid->push(rtrim(ltrim($ar['id'])));
        }
      
        $prim = RiwayatPendidikan::where('id_talenta',$this->id)->get();
        $iddel = collect();
        foreach($prim as $p){
          $iddel->push($p->id);
        }
        $iddel = $iddel->diff($saveid);
        if ( $iddel != null )
          RiwayatPendidikan::destroy($iddel);


       foreach ($arr as $ar) {

          //cari id jenjang pendidikan
          $jenjangpendidikan = JenjangPendidikan::where('nama', strtoupper(rtrim($ar['jenjang'])))->first();
          if(is_null($jenjangpendidikan)){
            return redirect(route('cv.board.index'))->with('error', 'Import Talenta GAGAL --> Bagian Pendidikan Formal, Jenjang Tidak Sesuai Referensi');
          }
          $id_jenjangpendidikan = (int)$jenjangpendidikan->id;

          //cari id kota
          $namakota = Kota::where('nama', strtoupper(rtrim($ar['kota'])))->first();
          if(is_null($namakota)){
            return redirect(route('cv.board.index'))->with('error', 'Import Talenta GAGAL --> Bagian Pendidikan Formal, Kota Tidak Sesuai Referensi');
          }
          $id_namakota = (int)$namakota->id;

          //cari id negara
          $namanegara = Provinsi::where('id', $namakota->provinsi_id);

          //cari id universitas
          $namakampus = Universitas::where('nama', rtrim($ar['perguruan_tinggi']))->first();
          if(is_null($namakampus)){
            return redirect(route('cv.board.index'))->with('error', 'Import Talenta GAGAL --> Bagian Pendidikan Formal,  Perguruan Tinggi Tidak Sesuai Referensi');
          }
          $id_namakampus = $namakampus->id;

           $id_kota = $id_namakota;
           $id_jenjang_pendidikan =  $id_jenjangpendidikan;
           $checkKota = Kota::find($id_kota);
           $checkJenjang = JenjangPendidikan::find($id_jenjang_pendidikan);
           $negara = Provinsi::where('id',$checkKota->provinsi_id)->first();
           $id_universitas = $id_namakampus;
       
           if ( $id_universitas == NULL )
             return redirect(route('cv.board.index'))->with('error', 'Bagian Pendidikan Formal, Nama Perguruan Tinggi tidak boleh kosong');
           $universitas = Universitas::where('id',$id_universitas)->pluck('nama')->first();
           if ($negara->is_luar_negeri){
             $negara = $negara->nama ; 
           }
           else{
            $negara = 'INDONESIA';
           }

          if($checkJenjang == null || $checkKota == null || $universitas == null){
            if($checkJenjang == null && $checkKota == null)
                 return redirect(route('cv.board.index'))->with('error', 'ID Jenjang dan ID Kota pada tabel Pendidikan Formal Invalid');
            else if ($checkKota == null)
               return redirect(route('cv.board.index'))->with('error', 'ID Kota pada tabel Pendidikan Formal invalid');
             else if ($universitas == null)
             return redirect(route('cv.board.index'))->with('error', 'ID Universitas pada tabel Pendidikan Formal invalid');
              else 
               return redirect(route('cv.board.index'))->with('error', 'ID Jenjang pada tabel Pendidikan Formal invalid');
          }
           
           
            if(ltrim(rtrim($ar['id'])) == null){
              try{
            RiwayatPendidikan::create([
              'id_talenta' => $this->id ,
              'id_jenjang_pendidikan' =>  $id_jenjangpendidikan,
              'penjurusan' => rtrim($ar['jurusan']),
              'id_universitas' => $id_universitas,
              'perguruan_tinggi' => rtrim($ar['perguruan_tinggi']),
              'tahun' => rtrim($ar['tahun_lulus']) ? rtrim($ar['tahun_lulus']) : null,
              'id_kota' => $id_kota,
              'kota' => $namakota->nama,
              'negara' =>$negara,
              'penghargaan' => rtrim($ar['penghargaan_yang_didapat']),
           ]);
          }
           catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel Pendidikan Formal Invalid');
         }
            }
            else {
              try {
                RiwayatPendidikan::updateOrCreate(
              [
                'id'=> rtrim(ltrim($ar['id'])),
                'id_talenta' => $this->id,
            ],[
                'id_talenta' => $this->id ,
                'id_jenjang_pendidikan' =>  $id_jenjangpendidikan,
                'penjurusan' => rtrim($ar['jurusan']),
                'id_universitas' => $id_universitas,
                'perguruan_tinggi' => rtrim($ar['perguruan_tinggi']),
                'tahun' => rtrim($ar['tahun_lulus']) ? rtrim($ar['tahun_lulus']) : null ,
                'id_kota' => $id_kota,
                'kota' => $namakota->nama,
                'negara' =>$negara,
                'penghargaan' => rtrim($ar['penghargaan_yang_didapat']),
                 ]);
              }
               catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel Pendidikan Formal Invalid');
         }
            }
       }
    }







    public function headingRow(): int
    {
        return $this->suffix;
    }




}
