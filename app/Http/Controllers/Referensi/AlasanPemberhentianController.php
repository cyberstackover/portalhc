<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\AlasanPemberhentian;
use App\KategoriPemberhentian;
use DB;

class AlasanPemberhentianController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'referensi.alasanpemberhentian';
         $this->middleware('permission:alasanpemberhentian-list');
         $this->middleware('permission:alasanpemberhentian-create');
         $this->middleware('permission:alasanpemberhentian-edit');
         $this->middleware('permission:alasanpemberhentian-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Referensi Alasan Pemberhentian');
        return view($this->__route.'.index',[
            'pagetitle' => 'Referensi Alasan Pemberhentian',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('referensi.alasanpemberhentian.index'),
                    'menu' => 'Alasan Pemberhentian'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(AlasanPemberhentian::query())
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data Alasan Pemberhentian '.$row->keterangan.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'" data-keterangan="'.$row->keterangan.'" data-toggle="tooltip" data-original-title="Hapus data Alasan Pemberhentian '.$row->keterangan.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->editColumn('kategori_pemberhentian', function($row){
                return $row->kategori_pemberhentian->nama;
            })
            ->rawColumns(['keterangan','action'])
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
        $alasanpemberhentian = AlasanPemberhentian::get();
        $kategoripemberhentians = KategoriPemberhentian::get();
       
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'alasanpemberhentian' => $alasanpemberhentian,
            'kategoripemberhentians' => $kategoripemberhentians
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
            $param['id_kategori_pemberhentian'] = $request->input('id_kategori_pemberhentian');
            $param['keterangan'] = $request->input('keterangan');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $alasanpemberhentian = AlasanPemberhentian::create((array)$param);

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
                                  $alasanpemberhentian = AlasanPemberhentian::find((int)$request->input('id'));
                                  $alasanpemberhentian->update((array)$param);

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

            $alasanpemberhentian = AlasanPemberhentian::find((int)$request->input('id'));
            $kategoripemberhentians = KategoriPemberhentian::get();

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'alasanpemberhentian' => $alasanpemberhentian,
                    'kategoripemberhentians' => $kategoripemberhentians

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = AlasanPemberhentian::find((int)$request->input('id'));
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
        $required['id_kategori_pemberhentian'] = 'required';
        $required['keterangan'] = 'required';

        $message['keterangan.required'] = 'Keterangan Alasan Pemberhentian wajib diinput';
        $message['id_kategori_pemberhentian.required'] = 'Jenis Alasan Pemberhentian wajib diinput';

        return Validator::make($request->all(), $required, $message);       
    }
}