<?php

namespace App\Imports\backup;

use Illuminate\Support\Facades\Hash;
use App\RiwayatOrganisasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportOrganisasiFormal implements ToCollection   , WithHeadingRow, WithMultipleSheets
{

    public function __construct($row,$id ){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Nama Kegiatan/Organisasi')->keys()->first();
        // dd($this->suffix);
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
        $date = strtotime(date('Y-m-d'));



        foreach($row as $r) {

            if($r['nama_kegiatanorganisasi'] == null){
                break ;
            }
            $arr->push($r);
        }

       foreach ($arr as $ar) {
        $tanggal = explode('-',$ar['rentang_waktu']);
        for ( $i = 0  ; $i < count($tanggal) ; $i++ ){
            $tanggal[$i] =  str_replace(' ','',$tanggal[$i]);
        }
        if (count($tanggal) < 3) {
          return redirect(route('cv.board.index'))->with('error', 'Rentang Waktu Organisasi Formal Invalid, Gunakan YYYY-MM-DD - YYYY-MM-DD');
       }
        $awal = $tanggal[0].'-'.$tanggal[1].'-'.$tanggal[2];
        $akhir = null ;
        if ( count($tanggal) == 6 )
        $akhir = $tanggal[3].'-'.$tanggal[4].'-'.$tanggal[5];
        if(ltrim(rtrim($ar['id'])) == null){
          try {
           RiwayatOrganisasi::create([
              'id_talenta' => $this->id ,
              'nama_organisasi' =>  rtrim($ar['nama_kegiatanorganisasi']) ,
              'jabatan' => rtrim($ar['jabatan']),
              'tanggal_awal' => $awal ,
              'tanggal_akhir' => $akhir ,
              'masih_aktif' => strtotime($akhir) >= $date ? 1  : 0 ,
              'formal_flag' => true ,
              'kegiatan_organisasi' =>rtrim($ar['uraian_singkat_kegiatanorganisasi']),
           ]);
         }
            catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Rentang Waktu Organisasi Formal Invalid, Gunakan YYYY-MM-DD - YYYY-MM-DD');
         }
        }
        else{
          try {
            RiwayatOrganisasi::where('id',ltrim(rtrim($ar['id'])))->where('id_talenta',$this->id)
            ->update([
                'id_talenta' => $this->id ,
                'nama_organisasi' => rtrim($ar['nama_kegiatanorganisasi']) ,
                'jabatan' => rtrim($ar['jabatan']),
                'tanggal_awal' => $awal ,
                'tanggal_akhir' => $akhir ,
                'masih_aktif' => strtotime($akhir) >= $date ? 1  : 0 ,
                'formal_flag' => true ,
                'kegiatan_organisasi' => rtrim($ar['uraian_singkat_kegiatanorganisasi']),
            ]);
          }
             catch(\Exception $e){
           DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Rentang Waktu Organisasi Formal Invalid, Gunakan YYYY-MM-DD - YYYY-MM-DD');
         }
        }

       }
    }







    public function headingRow(): int
    {
        return $this->suffix;
    }




}
