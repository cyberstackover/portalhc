<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Perusahaan;
use App\JenisSk;
use App\KategoriMobility;
use App\MobilityJabatan;
use App\KategoriPemberhentian;
use App\AlasanPemberhentian;
use App\GrupJabatan;
use App\SuratKeputusan;
use App\StrukturOrgan;
use App\PeriodeJabatan;
use App\Periode;
use App\Rekomendasi;
use App\Talenta;
use App\JenisMobilityJabatan;
use App\RincianSK;
use App\JenisJabatan;
use App\OrganPerusahaan;
use App\SKPengangkatan;
use App\SKPemberhentian;
use App\SKNomenklatur;
use App\SKPenetapanplt;
use App\SKAlihtugas;
use App\SKKomIndependen;
use App\User;
use App\Instansi;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use App\Imports\RowImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Exports\ExcelSheet;
use App\Exports\ReportTalenta;

class ReportController extends Controller
{
  protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         ini_set( 'max_execution_time', 0);
         $this->__route = 'report';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
      activity()->log('Menu Report');
      
      return view($this->__route.'.index',[
        'pagetitle' => 'Report',
        'breadcrumb' => [
            [
                'url' => '/',
                'menu' => 'Report'
            ], 
            [
                'url' => route('report.index'),
                'menu' => 'Data BUMN'
            ]                 
        ]
    ]);
    }
    
    public function export(Request $request) 
    {
        ini_set('memory_limit', '-1');
        $filter = $request->filter;
        return Excel::download(new ReportTalenta($filter), 'Data Portal HC-'.$request->filter.'.xlsx');
    }
}