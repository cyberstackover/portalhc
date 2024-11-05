<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CVHelper;
use App\RiwayatJabatanLain;
use App\Talenta;
use App\BidangJabatan;
use Carbon\Carbon;
use DB;
use Illuminate\Validation\Rule;

class RiwayatJabatanLainController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.riwayat_jabatan_lain';;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      return redirect(route('cv.riwayat_jabatan.index', ['id_talenta' => $id]));
    }

    public function datatable(Request $request, $id)
    {
        $data = RiwayatJabatanLain::where('id_talenta', $id)->orderBy("tanggal_awal", 'desc');

        try{
            return datatables()->of($data)
            ->editColumn('tupoksi', function ($row){
                return $row->tupoksi;
            })
            ->editColumn('penugasan', function ($row){
                return "<b>".$row->penugasan."</b></br><span>".$row->instansi."</span>";
            })
            ->editColumn('tanggal_awal', function ($row){
                return CVHelper::tglFormat($row->tanggal_awal, 2);
            })
            ->editColumn('tanggal_akhir', function ($row){
                return CVHelper::tglFormat($row->tanggal_akhir, 2);
            })
            ->editColumn('bidang_jabatan', function ($row){
                return $row->bidangJabatan->nama ?? '';
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-icon btn-sm  btn-light-primary mr-2 cls-button-edit second_table-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data Jabatan"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn  btn-light-danger mr-2 btn-icon cls-button-delete btn-sm second_table-delete" data-id="'.$id.'" data-periode="'.$row->penugasan.'" data-toggle="tooltip" data-original-title="Hapus data Jabatan"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action','penugasan','tupoksi'])
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
                  'bidang_jabatans' =>BidangJabatan::get(),
          ]);
      }catch(Exception $e){}
    }

    public function edit(Request $request, $id)
    {
        try{
          return view($this->__route.'.form',[
                  'actionform' => 'update',
                  'id_talenta' => $id,
                  'data' => RiwayatJabatanLain::find((int)$request->input('id')),
                  'bidang_jabatans' =>BidangJabatan::get(),
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

          $param['tanggal_awal'] = Carbon::createFromFormat('d/m/Y', $request->tanggal_awal)->format('Y-m-d');
          $param['tanggal_akhir'] = null;
          if($request->tanggal_akhir){
            $param['tanggal_akhir'] = Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir)->format('Y-m-d');
          }


          switch ($request->input('actionform')) {
              case 'insert': DB::beginTransaction();
                             try{
                                $data = RiwayatJabatanLain::create($param);
                                $param_talenta['fill_jabatan'] = CVHelper::jabatanFillCheck($id);
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
                                $data = RiwayatJabatanLain::find((int)$request->input('id'));
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
        $required['penugasan'] = 'required';
        $required['tanggal_awal'] = 'required';
        $required['instansi'] = 'required';
        $required['tupoksi'] = 'required';

        $message['penugasan.required'] = 'Penugasan wajib Diisi';
        $message['tanggal_awal.required'] = 'Tanggal Awal wajib Diisi';
        $message['instansi.required'] = 'Instansi wajib Diisi';
        $message['tupoksi.required'] = 'Tupoksi
         wajib Diisi';

        return Validator::make($request->all(), $required, $message);       
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = RiwayatJabatanLain::find((int)$request->input('id'));
            $id = $data->id_talenta;
            $data->delete();
            $param_talenta['fill_jabatan'] = CVHelper::jabatanFillCheck($id);
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