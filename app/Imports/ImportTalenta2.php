<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use App\Talenta;
use App\GolonganDarah;
use App\Suku;
use App\Universitas;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\CVInterest;
use App\CVNilai;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Facades\Excel;
use App\Agama ;
use App\CVSummary;
use App\Kota;
use App\Provinsi;
use App\StatusKawin;
use Illuminate\Support\Facades\DB;
use App\Helpers\CVHelper;



class ImportTalenta2 implements ToCollection  , WithMappedCells, WithMultipleSheets 
{

    public function __construct($row = [],$nama_file = "" ){
        ini_set('memory_limit', '-1');
        $this->row = $row ;
        $this->nama_file = $nama_file ;


    }

    public function sheets(): array
    {
        return [
            1 => $this,
        ];
    }

    public function collection(Collection $row)
    {

           // read data talent
           $id_talenta =  rtrim($row['id_talenta']);
           $nama_lengkap =  rtrim($row['nama_lengkap']);
           $gelar_akademik = rtrim($row['gelar_akademik']);
           $nik =   str_replace("'", "", rtrim($row['nik']));
           $tempat_lahir =   rtrim($row['tempat_lahir']);
           $tanggal_lahir =   rtrim($row['tanggal_lahir']);
           $kelamin =   rtrim($row['jk']);
           $gol_darah =   rtrim($row['gol_darah']);
           $suku =   rtrim($row['suku']);
           $agama =   rtrim($row['agama']);
           $status_kawin =   rtrim($row['status_kawin']);
           $alamat =   rtrim($row['alamat']);
           $handphone =   rtrim($row['handphone']);
           $email =   rtrim($row['email']);
           $npwp =   str_replace("'", "", rtrim($row['npwp']));
           

           //get id kota
           $cekkota = Kota::where('nama',strtoupper($tempat_lahir))->first();
           if(is_null($cekkota)){
            return redirect(route('cv.board.index'))->with('error', 'Import Talenta GAGAL --> Kota Tempat Lahir Tidak Sesuai Referensi');
           }
           $id_kota = $cekkota->id;

           //get referensi jenis kelamin
           if ($kelamin == 'Pilih Jenis Kelamin' ){
              return redirect(route('cv.board.index'))->with('error', 'Import Talenta GAGAL --> Jenis Kelamin Harus Dipilih');
           }
           if($kelamin == 'Laki - Laki'){
            $jk = 'L';
           } else {
            $jk = 'P';
           }

           
           // get id agama
           if ($agama == 'Pilih Agama' ){
              return redirect(route('cv.board.index'))->with('error', 'Import Talenta GAGAL --> Agama Harus Dipilih');
           }
           $cekagama = Agama::where('nama',$agama)->first();
           $id_agama = $cekagama->id;

           //get id suku
           if ($suku == 'Pilih Suku' ){
              return redirect(route('cv.board.index'))->with('error', 'Import Talenta GAGAL --> Suku Harus Dipilih');
           }
           $ceksuku = Suku::where('nama', $suku)->first();
           $id_suku = $ceksuku->id;

           //get id golongan darah
           if ($gol_darah == 'Pilih Golongan Darah' ){
              return redirect(route('cv.board.index'))->with('error', 'Import Talenta GAGAL --> Golongan Darah Harus Dipilih');
           }
           $cekgoldar = GolonganDarah::where('nama', $gol_darah)->first();
           $id_golongan_darah = $cekgoldar->id;

           //get id status kawin
           if ($status_kawin == 'Pilih Status Kawin' ){
              return redirect(route('cv.board.index'))->with('error', 'Import Talenta GAGAL --> Status Kawin Harus Dipilih');
           }
           $cekstatuskawin = StatusKawin::where('nama', $status_kawin)->first();
           $id_status_kawin = $cekstatuskawin->id;

           if($id_talenta==''){
            return redirect(route('cv.board.index'))->with('error', 'CV Illegal / User Not Found');
           }

            try {
                $param = [
                    'nama_lengkap' => $nama_lengkap,
                    'gelar' => $gelar_akademik,
                    'nik' => str_replace("'", '', $nik),
                    'tempat_lahir' => $tempat_lahir,
                    'tanggal_lahir' => \Carbon\Carbon::createFromFormat('d/m/Y', $tanggal_lahir)->format('Y-m-d'),
                    'jenis_kelamin' => $jk,
                    'id_golongan_darah' => $id_golongan_darah,
                    'gol_darah' => $gol_darah,
                    'id_suku' => $id_suku,
                    'suku' => $suku,
                    'id_agama' => $id_agama,
                    'id_status_kawin' => $id_status_kawin ,
                    'status_kawin' => $status_kawin,
                    'alamat' => $alamat,
                    'nomor_hp' => str_replace("'", '', $handphone),
                    'email' => $email,
                    'npwp' => str_replace("'", '', $npwp)
                ];

                if($id_talenta!=''){
                    $talenta = Talenta::find($id_talenta)->update($param);
                }else{
                    $talenta = Talenta::create($param);
                    $id_talenta = $talenta->id;
                }

                CVInterest::updateOrCreate(['id_talenta'=> $id_talenta],
                    [
                    'interest' => rtrim($row['interest'])
                ]);

                CVNilai::updateOrCreate(['id_talenta'=> $id_talenta],
                    [
                    'nilai' => rtrim($row['nilai'])
                ]);
            }
            catch(\Exception $ex){ 
                DB::rollBack();
                return redirect(route('cv.board.index'))->with('error', 'Biodata Belum Lengkap / Salah Tipe Data. Pastikan Date Menggunakan Format DD/MM/YYYY');

            }

            Excel::import(new ImportKelas($this->row,$id_talenta),public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportCluster($this->row,$id_talenta),public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportKeahlian($this->row,$id_talenta),public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportRiwayatPekerjaan($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportRiwayatJabatan($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportOrganisasiFormal($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportOrganisasiNonFormal($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportPenghargaan($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportPendidikanFormal($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportPendidikanFungsional($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportKTI($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportPengalamanNarasumber($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportReferensi2($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportKetPasangan2($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportKetAnak2($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
            Excel::import(new ImportSocialMedia($this->row,$id_talenta), public_path('uploads/cv/'.$this->nama_file));
            
    }

    public function mapping(): array
    {
        return [
            'id_talenta' => 'DJ5',
            'nama_lengkap'  => 'E6',
            'gelar_akademik' => 'E7',
            'nik' => 'E8',
            'id_kota' => 'E19',
            'tempat_lahir' => 'E9',
            'tanggal_lahir' => 'E10',
            'jk'  => 'E11' ,
            'gol_darah' => 'E19',
            'suku' => 'E18',
            'agama' => 'E12',
            'status_kawin' => 'E20',
            'alamat' => 'E14',
            'handphone' => 'E15',
            'email' => 'E16',
            'npwp' => 'E17' ,
            'interest' => 'C30',
            'nilai' => 'C26'
        ];
    }





    public function headingRow(): int
    {
        return 53;
    }




}
