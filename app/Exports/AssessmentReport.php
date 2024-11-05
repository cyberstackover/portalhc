<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;

class AssessmentReport implements FromCollection, WithHeadings
{

	public function headings(): array
    {
        return [
           ['No', 'Nama Talent', 'NIK', 'Hasil Assessment']
        ];
    }

    public function collection()
    {
    	$id_sql = "SELECT assessment_nilai.hasil, talenta.nama_lengkap, talenta.nik from assessment_nilai
                    LEFT JOIN talenta on talenta.id = assessment_nilai.id_talenta
                    ORDER BY talenta.nama_lengkap";
         $isiadmin  = DB::select(DB::raw($id_sql));
         $collections = new Collection;
         $no = 1;
         foreach($isiadmin as $val){

                $collections->push([
                    'no' => $no,
                    'nama_lengkap' => $val->nama_lengkap,
                    'nik' => "'".$val->nik,
                    'hasil_assessment' => $val->hasil,
                ]);
                $no++;
            }
        return $collections;
    }
}
