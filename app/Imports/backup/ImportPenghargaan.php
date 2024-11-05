<?php

namespace App\Imports\backup;

use Illuminate\Support\Facades\Hash;
use App\DataPenghargaan;
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

class ImportPenghargaan implements ToCollection   , WithHeadingRow, WithMultipleSheets
{

    public function __construct($row,$id ){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Jenis Penghargaan')->keys()->first();

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
        // dd($row);
        foreach($row as $r) {

            if($r['jenis_penghargaan'] == null){
                break ;
            }
            $arr->push($r);
        }

       foreach ($arr as $ar) {
        if(ltrim(rtrim($ar['id'])) == null){
            try {
           DataPenghargaan::create([
            'id_talenta' => $this->id ,
            'jenis_penghargaan' => rtrim($ar['jenis_penghargaan']) ,
            'tingkat' => rtrim($ar['tingkat']),
            'pemberi_penghargaan' => rtrim($ar['diberikan_oleh']) ,
            'tahun' => rtrim($ar['tahun']),

           ]);
       }
        catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Penghargaan Invalid');
         }
        }
        else {
            try {
            DataPenghargaan::where('id',ltrim(rtrim($ar['id'])))->where('id_talenta',$this->id)
            ->update([
                'id_talenta' => $this->id ,
                'jenis_penghargaan' => rtrim($ar['jenis_penghargaan']) ,
                'tingkat' => rtrim($ar['tingkat']),
                'pemberi_penghargaan' => rtrim($ar['diberikan_oleh']) ,
                'tahun' => rtrim($ar['tahun']),

                ]);
        }
         catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Data Penghargaan Invalid');
         }
            }


       }
    }






    public function headingRow(): int
    {
        return $this->suffix;
    }




}
