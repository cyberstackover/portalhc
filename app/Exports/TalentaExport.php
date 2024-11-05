<?php

namespace App\Exports;

use App\Talenta;
use App\CVSummary;
use App\CVInterest;
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
use App\TransactionTalentaSocialMedia ; 

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class TalentaExport implements FromView , WithEvents,WithTitle
,WithDrawings
{
    public function __construct($id = "" ){
        $this->id = $id ;


    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function registerEvents(): array
{
    return [
        AfterSheet::class    => function(AfterSheet $event) {


               $event->sheet->getDelegate()->getStyle("C1:J1")->getFont()->setSize(22);
               $event->sheet->getDelegate()->getStyle("C12:O12")->getFont()->setSize(26);
               $event->sheet->getDelegate()->getStyle("C3:P3")->getFont()->setSize(18);
               $event->sheet->getDelegate()->getStyle("A1:R264")->getFont()->setName('Arial');
               $event->sheet->getDelegate()->getStyle("A14:R264")->getFont()->setSize(14);
               $event->sheet->getDelegate()->getSheetView()->setZoomScale(70);
               $event->sheet->styleCells(
                'C1:J1',
                [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]
            );
            $event->sheet->styleCells(
                'A14:R264',
                [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                    ],
                ]
            );

               $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(7);
               $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(0);
               $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(4.75);
               $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(48.5);
               $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(19.88);
               $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(20.38);
               $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(45);
               $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(17.75);
               $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(17);
               $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(39.5);
               $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(15);
               $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(20.88);
               $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(27.5);
               $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(3.75);
               $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(25.38);
                 $event->sheet->getDelegate()->getColumnDimension('DJ')->setVisible(false);
        }
    ];
}

public function drawings()
{
    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('Gambar 1');
    $drawing->setDescription('No Merge Cell');
    $drawing->setPath(public_path('/img/cv/image001.png'));
    $drawing->setCoordinates('D5');

    $drawing2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing2->setName('Gambar 2');
    $drawing2->setDescription('No Formula');
    $drawing2->setPath(public_path('/img/cv/image002.png'));
    $drawing2->setCoordinates('E5');

    $drawing3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing3->setName('Gambar 3');
    $drawing3->setDescription('No Add Column');
    $drawing3->setPath(public_path('/img/cv/image003.png'));
    $drawing3->setCoordinates('H5');

    $drawing4 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing4->setName('Gambar 4');
    $drawing4->setDescription('No Other Link/Doc Ref');
    $drawing4->setPath(public_path('/img/cv/image004.png'));
    $drawing4->setCoordinates('L5');

    $talenta = Talenta::find($this->id);
    $foto = asset('img/foto_talenta/'.@$talenta->foto);

    $drawing5 = 
    new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing5->setName('Gambar 5');
    $drawing5->setDescription('Foto Talenta');
   
    $drawing5->setResizeProportional(false);
    $drawing5->setHeight(440);
     $drawing5->setWidth(330);
  if(@$talenta->foto != null){
    $drawing5->setPath(public_path('img'.DIRECTORY_SEPARATOR.'foto_talenta'.DIRECTORY_SEPARATOR.$talenta->foto));
    
}else {
    if( @$talenta->jenis_kelamin == "P")
    $drawing5->setPath(public_path('img'.DIRECTORY_SEPARATOR.'foto_talenta'.DIRECTORY_SEPARATOR."female.png"));
    else 
    $drawing5->setPath(public_path('img'.DIRECTORY_SEPARATOR.'foto_talenta'.DIRECTORY_SEPARATOR."male.png"));
}
$drawing5->setCoordinates('L16');
    return [$drawing,$drawing2,$drawing3,$drawing4,$drawing5];
}

    public function view(): View
    {
        $Talenta = Talenta::find($this->id);
        $CVSummary = CVSummary::where('id_talenta',$this->id)->first() ;
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
     

        return view('cv.board.talenta',compact([
            'Talenta',
            'CVSummary',
            'CVInterest',
            'RiwayatJabatanLain',
            'TransactionTalentaKeahlian',
            'Keahlian',
            'DataKeluargaAnak',
            'DataKeluarga',
            'DataKaryaIlmiah',
            'RiwayatOrganisasi',
            'RiwayatPendidikan',
            'RiwayatPelatihan',
            'PengalamanLain',
            'DataPenghargaan',
            'ReferensiCV',
            'Agama',
            'JenjangPendidikan',
            'Kota',
            'Provinsi',
            'StatusKawin',
            'SocialMedia',
            'ListSosmed'
        ]));
    }
     public function title(): string
    {
        return 'CV' ;
    }
}
