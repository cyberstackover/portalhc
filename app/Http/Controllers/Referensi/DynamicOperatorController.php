<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DynamicOperator;
use DB;

class DynamicOperatorController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->__route = 'referensi.dynamicfilter.operator';
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
        activity()->log('Menu Referensi Operator');
        return view($this->__route.'.index',[
            'pagetitle' => 'Referensi Operator',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('referensi.dynamicfilter.operator.index'),
                    'menu' => 'Operator'
                ]
            ]
        ]);

    }

    public function getAllData()
    {
        return response()->json(DynamicOperator::get(),200);
    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(DynamicOperator::orderBy('nama','asc'))
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data '.$row->nama.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'" data-operator="'.$row->nama.'" data-toggle="tooltip" data-original-title="Hapus data '.$row->nama.'"><i class="flaticon-delete"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->editColumn('check', function($row){
                $button = '<div class="d-flex justify-content-center">';
                // $button .= '<div class="row">';
                // $button .= '<div class="col-lg-6">';
                if($row->is_number){
                    $button .='
                    <div class="form-group row">
                    <label for="" class="col-form-label ml-2">Number</label>
                    <div class="kt-checkbox-list ml-2 mt-1">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" data-is_number="0" data-toggle="tooltip" data-original-title="Jadikan tipe data bukan angka/number pada" data-nama="'.$row->nama.'" data-id="'.$row->id.'" id="is_number" checked="checked">
                            <span></span>
                        </label>
                    </div>
                    </div>';
                } else{
                    $button .='
                    <div class="form-group row">
                    <label for="" class="col-form-label ml-2">Number</label>
                    <div class="kt-checkbox-list ml-2 mt-1">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" data-is_number="1" data-toggle="tooltip" data-original-title="Jadikan tipe data angka/number pada" data-nama="'.$row->nama.'" data-id="'.$row->id.'" id="is_number">
                            <span></span>
                        </label>
                    </div>
                    </div>';
                }

                if($row->is_sorting){
                    $button .= '<div class="form-group row ml-3">
            <label for="" class="col-form-label ml-2">Sorting</label>
            <div class="kt-checkbox-list ml-2 mt-1">
                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                    <input type="checkbox" data-is_sorting="0" data-toggle="tooltip" data-original-title="Jadikan tipe data tidak dapat disorting pada" data-nama="'.$row->nama.'" data-id="'.$row->id.'" id="is_sorting" checked="checked">
                    <span></span>
                </label>
            </div>
            </div>';
                } else{
                    $button .= '<div class="form-group row ml-3">
                    <label for="" class="col-form-label ml-2">Sorting</label>
                    <div class="kt-checkbox-list ml-2 mt-1">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" data-is_sorting="1" data-toggle="tooltip" data-original-title="Jadikan tipe data dapat disorting pada" data-nama="'.$row->nama.'" data-id="'.$row->id.'" id="is_sorting">
                            <span></span>
                        </label>
                    </div>
                    </div>';
                }

                // $button .= '</div>';
                // $button .= '<div class="col-lg-6">';
                if($row->aktif){
                    $button .='
                    <div class="form-group row ml-3">
                    <label for="" class="col-form-label ml-2">Aktif</label>
                    <div class="kt-checkbox-list ml-2 mt-1">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" data-aktif="0" data-toggle="tooltip" data-original-title="Nonaktifkan" data-nama="'.$row->nama.'" data-id="'.$row->id.'" id="aktif" checked="checked">
                            <span></span>
                        </label>
                    </div>
                    </div>';
            } else{
                $button .= '
                <div class="form-group row ml-3">
                <label for="" class="col-form-label ml-2">Aktif</label>
                <div class="kt-checkbox-list ml-2 mt-1">
                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                        <input type="checkbox" data-aktif="1" data-toggle="tooltip" data-original-title="Aktifkan" data-nama="'.$row->nama.'" data-id="'.$row->id.'" id="aktif">
                        <span></span>
                    </label>
                </div>
                </div>';
                  }
                //   $button .= '</div>';
                //   $button .= '</div>';
                  $button .= '</div>';
                    return $button;
            })
            ->rawColumns(['nama','is_sorting','check','tipe','action'])
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
       $operator = DynamicOperator::get();
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'operator' => $operator
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            $param['operator'] = $request->input('operator');
            $param['keterangan'] = $request->input('keterangan');
            $param['aktif'] = $request->has('aktif') && $request->input('aktif') == 'on'? true : false;
            $param['is_number'] = $request->has('is_number') && $request->input('is_number') == 'on'? true : false;
            $param['is_sorting'] = $request->has('is_sorting') && $request->input('is_sorting') == 'on'? true : false;

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $operator = DynamicOperator::create((array)$param);

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
                                  $operator = DynamicOperator::find((int)$request->input('id'));
                                  $operator->update((array)$param);

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

            $operator = DynamicOperator::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'operator' => $operator

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = DynamicOperator::find((int)$request->input('id'));
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

    public function aktif(Request $request)
    {
        DB::beginTransaction();
        try{
            $operator = DynamicOperator::find((int)$request->input('id'));
            $param['aktif'] = $request->input('aktif') == '1' ? true : false;
            $operator->update((array)$param);
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

    public function is_number(Request $request)
    {
        DB::beginTransaction();
        try{
            $operator = DynamicOperator::find((int)$request->input('id'));
            $param['is_number'] = $request->input('is_number') == '1' ? true : false;
            $operator->update((array)$param);
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
    public function is_sorting(Request $request)
    {
        DB::beginTransaction();
        try{
            $operator = DynamicOperator::find((int)$request->input('id'));
            $param['is_sorting'] = $request->input('is_sorting') == '1' ? true : false;
            $operator->update((array)$param);
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

    protected function validateform($request)
    {
        $required['nama'] = 'required';
        $required['operator'] = 'required';

        $message['nama.required'] = 'Nama Operator wajib diinput';
        $message['operator.required'] = 'Operator wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }
}
