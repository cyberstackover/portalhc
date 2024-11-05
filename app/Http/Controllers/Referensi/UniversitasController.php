<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Universitas;
use App\Kota;
use App\Provinsi;
use DB;

class UniversitasController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'referensi.universitas';
         // $this->middleware('permission:universitas-list');
         // $this->middleware('permission:universitas-create');
         // $this->middleware('permission:universitas-edit');
         // $this->middleware('permission:universitas-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Referensi Universitas');
        return view($this->__route.'.index',[
            'pagetitle' => 'Referensi Universitas',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('referensi.universitas.index'),
                    'menu' => 'Universitas'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        try{
            $data = DB::table('universitas')->select(
                'universitas.id','universitas.nama','kota.nama as kota','provinsi.nama as negara'
            )
            ->leftjoin('kota','kota.id','=','universitas.id_kota')->leftjoin('provinsi','provinsi.id','=','universitas.id_negara');
            return datatables()->of($data)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data Universitas '.$row->nama.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" data-original-title="Hapus data Universitas '.$row->nama.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            
            ->rawColumns(['nama','action'])
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
            'universitas' => $universitas,
            'kota' => Kota::where('is_luar_negeri', false)->get(),
            'provinsi' => Provinsi::where('is_luar_negeri', false)->get(),
            'negara' => Provinsi::where('is_luar_negeri', true)->get(),
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
            $param['id_kota'] = $request->input('id_kota');
            $param['id_negara'] = $request->input('id_negara');
            
            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $universitas = Universitas::create((array)$param);
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
                                  $universitas = Universitas::find((int)$request->input('id'));
                                  $universitas->update((array)$param);

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

            $universitas = Universitas::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'universitas' => $universitas,
                    'kota' => Kota::where('is_luar_negeri', false)->get(),
                    'provinsi' => Provinsi::where('is_luar_negeri', false)->get(),
                    'negara' => Provinsi::where('is_luar_negeri', true)->get(),

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

    protected function validateform($request)
    {
        $required['nama'] = 'required';

        $message['nama.required'] = 'Nama Universitas wajib diinput';

        return Validator::make($request->all(), $required, $message);       
    }
}