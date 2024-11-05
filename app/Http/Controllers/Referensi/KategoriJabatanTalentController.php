<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\KategoriDataTalent;
use App\KategoriJabatanTalent;
use DB;

class KategoriJabatanTalentController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'referensi.kategorijabatantalent';
         /*$this->middleware('permission:alasanpemberhentian-list');
         $this->middleware('permission:alasanpemberhentian-create');
         $this->middleware('permission:alasanpemberhentian-edit');
         $this->middleware('permission:alasanpemberhentian-delete');*/
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Referensi Kategori Jabatan Talent');
        return view($this->__route.'.index',[
            'pagetitle' => 'Referensi Kategori Jabatan Talent',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('referensi.kategorijabatantalent.index'),
                    'menu' => 'Kategori Jabatan Talent'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(KategoriJabatanTalent::query())
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data Kategori Jabatan Talent '.$row->nama.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" data-original-title="Hapus data Kategori Jabatan Talent '.$row->nama.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->addColumn('kategori_data_talent', function($row){
                return $row->kategori_data_talent->nama;
            })
            ->rawColumns(['nama','action', 'keterangan', 'kategori_data_talent'])
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
        $kategoridata = KategoriDataTalent::get();
        $kategorijabatan = KategoriJabatanTalent::get();
       
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'kategoridata' => $kategoridata,
            'kategorijabatan' => $kategorijabatan
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
            $param['id_kategori_data_talent'] = (int)$request->input('id_kategori_data_talent');
            $param['nama'] = $request->input('nama');
            $param['keterangan'] = $request->input('keterangan')?$request->input('keterangan'):' ';

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $kategorijabatan = KategoriJabatanTalent::create((array)$param);

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
                                  $kategorijabatan = KategoriJabatanTalent::find((int)$request->input('id'));
                                  $kategorijabatan->update((array)$param);

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

            $kategorijabatan = KategoriJabatanTalent::find((int)$request->input('id'));
            $kategoridata = KategoriDataTalent::get();

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'kategoridata' => $kategoridata,
                    'kategorijabatan' => $kategorijabatan

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = KategoriJabatanTalent::find((int)$request->input('id'));
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
        $required['id_kategori_data_talent'] = 'required';
        $required['nama'] = 'required';

        $message['nama.required'] = 'Nama wajib diinput';
        $message['id_kategori_data_talent.required'] = 'Jenis Alasan wajib dipilih';

        return Validator::make($request->all(), $required, $message);       
    }
}