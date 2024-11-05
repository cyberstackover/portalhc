<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use App\RiwayatPelatihan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use App\Kota;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportPendidikanKompetensi implements ToCollection   , WithHeadingRow, WithMultipleSheets 
{

    public function __construct($row,$id ){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Nama Pendidikan dan Latihan/Pengembangan Kompetensi')->keys()->first();
        ++$this->suffix;



    }
      public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }
    public function collection(Collection $row)
    {
        $arr = collect();
        $num  = 0 ;
        foreach($row as $r) {

            $num++;
            if($num == 1 ) continue ;
            if($r['nama_pendidikan_dan_latihanpengembangan_kompetensi'] == null){
                break ;
            }
            $arr->push($r);
        }

         $saveid = collect();
        foreach ($arr as $ar) {
          if($ar['id']!= null)
            $saveid->push(rtrim(ltrim($ar['id'])));
        }
      
        $prim = RiwayatPelatihan::where('id_talenta',$this->id)->where('jenis_diklat',"Diklat Jabatan")->get();
        $iddel = collect();
        foreach($prim as $p){
          $iddel->push($p->id);
        }
        $iddel = $iddel->diff($saveid);
        if ( $iddel != null )
          RiwayatPelatihan::destroy($iddel);


        //  dd($arr);
       foreach ($arr as $ar) {
      //  dd($arr);
        $id_kota = rtrim($ar['id_kota']);
        $kota = Kota::find($id_kota);

        if(ltrim(rtrim($ar['id'])) == null){
          try {
        RiwayatPelatihan::create([
              'id_talenta' => $this->id ,
              'jenis_diklat' => 'Diklat Jabatan',
              'id_kota' => $id_kota ,
              'kota' => $kota->pluck('name')->first() ,
              'penyelenggara' => rtrim($ar['penyelenggara']),
              'lama_hari' => rtrim($ar['lama_diklat']) ,
              'pengembangan_kompetensi' => rtrim($ar['nama_pendidikan_dan_latihanpengembangan_kompetensi']) ,
              'nomor_sertifikasi' => rtrim($ar['nomor_sertifikasi']),
           ]);
      }
       catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel Diklat Jabatan Invalid');
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
              'jenis_diklat' => 'Diklat Jabatan',
              'id_kota' => $id_kota ,
              'kota' => $kota->nama,
              'penyelenggara' => rtrim($ar['penyelenggara']),
              'lama_hari' => rtrim($ar['lama_diklat']) ,
              'pengembangan_kompetensi' => rtrim($ar['nama_pendidikan_dan_latihanpengembangan_kompetensi']) ,
              'nomor_sertifikasi' => rtrim($ar['nomor_sertifikasi']),
            ]);
          }
           catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel Diklat Jabatan Invalid');
         }
        }
       }
    }







    public function headingRow(): int
    {
        return $this->suffix;
    }




}
