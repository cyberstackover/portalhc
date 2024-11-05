<?php

namespace App\Imports\backup;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Summary;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Interest;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Imports\ImportTalenta;
use Maatwebsite\Excel\Facades\Excel;


class RowImport implements ToCollection, WithMultipleSheets
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function __construct($nama_file = "" ){
       $this->nama_file = $nama_file ;
    }

    public function collection(Collection $row)
    {
            $ImportTalenta = new ImportTalenta($row,$this->nama_file);
            $ImportTalenta->sheets();
            Excel::import($ImportTalenta, public_path('uploads/cv/'.$this->nama_file));

    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }






}
