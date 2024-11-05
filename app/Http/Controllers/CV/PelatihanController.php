<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\RiwayatPelatihan;
use App\Talenta;
use App\Kota;
use App\Provinsi;
use App\TingkatDiklat;
use App\JenisDiklat;
use App\Helpers\CVHelper;
use DB;
use Illuminate\Validation\Rule;

class PelatihanController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.pelatihan';;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      return redirect(route('cv.pendidikan.index', ['id_talenta' => $id]));

    }

    public function datatable(Request $request, $id)
    {
        //$tahun = $request->tahun;
        $data = RiwayatPelatihan::where('id_talenta', $id)->get();
        //dd($data);

        try{
            return datatables()->of($data)
            ->editColumn('kota', function ($row){
                return $row->penyelenggara.'/'.$row->kota;
            })
            ->editColumn('lama_hari', function ($row){
                return $row->lama_hari.' Hari';
            })
            ->editColumn('tahun_diklat', function ($row){
                return $row->tahun_diklat;
            })
            ->editColumn('jenis_diklat', function ($row){
                return @$row->refJenis->nama;
            })
            ->addColumn('tingkat_diklat', function ($row){
                return @$row->refTingkat->nama;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-icon btn-sm  btn-light-primary mr-2 cls-button-edit pelatihan-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data pelatihan"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn  btn-light-danger mr-2 btn-icon cls-button-delete btn-sm pelatihan-delete" data-id="'.$id.'" data-periode="'.$row->pengembangan_kompetensi.'" data-toggle="tooltip" data-original-title="Hapus data pelatihan"><i class="flaticon-delete"></i></button>'; 

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
                  'id_talenta' => $id,
                  'actionform' => 'insert',
                  'kota' => Kota::where('is_luar_negeri', false)->get(),
                  'provinsi' => Provinsi::where('is_luar_negeri', false)->get(),
                  'negara' => Provinsi::where('is_luar_negeri', true)->get(),
                  'tingkatdiklat' => TingkatDiklat::get(),
                  'jenisdiklat' => JenisDiklat::get(),
          ]);
      }catch(Exception $e){}
    }

    public function edit(Request $request, $id)
    {
        $riwayat = RiwayatPelatihan::find((int)$request->input('id'));
        $kota = Kota::Where("provinsi_id", $riwayat->refKota->provinsi_id)->get();

        try{
          return view($this->__route.'.form',[
                  'id_talenta' => $id,
                  'actionform' => 'update',
                  'riwayat' => RiwayatPelatihan::find((int)$request->input('id')),
                  'kota' => $kota,
                  'provinsi' => Provinsi::where('is_luar_negeri', false)->get(),
                  'negara' => Provinsi::where('is_luar_negeri', true)->get(),
                  'tingkatdiklat' => TingkatDiklat::get(),
                  'jenisdiklat' => JenisDiklat::get(),
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
          $param['tahun_diklat'] = (int)$request->tahun_diklat;
          $param['id_tingkat'] = (int)$request->id_tingkat;
          $param['id_jenis'] = (int)$request->id_jenis;
          
          $kota = Kota::where('id', $request->id_kota)->first();
          $param['kota'] = $kota->nama;

          switch ($request->input('actionform')) {
              case 'insert': DB::beginTransaction();
                             try{
                                $riwayatPelatihan = RiwayatPelatihan::create($param);
                                $param_talenta['fill_pelatihan'] = CVHelper::pelatihanFillCheck($id);
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
                                $riwayatPelatihan = RiwayatPelatihan::find((int)$request->input('id'));
                                $riwayatPelatihan->update($param);

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
        $required['id_jenis'] = 'required';
        $required['penyelenggara'] = 'required';
        $required['nomor_sertifikasi'] = 'required';
        $required['pengembangan_kompetensi'] = 'required';
        $required['id_kota'] = 'required';
        $required['lama_hari'] = 'required';
        $required['tahun_diklat'] = 'required';
        $required['id_tingkat'] = 'required';

        $message['id_jenis.required'] = 'Jenis Diklat wajib dipilih';
        $message['penyelenggara.required'] = 'penyelenggara wajib dipilih';
        $message['nomor_sertifikasi.required'] = 'Nomor Sertifikasi wajib dipilih';
        $message['pengembangan_kompetensi.required'] = 'Pengembangan Kompetensi wajib dipilih';
        $message['lama_diklat.required'] = 'Lama Diklat wajib dipilih';
        $message['id_kota.required'] = 'Kota wajib dipilih';
        $message['tahun_diklat.required'] = 'Tahun wajib dipilih';
        $message['id_tingkat.required'] = 'Tingkat wajib dipilih';

        return Validator::make($request->all(), $required, $message);       
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = RiwayatPelatihan::find((int)$request->input('id'));
            $id = $data->id_talenta;
            $data->delete();
            $param_talenta['fill_pelatihan'] = CVHelper::pelatihanFillCheck($id);
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