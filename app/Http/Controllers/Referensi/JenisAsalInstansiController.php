<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\JenisAsalInstansiBaru;
use DB;

class JenisAsalInstansiController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'referensi.jenisasalinstansi';
         $this->middleware('permission:jenisasalinstansi-list');
         $this->middleware('permission:jenisasalinstansi-create');
         $this->middleware('permission:jenisasalinstansi-edit');
         $this->middleware('permission:jenisasalinstansi-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Referensi Jenis Asal Instansi');
        return view($this->__route.'.index',[
            'pagetitle' => 'Referensi Jenis Asal Instansi',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('referensi.jenisasalinstansi.index'),
                    'menu' => 'Jenis Asal Instansi'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(JenisAsalInstansiBaru::query())
            ->editColumn('direksi_induk', function($row){
                $html = '';
                if($row->direksi_induk == 't'){
                  $html .= 'TRUE';
                } else {
                  $html .= 'FALSE';
                }
                
                return $html;
            })
            ->editColumn('dekom_induk', function($row){
                $html = '';
                if($row->dekom_induk == 't'){
                  $html .= 'TRUE';
                } else {
                  $html .= 'FALSE';
                }
                
                return $html;
            })
            ->editColumn('direksi_anak_cucu', function($row){
                $html = '';
                if($row->direksi_anak_cucu == 't'){
                  $html .= 'TRUE';
                } else {
                  $html .= 'FALSE';
                }
                
                return $html;
            })
            ->editColumn('dekom_anak_cucu', function($row){
                $html = '';
                if($row->dekom_anak_cucu == 't'){
                  $html .= 'TRUE';
                } else {
                  $html .= 'FALSE';
                }
                
                return $html;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data Jenis Asal Instansi '.$row->nama.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'" data-jabatan="'.$row->nama.'" data-toggle="tooltip" data-original-title="Hapus data Jenis Asal Instansi'.$row->nama.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama','direksi_induk','dekom_induk','direksi_anak_cucu', 'dekom_anak_cucu','keterangan','tablename','action'])
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
        $jenisasalinstansi = JenisAsalInstansiBaru::get();
       
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'jenisasalinstansi' => $jenisasalinstansi
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
            $param['keterangan'] = $request->input('keterangan');
            $param['tablename'] = $request->input('tablename');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $jenisasalinstansi = JenisAsalInstansiBaru::create((array)$param);

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
                                  $jenisasalinstansi = JenisAsalInstansiBaru::find((int)$request->input('id'));
                                  $jenisasalinstansi->update((array)$param);

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

            $jenisasalinstansi = JenisAsalInstansiBaru::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'jenisasalinstansi' => $jenisasalinstansi

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = JenisAsalInstansiBaru::find((int)$request->input('id'));
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

        $message['nama.required'] = 'Nama Jenis Asal Instansi wajib diinput';

        return Validator::make($request->all(), $required, $message);       
    }
}