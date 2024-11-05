<?php

namespace App\Exports;

use App\Talenta;
use App\CVSummary;
use App\CVInterest;
use App\CVNilai;
use App\RiwayatJabatanLain ;
use App\TransactionTalentaKeahlian ;
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
use App\Kota;
use App\Provinsi;
use App\StatusKawin;
use App\SocialMedia;
use App\TransactionTalentaSocialMedia;
use App\Suku;
use App\GolonganDarah;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\EventExportStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\NamedRange;

class TalentaExport2 implements FromView, WithEvents, WithTitle
{
    public function __construct($id = "" ){
        $this->id = $id ;


    }
    /**
    * @return \Illuminate\Support\Collection
    */
   public function registerEvents(): array
   {
        //TODO: Implement registerEvents() method
        return[
            AfterSheet::class => function(AfterSheet $event){
                /**
                * Adicionando Estilos
                */

                /** @var Sheet $sheet */
                $sheet = $event->sheet;

                $sheet->getDelegate()->getStyle("C1:J1")->getFont()->setSize(22);
                $sheet->getDelegate()->getStyle("C24:O12")->getFont()->setSize(26);
                $sheet->getDelegate()->getStyle("C3:P3")->getFont()->setSize(18);
                $sheet->getDelegate()->getStyle("A1:R264")->getFont()->setName('Arial');
                //$sheet->getDelegate()->getStyle("A21:R264")->getFont()->setSize(14);
                $sheet->getDelegate()->getSheetView()->setZoomScale(70);
                $event->sheet->styleCells(
                    'C1:J1',
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );
                /*$event->sheet->styleCells(
                    'A21:R264',
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                        ],
                    ]
                );*/

                $sheet->getDelegate()->getColumnDimension('A')->setWidth(7);
                $sheet->getDelegate()->getColumnDimension('B')->setWidth(0);
                $sheet->getDelegate()->getColumnDimension('C')->setWidth(4.75);
                $sheet->getDelegate()->getColumnDimension('D')->setWidth(30.5);
                $sheet->getDelegate()->getColumnDimension('E')->setWidth(10.88);
                $sheet->getDelegate()->getColumnDimension('F')->setWidth(30.5);
                $sheet->getDelegate()->getColumnDimension('G')->setWidth(10.88);
                $sheet->getDelegate()->getColumnDimension('H')->setWidth(30.5);
                $sheet->getDelegate()->getColumnDimension('I')->setWidth(10.88);
                $sheet->getDelegate()->getColumnDimension('J')->setWidth(30.5);
                $sheet->getDelegate()->getColumnDimension('K')->setWidth(10.88);
                $sheet->getDelegate()->getColumnDimension('L')->setWidth(30.5);
                $sheet->getDelegate()->getColumnDimension('M')->setWidth(10.88);
                $sheet->getDelegate()->getColumnDimension('N')->setWidth(3.75);
                $sheet->getDelegate()->getColumnDimension('O')->setWidth(25.38);
                $sheet->getDelegate()->getColumnDimension('DJ')->setVisible(false);

                /**
                 * validation for jenis kelamin
                 */
                

                $getTalenta = Talenta::find($this->id);

                if(empty($getTalenta->jenis_kelamin)){
                    $jkelamin = 'Pilih Jenis Kelamin';
                } else {
                    if($getTalenta->jenis_kelamin == 'L'){
                        $jkelamin = 'Laki - Laki';
                    } elseif ($getTalenta->jenis_kelamin == 'P') {
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
                if(empty($getTalenta->id_agama)){
                    $jagama = "Pilih Agama";
                } else {
                    $getAgama = Agama::find(@$getTalenta->id_agama);
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
                if(empty($getTalenta->suku)){
                    $jsuku = "Pilih Suku";
                } else {
                    $jsuku = $getTalenta->suku;
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
                $objValidation->setPromptTitle('Pilih Jenis Golongan Darah');
                $objValidation->setPrompt('Silakan Pilih Salah Satu');
                $objValidation->setFormula1('"' . $sukus . '"');

                /**
                 * validation for Golongan Darah
                 */
                if(empty($getTalenta->gol_darah)){
                    $jgoldar = "Pilih Golongan Darah";
                } else {
                    $jgoldar = $getTalenta->gol_darah;
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
                if(empty($getTalenta->id_status_kawin)){
                    $jstatus = "Pilih Status Kawin";
                } else {
                    $getStatus = StatusKawin::where('id',$getTalenta->id_status_kawin)->pluck('nama')->first();
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
            }
        ];
   }
    

    public function view(): View
    {
        $Talenta = Talenta::find($this->id);
        $CVSummary = CVSummary::where('id_talenta',$this->id)->first();
        $CVInterest =  CVInterest::where('id_talenta',$this->id)->first() ;
        $RiwayatJabatanLain = RiwayatJabatanLain::where('id_talenta',$this->id)->get() ;
        $TransactionTalentaKeahlian = TransactionTalentaKeahlian::where('id_talenta',$this->id)->get() ;
        $Keahlian = Keahlian::get();
        $DataKeluargaAnak = DataKeluargaAnak::where('id_talenta',$this->id)->get() ;
        $DataKeluarga = DataKeluarga::where('id_talenta',$this->id)->get() ;
        $DataKaryaIlmiah = DataKaryaIlmiah::where('id_talenta',$this->id)->get() ;
        $RiwayatOrganisasi = RiwayatOrganisasi::where('id_talenta',$this->id)->get() ;
        $RiwayatPendidikan = RiwayatPendidikan::where('id_talenta',$this->id)->get() ;
        $RiwayatPelatihan = RiwayatPelatihan::where('id_talenta',$this->id)->get() ;
        $PengalamanLain = PengalamanLain::where('id_talenta',$this->id)->get() ;
        $DataPenghargaan = DataPenghargaan::where('id_talenta',$this->id)->get() ;
        $ReferensiCV = ReferensiCV::where('id_talenta',$this->id)->get() ;
        $Agama = Agama::find(@$Talenta->id_agama);
        $JenjangPendidikan = JenjangPendidikan::get();
        $Kota = Kota::all();
        $Provinsi = Provinsi::all();
        $StatusKawin = StatusKawin::all();
        $SocialMedia = SocialMedia::all();
        $ListSosmed = TransactionTalentaSocialMedia::all();
        $CVNilai = CVNilai::where('id_talenta',$this->id)->first();
     

        return view('cv.board.talenta2',compact([
            'Talenta',
            'Agama',
            'CVNilai',
            'CVInterest',
        ]));
    }
    
    public function title(): string
    {
        return 'Template CV Standar' ;
    }
}
