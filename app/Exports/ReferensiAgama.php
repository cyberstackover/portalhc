<?php
namespace App\Exports;

use App\Agama;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiAgama implements FromView , WithTitle
{
     public function view(): View
    {
        return view('cv.board.agama', [
            'agama' => Agama::all()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Agama' ;
    }
}
?>