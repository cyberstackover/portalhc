<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use App\DataKeluarga;
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

class ImportKetPasangan implements ToCollection   , WithHeadingRow, WithMultipleSheets 
{

    public function __construct($row,$id ){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Nama')->slice(1,1)->keys()->first();
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
            if($r['nama'] == null){
                break ;
            }
            $arr->push($r);
        }

         $saveid = collect();
        foreach ($arr as $ar) {
          if($ar['id']!= null)
            $saveid->push(rtrim(ltrim($ar['id'])));
        }
      
        $prim = DataKeluarga::where('id_talenta',$this->id)->get();
        $iddel = collect();
        foreach($prim as $p){
          $iddel->push($p->id);
        }
        $iddel = $iddel->diff($saveid);
        if ( $iddel != null )
          DataKeluarga::destroy($iddel);



       foreach ($arr as $ar) {
           if (strtoupper($ar['suamiistri']) == 'ISTRI' ){
            $jk = 'Wanita' ;
            $hk = 'Istri' ;
           }
            else {
                $jk = 'Pria' ;
                $hk = 'Suami' ;
            }
            $id_kota = rtrim($ar['id_kota']);
            if (is_numeric($id_kota) )
             $kota = Kota::find($id_kota);
            else
             $kota = null ;
            if ($kota == null)
               return redirect(route('cv.board.index'))->with('error', 'ID Kota pada tabel keterangan pasangan invalid');

            if(ltrim(rtrim($ar['id'])) == null){
              try {

        DataKeluarga::create([
              'id_talenta' => $this->id ,
              'nama' => rtrim($ar['nama']) ,
              'id_kota' => $id_kota ,
              'tempat_lahir' => $kota->nama ,
              'tanggal_lahir' => rtrim($ar['tgl_lahir']),
              'tanggal_menikah' => rtrim($ar['tgl_menikah']) ,
              'pekerjaan' => rtrim($ar['pekerjaan']) ,
              'keterangan' => rtrim($ar['keterangan']) ,
              'hubungan_keluarga' => $hk  ,
              'jenis_kelamin'  => $jk  ,
           ]);
      }  catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel Keterangan Pasangan Invalid');
         }
        }
        else {
          try {
            DataKeluarga::updateOrCreate(
              [
                'id'=> rtrim(ltrim($ar['id'])),
                'id_talenta' => $this->id,
            ],[
               'id_talenta' => $this->id ,
              'nama' => rtrim($ar['nama']) ,
              'id_kota' => $id_kota ,
              'tempat_lahir' => $kota->nama ,
              'tanggal_lahir' => rtrim($ar['tgl_lahir']),
              'tanggal_menikah' => rtrim($ar['tgl_menikah']) ,
              'pekerjaan' => rtrim($ar['pekerjaan']) ,
              'keterangan' => rtrim($ar['keterangan']) ,
        'hubungan_keluarga' => $hk  ,
         'jenis_kelamin'  => $jk  ,
             ]);
          }  catch(\Exception $e){
            
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Tabel Keterangan Pasangan Invalid');
         }
        }


       }
    }






    public function headingRow(): int
    {
        return $this->suffix;
    }




}
