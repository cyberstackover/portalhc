<?php
namespace App\Exports;

use App\Suku;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiSuku implements FromView , WithTitle
{
     public function view(): View
    {
        return view('cv.board.suku', [
            'suku' => Suku::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Suku' ;
    }
}
?>