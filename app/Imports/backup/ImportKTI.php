<?php

namespace App\Imports\backup;

use Illuminate\Support\Facades\Hash;
use App\DataKaryaIlmiah;
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

class ImportKTI implements ToCollection   , WithHeadingRow, WithMultipleSheets
{

    public function __construct($row,$id ){
        $this->row = $row;
        //dd($row);
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Judul')->keys()->first();
        //dd($this->suffix);
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
            if($r['judul'] == null){
                break ;
            }
            $arr->push($r);
        }

         $saveid = collect();
        foreach ($arr as $ar) {
          if($ar['id']!= null)
            $saveid->push(rtrim(ltrim($ar['id'])));
        }

        $prim = DataKaryaIlmiah::where('id_talenta',$this->id)->get();
        $iddel = collect();
        foreach($prim as $p){
          $iddel->push($p->id);
        }
        $iddel = $iddel->diff($saveid);
        if ( $iddel != null )
          DataKaryaIlmiah::destroy($iddel);


       foreach ($arr as $ar) {
           /*$data = explode('/', $ar['judul_dan_media_publikasi']);
           $judul = " ";
           $media = " ";

           if(count($data) >= 1)
           $judul = rtrim($data[0]);
           if(count($data) >=2 )
           $media = ltrim($data[1]);
           if ( $judul == null || $media == null ){
            return redirect(route('cv.board.index'))->with('error', 'Judul/Media Pada Tabel KTI Invalid');
           }*/
           if(ltrim(rtrim($ar['id'])) == null){
            try {
           DataKaryaIlmiah::create([
              'id_talenta' => $this->id ,
              'judul' => $ar['judul'],
              'media_publikasi' => $ar['media_publikasi'],
              'tahun' => rtrim($ar['tahun']) ? rtrim($ar['tahun']) : null,
           ]);
         }
           catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel KTII Invalid '.$e->getMessage());
         }
           }
           else {
            try {
            DataKaryaIlmiah::updateOrCreate(
              [
                'id'=> rtrim(ltrim($ar['id'])),
                'id_talenta' => $this->id,
            ],[
                'id_talenta' => $this->id ,
                'judul' => $ar['judul'],
                'media_publikasi' => $ar['media_publikasi'],
                'tahun' => rtrim($ar['tahun']) ? rtrim($ar['tahun']) : null,
             ]);
          }
            catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel KTI Invalid');
         }
           }

       }
    }






    public function headingRow(): int
    {
        return $this->suffix;
    }




}
