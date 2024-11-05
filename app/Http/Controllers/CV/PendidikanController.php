<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\JenjangPendidikan;
use App\Universitas;
use App\RiwayatPendidikan;
use App\Kota;
use App\Provinsi;
use App\Talenta;
use App\Helpers\CVHelper;
use DB;
use Illuminate\Validation\Rule;

class PendidikanController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.pendidikan';;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV Pendidikan');
      $talenta = Talenta::find($id);
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'active' => "pendidikan",
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
                  'url' => route('cv.pendidikan.index', ['id_talenta' => $id]),
                  'menu' => 'Pendidikan'
              ]               
          ]
      ]);

    }

    public function datatable(Request $request, $id)
    {
        $tahun = $request->tahun;
        $data = RiwayatPendidikan::select('*')->where('id_talenta', $id)->orderBy("tahun", 'desc');

        try{
            return datatables()->of($data)
            ->editColumn('id_jenjang_pendidikan', function ($row){
                return $row->jenjangPendidikan->nama.'-'.$row->penjurusan;
            })
            ->editColumn('kota', function ($row){
                return $row->kota.'/'.$row->negara;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-icon btn-sm  btn-light-primary mr-2 cls-button-edit pendidikan-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data pendidikan"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn  btn-light-danger mr-2 btn-icon cls-button-delete btn-sm pendidikan-delete" data-id="'.$id.'" data-periode="'.$row->jenjangPendidikan->nama.'-'.$row->penjurusan.'" data-toggle="tooltip" data-original-title="Hapus data pendidikan"><i class="flaticon-delete"></i></button>'; 

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
                  'jenjangPendidikan' => JenjangPendidikan::get(),
                  'kota' => Kota::where('is_luar_negeri', false)->get(),
                  'provinsi' => Provinsi::where('is_luar_negeri', false)->get(),
                  'negara' => Provinsi::where('is_luar_negeri', true)->get(),
                  'universitas' => Universitas::get()
          ]);
      }catch(Exception $e){}
    }

    public function edit(Request $request, $id)
    {
        $riwayat = RiwayatPendidikan::find((int)$request->input('id'));
        $kota = Kota::Where("provinsi_id", $riwayat->refKota->provinsi_id)->get();

        try{
          return view($this->__route.'.form',[
                  'actionform' => 'update',
                  'id_talenta' => $id,
                  'jenjangPendidikan' => JenjangPendidikan::get(),
                  'riwayat' => $riwayat,
                  'kota' => $kota,
                  'provinsi' => Provinsi::where('is_luar_negeri', false)->get(),
                  'negara' => Provinsi::where('is_luar_negeri', true)->get(),
                  'universitas' => Universitas::get()
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
          $param = $request->except(['id', '_token', '_method', 'actionform', 'negara', 'id_provinsi', 'id_universitas']);
          $param['id_talenta'] = $id;
          
          $kota = Kota::where('id', $request->id_kota)->first();
          $param['kota'] = $kota->nama;

          if($request->negara == "INDONESIA"){
            $param['negara'] = "INDONESIA";
          }else{
            $negara = Provinsi::where('id', $request->negara)->first();
            $param['negara'] = $negara->nama;
          }

          if($request->id_universitas){
            if (is_numeric($request->id_universitas)){
                $univ = Universitas::where('id', $request->id_universitas)->first();
            }else{
                $param_univ['unverified'] = 't';
                $param_univ['nama'] = $request->id_universitas;
                $param_univ['id_kota'] = $request->id_kota;
                $param_univ['id_provinsi'] = $request->provinsi;
                if($request->negara == "INDONESIA"){
                    $negara = Provinsi::where('nama', "INDONESIA")->first();
                    $param_univ['id_negara'] = @$negara->id;
                }else{
                    $param_univ['id_negara'] = $request->negara;
                }
                $univ = Universitas::create((array)$param_univ);
            }
            $param['id_universitas'] = $univ->id; 
            $param['perguruan_tinggi'] = $univ->nama;

          }

          switch ($request->input('actionform')) {
              case 'insert': DB::beginTransaction();
                             try{
                                $riwayatPendidikan = RiwayatPendidikan::create($param);
                                $param_talenta['fill_pendidikan'] = CVHelper::pendidikanFillCheck($id);
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
                                $riwayatPendidikan = RiwayatPendidikan::find((int)$request->input('id'));
                                $riwayatPendidikan->update($param);

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
        $required['id_jenjang_pendidikan'] = 'required';
        $required['tahun'] = 'required';
        $required['id_universitas'] = 'required';
        $required['penjurusan'] = 'required';
        $required['id_kota'] = 'required';
        $required['negara'] = 'required';

        $message['id_jenjang_pendidikan.required'] = 'Jenjang Pendidikan wajib dipilih';
        $message['id_universitas.required'] = 'Perguruan Tinggi wajib dipilih';
        $message['penjurusan.required'] = 'Penjurusan wajib dipilih';
        $message['tahun.required'] = 'Tahun wajib dipilih';
        $message['negara.required'] = 'Negara wajib dipilih';
        $message['id_kota.required'] = 'Kota wajib dipilih';

        return Validator::make($request->all(), $required, $message);       
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = RiwayatPendidikan::find((int)$request->input('id'));
            $id = $data->id_talenta;
            $data->delete();
            $param_talenta['fill_pendidikan'] = CVHelper::pendidikanFillCheck($id);
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
}