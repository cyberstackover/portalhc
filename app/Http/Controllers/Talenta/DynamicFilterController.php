<?php

declare(strict_types=1);

namespace App\Http\Controllers\Talenta;

use App\DynamicFilter;
use App\DynamicStandarValue;
use App\DynamicTabelSumber;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DynamicFilterController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->__route = 'talenta.filter_dynamic';

    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        activity()->log('Filter Dynamic');
        return view($this->__route.'.index', [
            'pagetitle' => 'Filter Dynamic',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes',
                ],
                [
                    'url' => route('talenta.filter_dynamic.index'),
                    'menu' => 'Filter Dynamic',
                ],
            ],
        ]);
    }

    public function getAllData()
    {
        return response()->json(DynamicFilter::get(), 200);
    }

    public function datatable(Request $request)
    {
        try {
            return datatables()->of(DynamicFilter::orderBy('submenu', 'asc'))
                ->addColumn('action', static function ($row) {
                    $id = (int) $row->id;
                    $button = '<div align="center">';

                    $button .= '&nbsp;';

                    $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data '.$row->submenu.'"><i class="flaticon-edit"></i></button>';

                    $button .= '&nbsp;';

                    $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'" data-parameter="'.$row->submenu.'" data-toggle="tooltip" data-original-title="Hapus data '.$row->submenu.'"><i class="flaticon-delete"></i></button>';

                    $button .= '</div>';
                    return $button;
                })
                ->editColumn('check', static function ($row) {
                $button = '<div class="d-flex justify-content-center">';
                // $button .= '<div class="row">';
                // $button .= '<div class="col-lg-6">';
                if ($row->is_number) {
                    $button .= '
                    <div class="form-group row">
                    <label for="" class="col-form-label ml-2">Number</label>
                    <div class="kt-checkbox-list ml-2 mt-1">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" data-is_number="0" data-toggle="tooltip" data-original-title="Jadikan tipe data bukan angka/number pada" data-nama="'.$row->submenu.'" data-id="'.$row->id.'" id="is_number" checked="checked">
                            <span></span>
                        </label>
                    </div>
                    </div>';
                } else {
                    $button .= '
                    <div class="form-group row">
                    <label for="" class="col-form-label ml-2">Number</label>
                    <div class="kt-checkbox-list ml-2 mt-1">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" data-is_number="1" data-toggle="tooltip" data-original-title="Jadikan tipe data angka/number pada" data-nama="'.$row->submenu.'" data-id="'.$row->id.'" id="is_number">
                            <span></span>
                        </label>
                    </div>
                    </div>';
                }

                if ($row->aktif) {
                    $button .= '
                    <div class="form-group row ml-3">
                    <label for="" class="col-form-label ml-2">Aktif</label>
                    <div class="kt-checkbox-list ml-2 mt-1">
                        <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                            <input type="checkbox" data-aktif="0" data-toggle="tooltip" data-original-title="Nonaktifkan" data-nama="'.$row->submenu.'" data-id="'.$row->id.'" id="aktif" checked="checked">
                            <span></span>
                        </label>
                    </div>
                    </div>';
                } else {
                    $button .= '
                <div class="form-group row ml-3">
                <label for="" class="col-form-label ml-2">Aktif</label>
                <div class="kt-checkbox-list ml-2 mt-1">
                    <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
                        <input type="checkbox" data-aktif="1" data-toggle="tooltip" data-original-title="Aktifkan" data-nama="'.$row->submenu.'" data-id="'.$row->id.'" id="aktif">
                        <span></span>
                    </label>
                </div>
                </div>';
                }

                $button .= '</div>';
                return $button;
            })
                ->editColumn('tabelsumber', static function ($row) {
                return $row->tabelSumber->tabel . ' - '. $row->tabelSumber->field;
            })
                ->editColumn('standarvalue', static function ($row) {
                if ($row->standarValue === null) {
                    return '';
                }
                $opsis = explode('|', $row->standarValue->opsi);
                $opsi = '';
                if (! empty($opsis)) {
                    foreach ($opsis as $val) {
                        $opsi .= '<span class="badge badge-secondary">'.$val.'</span>'.' ';
                    }
                }
                return $row->standarValue->nama.' <br> '.$opsi;


            })
                ->rawColumns(['submenu','tipe','check','standarvalue','action'])
                ->toJson();
        } catch (Exception $e) {
            return response([
                'draw' => 0,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $tabelsumbers = DynamicTabelSumber::get();
        $sumbers = [];
        foreach ($tabelsumbers as $value) {
        }
        for ($i = 0;$i < count($tabelsumbers);$i++) {
            $dynamic_tabel_sumber_id = DynamicFilter::where('dynamic_tabel_sumber_id', $tabelsumbers[$i]['id'])->count();
            if ($dynamic_tabel_sumber_id === 0) {
                $sumbers[$i] = [
                    'id' => $tabelsumbers[$i]['id'],
                    'tabel' => $tabelsumbers[$i]['tabel'],
                    'field' => $tabelsumbers[$i]['field'],
                ];
            }
        }

        $standarvalues = DynamicStandarValue::get();
        $parameter = DynamicFilter::get();

        $tipes = [
            'select','text','number','multiple',
        ];

        return view($this->__route.'.form', [
            'actionform' => 'insert',
            'parameter' => $parameter,
            'tabelsumbers' => $sumbers,
            'standarvalues' => $standarvalues,
            'tipes' => $tipes,
        ]);
    }

    public function store(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error',
        ];

        $validator = $this->validateform($request);
        if (! $validator->fails()) {
            $param['menu'] = $request->input('menu');
            $param['submenu'] = $request->input('submenu');
            $param['tipe'] = $request->input('tipe');
            $param['dynamic_tabel_sumber_id'] = $request->input('dynamic_tabel_sumber_id');
            $param['dynamic_standar_value_id'] = $request->input('dynamic_standar_value_id');
            $param['keterangan'] = $request->input('keterangan');
            $param['aktif'] = $request->has('aktif') && $request->input('aktif') === 'on' ? true : false;
            $param['is_number'] = $request->has('is_number') && $request->input('is_number') === 'on' ? true : false;

            switch ($request->input('actionform')) {
                case 'insert':
                    DB::beginTransaction();
                               try {
                                   $parameter = DynamicFilter::create((array) $param);

                                   DB::commit();
                                   $result = [
                                      'flag' => 'success',
                                      'msg' => 'Sukses tambah data',
                                      'title' => 'Sukses',
                                  ];
                               } catch (\Exception $e) {
                                   DB::rollback();
                                   $result = [
                                      'flag' => 'warning',
                                      'msg' => $e->getMessage(),
                                      'title' => 'Gagal',
                                  ];
                               }

    break;

                case 'update':
                    DB::beginTransaction();
                               try {
                                   $parameter = DynamicFilter::find((int) $request->input('id'));
                                   $parameter->update((array) $param);

                                   DB::commit();
                                   $result = [
                                      'flag' => 'success',
                                      'msg' => 'Sukses ubah data',
                                      'title' => 'Sukses',
                                  ];
                               } catch (\Exception $e) {
                                   DB::rollback();
                                   $result = [
                                      'flag' => 'warning',
                                      'msg' => 'Gagal ubah data',
                                      'title' => 'Gagal',
                                  ];
                               }

    break;
            }
        } else {
            $messages = $validator->errors()->all('<li>:message</li>');
            $result = [
                'flag' => 'warning',
                'msg' => '<ul>'.implode('', $messages).'</ul>',
                'title' => 'Gagal proses data',
            ];
        }

        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */

    public function edit(Request $request)
    {
        try {
            $parameter = DynamicFilter::find((int) $request->input('id'));
            $tabelsumbers = DynamicTabelSumber::get();
            $sumbers = [];
            foreach ($tabelsumbers as $value) {
            }
            for ($i = 0;$i < count($tabelsumbers);$i++) {
                $dynamic_tabel_sumber_id = DynamicFilter::where('dynamic_tabel_sumber_id', $tabelsumbers[$i]['id'])->count();
                if ($dynamic_tabel_sumber_id === 0) {
                    $sumbers[$i] = [
                            'id' => $tabelsumbers[$i]['id'],
                            'tabel' => $tabelsumbers[$i]['tabel'],
                            'field' => $tabelsumbers[$i]['field'],
                        ];
                }
            }
            $tabelsumber = DynamicTabelSumber::find($parameter->dynamic_tabel_sumber_id);
            array_push($sumbers, [
                    'id' => $tabelsumber->id,
                    'tabel' => $tabelsumber->tabel,
                    'field' => $tabelsumber->field,
                ]);
            $standarvalues = DynamicStandarValue::get();
            $tipes = [
                    'select','text','number','multiple',
                ];

            return view($this->__route.'.form', [
                    'actionform' => 'update',
                    'parameter' => $parameter,
                    'tabelsumbers' => $sumbers,
                    'standarvalues' => $standarvalues,
                    'tipes' => $tipes,
                ]);
        } catch (Exception $e) {
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = DynamicFilter::find((int) $request->input('id'));
            $data->delete();

            DB::commit();
            $result = [
                'flag' => 'success',
                'msg' => 'Sukses hapus data',
                'title' => 'Sukses',
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag' => 'warning',
                'msg' => 'Gagal hapus data',
                'title' => 'Gagal',
            ];
        }
        return response()->json($result);
    }

    public function aktif(Request $request)
    {
        DB::beginTransaction();
        try {
            $parameter = DynamicFilter::find((int) $request->input('id'));
            $param['aktif'] = $request->input('aktif') === '1' ? true : false;
            $parameter->update((array) $param);
            DB::commit();
            $result = [
                'flag' => 'success',
                'msg' => 'Sukses Ubah data',
                'title' => 'Sukses',
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag' => 'warning',
                'msg' => 'Gagal Ubah data',
                'title' => 'Gagal',
            ];
        }
        return response()->json($result);
    }

    public function is_number(Request $request)
    {
        DB::beginTransaction();
        try {
            $parameter = DynamicFilter::find((int) $request->input('id'));
            $param['is_number'] = $request->input('is_number') === '1' ? true : false;
            $parameter->update((array) $param);
            DB::commit();
            $result = [
                'flag' => 'success',
                'msg' => 'Sukses Ubah data',
                'title' => 'Sukses',
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $result = [
                'flag' => 'warning',
                'msg' => 'Gagal Ubah data',
                'title' => 'Gagal',
            ];
        }
        return response()->json($result);
    }

    protected function validateform($request)
    {
        $required['submenu'] = 'required';

        $message['submenu.required'] = 'Jenis Filter wajib diinput';

        return Validator::make($request->all(), $required, $message);
    }
}
