<?php

namespace App\Imports\backup;

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
              'kota' => $kota->pluck('nama')->first() ,
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
            RiwayatPelatihan::where('id',ltrim(rtrim($ar['id'])))->where('id_talenta',$this->id)
            ->update([
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
