<?php

namespace App\Imports\backup;

use Illuminate\Support\Facades\Hash;
use App\Keahlian;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use App\TransactionTalentaKeahlian ;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class ImportKeahlian implements ToCollection   , WithMappedCells, WithMultipleSheets
{

    public function __construct($row,$id,$flag ){
        $this->row = $row ;
        $this->id = $id ;
        $this->flag = $flag ;
        $this->suffix = $this->row->where('3','Keuangan')->keys()->first();
        ++$this->suffix;
        $this->barisCell = ['E','H','L'] ;
        $cnt = 0 ;
        $this->keahlian = Keahlian::get()->pluck('id');
        $this->data = collect();
        for ($j =  0 ; $j < $this->keahlian->count() ; $j ++) {
            $this->data->put($j,$this->barisCell[$cnt].$this->suffix  );
            $cnt++ ;
            if ($cnt == 3 ){
                $this->suffix++;
                $cnt = $cnt % 3 ;
            }
        }


    }

      public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }
    public function collection(Collection $row)
    {
        if($this->flag){
            for ($i = 0 ; $i < $row->count() ; $i++ ) {
                if (ltrim(rtrim($row[$i])) != null ){
                    if(TransactionTalentaKeahlian::where('id_talenta',$this->id)->where('id_keahlian',$this->keahlian[$i])->count() == 0    ) {
                    TransactionTalentaKeahlian::create([
                        'id_talenta' => $this->id ,
                        'id_keahlian' => $this->keahlian[$i] ,
                    ]);
                    }

                }
                else {
                $TTK =  TransactionTalentaKeahlian::where('id_talenta',$this->id)->where('id_keahlian',$this->keahlian[$i])->first() ;
                if ($TTK != null ){
                        $TTK->delete();
                    }

                }
            }
        }
        else{
            for ($i = 0 ; $i < $row->count() ; $i++ ) {
                if (ltrim(rtrim($row[$i])) != null ){
                TransactionTalentaKeahlian::create([
                    'id_talenta' => $this->id ,
                    'id_keahlian' => $this->keahlian[$i] ,
                ]);
                }
            }

        }

    }

    public function mapping(): array
    {
        return $this->data->toArray();
    }






    public function headingRow(): int
    {
        return $this->suffix;
    }




}
