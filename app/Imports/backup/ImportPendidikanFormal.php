<?php

namespace App\Imports\backup;

use Illuminate\Support\Facades\Hash;
use App\RiwayatPendidikan;
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
        $this->suffix = $this->row->where('3','ID Jenjang')->keys()->first();
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
        foreach($row as $r) {

            if(rtrim($r['id_jenjang']) == null){
                break ;
            }
            $arr->push($r);
        }
       foreach ($arr as $ar) {
           $id_kota = rtrim($ar['id_kota']);
           $id_jenjang_pendidikan =  rtrim($ar['id_jenjang']);
           $checkKota = Kota::find($id_kota);
           $checkJenjang = JenjangPendidikan::find($id_jenjang_pendidikan);
           $negara = Provinsi::where('id',$checkKota->provinsi_id)->first();
           if ($negara->is_luar_negeri){
            $negara = $negara->nama ;
           }
           else{
            $negara = 'INDONESIA';
           }

          if($checkJenjang == null || $checkKota == null){
            if($checkJenjang == null && $checkKota == null)
                 return redirect(route('cv.board.index'))->with('error', 'ID Jenjang dan ID Kota pada tabel Pendidikan Formal Invalid');
            else if ($checkKota == null)
               return redirect(route('cv.board.index'))->with('error', 'ID Kota pada tabel Pendidikan Formal invalid');
             else
               return redirect(route('cv.board.index'))->with('error', 'ID Kota pada tabel Pendidikan Formal invalid');
          }


            if(ltrim(rtrim($ar['id'])) == null){
              try{
            RiwayatPendidikan::create([
              'id_talenta' => $this->id ,
              'id_jenjang_pendidikan' =>  rtrim($ar['id_jenjang']),
              'penjurusan' => rtrim($ar['penjurusan']),
              'perguruan_tinggi' => rtrim($ar['perguruan_tinggi']),
              'tahun' => rtrim($ar['tahun_lulus']) ,
              'id_kota' => $id_kota,
              'kota' =>$checkKota->pluck('nama')->first(),
              'negara' =>$negara,
              'penghargaan' => rtrim($ar['penghargaan']),
           ]);
          }
           catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel Pendidikan Formal Invalid');
         }
            }
            else {
              try {
                RiwayatPendidikan::where('id',ltrim(rtrim($ar['id'])))->where('id_talenta',$this->id)
                ->update([
                'id_talenta' => $this->id ,
              'id_jenjang_pendidikan' =>  rtrim($ar['id_jenjang']),
              'penjurusan' => rtrim($ar['penjurusan']),
              'perguruan_tinggi' => rtrim($ar['perguruan_tinggi']),
              'tahun' => rtrim($ar['tahun_lulus']) ,
              'id_kota' => $id_kota,
              'kota' =>$checkKota->pluck('nama')->first(),
              'negara' =>$negara,
              'penghargaan' => rtrim($ar['penghargaan']),
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
