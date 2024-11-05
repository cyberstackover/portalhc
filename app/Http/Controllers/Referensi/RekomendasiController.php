<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rekomendasi;
use DB;

class RekomendasiController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'referensi.rekomendasi';
         $this->middleware('permission:rekomendasi-list');
         $this->middleware('permission:rekomendasi-create');
         $this->middleware('permission:rekomendasi-edit');
         $this->middleware('permission:rekomendasi-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Referensi Rekomendasi');
        return view($this->__route.'.index',[
            'pagetitle' => 'Referensi Rekomendasi',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('referensi.rekomendasi.index'),
                    'menu' => 'Rekomendasi'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(Rekomendasi::query())
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data Rekomendasi '.$row->nama.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'" data-rekomendasi="'.$row->nama.'" data-toggle="tooltip" data-original-title="Hapus data Rekomendasi'.$row->nama.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama','deskripsi','action'])
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
        $rekomendasi = Rekomendasi::get();
       
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'rekomendasi' => $rekomendasi
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
            $param['deskripsi'] = $request->input('deskripsi');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $rekomendasi = Rekomendasi::create((array)$param);

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
                                  $rekomendasi = Rekomendasi::find((int)$request->input('id'));
                                  $rekomendasi->update((array)$param);

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

            $rekomendasi = Rekomendasi::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'rekomendasi' => $rekomendasi

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Rekomendasi::find((int)$request->input('id'));
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

        $message['nama.required'] = 'Nama Rekomendasi wajib diinput';

        return Validator::make($request->all(), $required, $message);       
    }
}
