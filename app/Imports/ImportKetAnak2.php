<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use App\DataKeluargaAnak;
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

class ImportKetAnak2 implements ToCollection   , WithHeadingRow, WithMultipleSheets 
{
    public function __construct($row,$id ){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Nama')->slice(2,2)->keys()->first();
        ++$this->suffix;


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
      
        $prim = DataKeluargaAnak::where('id_talenta',$this->id)->get();
        $iddel = collect();
        foreach($prim as $p){
          $iddel->push($p->id);
        }
        $iddel = $iddel->diff($saveid);
        if ( $iddel != null )
          DataKeluargaAnak::destroy($iddel);


       foreach ($arr as $ar) {
          $jenis_kelamin = rtrim($ar['jenis_kelamin']);
           if ($jenis_kelamin == 'L' ){
            $jk = 'Wanita' ;
            $hk = 'Istri' ;
           }
            else {
                $jk = 'Pria' ;
                $hk = 'Suami' ;
            }
          $cekkota = Kota::where('nama', strtoupper(rtrim($ar['tempat_lahir'])))->first();
          if(is_null($cekkota)){
            return redirect(route('cv.board.index'))->with('error', 'Import Talenta GAGAL --> Bagian Anak, Tempat Lahir Tidak Sesuai Referensi Kota');
          }
          $id_kota = $cekkota->id;
        if (is_numeric($id_kota) )
             $kota = Kota::find($id_kota);
            else
             $kota = null ;
            /*if ($kota == null)
               return redirect(route('cv.board.index'))->with('error', 'ID Kota pada tabel keterangan anak invalid');*/
       
        if(ltrim(rtrim($ar['id'])) == null){
             try {
        DataKeluargaAnak::create([
              'id_talenta' => $this->id ,
              'nama' => rtrim($ar['nama']) ,
              'id_kota' => $id_kota ,
              'tempat_lahir' => $kota->nama ,
              'tanggal_lahir' => rtrim(\Carbon\Carbon::createFromFormat('d/m/Y', $ar['tanggal_lahir'])->format('Y-m-d')),
              'jenis_kelamin' => $jk ,
              'pekerjaan' => rtrim($ar['pekerjaan']) ,
              'keterangan' => rtrim($ar['keterangan']) ,


           ]);
    }
    catch(\Exception $e){
            
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi data anak Gagal: '.$e->getMessage());
         }
        }
        else {
            try {
            DataKeluargaAnak::updateOrCreate(
              [
                'id'=> rtrim(ltrim($ar['id'])),
                'id_talenta' => $this->id,
            ],[
                'id_talenta' => $this->id ,
                'nama' => rtrim($ar['nama']) ,
                'id_kota' => $id_kota ,
              'tempat_lahir' => $kota->nama  ,
                'tanggal_lahir' => rtrim(\Carbon\Carbon::createFromFormat('d/m/Y', $ar['tanggal_lahir'])->format('Y-m-d')),
                'jenis_kelamin' => $jk ,
                'pekerjaan' => rtrim($ar['pekerjaan']) ,
                'keterangan' => rtrim($ar['keterangan']) ,

             ]);
        }
        catch(\Exception $e){
           
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Update Data Tabel Keterangan Anak Invalid');
         }

        }
       }
        //return back()->with('success', 'Import Selesai');
    }

   




    public function headingRow(): int
    {
        return $this->suffix;
    }




}
