<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use App\KelasBumn;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use App\TransactionTalentaKelas ;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class ImportKelas implements ToCollection   , WithMappedCells, WithMultipleSheets 
{

    public function __construct($row,$id){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Kelas 1')->keys()->first();
        //dd($this->suffix);
        ++$this->suffix;
        $this->barisCell = ['E','G','I','K','M'] ;
        $cnt = 0 ;
        $this->kelas = KelasBumn::get()->pluck('id');
        $this->data = collect();
        for ($j =  0 ; $j < $this->kelas->count() ; $j ++) {
            $this->data->put($j,$this->barisCell[$cnt].$this->suffix  );
            $cnt++ ;
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
            //dd($row);
            for ($i = 0 ; $i < $row->count() ; $i++ ) {
                if (ltrim(rtrim($row[$i])) != null ){
                    if(TransactionTalentaKelas::where('id_talenta',$this->id)->where('id_kelas',$this->kelas[$i])->count() == 0    ) {
                    TransactionTalentaKelas::create([
                        'id_talenta' => $this->id ,
                        'id_kelas' => $this->kelas[$i] ,
                    ]);
                    }

                }
                else {
                $TTK =  TransactionTalentaKelas::where('id_talenta',$this->id)->where('id_kelas',$this->kelas[$i])->first() ;
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
