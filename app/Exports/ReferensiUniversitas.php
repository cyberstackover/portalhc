<?php
namespace App\Exports;

use App\Universitas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiUniversitas implements FromView , WithTitle
{
     public function view(): View
    {
        return view('cv.board.universitas', [
            'universitas' => Universitas::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Universitas' ;
    }
}
?>