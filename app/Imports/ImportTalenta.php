<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use App\Talenta;
use App\GolonganDarah;
use App\Suku;
use App\Universitas;
use App\StatusKawin ;

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
use Illuminate\Support\Facades\DB;



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

           $id_talenta =  rtrim($row['id_talenta']);
           $id_agama = rtrim($row['id_agama']);
           $id_goldar =   rtrim($row['id_golongan_darah']) ;
           $id_suku =   rtrim($row['id_golongan_darah']) ;
           $id_status_kawin = rtrim($row['id_status_kawin']);
           if ( $id_agama == NULL )
              return redirect(route('cv.board.index'))->with('error', 'ID Agama tidak boleh kosong');
           if ($id_goldar == null ){
              return redirect(route('cv.board.index'))->with('error', 'ID Golongan Darah tidak boleh kosong');
           }
           if ( $id_suku == NULL )
                return redirect(route('cv.board.index'))->with('error', 'ID suku tidak boleh kosong');
           if ( $id_status_kawin == NULL )
           return redirect(route('cv.board.index'))->with('error', 'ID status kawin tidak boleh kosong');
          $agama = Agama::find($id_agama)->pluck('nama')->first() ;
          //$agama = Agama::find($id_agama)->pluck('nama')->first() ;
        
          $goldar = GolonganDarah::where('id',$id_goldar)->pluck('nama')->first();
       
          $suku = Suku::where('id',$id_suku)->pluck('nama')->first();
          $status_kawin = StatusKawin::where('id',$id_status_kawin)->pluck('nama')->first();
            if (Talenta::find($id_talenta != null)){  
                try {
                Talenta::find($id_talenta)
                ->update([
                    'nama_lengkap' => rtrim($row['nama_lengkap']),
                    'gelar' => rtrim($row['gelar_akademik']),
                    'nik' => rtrim($row['nik']),
                    'tempat_lahir' => rtrim($row['tempat_lahir']),
                    'tanggal_lahir' => rtrim($row['tanggal_lahir']),
                    'jenis_kelamin' => rtrim($row['jk']),
                    'id_golongan_darah' => $id_goldar,
                    'gol_darah' => $goldar,
                    'id_suku' => $id_suku,
                    'suku' => $suku,
                    'id_agama' => $id_agama,
                    'id_status_kawin' => $id_status_kawin ,
                    'status_kawin' => $status_kawin,
                    'alamat' => rtrim($row['alamat']),
                    'nomor_hp' => rtrim($row['handphone']),
                    'email' => rtrim($row['email']),
                    'npwp' => rtrim($row['npwp']),
                   
                ]);

                CVSummary::updateOrCreate(
                    [
                        'id_talenta' => $id_talenta ]
                        ,
                [
                    'kompetensi' => rtrim($row['kompetensi']),
                    'kepribadian' => rtrim($row['kepribadian']),
                ]);

                CVInterest::updateOrCreate(['id_talenta'=> $id_talenta],
                    [
                    'ekonomi' => rtrim($row['ekonomi']),
                    'leadership' => rtrim($row['leadership']),
                    'sosial' => rtrim($row['sosial']),
                ]);
             }
             catch(\Exception $ex){ 
                DB::rollBack();
    return redirect(route('cv.board.index'))->with('error', 'Biodata Belum Lengkap / Salah Tipe Data. Pastikan Date Menggunakan Format YYYY-MM-DD');
 
 }
}
else {
 return redirect(route('cv.board.index'))->with('error', 'CV Illegal / User Not Found');

}
    Excel::import(new ImportKeahlian($this->row,$id_talenta),public_path('uploads/cv/'.$this->nama_file));
    Excel::import(new ImportRiwayatJabatan($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
    Excel::import(new ImportOrganisasiFormal($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
    Excel::import(new ImportOrganisasiNonFormal($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
    Excel::import(new ImportPenghargaan($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
   Excel::import(new ImportPendidikanFormal($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
    Excel::import(new ImportPendidikanKompetensi($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
    Excel::import(new ImportPendidikanFungsional($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
   Excel::import(new ImportKTI($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
    Excel::import(new ImportPengalamanNarasumber($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
    Excel::import(new ImportReferensi($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
    Excel::import(new ImportKetPasangan($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
    Excel::import(new ImportKetAnak($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
   Excel::import(new ImportSocialMedia($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));

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
            'id_golongan_darah' => 'E23',
            'gol_darah' => 'E24',
            'id_suku' => 'E25' ,
            'suku' => 'E26',
            'id_agama' => 'E27',
            'agama' => 'E28',
            'id_status_kawin' => 'E29',
            'status_kawin' => 'E30',
            'alamat' => 'E31',
            'handphone' => 'E32',
            'email' => 'E33',
            'npwp' => 'E34' ,
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
