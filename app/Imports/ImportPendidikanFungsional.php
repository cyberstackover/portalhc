<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use App\RiwayatPelatihan;
use App\TingkatDiklat;
use App\JenisDiklat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Kota ;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportPendidikanFungsional implements ToCollection   , WithHeadingRow, WithMultipleSheets 
{

    public function __construct($row,$id ){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Nama Pendidikan dan Latihan/Pengembangan Kompetensi')->keys()->first();
        //$this->suffix2 = $this->row->where('2','B. DIKLAT FUNGSIONAL')->keys()->first();
        ++$this->suffix;
        //++$this->suffix2;
        //$this->range = ($this->suffix2 - $this->suffix);

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
        //$num  = 0 ;
        
        foreach($row as $r) {

            //$num++;
            //if($num <= $this->range) continue ;
            if(rtrim($r['nama_pendidikan_dan_latihanpengembangan_kompetensi'] == null)){
                break ;
            }
            $arr->push($r);
        }

         $saveid = collect();
        foreach ($arr as $ar) {
          if($ar['id']!= null)
            $saveid->push(rtrim(ltrim($ar['id'])));
        }
      
        $prim = RiwayatPelatihan::where('id_talenta',$this->id)->where('jenis_diklat',"Diklat Fungsional")->get();
        $iddel = collect();
        foreach($prim as $p){
          $iddel->push($p->id);
        }
        $iddel = $iddel->diff($saveid);
        if ( $iddel != null )
          RiwayatPelatihan::destroy($iddel);

        
        foreach ($arr as $ar) {

            //cari id kota
            $namakota = Kota::where('nama', strtoupper(rtrim($ar['kota'])))->first();
            if(is_null($namakota)){
               return redirect(route('cv.board.index'))->with('error', 'Import Talenta GAGAL --> Bagian Pendidikan dan Latihan, Kota Tidak Sesuai Referensi');
            }
            $id_namakota = (int)$namakota->id;

            //cari id tingkat
            if(!empty(rtrim($ar['tingkat']))){
               $tingkatnama = TingkatDiklat::where('nama', strtoupper(rtrim($ar['tingkat'])))->first();
               //dd($tingkatnama->id);
               $id_tingkat = (int)$tingkatnama->id;
            } else {
               $id_tingkat = null;
            }

            //cari id jenis
            if(!empty(rtrim($ar['jenis_diklat']))){
               $jenisnama = JenisDiklat::where('nama', strtoupper(rtrim($ar['jenis_diklat'])))->first();
               //dd($tingkatnama->id);
               $id_jenis = (int)$jenisnama->id;
            } else {
               $id_jenis = null;
            }

             $id_kota = $id_namakota;
             $kota = Kota::find($id_kota);
            if(ltrim(rtrim($ar['id'])) == null){
              
                try {
            RiwayatPelatihan::create([
                  'id_talenta' => $this->id ,
                  'jenis_diklat' => 'Diklat Fungsional',
                  'id_kota' => $id_kota ,
                  'kota' => $kota->nama ,
                  'penyelenggara' => rtrim($ar['penyelenggara']),
                  'tahun_diklat' => rtrim($ar['tahun_diklat']) ? rtrim($ar['tahun_diklat']) : null ,
                  'pengembangan_kompetensi' => rtrim($ar['nama_pendidikan_dan_latihanpengembangan_kompetensi']) ,
                  'nomor_sertifikasi' => rtrim($ar['nomor_sertifikasi']),
                  'id_tingkat' => $id_tingkat,
                  'id_jenis' => $id_jenis,
               ]);
        }
          catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel Diklat Fungsional Invalid');
         }
            }
            else {
                   
                try {
                RiwayatPelatihan::updateOrCreate(
              [
                'id'=> rtrim(ltrim($ar['id'])),
                'id_talenta' => $this->id,
            ],[
                    'id_talenta' => $this->id ,
                    'jenis_diklat' => 'Diklat Fungsional',
                    'kota' => $kota->nama ,
                    'penyelenggara' => rtrim($ar['penyelenggara']),
                    'tahun_diklat' => rtrim($ar['tahun_diklat']) ? rtrim($ar['tahun_diklat']) : null ,
                    'pengembangan_kompetensi' => rtrim($ar['nama_pendidikan_dan_latihanpengembangan_kompetensi']) ,
                    'nomor_sertifikasi' => rtrim($ar['nomor_sertifikasi']),
                    'id_tingkat' => $id_tingkat,
                    'id_jenis' => $id_jenis,
                 ]);
            }
              catch(\Exception $e){
                //dd($e->getMessage());
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel Diklat Fungsional Invalid');
         }
            }

           }
    }







    public function headingRow(): int
    {
        return $this->suffix;
    }




}
