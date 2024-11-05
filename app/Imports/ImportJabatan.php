<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\RiwayatJabatanDirkomwas;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class ImportJabatan implements ToCollection  , WithValidation , WithHeadingRow, WithMultipleSheets 
{
    public function __construct($row,$id ){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Jabatan')->keys()->first();
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

            if($r['jabatan'] == null){
                break ;
            }
            $arr->push($r);
        }
       foreach ($arr as $ar) {
        RiwayatJabatanDirkomwas::create([
              'id_talenta' => $this->id ,
              'jabatan' => $ar['jabatan'] ,
              'uraian_singkat' => $ar['uraian_singkat_tugas_dan_kewenangan'],
              'rentang_waktu' => $ar['rentang_waktu_dimulai_dari_jabatan_terakhir'] ,
              'achievement' => $ar['achievement_maksimal_5_pencapaian'],
           ]);
       }
    }

    public function mapping(): array
    {
        return [
            'jabatan'  => $this->jabatan,
            'uraian_singkat' => $this->uraian_singkat,
            'rentang_waktu' => $this->rentang_waktu,
            'achievement' => $this->achievement,
        ];
    }





    public function headingRow(): int
    {
        return $this->suffix;
    }




}
