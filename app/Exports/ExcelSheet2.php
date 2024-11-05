<?php

namespace App\Exports;

use App\Talenta;
use App\CVSummary;
use App\CVInterest;
use App\CVNilai;
use App\RiwayatJabatanLain ;
use App\RiwayatJabatanDirkomwas ;
use App\TransactionTalentaKeahlian ;
use App\TransactionTalentaKelas ;
use App\TransactionTalentaCluster ;
use App\Keahlian ;
use App\DataKeluargaAnak;
use App\DataKeluarga;
use App\DataKaryaIlmiah;
use App\RiwayatOrganisasi;
use App\RiwayatPendidikan;
use App\RiwayatPelatihan;
use App\PengalamanLain;
use App\DataPenghargaan;
use App\ReferensiCV;
use App\Agama ;
use App\JenjangPendidikan;
use App\BidangJabatan;
use App\Kota;
use App\Provinsi;
use App\StatusKawin;
use App\SocialMedia;
use App\TransactionTalentaSocialMedia;
use App\Suku;
use App\GolonganDarah;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Input;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\NamedRange;

class ExcelSheet2 implements WithEvents
{
    public function __construct($id = "" ){
        $this->id = $id ;


    }
    /**
    * Export data 
    * @author Matin Malek
    * @return Array
    */
    public function registerEvents(): array
    {

      return [
         BeforeExport::class => function(BeforeExport $event){
            $event->writer->reopen(new \Maatwebsite\Excel\Files\LocalTemporaryFile(storage_path('template_cv.xlsx')),Excel::XLSX);
            $sheet = $event->writer->getSheetByIndex(1);

            
            $getTalenta = Talenta::find($this->id);

            if(empty(@$getTalenta->jenis_kelamin)){
                $jkelamin = 'Pilih Jenis Kelamin';
            } else {
                if(@$getTalenta->jenis_kelamin == 'L'){
                    $jkelamin = 'Laki - Laki';
                } elseif (@$getTalenta->jenis_kelamin == 'P') {
                    $jkelamin = 'Perempuan';
                } else {
                    $jkelamin = 'Pilih Jenis Kelamin';
                }
            }
            
            $sheet->setCellValue('E11', $jkelamin);
            $kelamins = "Laki - Laki, Perempuan";
            $objValidation = $sheet->getCell('E11')->getDataValidation();
            $objValidation->setType(DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setErrorTitle('Input error');
            $objValidation->setError('Value is not in list.');
            $objValidation->setPromptTitle('Pilih Jenis Kelamin');
            $objValidation->setPrompt('Silakan Pilih Salah Satu');
            $objValidation->setFormula1('"' . $kelamins . '"');

            /**
             * validation for Agama
             */
            if(empty(@$getTalenta->id_agama)){
                $jagama = "Pilih Agama";
            } else {
                $getAgama = Agama::find(@@$getTalenta->id_agama);
                if(empty($getAgama->nama)){
                    $jagama = "Pilih Agama";
                } else {
                    $jagama = $getAgama->nama;
                }
            }

            $sheet->setCellValue('E12', $jagama);
            $agamas = "Islam, Katolik, Kristen Protestan, Hindu, Budha, Kong Hu Cu, Kepercayaan";
            $objValidation = $sheet->getCell('E12')->getDataValidation();
            $objValidation->setType(DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setErrorTitle('Input error');
            $objValidation->setError('Value is not in list.');
            $objValidation->setPromptTitle('Pilih Jenis Agama');
            $objValidation->setPrompt('Silakan Pilih Salah Satu');
            $objValidation->setFormula1('"' . $agamas . '"');

            /**
             * validation for Suku
             */
            if(empty(@$getTalenta->suku)){
                $jsuku = "Pilih Suku";
            } else {
                $jsuku = @$getTalenta->suku;
            }

            $sheet->setCellValue('E18', $jsuku);
            $getsukus = Suku::get();
            $collectsukus = collect($getsukus);
            $sukus = $collectsukus->implode('nama', ',');
            $objValidation = $sheet->getCell('E18')->getDataValidation();
            $objValidation->setType(DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setErrorTitle('Input error');
            $objValidation->setError('Value is not in list.');
            $objValidation->setPromptTitle('Pilih Jenis Suku');
            $objValidation->setPrompt('Silakan Pilih Salah Satu');
            $objValidation->setFormula1('"' . $sukus . '"');

            /**
             * validation for Golongan Darah
             */
            if(empty(@$getTalenta->gol_darah)){
                $jgoldar = "Pilih Golongan Darah";
            } else {
                $jgoldar = @$getTalenta->gol_darah;
            }

            $sheet->setCellValue('E19', $jgoldar);
            $getdarahs = GolonganDarah::get();
            $collectdarahs = collect($getdarahs);
            $darahs = $collectdarahs->implode('nama', ',');
            $objValidation = $sheet->getCell('E19')->getDataValidation();
            $objValidation->setType(DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setErrorTitle('Input error');
            $objValidation->setError('Value is not in list.');
            $objValidation->setPromptTitle('Pilih Jenis Golongan Darah');
            $objValidation->setPrompt('Silakan Pilih Salah Satu');
            $objValidation->setFormula1('"' . $darahs . '"');

            /**
             * validation for Status Kawin
             */
            if(empty(@$getTalenta->id_status_kawin)){
                $jstatus = "Pilih Status Kawin";
            } else {
                $getStatus = StatusKawin::where('id',@$getTalenta->id_status_kawin)->pluck('nama')->first();
                if(empty($getStatus)){
                    $jstatus = "Pilih Status Kawin";
                } else {
                    $jstatus = $getStatus;
                }
            }

            $sheet->setCellValue('E20', $jstatus);
            $getkawins = StatusKawin::get();
            $collectkawins = collect($getkawins);
            $kawins = $collectkawins->implode('nama', ',');
            $objValidation = $sheet->getCell('E20')->getDataValidation();
            $objValidation->setType(DataValidation::TYPE_LIST);
            $objValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $objValidation->setAllowBlank(false);
            $objValidation->setShowInputMessage(true);
            $objValidation->setShowErrorMessage(true);
            $objValidation->setShowDropDown(true);
            $objValidation->setErrorTitle('Input error');
            $objValidation->setError('Value is not in list.');
            $objValidation->setPromptTitle('Pilih Dari Daftar');
            $objValidation->setPrompt('Silakan Pilih Salah Satu');
            $objValidation->setFormula1('"' . $kawins . '"');

            
            $jabatan_terakhir = DB::select("select s.nomenklatur_jabatan as jabatan, p.nama_lengkap
                                    from view_organ_perusahaan v
                                    left join struktur_organ s on v.id_struktur_organ = s.id
                                    left join perusahaan p on p.id = s.id_perusahaan
                                    where v.id_talenta = $this->id
                                    and v.aktif = 't'
                                    order by s.urut ASC 
                                    limit 1");
            $sheet->setCellValue('DJ5', @$getTalenta->id);
            $sheet->setCellValue('E6', @$getTalenta->nama_lengkap);
            $sheet->setCellValue('E7', @$getTalenta->gelar);
            $sheet->setCellValue('E8', "'".str_replace("'", "", @$getTalenta->nik));
            $sheet->setCellValue('E9', @$getTalenta->tempat_lahir);
            $sheet->setCellValue('E10', \Carbon\Carbon::parse(@$getTalenta->tanggal_lahir)->format('d/m/Y'));
            $sheet->setCellValue('E13', @$jabatan_terakhir[0]->jabatan);
            $sheet->setCellValue('E14', @$getTalenta->alamat);
            $sheet->setCellValue('E15', "'".@$getTalenta->nomor_hp);
            $sheet->setCellValue('E16', @$getTalenta->email);
            $sheet->setCellValue('E17', "'".str_replace("'", "", @$getTalenta->npwp));

            $sheet->setCellValue('K110', "Jakarta, ".date('d/m/Y'));
            $sheet->setCellValue('K115', strtoupper(@$getTalenta->nama_lengkap));
            
            $styleArray =
                [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE]
                    ],
                    'font' => [
                        'bold'      =>  false,
                        'color' => ['argb' => '000000'],
                    ]
                ];
            $styleArray_center =
                [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE]
                    ],
                    'font' => [
                        'bold'      =>  false,
                        'color' => ['argb' => '000000'],
                    ]
                ];
            $styleArray_left =
                [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE]
                    ],
                    'font' => [
                        'bold'      =>  false,
                        'color' => ['argb' => '000000'],
                    ]
                ];

            
            $nilai = CVNilai::where('id_talenta', $this->id)->first();
            $interest = CVInterest::where('id_talenta', $this->id)->first();
            $sheet->setCellValue('C26', @$nilai->nilai);
            $sheet->setCellValue('C30', @$interest->interest);
            $sheet->styleCells('C26',$styleArray);
            $sheet->styleCells('C30',$styleArray);

            
            $socialmedia = TransactionTalentaSocialMedia::where('id_talenta', $this->id)->get();
            $sheet->setCellValue('DJ100', 'id');
            foreach($socialmedia as $d){
                if(@$d->socialMedia->nama == 'Facebook'){
                    $sheet->setCellValue('DJ101', @$d->socialMedia->id);
                    $sheet->setCellValue('F101', $d->name_social_media);
                }else if(@$d->socialMedia->nama == 'Instagram'){
                    $sheet->setCellValue('DJ102', @$d->socialMedia->id);
                    $sheet->setCellValue('F102', $d->name_social_media);
                }else if(@$d->socialMedia->nama == 'Twitter'){
                    $sheet->setCellValue('DJ103', @$d->socialMedia->id);
                    $sheet->setCellValue('F103', $d->name_social_media);
                }else if(@$d->socialMedia->nama == 'Linkedin'){
                    $sheet->setCellValue('DJ104', @$d->socialMedia->id);
                    $sheet->setCellValue('F104', $d->name_social_media);
                }
                $sheet->styleCells('F101:F104',$styleArray);
            }
            

            $trans_kelas = TransactionTalentaKelas::where('id_talenta', $this->id)->get();
            foreach($trans_kelas as $d){
                if($d->id_kelas == 1){
                    $sheet->setCellValue('E36', 'X');
                }else if($d->id_kelas == 2){
                    $sheet->setCellValue('G36', 'X');
                }else if($d->id_kelas == 3){
                    $sheet->setCellValue('I36', 'X');
                }else if($d->id_kelas == 4){
                    $sheet->setCellValue('K36', 'X');
                }else if($d->id_kelas == 5){
                    $sheet->setCellValue('M36', 'X');
                }
            }
            
            $trans_cluster = TransactionTalentaCluster::where('id_talenta', $this->id)->orderBy('id_cluster','ASC')->get();
            foreach($trans_cluster as $d){
                if($d->id_cluster == 1){
                    $sheet->setCellValue('E38', 'X');//E38
                }else if($d->id_cluster == 2){
                    $sheet->setCellValue('G38', 'X');//G38
                }else if($d->id_cluster == 3){
                    $sheet->setCellValue('I38', 'X');//I38
                }else if($d->id_cluster == 4){
                    $sheet->setCellValue('K38', 'X');//K38
                }else if($d->id_cluster == 5){
                    $sheet->setCellValue('E39', 'X');//E39
                }else if($d->id_cluster == 6){
                    $sheet->setCellValue('G39', 'X');//G39
                }else if($d->id_cluster == 7){
                    $sheet->setCellValue('I39', 'X');//I39
                }else if($d->id_cluster == 8){
                    $sheet->setCellValue('K39', 'X');//K39
                }else if($d->id_cluster == 9){
                    $sheet->setCellValue('E40', 'X');//E40
                }else if($d->id_cluster == 10){
                    $sheet->setCellValue('G40', 'X');//G40
                }else if($d->id_cluster == 11){
                    $sheet->setCellValue('I40', 'X');//I40
                }else if($d->id_cluster == 12){
                    $sheet->setCellValue('K40', 'X');//K40
                }
            }

            $trans_keahlian = TransactionTalentaKeahlian::where('id_talenta', $this->id)->orderBy('id_keahlian','ASC')->get();
            foreach($trans_keahlian as $d){
                if(@$d->id_keahlian == 1){
                    $sheet->setCellValue('E42', 'X');
                }else if(@$d->id_keahlian == 20){
                    $sheet->setCellValue('G42', 'X');
                }else if(@$d->id_keahlian == 21){
                    $sheet->setCellValue('I42', 'X');
                }else if(@$d->id_keahlian == 22){
                    $sheet->setCellValue('K42', 'X');
                }else if(@$d->id_keahlian == 23){
                    $sheet->setCellValue('M42', 'X');
                }else if(@$d->id_keahlian == 24){
                    $sheet->setCellValue('E43', 'X');
                }else if(@$d->id_keahlian == 25){
                    $sheet->setCellValue('G43', 'X');
                }else if(@$d->id_keahlian == 26){
                    $sheet->setCellValue('I43', 'X');
                }else if(@$d->id_keahlian == 27){
                    $sheet->setCellValue('K43', 'X');
                }else if(@$d->id_keahlian == 28){
                    $sheet->setCellValue('M43', 'X');
                }else if(@$d->id_keahlian == 29){
                    $sheet->setCellValue('E44', 'X');
                }else if(@$d->id_keahlian == 30){
                    $sheet->setCellValue('G44', 'X');
                }else if(@$d->id_keahlian == 31){
                    $sheet->setCellValue('I44', 'X');
                }else if(@$d->id_keahlian == 32){
                    $sheet->setCellValue('K44', 'X');
                }
            }


            $DataKeluargaAnak = DataKeluargaAnak::where('id_talenta',$this->id)->get() ;
            $i = 96;
            $no = 1;
            $sheet->setCellValue('DJ95', 'id');
            foreach($DataKeluargaAnak as $d){
                $sheet->insertNewRowBefore($i, 1);
                $sheet->setCellValue('DJ'.$i, $d->id);
                $sheet->setCellValue('C'.$i, $no);
                $sheet->setCellValue('D'.$i, $d->nama);
                $sheet->setCellValue('E'.$i, $d->tempat_lahir);
                $sheet->setCellValue('F'.$i, \Carbon\Carbon::parse($d->tanggal_lahir)->format('d/m/Y'));
                $sheet->setCellValue('G'.$i, $d->jenis_kelamin);
                $sheet->setCellValue('H'.$i, $d->pekerjaan);
                $sheet->setCellValue('K'.$i, $d->keterangan);
                
                
                $sheet->mergeCells('H'.$i.':J'.$i);
                $sheet->mergeCells('K'.$i.':M'.$i);
                $sheet->styleCells('C'.$i.':M'.$i,$styleArray_center);
                $sheet->styleCells('D'.$i,$styleArray);
                $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);

                $no++;
                $i++;
            }
            if(count($DataKeluargaAnak)==0) {
                $j = $i;
                for($i=$j; $i<$j+3; $i++){
                    $sheet->insertNewRowBefore($i, 1);
                    $sheet->mergeCells('H'.$i.':J'.$i);
                    $sheet->mergeCells('K'.$i.':M'.$i);
                    $sheet->styleCells('C'.$i.':M'.$i,$styleArray_center);
                    $sheet->styleCells('D'.$i,$styleArray);
                    $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                }
            }
            
            $DataKeluarga = DataKeluarga::where('id_talenta',$this->id)->get() ;
            $i = 93;
            $no = 1;
            $sheet->setCellValue('DJ92', 'id');
            foreach($DataKeluarga as $d){
                $sheet->insertNewRowBefore($i, 1);
                $sheet->setCellValue('DJ'.$i, $d->id);
                $sheet->setCellValue('C'.$i, $no);
                $sheet->setCellValue('D'.$i, $d->nama);
                $sheet->setCellValue('E'.$i, $d->tempat_lahir);
                $sheet->setCellValue('F'.$i, \Carbon\Carbon::parse($d->tanggal_lahir)->format('d/m/Y'));
                $sheet->setCellValue('G'.$i, $d->jenis_kelamin);
                $sheet->setCellValue('H'.$i, $d->pekerjaan);
                $sheet->setCellValue('I'.$i, $d->keterangan);
                $sheet->setCellValue('K'.$i, \Carbon\Carbon::parse($d->tanggal_menikah)->format('d/m/Y'));
                
                $sheet->mergeCells('I'.$i.':J'.$i);
                $sheet->mergeCells('K'.$i.':M'.$i);
                $sheet->styleCells('C'.$i.':M'.$i,$styleArray_center);
                $sheet->styleCells('D'.$i,$styleArray);
                $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);

                $no++;
                $i++;
            }
            if(count($DataKeluarga)==0) {
                $j = $i;
                for($i=$j; $i<$j+3; $i++){
                    $sheet->insertNewRowBefore($i, 1);
                    $sheet->mergeCells('I'.$i.':J'.$i);
                    $sheet->mergeCells('K'.$i.':M'.$i);
                    $sheet->styleCells('C'.$i.':M'.$i,$styleArray_center);
                    $sheet->styleCells('D'.$i,$styleArray);
                    $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                }
            }
            
