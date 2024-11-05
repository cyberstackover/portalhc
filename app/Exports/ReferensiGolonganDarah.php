<?php
namespace App\Exports;

use App\GolonganDarah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiGolonganDarah implements FromView , WithTitle
{
     public function view(): View
    {
        return view('cv.board.goldar', [
            'goldar' => GolonganDarah::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Golongan Darah' ;
    }
}
?>