<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CVHelper;
use App\RiwayatOrganisasi;
use App\Talenta;
use App\TidakMemilikiOrganisasi;
use App\TidakMemilikiOrganisasiNonformal;
use Carbon\Carbon;
use DB;
use Illuminate\Validation\Rule;

class RiwayatOrganisasiController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.riwayat_organisasi';;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV Organisasi');
      $talenta = Talenta::find($id);
      $tidak_memiliki = TidakMemilikiOrganisasi::where('id_talenta', (int)$id)->first();
      $tidak_memiliki2 = TidakMemilikiOrganisasiNonformal::where('id_talenta', (int)$id)->first();
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'tidak_memiliki' => $tidak_memiliki,
          'tidak_memiliki2' => $tidak_memiliki2,
          'active' => "organisasi",
          'breadcrumb' => [
              [
                  'url' => '/',
                  'menu' => 'Homes'
              ],
              [
                  'url' => route('cv.board.index'),
                  'menu' => 'CV'
              ] ,
              [
                  'url' => route('cv.riwayat_organisasi.index', ['id_talenta' => $id]),
                  'menu' => 'Organisasi'
              ]               
          ]
      ]);

    }

    public function datatable(Request $request, $id)
    {
        $data = RiwayatOrganisasi::select('*')->where('id_talenta', $id)->where('formal_flag', true)->orderBy("tahun_awal", 'desc')->orderBy("tahun_akhir", 'desc');

        try{
            return datatables()->of($data)
            ->editColumn('tahun_awal', function ($row){
                if($row->tahun_akhir){
                  return $row->tahun_awal." - ".$row->tahun_akhir;
                }else{                  
                  return $row->tahun_awal." - Saat Ini";
                }
            })
            ->editColumn('jabatan', function ($row){
                return "<b>".$row->jabatan."</b></br><span>".$row->nama_organisasi."</span>";
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-icon btn-sm  btn-light-primary mr-2 cls-button-edit first_table-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data Referensi"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn  btn-light-danger mr-2 btn-icon cls-button-delete btn-sm first_table-delete" data-id="'.$id.'" data-periode="'.$row->nama_organisasi.'" data-toggle="tooltip" data-original-title="Hapus data Referensi"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action', 'jabatan'])
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

    public function datatable_nonformal(Request $request, $id)
    {
        $data = RiwayatOrganisasi::select('*')->where('id_talenta', $id)->where('formal_flag', false)->orderBy("tahun_akhir", 'desc')->orderBy("tahun_awal", 'desc');

        try{
            return datatables()->of($data)
            ->editColumn('tahun_awal', function ($row){
                if($row->tahun_akhir){
                  return $row->tahun_awal." - ".$row->tahun_akhir;
                }else{                  
                  return $row->tahun_awal." - Saat Ini";
                }
            })
            ->editColumn('jabatan', function ($row){
                return "<b>".$row->jabatan."</b></br><span>".$row->nama_organisasi."</span>";
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-icon btn-sm  btn-light-primary mr-2 cls-button-edit second_table-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data Referensi"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn  btn-light-danger mr-2 btn-icon cls-button-delete btn-sm second_table-delete" data-id="'.$id.'" data-periode="'.$row->nama_organisasi.'" data-toggle="tooltip" data-original-title="Hapus data Referensi"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action', 'jabatan'])
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

    public function create(Request $request, $id)
    {
      try{
          return view($this->__route.'.form',[
                  'actionform' => 'insert',
                  'id_talenta' => $id
          ]);
      }catch(Exception $e){}
    }

    public function create_nonformal(Request $request, $id)
    {
      try{
          return view($this->__route.'.form_nonformal',[
                  'actionform' => 'insert',
                  'id_talenta' => $id
          ]);
      }catch(Exception $e){}
    }

    public function edit(Request $request, $id)
    {
        try{
          return view($this->__route.'.form',[
                  'actionform' => 'update',
                  'id_talenta' => $id,
                  'data' => RiwayatOrganisasi::find((int)$request->input('id')),
          ]);
      }catch(Exception $e){}

    }

    public function edit_non_formal(Request $request, $id)
    {
        try{
          return view($this->__route.'.form_nonformal',[
                  'actionform' => 'update',
                  'id_talenta' => $id,
                  'data' => RiwayatOrganisasi::find((int)$request->input('id')),
          ]);
      }catch(Exception $e){}

    }

    public function store(Request $request, $id)
    {
      $result = [
          'flag' => 'error',
          'msg' => 'Error System',
          'title' => 'Error'
      ];      

      $validator = $this->validateform($request);   

      if (!$validator->fails()) {          
          $param = $request->except(['id', '_token', '_method', 'actionform', 'formal_flag']);
          $param['id_talenta'] = $id;

          if($request->formal_flag == "TRUE"){
            $param['formal_flag'] = true;
          }else{
            $param['formal_flag'] = false;
          }


          /*$param['tanggal_awal'] = Carbon::createFromFormat('d/m/Y', $request->tanggal_awal)->format('Y-m-d');
          $param['tanggal_akhir'] = null;
          if($request->tanggal_akhir){
            $param['tanggal_akhir'] = Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir)->format('Y-m-d');
          }*/

          $param['tahun_awal'] = $request->tahun_awal;
          $param['tahun_akhir'] = null;
          if($request->tahun_akhir){
            $param['tahun_akhir'] = $request->tahun_akhir;
          }


          switch ($request->input('actionform')) {
              case 'insert': DB::beginTransaction();
                             try{
                                $data = RiwayatOrganisasi::create($param);

                                
                                if($request->formal_flag == "TRUE"){
                                    $tidakmemiliki = TidakMemilikiOrganisasi::where('id_talenta', (int)$id)->first();
                                    if(@$tidakmemiliki->id_talenta){
                                        $tidakmemiliki->delete();
                                    }
                                    $param_talenta['fill_organisasi'] = CVHelper::organisasiFillCheck($id);
                                }else{
                                    $tidakmemiliki = TidakMemilikiOrganisasiNonformal::where('id_talenta', (int)$id)->first();
                                    if(@$tidakmemiliki->id_talenta){
                                        $tidakmemiliki->delete();
                                    }
                                    $param_talenta['fill_organisasinonformal'] = CVHelper::organisasinonformalFillCheck($id);
                                }
                                $param_talenta['persentase'] = CVHelper::fillPercentage($id);
                                $status = Talenta::find($id)->update($param_talenta);

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
                                $data = RiwayatOrganisasi::find((int)$request->input('id'));
                                $data->update($param);

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
        $required['nama_organisasi'] = 'required';
        $required['tahun_awal'] = 'required';
        $required['jabatan'] = 'required';
        $required['kegiatan_organisasi'] = 'required';

        $message['nama_organisasi.required'] = 'Nama Organisasi wajib Diisi';
        $message['tahun_awal.required'] = 'Tahun Awal wajib Diisi';
        $message['jabatan.required'] = 'Jabatan wajib Diisi';
        $message['kegiatan_organisasi.required'] = 'Uraian wajib Diisi';

        return Validator::make($request->all(), $required, $message);       
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = RiwayatOrganisasi::find((int)$request->input('id'));
            $id = $data->id_talenta;
            $data->delete();
            $param_talenta['fill_organisasi'] = CVHelper::organisasiFillCheck($id);
            $param_talenta['persentase'] = CVHelper::fillPercentage($id);
            $status = Talenta::find($id)->update($param_talenta);

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

    public function tidak_memiliki(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $data = TidakMemilikiOrganisasi::where('id_talenta', (int)$id)->first();
            if(@$data->id_talenta){
                $data->delete();
            }else{
                $param['id_talenta'] = $id;
                TidakMemilikiOrganisasi::create($param);
            }
            $param_talenta['fill_organisasi'] = CVHelper::organisasiFillCheck($id);
            $param_talenta['persentase'] = CVHelper::fillPercentage($id);
            $status = Talenta::find($id)->update($param_talenta);

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

    public function tidak_memiliki_nonformal(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $data = TidakMemilikiOrganisasiNonformal::where('id_talenta', (int)$id)->first();
            if(@$data->id_talenta){
                $data->delete();
            }else{
                $param['id_talenta'] = $id;
                TidakMemilikiOrganisasiNonformal::create($param);
            }
            $param_talenta['fill_organisasi_nonformal'] = CVHelper::organisasinonformalFillCheck($id);
            $param_talenta['persentase'] = CVHelper::fillPercentage($id);
            $status = Talenta::find($id)->update($param_talenta);

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
}