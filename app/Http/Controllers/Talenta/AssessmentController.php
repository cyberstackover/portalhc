<?php
namespace App\Http\Controllers\Talenta;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Talenta\VerifikasiKbumnController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Talenta;
use App\Perusahaan;
use App\LembagaAssessment;
use DB;
use Carbon\Carbon;
use App\User;
use Illuminate\Validation\Rule;

class AssessmentController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->__route = 'talenta.assessment';
         $this->__title = "Assessment Assignment";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
      activity()->log('Menu Talenta Assessment Assignment');
      $data['jumlah_nominated'] = VerifikasiKbumnController::query_talenta(5)->get()->count();
      $data['jumlah_eligible1'] = VerifikasiKbumnController::query_talenta(6)->get()->count();
      $data['jumlah_eligible2'] = VerifikasiKbumnController::query_talenta(7)->get()->count();
      $data['jumlah_qualified'] = VerifikasiKbumnController::query_talenta(8)->get()->count();

      return view($this->__route.'.index',[
          'pagetitle' => $this->__title,
          'route' => $this->__route,
            'talenta' => Talenta::orderBy('id', 'asc')->get(),
            'perusahaan' => Perusahaan::orderBy('id', 'asc')->get(),
            'data' => $data,
            'breadcrumb' => [
              [
                  'url' => '/',
                  'menu' => 'Homes'
              ],
              [
                  'url' => route('cv.board.index'),
                  'menu' => 'Talent Management'
              ],
              [
                  'url' => route('talenta.assessment.index'),
                  'menu' => 'Assessment Assignment'
              ]
          ]
      ]);

    }


    public function datatable(Request $request)
    {
        $id_users = \Auth::user()->id;
        $id_users_bumn = \Auth::user()->id_bumn;
        $users = User::where('id', $id_users)->first();

        $talenta = DB::table('talenta')
                      ->whereIn('talenta.id_status_talenta', [6,7])
                      ->leftJoin('status_talenta', 'status_talenta.id', '=', 'talenta.id_status_talenta')
                      ->leftJoin('view_organ_perusahaan', 'talenta.id', '=', 'view_organ_perusahaan.id_talenta')
                      ->leftJoin('struktur_organ', 'struktur_organ.id', '=', 'view_organ_perusahaan.id_struktur_organ')
                      ->leftJoin('perusahaan', 'perusahaan.id', '=', 'struktur_organ.id_perusahaan')
                      ->leftJoin('perusahaan as p', 'p.id', '=', 'talenta.id_perusahaan')
                      ->leftJoin(DB::raw("lateral (select s.nomenklatur_jabatan, p.nama_lengkap
                                      from view_organ_perusahaan v
                                      left join struktur_organ s on v.id_struktur_organ = s.id
                                      left join perusahaan p on p.id = s.id_perusahaan
                                      where v.id_talenta = talenta.id 
                                      and v.aktif = 't'
                                      order by s.urut ASC 
                                      limit 1) jabatan"), 'talenta.id', '=', 'talenta.id')
                      ->select(DB::raw("talenta.id,
                                    talenta.nama_lengkap, 
                                    talenta.nik, 
                                    talenta.persentase, 
                                    talenta.id_lembaga_assessment, 
                                    talenta.id_perusahaan, 
                                    talenta.id_status_talenta, 
                                    jabatan.nama_lengkap as nama_perusahaan, 
                                    jabatan.nomenklatur_jabatan as jabatan,
                                    p.id as perusahaan_id,
                                    p.nama_lengkap as p_nama_perusahaan,
                                    status_talenta.nama as status_talenta,
                                    status_talenta.nama as stalenta,
                                    talenta.id_jenis_asal_instansi"))
                                    ->where('p.is_active', true)
                      ->GroupBy('talenta.id', 'jabatan.nomenklatur_jabatan', 'jabatan.nama_lengkap', 'status_talenta.nama','p.id')
                      ->orderBy('talenta.id_status_talenta', 'ASC')
                      ->orderBy('talenta.persentase', 'DESC')
                      ->orderBy('talenta.nama_lengkap', 'ASC');

       if($users->kategori_user_id != 1){
            $talenta = $talenta->whereRaw("(( view_organ_perusahaan.aktif = true 
                                        and (view_organ_perusahaan.tanggal_akhir >= now( ) 
                                        or view_organ_perusahaan.tanggal_akhir is null) )
                                        AND
                                        (( (perusahaan.id in (
                                        WITH RECURSIVE anak AS (
                                        SELECT
                                          perusahaan_id,
                                          perusahaan_induk_id,
                                          tmt_awal,
                                          tmt_akhir 
                                        FROM
                                          perusahaan_relasi 
                                        WHERE
                                          perusahaan_induk_id = ".$id_users_bumn." 
                                          UNION
                                          SELECT
                                            pr.perusahaan_id,
                                            pr.perusahaan_induk_id,
                                            pr.tmt_awal,
                                            pr.tmt_akhir 
                                          FROM
                                            perusahaan_relasi pr
                                            INNER JOIN anak A ON A.perusahaan_id = pr.perusahaan_induk_id 
                                          ) SELECT
                                          P.ID
                                        FROM
                                          anak ak
                                          LEFT JOIN perusahaan P ON ak.perusahaan_id = P.ID 
                                        WHERE
                                          P.is_active is true 
                                          GROUP BY
                                            P.urut,
                                            P.ID,
                                            P.nama_lengkap,
                                            ak.perusahaan_induk_id,
                                            ak.perusahaan_id,
                                            ak.tmt_awal,
                                            ak.tmt_akhir 
                                          ORDER BY
                                          P.urut ASC,
                                        P.ID ASC 
                                    ))
                                  ) OR perusahaan.id = ".$id_users_bumn.")
                                  OR 
                                  talenta.id_perusahaan = ".$id_users_bumn.") ");
        }

        if($request->nama_lengkap){
          $talenta->where("talenta.nama_lengkap",'ilike', '%'.$request->nama_lengkap.'%');
        }

        if($request->instansi){
          $instansi = Perusahaan::find($request->instansi);
          $talenta->where("jabatan.nama_lengkap", $instansi->nama_lengkap);
        }

        try{
            return datatables()->of($talenta)
            ->editColumn('nama_lengkap', function ($row){
                if($row->nik != '')  {
                  $return = '<a href="javascript:;" class="cls-minicv" data-id="'.(int)$row->id.'" data-toggle="tooltip" data-original-title="CV">
                              <b>'.$row->nama_lengkap."</b></br><span>".$row->nik.'
                          </span></a>';
                }else{
                  $return = '<a href="javascript:;" class="cls-minicv" data-id="'.(int)$row->id.'" data-toggle="tooltip" data-original-title="CV">
                                          <b>'.$row->nama_lengkap."</b></br><span style='color:red'><i>NIK belum ada</i>
                                      </span></a>";
                }
                
                return $return;
            })
            ->editColumn('jabatan', function ($row){
                if($row->jabatan){
                  return "<b>".$row->jabatan."</b></br><span>".$row->nama_perusahaan."</span>";
                  //return "<b>".$row->jabatan."</span>";
                }else{
                  return "Tidak Sedang Menjabat";
                }
            })
            ->addColumn('bumn_induk', function ($row){
                if(empty($row->id_bumn)){
                  return "Tidak Ada Perusahaan";
                } else {
                  $Induk = Perusahaan::where('id', $row->id_bumn)->first();
                  if($Induk->induk == 0){
                    return "<b>".$Induk->nama_lengkap."</span>";
                  } else {
                    $anak = Perusahaan::where('id', $Induk->induk)->first();
                    return "<b>".$anak->nama_lengkap."</span>";
                  }
                }
            })
            ->addColumn('status_pengisian', function ($row){
                $persentase = (int)$row->persentase;
                if($persentase == 100){
                  return '<b>'.$persentase.'</b>';
                }else{
                  return $persentase;
                }
            })
            ->editColumn('stalenta', function ($row)use($users){
                $color = '';
                $html = '';
                $indukperusah = '';
                if($row->id_status_talenta==1){
                  $color = '#c5ff6f';
                }else if($row->id_status_talenta==2){
                  $color = '#b8e0f4';
                }else if($row->id_status_talenta==3){
                  $color = '#d4d4d4';
                }else if($row->id_status_talenta==4){
                  $color = '#efb3f5';
                }else if($row->id_status_talenta==5){
                  $color = '#b5f0ed';
                }else if($row->id_status_talenta==6){
                  $color = '#a7a0ff';
                }else if($row->id_status_talenta==7){
                  $color = '#a7a0ff';
                }else if($row->id_status_talenta==8){
                  $color = '#6ebbff';
                }

                if(empty($row->id_bumn)){
                  $id_perusahaan = (int)$row->id_perusahaan;
                  if(empty($id_perusahaan)){
                    $indukperusah .= 'Tidak Mempunyai Perusahaan';
                  } else {
                    $getPerusahaan = Perusahaan::where('id', $id_perusahaan)->first();
                    $indukperusah .= "<b>".$getPerusahaan->nama_lengkap."</b>";
                  }
                  
                } else {
                  $Induk = Perusahaan::where('id', $row->id_bumn)->first();
                  if($Induk->induk == 0){
                    $indukperusah .= "<b>".$Induk->nama_lengkap."</b>";
                  } else {
                    $anak = Perusahaan::where('id', $Induk->induk)->first();
                    $indukperusah .= "<b>".$anak->nama_lengkap."</b>";
                  }
                }
                $edit = '';

                $html .= '<a href="#" style="color:#4f4f4f;background-color:'.$color.';" class="cls-logstatus kt-badge kt-badge--inline kt-badge--pill kt-badge--rounded" data-id="'.(int)$row->id.'" data-toggle="tooltip" data-original-title="Log Status"><b>'.$row->stalenta.'</b></a></b>'.$edit.'</br><span>'.$indukperusah.'</span>';
                return $html;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $lembaga_assessment = LembagaAssessment::get();
                
                $button = '<select data-id_talenta="'.$id.'" class="form-control kt-select2 id_lembaga_assessment" name="id_lembaga_assessment">
                    <option></option>';
                foreach($lembaga_assessment as $data){
                  $selected = '';
                  if($row->id_lembaga_assessment == $data->id) {
                    $selected = ' selected ';
                  }
                  $button .= '<option value="'.$data->id.'"'.$selected.'>'.$data->nama.'</option>';
                }
                $button .= '</select>';

                return $button;
            })
            ->rawColumns(['action','nama_lengkap','jabatan', 'stalenta', 'nama_perusahaan', 'talent_jabatan', 'bumn_induk', 'status_pengisian'])
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

    public function approve(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Talenta::find((int)$request->input('id'));
            $param['id_lembaga_assessment'] = (int)$request->input('id_lembaga_assessment');
            $param['id_status_talenta'] = 7; //status eligible 2
            $status = $data->update($param);

            if($status){
              RegisterController::store_log($data->id, $param['id_status_talenta']);
            }

            DB::commit();

            $data['jumlah_nominated'] = VerifikasiKbumnController::query_talenta(5)->get()->count();
            $data['jumlah_eligible1'] = VerifikasiKbumnController::query_talenta(6)->get()->count();
            $data['jumlah_eligible2'] = VerifikasiKbumnController::query_talenta(7)->get()->count();
            $data['jumlah_qualified'] = VerifikasiKbumnController::query_talenta(8)->get()->count();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses pilih lembaga',
                'title' => 'Sukses',
                'data' => $data,
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal approve talenta',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }
    
}
