<?php

namespace App\Imports\backup;

use Illuminate\Support\Facades\Hash;
use App\Talenta;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\CVInterest;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Facades\Excel;
use App\Agama ;
use App\CVSummary;
use App\Kota;
use App\Provinsi;
use App\Helpers\CVHelper;



class ImportTalenta implements ToCollection  , WithMappedCells, WithMultipleSheets
{

    public function __construct($row = [],$nama_file = "" ){

        $this->row = $row ;
        $this->nama_file = $nama_file ;


    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function collection(Collection $row)
    {
            $talenta = Talenta::where('nama_lengkap',rtrim($row['nama_lengkap']))->first() ;
            if($talenta != null ){
                $row['id_talenta'] = $talenta->id;
            }

            $flag  = false ;
            $arr = collect();
            if($row['id_talenta'] != null ){
                if(Talenta::find(rtrim($row['id_talenta'])) != null){
                $flag = true ;
                $arr->id = rtrim($row['id_talenta']);
                }
            }

            if(!$flag){
                try {
            $arr = Talenta::create([
                   'nama_lengkap' => rtrim($row['nama_lengkap']),
                    'gelar' => rtrim($row['gelar_akademik']),
                    'nik' => rtrim($row['nik']),
                    'tempat_lahir' => rtrim($row['tempat_lahir']),
                    'tanggal_lahir' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir']),
                    'jenis_kelamin' => rtrim($row['jk']),
                    'id_gol_darah' => rtrim($row['id_gol_darah']),
                    'gol_darah' => rtrim($row['gol_darah']),
                    'id_suku' => rtrim($row['id_suku']),
                    'suku' => rtrim($row['suku']),
                    'id_agama' => rtrim($row['id_agama']),
                    'agama' => rtrim($row['agama']),
                    'id_status_kawin' => rtrim($row['id_status_kawin']),
                    'jabatan_asal_instansi' => rtrim($row['jabatan_indv']),
                    'alamat' => rtrim($row['alamat']),
                    'nomor_hp' => rtrim($row['handphone']),
                    'email' => rtrim($row['email']),
                    'npwp' => rtrim($row['npwp']),

                ]);

            $status = CVHelper::updatePersentase($arr->id);
        }
       catch(\Exception $ex){
     return redirect(route('cv.board.index'))->with('error', 'Biodata Belum Lengkap / Salah Tipe');

}
            CVSummary::create([
                'id_talenta' => $arr->id,
                'kompetensi' => rtrim($row['kompetensi']),
                'kepribadian' => rtrim($row['kepribadian']),
            ]);

            CVInterest::create([
                'id_talenta' => rtrim($arr->id),
                'ekonomi' => rtrim($row['ekonomi']),
                'leadership' => rtrim($row['leadership']),
                'sosial' => rtrim($row['sosial']),
            ]);
            }
            else {
                try {

                Talenta::find($arr->id)
                ->update([
                    'nama_lengkap' => rtrim($row['nama_lengkap']),
                    'gelar' => rtrim($row['gelar_akademik']),
                    'nik' => rtrim($row['nik']),
                    'tempat_lahir' => rtrim($row['tempat_lahir']),
                    'tanggal_lahir' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_lahir']),
                    'jenis_kelamin' => rtrim($row['jk']),
                    'id_gol_darah' => rtrim($row['id_gol_darah']),
                    'gol_darah' => rtrim($row['gol_darah']),
                    'id_suku' => rtrim($row['id_suku']),
                    'suku' => rtrim($row['suku']),
                    'id_agama' => rtrim($row['id_agama']),
                    'id_status_kawin' => rtrim($row['id_status_kawin']),
                    'status_kawin' => rtrim($row['status_kawin']),
                    'alamat' => rtrim($row['alamat']),
                    'nomor_hp' => rtrim($row['handphone']),
                    'email' => rtrim($row['email']),
                    'npwp' => rtrim($row['npwp']),

                ]);
                $status = CVHelper::updatePersentase($arr->id);

                CVSummary::where('id_talenta',$arr->id)
                ->update([
                    'kompetensi' => rtrim($row['kompetensi']),
                    'kepribadian' => rtrim($row['kepribadian']),
                ]);

                CVInterest::where('id_talenta',$arr->id)
                ->update([
                    'ekonomi' => rtrim($row['ekonomi']),
                    'leadership' => rtrim($row['leadership']),
                    'sosial' => rtrim($row['sosial']),
                ]);
             }catch(\Exception $ex){
    return redirect(route('cv.board.index'))->with('error', 'Biodata Belum Lengkap / Salah Tipe');

 }

            }
             Excel::import(new ImportKeahlian($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));

             // Excel::import(new ImportJabatan($this->row,$arr->id), public_path('uploads/cv/'.$this->nama_file));
           Excel::import(new ImportRiwayatJabatan($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));
           Excel::import(new ImportOrganisasiFormal($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportOrganisasiNonFormal($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));
             Excel::import(new ImportPenghargaan($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));
             Excel::import(new ImportPendidikanFormal($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));
             Excel::import(new ImportPendidikanKompetensi($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));
          Excel::import(new ImportPendidikanFungsional($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));
             Excel::import(new ImportKTI($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));
             Excel::import(new ImportPengalamanNarasumber($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));
             Excel::import(new ImportReferensi($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));
             Excel::import(new ImportKetPasangan($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));
             Excel::import(new ImportKetAnak($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));
              Excel::import(new ImportSocialMedia($this->row,$arr->id,$flag), public_path('uploads/cv/'.$this->nama_file));

    }

    public function mapping(): array
    {
        return [
            'id_talenta' => 'DJ15',
            'nama_lengkap'  => 'E16',
            'gelar_akademik' => 'E17',
            'nik' => 'E18',
            'id_kota' => 'E19',
            'tempat_lahir' => 'E20',
            'tanggal_lahir' => 'E21',
            'jk'  => 'E22' ,
            'id_gol_darah'  => 'E23' ,
            'gol_darah'  => 'E24' ,
            'id_suku'  => 'E25' ,
            'suku'  => 'E26' ,
            'id_agama'  => 'E27' ,
            'agama'  => 'E28' ,
            'id_status_kawin'  => 'E29' ,
            'status_kawin'  => 'E30' ,
            'alamat'  => 'E31' ,
            'handphone'  => 'E32' ,
            'email'  => 'E33' ,
            'npwp'  => 'E34' ,
            'kompetensi' => 'C40',
            'kepribadian' => 'C41',
            'ekonomi' => 'C46',
            'leadership' => 'C49',
            'sosial' => 'C52',
        ];
    }





    public function headingRow(): int
    {
        return 53;
    }




}
