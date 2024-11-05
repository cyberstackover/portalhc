<?php
namespace App\Exports;

use App\Kota;
use App\Provinsi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReferensiKotaDN implements FromView , WithTitle
{
     public function view(): View
    {
       
        return view('cv.board.kota', [
            'kota' => Kota::join('provinsi','kota.provinsi_id','provinsi.id')->where('provinsi.is_luar_negeri','false')
            ->select('kota.id as id_kota','provinsi_id as id_provinsi','provinsi.nama as nama_provinsi','kota.nama as nama_kota')
            ->orderBy('id_provinsi','asc')->get()
        ]);
    }

    public function title(): string
    {
        return 'Referensi Kota DN' ;
    }
}
?>