<?php
namespace App\Exports;

use App\StatusKawin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiKawin implements FromView , WithTitle
{
     public function view(): View
    {
        return view('cv.board.statuskawin', [
            'StatusKawin' => StatusKawin::all()
        ]);
    }

    public function title(): string
    {
        return 'Ref. Status Kawin' ;
    }
}
?>