            $ReferensiCV = ReferensiCV::where('id_talenta',$this->id)->get() ;
            $i = 88;
            $no = 1;
            $sheet->setCellValue('DJ87', 'id');
            foreach($ReferensiCV as $d){
                $sheet->insertNewRowBefore($i, 1);
                $sheet->setCellValue('DJ'.$i, $d->id);
                $sheet->setCellValue('C'.$i, $no);
                $sheet->setCellValue('D'.$i, $d->nama);
                $sheet->setCellValue('F'.$i, $d->perusahaan);
                $sheet->setCellValue('H'.$i, $d->jabatan);
                $sheet->setCellValue('K'.$i, "'".$d->nomor_handphone);
                
                $sheet->mergeCells('D'.$i.':E'.$i);
                $sheet->mergeCells('F'.$i.':G'.$i);
                $sheet->mergeCells('H'.$i.':J'.$i);
                $sheet->styleCells('C'.$i.':M'.$i,$styleArray);
                $sheet->styleCells('C'.$i,$styleArray_center);
                $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);

                $no++;
                $i++;
            }
            if(count($ReferensiCV)==0) {
                $j = $i;
                for($i=$j; $i<$j+3; $i++){
                    $sheet->insertNewRowBefore($i, 1);
                    $sheet->mergeCells('D'.$i.':E'.$i);
                    $sheet->mergeCells('F'.$i.':G'.$i);
                    $sheet->mergeCells('H'.$i.':J'.$i);
                    $sheet->styleCells('C'.$i.':M'.$i,$styleArray);
                    $sheet->styleCells('C'.$i,$styleArray_center);
                    $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                }
            }
            
            $PengalamanLain = PengalamanLain::where('id_talenta',$this->id)->get() ;
            $i = 83;
            $no = 1;
            $sheet->setCellValue('DJ82', 'id');
            foreach($PengalamanLain as $d){
                $sheet->insertNewRowBefore($i, 1);
                $sheet->setCellValue('DJ'.$i, $d->id);
                $sheet->setCellValue('C'.$i, $no);
                $sheet->setCellValue('D'.$i, $d->acara);
                $sheet->setCellValue('F'.$i, $d->penyelenggara);
                $sheet->setCellValue('H'.$i, $d->periode);
                $sheet->setCellValue('I'.$i, $d->lokasi);
                $sheet->setCellValue('K'.$i, $d->peserta);
                
                $sheet->mergeCells('D'.$i.':E'.$i);
                $sheet->mergeCells('F'.$i.':G'.$i);
                $sheet->mergeCells('I'.$i.':J'.$i);
                $sheet->mergeCells('K'.$i.':M'.$i);
                $sheet->styleCells('C'.$i.':M'.$i,$styleArray);
                $sheet->styleCells('C'.$i,$styleArray_center);
                $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);

                $no++;
                $i++;
            }
            if(count($PengalamanLain)==0) {
                $j = $i;
                for($i=$j; $i<$j+3; $i++){
                    $sheet->insertNewRowBefore($i, 1);
                    $sheet->mergeCells('D'.$i.':E'.$i);
                    $sheet->mergeCells('F'.$i.':G'.$i);
                    $sheet->mergeCells('I'.$i.':J'.$i);
                    $sheet->mergeCells('K'.$i.':M'.$i);
                    $sheet->styleCells('C'.$i.':M'.$i,$styleArray);
                    $sheet->styleCells('C'.$i,$styleArray_center);
                    $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                }
            }
            
            $DataKaryaIlmiah = DataKaryaIlmiah::where('id_talenta',$this->id)->get() ;
            $i = 79;
            $no = 1;
            $sheet->setCellValue('DJ78', 'id');
            foreach($DataKaryaIlmiah as $d){
                $sheet->insertNewRowBefore($i, 1);
                $sheet->setCellValue('DJ'.$i, $d->id);
                $sheet->setCellValue('C'.$i, $no);
                $sheet->setCellValue('D'.$i, $d->judul);
                $sheet->setCellValue('F'.$i, $d->media_publikasi);
                $sheet->setCellValue('K'.$i, $d->tahun);
                
                $sheet->mergeCells('D'.$i.':E'.$i);
                $sheet->mergeCells('F'.$i.':J'.$i);
                $sheet->mergeCells('K'.$i.':M'.$i);
                $sheet->styleCells('C'.$i.':M'.$i,$styleArray_center);
                $sheet->styleCells('D'.$i,$styleArray);
                $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);

                $no++;
                $i++;
            }
            if(count($DataKaryaIlmiah)==0) {
                $j = $i;
                for($i=$j; $i<$j+3; $i++){
                    $sheet->insertNewRowBefore($i, 1);
                    $sheet->mergeCells('D'.$i.':E'.$i);
                    $sheet->mergeCells('F'.$i.':J'.$i);
                    $sheet->mergeCells('K'.$i.':M'.$i);
                    $sheet->styleCells('C'.$i.':M'.$i,$styleArray_center);
                    $sheet->styleCells('D'.$i,$styleArray);
                    $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                }
            }
            
            $RiwayatPelatihan = RiwayatPelatihan::where('id_talenta',$this->id)->get() ;
            $i = 75;
            $no = 1;
            $sheet->setCellValue('DJ74', 'id');
            foreach($RiwayatPelatihan as $d){
                
                $sheet->insertNewRowBefore($i, 1);
                $sheet->setCellValue('DJ'.$i, $d->id);
                $sheet->setCellValue('C'.$i, $no);
                $sheet->setCellValue('D'.$i, $d->pengembangan_kompetensi);
                $sheet->setCellValue('F'.$i, $d->penyelenggara);
                $sheet->setCellValue('G'.$i, $d->kota);
                $sheet->setCellValue('H'.$i, $d->tahun);
                $sheet->setCellValue('I'.$i, $d->nomor_sertifikasi);
                $sheet->setCellValue('K'.$i, @$d->refJenis->nama);
                $sheet->setCellValue('M'.$i, @$d->refTingkat->nama);
                
                $sheet->mergeCells('D'.$i.':E'.$i);
                $sheet->mergeCells('I'.$i.':J'.$i);
                $sheet->mergeCells('K'.$i.':L'.$i);
                $sheet->styleCells('C'.$i.':J'.$i,$styleArray_center);
                $sheet->styleCells('K'.$i.':M'.$i,$styleArray_left);
                $sheet->styleCells('D'.$i,$styleArray);
                $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                
                $jenis = "Diklat Jabatan, Diklat Fungsional";
                $objValidation = $sheet->getCell('L'.$i)->getDataValidation();
                $objValidation->setType(DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pilih Jenis Diklat');
                $objValidation->setPrompt('Silakan Pilih Salah Satu');
                $objValidation->setFormula1('"' . $jenis . '"');

                $no++;
                $i++;
            }
            if(count($RiwayatPelatihan)==0) {
                $j = $i;
                for($i=$j; $i<$j+3; $i++){
                    $sheet->insertNewRowBefore($i, 1);
                    $sheet->mergeCells('D'.$i.':E'.$i);
                    $sheet->mergeCells('I'.$i.':J'.$i);
                    $sheet->mergeCells('K'.$i.':L'.$i);
                    $sheet->styleCells('C'.$i.':J'.$i,$styleArray_center);
                    $sheet->styleCells('K'.$i.':M'.$i,$styleArray_left);
                    $sheet->styleCells('D'.$i,$styleArray);
                    $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                    
                    $jenis = "Diklat Jabatan, Diklat Fungsional";
                    $objValidation = $sheet->getCell('L'.$i)->getDataValidation();
                    $objValidation->setType(DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setShowDropDown(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('Value is not in list.');
                    $objValidation->setPromptTitle('Pilih Jenis Diklat');
                    $objValidation->setPrompt('Silakan Pilih Salah Satu');
                    $objValidation->setFormula1('"' . $jenis . '"');
                }
            }
            
            $RiwayatPendidikan = RiwayatPendidikan::where('id_talenta',$this->id)->get() ;
            $i = 72;
            $no = 1;
            $sheet->setCellValue('DJ71', 'id');
            foreach($RiwayatPendidikan as $d){
                $sheet->insertNewRowBefore($i, 1);
                $sheet->setCellValue('DJ'.$i, $d->id);
                $sheet->setCellValue('C'.$i, $no);
                $sheet->setCellValue('D'.$i, @$d->jenjangPendidikan->nama);
                $sheet->setCellValue('E'.$i, $d->penjurusan);
                $sheet->setCellValue('G'.$i, $d->tahun);
                $sheet->setCellValue('H'.$i, $d->perguruan_tinggi);
                $sheet->setCellValue('I'.$i, $d->kota);
                $sheet->setCellValue('J'.$i, $d->negara);
                $sheet->setCellValue('K'.$i, $d->penghargaan);
                
                $sheet->mergeCells('E'.$i.':F'.$i);
                $sheet->mergeCells('K'.$i.':M'.$i);
                $sheet->styleCells('C'.$i.':M'.$i,$styleArray_center);
                $sheet->styleCells('D'.$i,$styleArray);
                $sheet->styleCells('H'.$i,$styleArray);
                $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                
                $getjenjang = JenjangPendidikan::get();
                $collectjenjang = collect($getjenjang);
                $jenjang = $collectjenjang->implode('nama', ',');
                $objValidation = $sheet->getCell('D'.$i)->getDataValidation();
                $objValidation->setType(DataValidation::TYPE_LIST);
                $objValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $objValidation->setAllowBlank(false);
                $objValidation->setShowInputMessage(true);
                $objValidation->setShowErrorMessage(true);
                $objValidation->setShowDropDown(true);
                $objValidation->setErrorTitle('Input error');
                $objValidation->setError('Value is not in list.');
                $objValidation->setPromptTitle('Pilih Jenis Jenjang Pendidikan');
                $objValidation->setPrompt('Silakan Pilih Salah Satu');
                $objValidation->setFormula1('"' . $jenjang . '"');

                $no++;
                $i++;
            }
            if(count($RiwayatPendidikan)==0) {
                $j = $i;
                for($i=$j; $i<$j+3; $i++){
                    $sheet->insertNewRowBefore($i, 1);
                    $sheet->mergeCells('E'.$i.':F'.$i);
                    $sheet->mergeCells('K'.$i.':M'.$i);
                    $sheet->styleCells('C'.$i.':M'.$i,$styleArray_center);
                    $sheet->styleCells('D'.$i,$styleArray);
                    $sheet->styleCells('H'.$i,$styleArray);
                    $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                    
                    $getjenjang = JenjangPendidikan::get();
                    $collectjenjang = collect($getjenjang);
                    $jenjang = $collectjenjang->implode('nama', ',');
                    $objValidation = $sheet->getCell('D'.$i)->getDataValidation();
                    $objValidation->setType(DataValidation::TYPE_LIST);
                    $objValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                    $objValidation->setAllowBlank(false);
                    $objValidation->setShowInputMessage(true);
                    $objValidation->setShowErrorMessage(true);
                    $objValidation->setShowDropDown(true);
                    $objValidation->setErrorTitle('Input error');
                    $objValidation->setError('Value is not in list.');
                    $objValidation->setPromptTitle('Pilih Jenis Jenjang Pendidikan');
                    $objValidation->setPrompt('Silakan Pilih Salah Satu');
                    $objValidation->setFormula1('"' . $jenjang . '"');
                }
            }
            
            $DataPenghargaan = DataPenghargaan::where('id_talenta',$this->id)->get() ;
            $i = 67;
            $no = 1;
            $sheet->setCellValue('DJ66', 'id');
            foreach($DataPenghargaan as $d){
                $sheet->insertNewRowBefore($i, 1);
                $sheet->setCellValue('DJ'.$i, $d->id);
                $sheet->setCellValue('C'.$i, $no);
                $sheet->setCellValue('D'.$i, $d->jenis_penghargaan);
                $sheet->setCellValue('F'.$i, $d->tingkat);
                $sheet->setCellValue('H'.$i, $d->pemberi_penghargaan);
                $sheet->setCellValue('K'.$i, $d->tahun);
                
                $sheet->mergeCells('D'.$i.':E'.$i);
                $sheet->mergeCells('F'.$i.':G'.$i);
                $sheet->mergeCells('H'.$i.':J'.$i);
                $sheet->mergeCells('K'.$i.':M'.$i);
                $sheet->styleCells('C'.$i.':M'.$i,$styleArray_center);
                $sheet->styleCells('D'.$i,$styleArray);
                $sheet->styleCells('H'.$i,$styleArray);
                $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);

                $no++;
                $i++;
            }
            if(count($DataPenghargaan)==0) {
                $j = $i;
                for($i=$j; $i<$j+3; $i++){
                    $sheet->insertNewRowBefore($i, 1);
                    $sheet->mergeCells('D'.$i.':E'.$i);
                    $sheet->mergeCells('F'.$i.':G'.$i);
                    $sheet->mergeCells('H'.$i.':J'.$i);
                    $sheet->mergeCells('K'.$i.':M'.$i);
                    $sheet->styleCells('C'.$i.':M'.$i,$styleArray_center);
                    $sheet->styleCells('D'.$i,$styleArray);
                    $sheet->styleCells('H'.$i,$styleArray);
                    $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                }
            }
            
            $RiwayatOrganisasi = RiwayatOrganisasi::where('id_talenta',$this->id)->where('formal_flag',false)->get() ;
            $i = 62;
            $no = 1;
            $sheet->setCellValue('DJ61', 'id');
            foreach($RiwayatOrganisasi as $d){
                $sheet->insertNewRowBefore($i, 1);
                $sheet->setCellValue('DJ'.$i, $d->id);
                $sheet->setCellValue('C'.$i, $no);
                $sheet->setCellValue('D'.$i, $d->nama_organisasi);
                $sheet->setCellValue('E'.$i, $d->jabatan);
                //$sheet->setCellValue('H'.$i, \Carbon\Carbon::parse($d->tanggal_awal)->format('d/m/Y'));
                $sheet->setCellValue('H'.$i, $d->tahun_awal);
                if(empty($d->tahun_akhir)){
                    $sheet->setCellValue('I'.$i, ' ');
                } else {
                    //$sheet->setCellValue('I'.$i, \Carbon\Carbon::parse($d->tanggal_akhir)->format('d/m/Y'));
                    $sheet->setCellValue('I'.$i, $d->tahun_akhir);
                }
                
                $sheet->setCellValue('K'.$i, $d->kegiatan_organisasi);
                
                $sheet->mergeCells('E'.$i.':G'.$i);
                $sheet->mergeCells('I'.$i.':J'.$i);
                $sheet->mergeCells('K'.$i.':M'.$i);
                $sheet->styleCells('C'.$i.':M'.$i,$styleArray);
                $sheet->styleCells('C'.$i,$styleArray_center);
                $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);

                $no++;
                $i++;
            }
            if(count($RiwayatOrganisasi)==0) {
                $j = $i;
                for($i=$j; $i<$j+3; $i++){
                    $sheet->insertNewRowBefore($i, 1);
                    $sheet->mergeCells('E'.$i.':G'.$i);
                    $sheet->mergeCells('I'.$i.':J'.$i);
                    $sheet->mergeCells('K'.$i.':M'.$i);
                    $sheet->styleCells('C'.$i.':M'.$i,$styleArray);
                    $sheet->styleCells('C'.$i,$styleArray_center);
                    $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                }
            }

            $RiwayatOrganisasi = RiwayatOrganisasi::where('id_talenta',$this->id)->where('formal_flag',true)->get() ;
            $i = 59;
            $no = 1;
            $sheet->setCellValue('DJ58', 'id');
            foreach($RiwayatOrganisasi as $d){
                $sheet->insertNewRowBefore($i, 1);
                $sheet->setCellValue('DJ'.$i, $d->id);
                $sheet->setCellValue('C'.$i, $no);
                $sheet->setCellValue('D'.$i, $d->nama_organisasi);
                $sheet->setCellValue('E'.$i, $d->jabatan);
                //$sheet->setCellValue('H'.$i, \Carbon\Carbon::parse($d->tanggal_awal)->format('d/m/Y'));
                $sheet->setCellValue('H'.$i, $d->tahun_awal);
                if(empty($d->tahun_akhir)){
                    $sheet->setCellValue('I'.$i, ' ');
                } else {
                    //$sheet->setCellValue('I'.$i, \Carbon\Carbon::parse($d->tanggal_akhir)->format('d/m/Y'));
                    $sheet->setCellValue('I'.$i, $d->tahun_akhir);
                }
                $sheet->setCellValue('K'.$i, $d->kegiatan_organisasi);
                
                $sheet->mergeCells('E'.$i.':G'.$i);
                $sheet->mergeCells('I'.$i.':J'.$i);
                $sheet->mergeCells('K'.$i.':M'.$i);
                $sheet->styleCells('C'.$i.':M'.$i,$styleArray);
                $sheet->styleCells('C'.$i,$styleArray_center);
                $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);

                $no++;
                $i++;
            }
            if(count($RiwayatOrganisasi)==0) {
                $j = $i;
                for($i=$j; $i<$j+3; $i++){
                    $sheet->insertNewRowBefore($i, 1);
                    $sheet->mergeCells('E'.$i.':G'.$i);
                    $sheet->mergeCells('I'.$i.':J'.$i);
                    $sheet->mergeCells('K'.$i.':M'.$i);
                    $sheet->styleCells('C'.$i.':M'.$i,$styleArray);
                    $sheet->styleCells('C'.$i,$styleArray_center);
                    $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                }
            }

            $RiwayatJabatan = RiwayatJabatanDirkomwas::where('id_talenta',$this->id)->get() ;
            
            
            $i = 54;
            $no = 1;
            $sheet->setCellValue('DJ53', 'id');
            foreach($RiwayatJabatan as $d){

                $sheet->insertNewRowBefore($i, 1);
                $sheet->setCellValue('DJ'.$i, $d->id);
                $sheet->setCellValue('C'.$i, $no);
                $sheet->setCellValue('D'.$i, $d->jabatan);
                $sheet->setCellValue('E'.$i, $d->nama_perusahaan);
                $sheet->setCellValue('H'.$i, \Carbon\Carbon::parse($d->tanggal_awal)->format('d/m/Y'));
                $sheet->setCellValue('I'.$i, \Carbon\Carbon::parse($d->tanggal_akhir)->format('d/m/Y'));
                $sheet->setCellValue('K'.$i, $d->tupoksi);
                $sheet->setCellValue('L'.$i, $d->achievement);
                if($d->BidangJabatan){
                    $sheet->setCellValue('M'.$i, $d->BidangJabatan->nama);
                } else{
                    $sheet->setCellValue('M'.$i, '');
                }
                
                $sheet->mergeCells('E'.$i.':G'.$i);
                $sheet->mergeCells('I'.$i.':J'.$i);
                // $sheet->mergeCells('L'.$i.':M'.$i);
                $sheet->styleCells('C'.$i.':M'.$i,$styleArray);
                $sheet->styleCells('H'.$i.':J'.$i,$styleArray_center);
                $sheet->styleCells('C'.$i,$styleArray_center);
                $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);

                $no++;
                $i++;
            }
            if(count($RiwayatJabatan)==0) {
                $j = $i;
                for($i=$j; $i<$j+3; $i++){
                    $sheet->insertNewRowBefore($i, 1);
                    $sheet->mergeCells('E'.$i.':G'.$i);
                    $sheet->mergeCells('I'.$i.':J'.$i);
                    // $sheet->mergeCells('L'.$i.':M'.$i);
                    $sheet->styleCells('C'.$i.':M'.$i,$styleArray);
                    $sheet->styleCells('H'.$i.':J'.$i,$styleArray_center);
                    $sheet->styleCells('C'.$i,$styleArray_center);
                    $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                }
            }

            $RiwayatJabatanLain = RiwayatJabatanLain::where('id_talenta',$this->id)->get() ;
            $i = 51;
            $no = 1;
            $sheet->setCellValue('DJ50', 'id');
            foreach($RiwayatJabatanLain as $d){
                $sheet->insertNewRowBefore($i, 1);
                $sheet->setCellValue('DJ'.$i, $d->id);
                $sheet->setCellValue('C'.$i, $no);
                $sheet->setCellValue('D'.$i, $d->penugasan);
                $sheet->setCellValue('E'.$i, $d->instansi);
                $sheet->setCellValue('H'.$i, \Carbon\Carbon::parse($d->tanggal_awal)->format('d/m/Y'));
                $sheet->setCellValue('I'.$i, \Carbon\Carbon::parse($d->tanggal_akhir)->format('d/m/Y'));
                $sheet->setCellValue('K'.$i, $d->tupoksi);
                if($d->BidangJabatan){
                    $sheet->setCellValue('L'.$i, $d->BidangJabatan->nama);
                } else{
                    $sheet->setCellValue('L'.$i, '');
                }
                //$sheet->setCellValue('L'.$i, $d->achievement);
                
                $sheet->mergeCells('E'.$i.':G'.$i);
                $sheet->mergeCells('I'.$i.':J'.$i);
                $sheet->mergeCells('L'.$i.':M'.$i);
                $sheet->styleCells('C'.$i.':M'.$i,$styleArray);
                $sheet->styleCells('H'.$i.':J'.$i,$styleArray_center);
                $sheet->styleCells('C'.$i,$styleArray_center);
                $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);

                $no++;
                $i++;
            }
            if(count($RiwayatJabatanLain)==0) {
                $j = $i;
                for($i=$j; $i<$j+3; $i++){
                    $sheet->insertNewRowBefore($i, 1);
                    $sheet->mergeCells('E'.$i.':G'.$i);
                    $sheet->mergeCells('I'.$i.':J'.$i);
                    $sheet->mergeCells('L'.$i.':M'.$i);
                    $sheet->styleCells('C'.$i.':M'.$i,$styleArray);
                    $sheet->styleCells('H'.$i.':J'.$i,$styleArray_center);
                    $sheet->styleCells('C'.$i,$styleArray_center);
                    $sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                }
            }

            $event->getWriter()->setActiveSheetIndex(1);
            return $event->getWriter()->getSheetByIndex(1);
         }
      ];
    }
    
}
