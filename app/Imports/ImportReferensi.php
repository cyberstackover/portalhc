<?php

namespace App\Imports;

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

         $saveid = collect();
        foreach ($arr as $ar) {
          if($ar['id']!= null)
            $saveid->push(rtrim(ltrim($ar['id'])));
        }
      
        $prim = ReferensiCV::where('id_talenta',$this->id)->get();
        $iddel = collect();
        foreach($prim as $p){
          $iddel->push($p->id);
        }
        $iddel = $iddel->diff($saveid);
        if ( $iddel != null )
          ReferensiCV::destroy($iddel);





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
            ReferensiCV::updateOrCreate(
              [
                'id'=> rtrim(ltrim($ar['id'])),
                'id_talenta' => $this->id,
            ],[
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
