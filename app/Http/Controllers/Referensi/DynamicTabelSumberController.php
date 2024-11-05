<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DynamicTabelSumber;
use DB;

class DynamicTabelSumberController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->__route = 'referensi.dynamicfilter.tabelsumber';
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
        activity()->log('Menu Referensi Tabel Sumber');
        return view($this->__route.'.index',[
            'pagetitle' => 'Referensi Tabel Sumber',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('referensi.dynamicfilter.tabelsumber.index'),
                    'menu' => 'Tabel Sumber'
                ]
            ]
        ]);

    }

    public function getAllData()
    {
        return response()->json(DynamicTabelSumber::get(),200);
    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(DynamicTabelSumber::query())
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data '.$row->tabel.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'" data-tabelsumber="'.$row->tabel.'" data-toggle="tooltip" data-original-title="Hapus data '.$row->tabel.'"><i class="flaticon-delete"></i></button>';

                $button .= '</div>';
                return $button;
            })
            // ->editColumn('sumber', function($row){
            //     return $row->tabelSumber->tabel . ' '. $row->tabelSumber->field;
            // })
            ->rawColumns(['nama','tipe','action'])
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
       $tabelsumber = DynamicTabelSumber::get();
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'tabelsumber' => $tabelsumber,
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
            $param['tabel'] = $request->input('tabel');
            $param['field'] = $request->input('field');
            $param['alias'] = $request->input('alias');
            $param['query'] = $request->input('query');
            $param['keterangan'] = $request->input('keterangan');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $tabelsumber = DynamicTabelSumber::create((array)$param);

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
                                  $tabelsumber = DynamicTabelSumber::find((int)$request->input('id'));
                                  $tabelsumber->update((array)$param);

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

            $tabelsumber = DynamicTabelSumber::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'tabelsumber' => $tabelsumber

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = DynamicTabelSumber::find((int)$request->input('id'));
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
        $required['tabel'] = 'required';
        $required['field'] = 'required';
        $required['alias'] = 'required';

        $message['tabel.required'] = 'Nama Tabel wajib diinput';
        $message['field.required'] = 'Field wajib diinput';
        $message['field.required'] = 'Field wajib diinput';
        $message['alias.required'] = 'Alias wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }
}
