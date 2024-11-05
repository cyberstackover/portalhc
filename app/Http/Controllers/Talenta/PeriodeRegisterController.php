<?php

namespace App\Http\Controllers\Talenta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\PeriodeRegister;
use DB;

class PeriodeRegisterController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'talenta.periode';
         $this->__title = "Periode";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Talenta Periode');
        return view($this->__route.'.index',[
            'pagetitle' => $this->__title,
            'breadcrumb' => [
                [
                  'url' => '/',
                  'menu' => 'Homes'
              ],
              [
                  'url' => route('talenta.periode.index'),
                  'menu' => 'Talent Management'
              ],
              [
                  'url' => route('talenta.periode.index'),
                  'menu' => 'Periode'
              ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(PeriodeRegister::orderBy('id', 'ASC')->get())
            ->editColumn('aktif', function ($row){
                $html = '';

                $aktif = $row->aktif ? 'checked' : '';

                $html .= '<div align="center"><input type="checkbox" name="aktif" id="aktif" class="make-switch" data-on-text="Aktif" data-off-text="Tidak" data-size="mini" onchange="submitAktif('.(int)$row->id.', this.checked);" '.$aktif.'></div>';

                return $html;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data periode '.$row->nama.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'" data-nama="'.$row->nama.'" data-toggle="tooltip" data-original-title="Hapus data periode '.$row->nama.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama','tmt_awal','tmt_akhir','aktif','action'])
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
        $register = PeriodeRegister::get();
       
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'register' => $register
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
            $param['keterangan'] = $request->input('keterangan')?$request->input('keterangan'):' ';
            $param['tmt_awal'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tmt_awal)->format('Y-m-d');
            $param['tmt_akhir'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tmt_akhir)->format('Y-m-d');


            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  PeriodeRegister::query()->update(['aktif' => false]);
                                  $param['aktif'] = true;
                                  $status = PeriodeRegister::create((array)$param);

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
                                  $status = PeriodeRegister::find((int)$request->input('id'));
                                  $status->update((array)$param);

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

            $register = PeriodeRegister::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'register' => $register

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = PeriodeRegister::find((int)$request->input('id'));
            $data->delete();

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses Hapus data Periode',
                'title' => 'Sukses'
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal Hapus Data Periode',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);       
    }

    public function changeaktif(Request $request)
    {
        $id = $request->id;
        $required = $request->required;

        DB::beginTransaction();
        try {
            PeriodeRegister::query()->update(['aktif' => false]);
            $perioderegister = PeriodeRegister::find($id);
            $perioderegister->aktif = $required;
            $perioderegister->save();

            \DB::commit();
            $save = true;
        } catch (Exception $e) {
            \DB::rollback();
            $save = false;
        }

        if($save == true){
         $flag = 'success';
         $msg = 'Sukses Ubah Status';
         $title = 'Sukses';           
            } else {
         $flag = 'error';
         $msg = 'Gagal Ubah Status';
         $title = 'Error';          
        }

        return response()->json(['flag' => $flag, 'msg' => $msg, 'title' => $title]);
    }

    protected function validateform($request)
    {
        $required['nama'] = 'required';

        $message['nama.required'] = 'Nama Status wajib diinput';

        return Validator::make($request->all(), $required, $message);       
    }
}
