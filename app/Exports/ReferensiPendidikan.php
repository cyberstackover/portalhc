<?php
namespace App\Exports;

use App\JenjangPendidikan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiPendidikan implements FromView , WithTitle
{
     public function view(): View
    {
        return view('cv.board.pendidikan', [
            'JenjangPendidikan' => JenjangPendidikan::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Pendidikan' ;
    }
}
?>