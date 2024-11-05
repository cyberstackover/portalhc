<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CVHelper;
use App\DataKeluarga;
use App\DataKeluargaAnak;
use App\JenjangPendidikan;
use App\RiwayatPendidikan;
use App\Talenta;
use App\Kota;
use App\Provinsi;
use App\TidakMemilikiAnak;
use Carbon\Carbon;
use DB;
use Illuminate\Validation\Rule;

class KeluargaController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.keluarga';;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV Keluarga');
      $talenta = Talenta::find($id);
      $tidak_memiliki = TidakMemilikiAnak::where('id_talenta', (int)$id)->first();
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'tidak_memiliki' => $tidak_memiliki,
          'active' => "keluarga",
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
                  'url' => route('cv.keluarga.index', ['id_talenta' => $id]),
                  'menu' => 'Keluarga'
              ]               
          ]
      ]);

    }

    public function datatable(Request $request, $id)
    {
        $data = DataKeluarga::select('*')->where('id_talenta', $id)->orderBy("tanggal_lahir", 'asc');

        try{
            return datatables()->of($data)
            ->editColumn('tanggal_lahir', function ($row){
                return CVHelper::tglFormat($row->tanggal_lahir, 2);
            })
            ->editColumn('tanggal_menikah', function ($row){
                return CVHelper::tglFormat($row->tanggal_menikah, 2);
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-icon btn-sm  btn-light-primary mr-2 cls-button-edit first_table-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data keluarga"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn  btn-light-danger mr-2 btn-icon cls-button-delete btn-sm first_table-delete" data-id="'.$id.'" data-periode="'.$row->nama.'" data-toggle="tooltip" data-original-title="Hapus data keluarga"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action'])
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

    public function datatable_anak(Request $request, $id)
    {
        $data = DataKeluargaAnak::select('*')->where('id_talenta', $id)->orderBy("tanggal_lahir", 'asc');

        try{
            return datatables()->of($data)
            ->editColumn('tanggal_lahir', function ($row){
                return CVHelper::tglFormat($row->tanggal_lahir, 2);
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-icon btn-sm  btn-light-primary mr-2 cls-button-edit second_table-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data anak"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn  btn-light-danger mr-2 btn-icon cls-button-delete btn-sm second_table-delete" data-id="'.$id.'" data-periode="'.$row->nama.'" data-toggle="tooltip" data-original-title="Hapus data anak"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action'])
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
                  'id_talenta' => $id,
                  'kota' => Kota::where('is_luar_negeri', false)->get(),
                  'provinsi' => Provinsi::where('is_luar_negeri', false)->get(),
                  'negara' => Provinsi::where('is_luar_negeri', true)->get(),
          ]);
      }catch(Exception $e){}
    }

    public function create_anak(Request $request, $id)
    {
      try{
          return view($this->__route.'.form_anak',[
                  'actionform' => 'insert',
                  'id_talenta' => $id,
                  'kota' => Kota::where('is_luar_negeri', false)->get(),
                  'provinsi' => Provinsi::where('is_luar_negeri', false)->get(),
                  'negara' => Provinsi::where('is_luar_negeri', true)->get(),
          ]);
      }catch(Exception $e){}
    }

    public function edit(Request $request, $id)
    {
      $riwayat = DataKeluarga::find((int)$request->input('id'));
      $kota = Kota::Where("provinsi_id", $riwayat->refKota->provinsi_id)->get();
      
      try{
          return view($this->__route.'.form',[
                  'actionform' => 'update',
                  'id_talenta' => $id,
                  'riwayat' => $riwayat,
                  'kota' => $kota,
                  'provinsi' => Provinsi::where('is_luar_negeri', false)->get(),
                  'negara' => Provinsi::where('is_luar_negeri', true)->get(),
          ]);
      }catch(Exception $e){}

    }

    public function edit_anak(Request $request, $id)
    {
      $riwayat = DataKeluargaAnak::find((int)$request->input('id'));
      $kota = Kota::Where("provinsi_id", $riwayat->refKota->provinsi_id)->get();

      try{
          return view($this->__route.'.form_anak',[
                  'actionform' => 'update',
                  'id_talenta' => $id,
                  'riwayat' => $riwayat,
                  'kota' => $kota,
                  'provinsi' => Provinsi::where('is_luar_negeri', false)->get(),
                  'negara' => Provinsi::where('is_luar_negeri', true)->get(),
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
          $param = $request->except(['id', '_token', '_method', 'actionform', 'negara', 'id_provinsi']);
          $param['id_talenta'] = $id;
          $param['tanggal_lahir'] = Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d');
          $param['tanggal_menikah'] = Carbon::createFromFormat('d/m/Y', $request->tanggal_menikah)->format('Y-m-d');

          if($request->hubungan_keluarga == "Suami"){
            $param['jenis_kelamin'] = "L";
          }else{
            $param['jenis_kelamin'] = "P";
          }

          $kota = Kota::where('id', $request->id_kota)->first();
          $param['tempat_lahir'] = $kota->nama;

          switch ($request->input('actionform')) {
              case 'insert': DB::beginTransaction();
                             try{
                                $data = DataKeluarga::create($param);
                                $param_talenta['fill_keluarga'] = CVHelper::keluargaFillCheck($id);
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
                                $data = DataKeluarga::find((int)$request->input('id'));
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

    public function store_anak(Request $request, $id)
    {
      $result = [
          'flag' => 'error',
          'msg' => 'Error System',
          'title' => 'Error'
      ];      

      $validator = $this->validateformAnak($request);   

      if (!$validator->fails()) {          
          $param = $request->except(['id', '_token', '_method', 'actionform', 'negara', 'id_provinsi']);
          $param['id_talenta'] = $id;
          $param['tanggal_lahir'] = Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d');
          
          $kota = Kota::where('id', $request->id_kota)->first();
          $param['tempat_lahir'] = $kota->nama;

          switch ($request->input('actionform')) {
              case 'insert': DB::beginTransaction();
                             try{
                                $data = DataKeluargaAnak::create($param);
                                $tidakmemiliki = TidakMemilikiAnak::where('id_talenta', (int)$id)->first();
                                if(@$tidakmemiliki->id_talenta){
                                    $tidakmemiliki->delete();
                                }
                                $param_talenta['fill_keluarga'] = CVHelper::keluargaFillCheck($id);
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
                                $data = DataKeluargaAnak::find((int)$request->input('id'));
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
        $required['nama'] = 'required';
        $required['tanggal_lahir'] = 'required';
        $required['tanggal_menikah'] = 'required';
        $required['pekerjaan'] = 'required';
        $required['id_kota'] = 'required';

        $message['nama.required'] = 'Nama wajib Diisi';
        $message['tanggal_lahir.required'] = 'Tanggal Lahir wajib Diisi';
        $message['tanggal_menikah.required'] = 'Tanggal Menikah wajib Diisi';
        $message['pekerjaan.required'] = 'perkerjaan wajib Diisi';
        $message['id_kota.required'] = 'Tempat Lahir wajib Diisi';

        return Validator::make($request->all(), $required, $message);       
    }

    protected function validateformAnak($request)
    {
        $required['nama'] = 'required';
        $required['tanggal_lahir'] = 'required';
        $required['pekerjaan'] = 'required';
        $required['id_kota'] = 'required';

        $message['nama.required'] = 'Nama wajib Diisi';
        $message['tanggal_lahir.required'] = 'Tanggal Lahir wajib Diisi';
        $message['pekerjaan.required'] = 'perkerjaan wajib Diisi';
        $message['id_kota.required'] = 'Tempat Lahir wajib Diisi';

        return Validator::make($request->all(), $required, $message);       
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = DataKeluarga::find((int)$request->input('id'));
            $id = $data->id_talenta;
            $data->delete();
            $param_talenta['fill_keluarga'] = CVHelper::keluargaFillCheck($id);
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

    public function delete_anak(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = DataKeluargaAnak::find((int)$request->input('id'));
            $id = $data->id_talenta;
            $data->delete();
            $param_talenta['fill_keluarga'] = CVHelper::keluargaFillCheck($id);
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
            $data = TidakMemilikiAnak::where('id_talenta', (int)$id)->first();
            if(@$data->id_talenta){
                $data->delete();
            }else{
                $param['id_talenta'] = $id;
                TidakMemilikiAnak::create($param);
            }
            $param_talenta['fill_keluarga'] = CVHelper::keluargaFillCheck($id);
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