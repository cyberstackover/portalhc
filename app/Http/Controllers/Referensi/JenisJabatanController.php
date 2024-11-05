<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\JenisJabatan;
use App\GrupJabatan;
use DB;

class JenisJabatanController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'referensi.jenisjabatan';
         $this->middleware('permission:jenisjabatan-list');
         $this->middleware('permission:jenisjabatan-create');
         $this->middleware('permission:jenisjabatan-edit');
         $this->middleware('permission:jenisjabatan-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Referensi Jenis Jabatan');
        $jenisjabatans = JenisJabatan::orderBy('id_grup_jabatan')->orderBy('urut')->get();
        return view($this->__route.'.index',[
            'jenisjabatans' => $jenisjabatans,
            'pagetitle' => 'Referensi Jenis Jabatan',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('referensi.jenisjabatan.index'),
                    'menu' => 'Jenis Jabatan'
                ]               
            ]
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $grupjabatans = GrupJabatan::get();
        $jenis_jabatans = JenisJabatan::get();
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'grupjabatans' => $grupjabatans,
            'jenis_jabatans' => $jenis_jabatans,
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
            $param['id_grup_jabatan']          = $request->input('id_grup_jabatan');
            $param['nama']                     = $request->input('nama');
            $param['prosentase_gaji']          = $request->input('prosentase_gaji');
            $param['parent_id']                = $request->input('parent_id');
            $param['id_jns_jab_pengali']       = $request->input('id_jns_jab_pengali');
            $param['urut']                     = $request->input('urut');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $jenisjabatan = JenisJabatan::create((array)$param);

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
                                  $jenisjabatan = JenisJabatan::find((int)$request->input('id'));
                                  $jenisjabatan->update((array)$param);

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

            $jenisjabatan = JenisJabatan::find((int)$request->input('id'));
            $grupjabatans = GrupJabatan::get();
            $jenis_jabatans = JenisJabatan::get();

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'jenisjabatan' => $jenisjabatan,
                    'grupjabatans' => $grupjabatans,
                    'jenis_jabatans' => $jenis_jabatans

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = JenisJabatan::find((int)$request->input('id'));
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
        $required['id_grup_jabatan'] = 'required';
        $required['nama'] = 'required';

        $message['nama.required'] = 'Nama Jenis Jabatan wajib diinput';
        $message['id_grup_jabatan.required'] = 'Jenis Grup Jabatan wajib diinput';

        return Validator::make($request->all(), $required, $message);       
    }
}