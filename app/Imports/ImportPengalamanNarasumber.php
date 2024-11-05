<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use App\PengalamanLain;
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

class ImportPengalamanNarasumber implements ToCollection   , WithHeadingRow, WithMultipleSheets 
{

    public function __construct($row,$id ){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Acara')->keys()->first();
        ++$this->suffix;
        //++$this->suffix;



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
            //dd($r);
            if($r['acara'] == null){
                break ;
            }
            $arr->push($r);
        }

         $saveid = collect();
        foreach ($arr as $ar) {
          if($ar['id']!= null)
            $saveid->push(rtrim(ltrim($ar['id'])));
        }
      
        $prim = PengalamanLain::where('id_talenta',$this->id)->get();
        $iddel = collect();
        foreach($prim as $p){
          $iddel->push($p->id);
        }
        $iddel = $iddel->diff($saveid);
        if ( $iddel != null )
          PengalamanLain::destroy($iddel);



       foreach ($arr as $ar) {
        if(ltrim(rtrim($ar['id'])) == null){
          try {
           PengalamanLain::create([
              'id_talenta' => $this->id ,
              'acara' => rtrim($ar['acara']) ,
              'penyelenggara' => rtrim($ar['penyelenggara']),
              'periode' => rtrim($ar['tahun']) ? rtrim($ar['tahun']) : null ,
              'lokasi' => rtrim($ar['lokasi']),
              'peserta' => rtrim($ar['peserta']),
           ]);
         }
           catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel Pengalaman Narasumber Invalid');
         }
        }
        else {
          try {
            PengalamanLain::updateOrCreate(
              [
                'id'=> rtrim(ltrim($ar['id'])),
                'id_talenta' => $this->id,
            ],[
                'id_talenta' => $this->id ,
                'acara' => rtrim($ar['acara']) ,
              'penyelenggara' => rtrim($ar['penyelenggara']),
                'periode' => rtrim($ar['tahun']) ? rtrim($ar['tahun']) : null ,
                'lokasi' => rtrim($ar['lokasi']),
                'peserta' => rtrim($ar['peserta']),
             ]);
          }
            catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Update Data Tabel Pengalaman Narasumber Invalid');
         }
        }

       }
    }






    public function headingRow(): int
    {
        return $this->suffix;
    }




}
