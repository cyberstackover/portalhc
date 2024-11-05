<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use App\ClusterBumn;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use App\TransactionTalentaCluster ;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class ImportCluster implements ToCollection   , WithMappedCells, WithMultipleSheets 
{

    public function __construct($row,$id){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Energi & Migas')->keys()->first();
        //dd($this->suffix);
        ++$this->suffix;
        $this->barisCell = ['E','G','I','K'] ;
        $cnt = 0 ;
        $this->cluster = ClusterBumn::orderBy('id','ASC')->get()->pluck('id');
        $this->data = collect();
        for ($j =  0 ; $j < $this->cluster->count() ; $j ++) {
            $this->data->put($j,$this->barisCell[$cnt].$this->suffix  );
            $cnt++ ;
            if ($cnt == 4 ){
                $this->suffix++;
                $cnt = $cnt % 4 ;
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
            //dd($row);
            for ($i = 0 ; $i < $row->count() ; $i++ ) {
                if (ltrim(rtrim($row[$i])) != null ){
                    if(TransactionTalentaCluster::where('id_talenta',$this->id)->where('id_cluster',$this->cluster[$i])->count() == 0    ) {
                    TransactionTalentaCluster::create([
                        'id_talenta' => $this->id ,
                        'id_cluster' => $this->cluster[$i] ,
                    ]);
                    }

                }
                else {
                $TTC =  TransactionTalentaCluster::where('id_talenta',$this->id)->where('id_cluster',$this->cluster[$i])->first() ;
                if ($TTC != null ){
                        $TTC->delete();
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
