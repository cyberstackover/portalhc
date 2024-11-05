<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\KomposisiProfesional;
use App\KelasBumn;
use DB;

class KomposisiProfesionalController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'referensi.komposisiprofesional';
         $this->middleware('permission:komposisiprofesional-list');
         $this->middleware('permission:komposisiprofesional-create');
         $this->middleware('permission:komposisiprofesional-edit');
         $this->middleware('permission:komposisiprofesional-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Referensi Komposisi Profesional');
        return view($this->__route.'.index',[
            'pagetitle' => 'Referensi Komposisi Profesional',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('referensi.komposisiprofesional.index'),
                    'menu' => 'Komposisi Profesional'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(KomposisiProfesional::query())
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data Komposisi Profesional"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'"  data-toggle="tooltip" data-original-title="Hapus data Komposisi Profesional"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->editColumn('kelas_bumn', function($row){
                return $row->kelas_bumn->nama;
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
        $komposisiprofesional = KomposisiProfesional::get();
        $kelasbumns = KelasBumn::get();
       
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'komposisiprofesional' => $komposisiprofesional,
            'kelasbumns' => $kelasbumns
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
            $param['id_kelas_bumn'] = $request->input('id_kelas_bumn');
            $param['jumlah_minimal'] = $request->input('jumlah_minimal');
            $param['jumlah_maksimal'] = $request->input('jumlah_maksimal');
            $param['keterangan'] = $request->input('keterangan');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $komposisiprofesional = KomposisiProfesional::create((array)$param);

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
                                  $komposisiprofesional = KomposisiProfesional::find((int)$request->input('id'));
                                  $komposisiprofesional->update((array)$param);

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

            $komposisiprofesional = KomposisiProfesional::find((int)$request->input('id'));
            $kelasbumns = KelasBumn::get();

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'komposisiprofesional' => $komposisiprofesional,
                    'kelasbumns' => $kelasbumns

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = KomposisiProfesional::find((int)$request->input('id'));
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
        $required['id_kelas_bumn'] = 'required';
        $required['jumlah_minimal'] = 'required';
        $required['jumlah_maksimal'] = 'required';

        $message['id_kelas_bumn.required'] = 'Kelas BUMN wajib diinput';
        $message['jumlah_minimal.required'] = 'Jumlah Minimal wajib diinput';
        $message['jumlah_maksimal.required'] = 'Jumlah maksimal wajib diinput';

        return Validator::make($request->all(), $required, $message);       
    }
}