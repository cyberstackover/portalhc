<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\TargetAsalInstansi;
use App\JenisAsalInstansi;
use DB;

class TargetAsalInstansiController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'referensi.targetasalinstansi';
         $this->middleware('permission:targetasalinstansi-list');
         $this->middleware('permission:targetasalinstansi-create');
         $this->middleware('permission:targetasalinstansi-edit');
         $this->middleware('permission:targetasalinstansi-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Referensi Target Asal Instansi');
        return view($this->__route.'.index',[
            'pagetitle' => 'Referensi Target Asal Instansi',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('referensi.targetasalinstansi.index'),
                    'menu' => 'Target Asal Instansi'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(TargetAsalInstansi::query())
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data Target Asal Instansi"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'"  data-toggle="tooltip" data-original-title="Hapus data Target Asal Instansi"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->editColumn('jenis_asal_instansi', function($row){
                return $row->jenis_asal_instansi->nama;
            })
            ->rawColumns(['keterangan', 'jumlah_minimal', 'jumlah_maksimal','action'])
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
        $targetasalinstansi = TargetAsalInstansi::get();
        $jenisasalinstansis = JenisAsalInstansi::get();
       
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'targetasalinstansi' => $targetasalinstansi,
            'jenisasalinstansis' => $jenisasalinstansis
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
            $param['id_jenis_asal_instansi'] = $request->input('id_jenis_asal_instansi');
            $param['jumlah_minimal'] = $request->input('jumlah_minimal');
            $param['jumlah_maksimal'] = $request->input('jumlah_maksimal');
            $param['keterangan'] = $request->input('keterangan');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $targetasalinstansi = TargetAsalInstansi::create((array)$param);

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
                                  $targetasalinstansi = TargetAsalInstansi::find((int)$request->input('id'));
                                  $targetasalinstansi->update((array)$param);

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

            $targetasalinstansi = TargetAsalInstansi::find((int)$request->input('id'));
            $jenisasalinstansis = JenisAsalInstansi::get();

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'targetasalinstansi' => $targetasalinstansi,
                    'jenisasalinstansis' => $jenisasalinstansis

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = TargetAsalInstansi::find((int)$request->input('id'));
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
        $required['id_jenis_asal_instansi'] = 'required';
        $required['jumlah_minimal'] = 'required';
        $required['jumlah_maksimal'] = 'required';

        $message['jumlah_minimal.required'] = 'Jumlah Minimal wajib diinput';
        $message['jumlah_maksimal.required'] = 'Jumlah maksimal wajib diinput';
        $message['id_jenis_asal_instansi.required'] = 'Jenis Target Asal Instansi wajib diinput';

        return Validator::make($request->all(), $required, $message);       
    }
}