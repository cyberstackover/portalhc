<?php

namespace App\Http\Controllers\Organ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\KelasMaster;
use App\KelasBumn;
use App\Perusahaan;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class KelasBumnController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'organ.kelasbumn';
         $this->middleware('permission:kelasbumn-list');
         $this->middleware('permission:kelasbumn-create');
         $this->middleware('permission:kelasbumn-edit');
         $this->middleware('permission:kelasbumn-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
    	activity()->log('Menu Organ Kelas BUMN');
        return view($this->__route.'.index',[
            'pagetitle' => 'kelas BUMN',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Home'
                ],
                [
                    'url' => route('organ.kelasbumn.index'),
                    'menu' => 'Kelas BUMN'
                ]               
            ]
        ]);
    }

    public function detailbumn(Request $request)
    {
        $id = $request->input('id');

        $sql_detailbumn = 'SELECT
                                perusahaan.nama_lengkap 
                            FROM
                                kelas_has_bumn
                                LEFT JOIN perusahaan ON perusahaan.ID = kelas_has_bumn.perusahaan_id
                                LEFT JOIN kelas_master ON kelas_master.ID = kelas_has_bumn.kelas_master_id
                            where
                              kelas_master.id = '.$id.'
                            ORDER BY
                              perusahaan.id asc';

        $detailbumns  = DB::select(DB::raw($sql_detailbumn));

        return view($this->__route.'.detailbumn',[
            'detailbumns' => $detailbumns,
        ]);
    }

    public function datatable(Request $request)
    {
        $kelass = KelasBumn::orderBy('id','ASC')->get();

        try{

            $id_sql = 'SELECT
                            kelas_master.ID,
                            kelas_bumn.nama,
                            kelas_bumn.id as kelas_id,
                            kelas_master.std_direksi,
                            kelas_master.std_direksi_max,
                            kelas_master.std_komwas,
                            kelas_master.std_komwas_max,
                            count(kelas_has_bumn.perusahaan_id) as total_bumn
                        FROM
                            kelas_master
                            LEFT JOIN kelas_has_bumn ON kelas_has_bumn.kelas_master_id = kelas_master.
                            ID LEFT JOIN kelas_bumn ON kelas_bumn.ID = kelas_master.kelas_bumn_id
                        GROUP BY
                          kelas_master.ID,
                            kelas_bumn.nama,
                            kelas_bumn.id,
                            kelas_master.std_direksi,
                            kelas_master.std_komwas,
                            kelas_master.std_direksi_max,
                            kelas_master.std_komwas_max
                        ORDER BY
                          kelas_master.id asc';

            $isikelass  = DB::select(DB::raw($id_sql));

            return datatables()->of($isikelass)
            ->editColumn('total_bumn', function ($row){
                return '<a href="javascript:;" class="btn btn-outline-hover-danger cls-button-detail" style="cursor: pointer;" data-toggle="tooltip" data-id="'.$row->kelas_id.'">'.$row->total_bumn.' </a>';
                
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah kelas bumn '.$row->nama.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete" data-id="'.$id.'" data-kelasbumn="'.$row->nama.'" data-toggle="tooltip" data-original-title="Hapus Kelas BUMN '.$row->nama.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama','std_direksi','std_komwas','total_bumn', 'std_direksi_max','std_komwas_max','action'])
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

    public function create()

    {
        $kelasbumns = KelasBumn::get();
        $perusahaans = Perusahaan::get();
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'kelasbumns' => $kelasbumns,
            'perusahaans' => $perusahaans
        ]);

    }

    public function edit(Request $request)
    {

        try{

            $perusahaans = Perusahaan::get();
            $kelasmasters = KelasMaster::find((int)$request->input('id'));
            $kelasbumns = KelasBumn::get();
            $kelashasbumns = DB::table("kelas_has_bumn")->where("kelas_has_bumn.kelas_master_id",(int)$request->input('id'))
                ->pluck('kelas_has_bumn.perusahaan_id','kelas_has_bumn.perusahaan_id')
                ->all();

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'kelasmasters' => $kelasmasters,
                    'kelasbumns' => $kelasbumns,
                    'kelashasbumns' => $kelashasbumns,
                    'perusahaans' => $perusahaans

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $datamaster = KelasMaster::find((int)$request->input('id'));
            $datamaster->delete();

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

    public function store(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];      

        $validator = $this->validateform($request);   

        if (!$validator->fails()) {
            $param['kelas_bumn_id'] = $request->input('kelas_bumn_id');
            $param['std_direksi'] = $request->input('std_direksi');
            $param['std_komwas'] = $request->input('std_komwas');
            $param['std_direksi_max'] = $request->input('std_direksi_max');
            $param['std_komwas_max'] = $request->input('std_komwas_max');
            $perusahaan_id = $request->input('perusahaan_id');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $kelasmaster = KelasMaster::create((array)$param);
                                  $kelasmaster->bumns()->sync($perusahaan_id);
                                  //$role->units()->sync($unit);

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
                                  $kelasmaster = KelasMaster::find((int)$request->input('id'));
                                  $kelasmaster->bumns()->sync($perusahaan_id);
                                  //$role->units()->sync($unit);
                                  $kelasmaster->update((array)$param);

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
                                    'msg' => $e->getMessage(),
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

    protected function validateform($request)
    {
        $required['kelas_bumn_id'] = 'required';
        $required['std_direksi'] = 'required';
        $required['std_komwas'] = 'required';
        $required['perusahaan_id'] = 'required';

        $message['perusahaan_id.required'] = 'Perusahaan wajib dipilih';
        $message['kelas_bumn_id.required'] = 'Kelas wajib dipilih';
        $message['std_direksi.required'] = 'STD Direksi wajib dipilih';
        $message['std_komwas.required'] = 'Permission wajib dipilih';

        return Validator::make($request->all(), $required, $message);       
    }
}
