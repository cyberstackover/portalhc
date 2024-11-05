<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Kota;
use App\Provinsi;
use DB;

class KotaController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'referensi.kota';
         // $this->middleware('permission:kota-list');
         // $this->middleware('permission:kota-create');
         // $this->middleware('permission:kota-edit');
         // $this->middleware('permission:kota-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Referensi Kota');
        return view($this->__route.'.index',[
            'pagetitle' => 'Referensi Kota',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('referensi.kota.index'),
                    'menu' => 'Kota'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(Kota::query())
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data Kota '.$row->nama.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'" data-jabatan="'.$row->nama.'" data-toggle="tooltip" data-original-title="Hapus data Kota '.$row->nama.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->editColumn('provinsi', function($row){
                return $row->provinsi->nama;
            })
            ->rawColumns(['nama','is_luar_negeri','action'])
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
        $provinsis = Provinsi::get();
       
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'provinsis' => $provinsis
        ]);

    }

    public function store(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];      

        $validator = $this->validateform($request);   
        if (!$validator->fails()) {
            $param['nama'] = $request->input('nama');
            $param['is_luar_negeri'] = $request->input('is_luar_negeri');
            $param['provinsi_id'] = $request->input('provinsi_id');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $kota = Kota::create((array)$param);

                                  DB::commit();
                                  $result = [
                                    'flag'  => 'success',
                                    'msg' => 'Sukses tambah data',
                                    'title' => 'Sukses'
                                  ];
                               }catch(\Exception $e){
                                  DB::rollback();
                                  $result = [
                                    'flag'  => 'warning',
                                    'msg' => $e->getMessage(),
                                    'title' => 'Gagal'
                                  ];
                               }

                break;
                
                case 'update': DB::beginTransaction();
                               try{
                                  $kota = Kota::find((int)$request->input('id'));
                                  $kota->update((array)$param);

                                  DB::commit();
                                  $result = [
                                    'flag'  => 'success',
                                    'msg' => 'Sukses ubah data',
                                    'title' => 'Sukses'
                                  ];
                               }catch(\Exception $e){
                                  DB::rollback();
                                  $result = [
                                    'flag'  => 'warning',
                                    'msg' => 'Gagal ubah data',
                                    'title' => 'Gagal'
                                  ];
                               }

                break;
            }
        }else{
            $messages = $validator->errors()->all('<li>:message</li>');
            $result = [
                'flag'  => 'warning',
                'msg' => '<ul>'.implode('', $messages).'</ul>',
                'title' => 'Gagal proses data'
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
            $provinsis = Provinsi::get();
            $kota = Kota::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'kota' => $kota,
                    'provinsis' => $provinsis

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Kota::find((int)$request->input('id'));
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

    protected function validateform($request)
    {
        $required['nama'] = 'required';

        $message['nama.required'] = 'Nama Kota wajib diinput';

        return Validator::make($request->all(), $required, $message);       
    }
}
