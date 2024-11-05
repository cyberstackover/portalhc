<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Talenta;
use DB;

class PejabatBaruController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     //komen
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'administrasi.pejabatbaru';
         $this->middleware('permission:pejabatbaru-list');
         $this->middleware('permission:pejabatbaru-create');
         $this->middleware('permission:pejabatbaru-edit');
         $this->middleware('permission:pejabatbaru-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Administrasi SK Pejabat Baru');
        return view($this->__route.'.index',[
            'pagetitle' => 'Administrasi SK Pejabat Baru',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('administrasi.pejabatbaru.index'),
                    'menu' => 'Administasi SK'
                ],
                [
                    'url' => route('administrasi.pejabatbaru.index'),
                    'menu' => 'Pejabat Baru'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        try{

            $id_sql = "SELECT
                          talenta.id,
                          talenta.nama_lengkap,
                          talenta.email,
                          CASE
                            WHEN organ_perusahaan.id IS NULL THEN
                            ' Baru '  ELSE  ' Menjabat'
                          END AS status 
                        FROM
                          talenta
                          LEFT JOIN organ_perusahaan ON organ_perusahaan.id_talenta = talenta.ID
                        GROUP BY
                          talenta.id,
                          talenta.nama_lengkap,
                          talenta.email,
                          organ_perusahaan.id
                          ";

            $isiadmin  = DB::select(DB::raw($id_sql));

            return datatables()->of($isiadmin)
            ->editColumn('status', function($row){
                      $html = '';
                      if($row->status=='Baru'){
                        $html .= '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row->status.'</span>';
                      } else {
                        $html .= '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row->status.'</span>';
                      }
                      
                      return $html;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Pejabat Baru '.$row->nama_lengkap.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete" data-id="'.$id.'" data-pejabatbaru="'.$row->nama_lengkap.'" data-toggle="tooltip" data-original-title="Hapus Pejabat Baru '.$row->nama_lengkap.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama_lengkap','email','status','action'])
            ->toJson();
        }catch(Exception $e){
            return response([
                'draw'            => 0,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => []
            ]);
        }
    }
}