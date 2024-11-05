<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Universitas;
use DB;

class ApproveUniversitasController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'referensi.approve_universitas';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Persetujuan Nama Universitas');
        return view($this->__route.'.index',[
            'pagetitle' => 'Persetujuan Nama Universitas',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('referensi.approve_universitas.index'),
                    'menu' => 'Persetujuan Universitas'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        try{
            $query = Universitas::select('universitas.*', 'kota.nama as kota', 'provinsi.nama as negara')
                    ->leftjoin('kota','kota.id','=','universitas.id_kota')
                    ->leftjoin('provinsi','provinsi.id','=','universitas.id_negara')
                    ->where('universitas.unverified', '=', 't');
            return datatables()->of($query)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-approve" data-nama="'.$row->nama.'" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Approve data Universitas '.$row->nama.'"><i class="flaticon2-checkmark"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" data-original-title="Hapus data Universitas '.$row->nama.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama','kota','negara','action'])
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $universitas = Universitas::get();
       
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'universitas' => $universitas
        ]);

    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $universitas = Universitas::find((int)$request->input('id'));
            $param['unverified'] = 'f';
            $universitas->update((array)$param);

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses Ubah data',
                'title' => 'Sukses'
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal Ubah data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {

        try{

            $universitas = Universitas::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'universitas' => $universitas

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Universitas::find((int)$request->input('id'));
            $data->delete();

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses hapus data',
                'title' => 'Sukses'
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal hapus data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);       
    }

}