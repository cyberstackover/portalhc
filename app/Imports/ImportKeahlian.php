<?php

namespace App\Imports;

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

    public function __construct($row,$id){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Keuangan')->keys()->first();
        //dd($this->suffix);
        ++$this->suffix;
        $this->barisCell = ['E','G','I','K','M'] ;
        $cnt = 0 ;
        $this->keahlian = Keahlian::whereIn('id',array(1,20,21,22,23,24,25,26,27,28,29,30,31,32))->orderBy('id','ASC')->get()->pluck('id');
        $this->data = collect();
        for ($j =  0 ; $j < $this->keahlian->count() ; $j ++) {
            $this->data->put($j,$this->barisCell[$cnt].$this->suffix  );
            $cnt++ ;
            if ($cnt == 5 ){
                $this->suffix++;
                $cnt = $cnt % 5 ;
            }
        }


    }

      public function sheets(): array
    {
        return [
            1 => $this,
        ];
    }
    public function collection(Collection $row)
    {
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

    public function mapping(): array
    {
        return $this->data->toArray();
    }






    public function headingRow(): int
    {
        return $this->suffix;
    }




}
