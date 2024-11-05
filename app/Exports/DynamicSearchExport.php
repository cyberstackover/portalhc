<?php

namespace App\Exports;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use DB;

class DynamicSearchExport implements FromView , WithTitle
{
    public function __construct($talenta, $dynamic_filter, $query_string){
        $this->talenta = $talenta ;
        $this->dynamic_filter = $dynamic_filter ;
        $this->query_string = $query_string ;
    }

    public function view(): View
    {  
      $id_users = \Auth::user()->id;
      $users = User::where('id', $id_users)->first();

      return view('talenta.dynamic_search.export', [
          'talenta' => $this->talenta, 
          'dynamic_filter' => $this->dynamic_filter,
          'tanggal' => date('d-m-Y'),
          'user' => $users->username,
          'query' => $this->query_string,
      ]);
    }

    public function title(): string
    {
        return 'Data Talenta' ;
    }
}
