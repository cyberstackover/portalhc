<?php

namespace App\Imports\backup;

use Illuminate\Support\Facades\Hash;
use App\ReferensiCV;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportReferensi implements ToCollection   , WithHeadingRow, WithMultipleSheets
{
    public function __construct($row,$id ){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Nama')->keys()->first();
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
            // dd($r);
            if($r['jabatan'] == null){
                break ;
            }
            $arr->push($r);
        }

       foreach ($arr as $ar) {
        if(ltrim(rtrim($ar['id'])) == null){
          try {
           ReferensiCV::create([
              'id_talenta' => $this->id ,
              'nama' => rtrim($ar['nama']) ,
              'perusahaan' => rtrim($ar['perusahaan']) ,
              'jabatan' => rtrim($ar['jabatan']),
              'nomor_handphone' => rtrim($ar['nomor_handphone']) ,

           ]);
         }
           catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel Referensi Invalid');
         }
        }
        else {
          try {
            ReferensiCV::where('id',ltrim(rtrim($ar['id'])))->where('id_talenta',$this->id)
            ->update([
                'id_talenta' => $this->id ,
                'nama' => rtrim($ar['nama']) ,
                'perusahaan' => rtrim($ar['perusahaan']) ,
                'jabatan' => rtrim($ar['jabatan']),
                'nomor_handphone' => rtrim($ar['nomor_handphone']) ,

             ]);
          }
            catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel Referensi Invalid');
         }
        }

       }
    }


    public function headingRow(): int
    {
        return $this->suffix;
    }




}
