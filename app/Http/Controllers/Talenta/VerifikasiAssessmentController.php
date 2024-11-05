<?php
namespace App\Http\Controllers\Talenta;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\KelasBumn;
use App\ClusterBumn;
use App\Keahlian;
use App\Talenta;
use App\LembagaAssessment;
use App\AssessmentNilai;
use App\AssessmentKompetensi;
use App\AssessmentKualifikasi;
use App\AssessmentKarakter;
use App\AssessmentKelas;
use App\AssessmentCluster;
use App\AssessmentKeahlian;
use App\AssessmentOrganisasi;
use App\RefKompetensi;
use App\KualifikasiPersonal;
use App\Karakters;
use App\KonteksOrganisasi;
use App\TransactionTalentaKeahlian;
use App\TransactionTalentaCluster;
use App\TransactionTalentaKelas;
use App\TransactionTalentaSocialMedia;
use App\Perusahaan;
use App\SocialMedia;
use DB;
use Carbon\Carbon;
use App\User;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\AssessmentReport;

class VerifikasiAssessmentController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->__route = 'talenta.verifikasi_assessment';
         $this->__title = "Verifikasi Assessment";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
      activity()->log('Menu Talenta Verifikasi Assessment');
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
                  'url' => '/',
                  'menu' => 'Talent Management'
              ],
              [
                  'url' => route('talenta.verifikasi_assessment.index'),
                  'menu' => 'Verifikasi Assessment'
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
                      ->where('talenta.id_status_talenta', 7) // status eligble 2
                      ->leftJoin('status_talenta', 'status_talenta.id', '=', 'talenta.id_status_talenta')
                      ->leftJoin('lembaga_assessment', 'lembaga_assessment.id', '=', 'talenta.id_lembaga_assessment')
                      ->leftJoin('view_organ_perusahaan', 'talenta.id', '=', 'view_organ_perusahaan.id_talenta')
                      ->leftJoin('struktur_organ', 'struktur_organ.id', '=', 'view_organ_perusahaan.id_struktur_organ')
                      ->leftJoin('perusahaan', 'perusahaan.id', '=', 'struktur_organ.id_perusahaan')
                      ->leftJoin(DB::raw("lateral (SELECT id, id_talenta, hasil, full_report, short_report, tanggal_expired FROM assessment_nilai where assessment_nilai.id_talenta = talenta.id order by assessment_nilai.tanggal_expired desc limit 1) assessment_nilai"), 'assessment_nilai.id_talenta', '=', 'talenta.id')
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
                                    talenta.id_status_talenta,
                                    talenta.id_lembaga_assessment, 
                                    jabatan.nama_lengkap as nama_perusahaan, 
                                    jabatan.nomenklatur_jabatan as jabatan,
                                    status_talenta.nama as status_talenta,
                                    lembaga_assessment.nama as lembaga_assessment,
                                    assessment_nilai.id as id_assessment_nilai,
                                    assessment_nilai.hasil,
                                    assessment_nilai.short_report,
                                    assessment_nilai.full_report,
                                    talenta.id_jenis_asal_instansi"))
                      ->GroupBy('talenta.id', 'jabatan.nomenklatur_jabatan', 'jabatan.nama_lengkap', 'status_talenta.nama', 'lembaga_assessment.nama','assessment_nilai.id', 'assessment_nilai.hasil', 'assessment_nilai.short_report', 'assessment_nilai.full_report')
                      ->orderBy(DB::raw("case when talenta.id_status_talenta = 4 then 1 else 2 end"))
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
                }else{
                  return "Tidak Sedang Menjabat";
                }
            })
            ->editColumn('hasil', function ($row){
                $return = '';
                if($row->hasil=='Qualified'){
                    $return = '<a href="javascript:;" class="cls-button-show" data-id="'.$row->id_assessment_nilai.'"><span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row->hasil.'</span></a>';
                  }else if($row->hasil=='Not Qualified'){
                  $return = '<a href="javascript:;" class="cls-button-show" data-id="'.$row->id_assessment_nilai.'"><span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row->hasil.'</span></a>';
                }
                
                return $return;
            })
            ->editColumn('lembaga_assessment', function ($row){
                return $row->lembaga_assessment;
            })
            ->editColumn('log_status', function ($row){
                $color = '';
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
                $return = '<a href="javascript:;" style="color:#4f4f4f;background-color:'.$color.';" class="cls-logstatus kt-badge kt-badge--inline kt-badge--pill kt-badge--rounded" data-id="'.(int)$row->id.'" data-toggle="tooltip" data-original-title="Log Status">'.$row->status_talenta.'</a>';
                return $return;
            })
            ->addColumn('approve', function ($row){
                $id = (int)$row->id;
                
                $button = '<label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" data-id="'.(int)$row->id.'" data-nama_lengkap="'.$row->nama_lengkap.'" value="'.$id.'" form="form_group" class="checked_item"/>
                <span></span>
                </label>';
                
                if(empty($row->hasil)){
                  $button = '<label class="mt-checkbox mt-checkbox-outline">
                  <input type="checkbox" value="'.$id.'" form="form_group" disabled/>
                  <span></span>
                  </label>';
                }

                return $button;
            })
            ->addColumn('reject', function ($row){
                $id = (int)$row->id;
                
                $button = '<label class="mt-checkbox mt-checkbox-outline">
                <input class="reject_item" type="checkbox" data-id="'.(int)$row->id.'" data-nama_lengkap="'.$row->nama_lengkap.'" value="'.$id.'" form="form_group"/>
                <span></span>
                </label>';
                
                if(empty($row->hasil)){
                  $button = '<label class="mt-checkbox mt-checkbox-outline">
                  <input type="checkbox" value="'.$id.'" form="form_group" disabled/>
                  <span></span>
                  </label>';
                }

                return $button;
            })
            ->addColumn('cancel', function ($row){
                $id = (int)$row->id_assessment_nilai;
                if(empty($row->id_assessment_nilai)){
                  return '<a href="javascript:;" class="cancelAssignment" data-talenta_id="'.$row->id.'" data-id="'.$row->id_assessment_nilai.'"><span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">Cancel</span></a>';
                }
                
            })
            ->addColumn('assesment', function ($row){
                $id = (int)$row->id;
                if(empty($row->id_assessment_nilai)){
                  $disabled = '';
                } else{
                  $disabled = 'disabled';
                }
                $id = (int)$row->id;
                $lembaga_assessment = LembagaAssessment::get();
                
                $button = '<select data-id_talenta="'.$id.'" data-assessment_id="'.$row->id_assessment_nilai.'" class="form-control kt-select2 id_lembaga_assessment" name="id_lembaga_assessment">
                    <option></option>';
                foreach($lembaga_assessment as $data){
                  $selected = '';
                  if($row->id_lembaga_assessment == $data->id) {
                    $selected = ' selected ';
                  }
                  $button .= '<option value="'.$data->id.'"'.$selected.' '.$disabled.'>'.$data->nama.'</option>';
                }
                $button .= '</select>';

                return $button;
                
            })
            ->rawColumns(['approve','reject','hasil','nama_lengkap','jabatan', 'log_status','cancel','assesment'])
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
    
    public function update_talenta(Request $request)
    {
        DB::beginTransaction();
        try{
            if(!empty($request->input('checked_list'))){
              $checked_list = explode(",", $request->input('checked_list'));
              $param['id_status_talenta'] = 8; //status qualified
              $status = Talenta::whereIn('id', $checked_list)->update($param);

              if($status){
                foreach ($checked_list as $key => $value) {
                  RegisterController::store_log($value, $param['id_status_talenta']);
                }
              }
            }
            
            if(!empty($request->input('reject_list'))){
              $reject_list = explode(",", $request->input('reject_list'));
              $param['id_status_talenta'] = 3; //status non talent
              $status = Talenta::whereIn('id', $reject_list)->update($param);

              if($status){
                foreach ($reject_list as $key => $value) {
                  RegisterController::store_log($value, $param['id_status_talenta']);
                }
              }
            }
            DB::commit();

            $data['jumlah_nominated'] = VerifikasiKbumnController::query_talenta(5)->get()->count();
            $data['jumlah_eligible1'] = VerifikasiKbumnController::query_talenta(6)->get()->count();
            $data['jumlah_eligible2'] = VerifikasiKbumnController::query_talenta(7)->get()->count();
            $data['jumlah_qualified'] = VerifikasiKbumnController::query_talenta(8)->get()->count();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses submit talenta',
                'title' => 'Sukses',
                'data' => $data,
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal submit talenta',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function show(Request $request){
      {
        try{
              $assesment = AssessmentNilai::find((int)$request->input('id'));

              $talenta = DB::table('view_organ_perusahaan')
              ->leftJoin('talenta', 'talenta.id', '=', 'view_organ_perusahaan.id_talenta')
              ->leftjoin('riwayat_jabatan_dirkomwas', function($query){
                  $query->on('riwayat_jabatan_dirkomwas.id_talenta', '=', 'talenta.id')
                  ->whereNull('riwayat_jabatan_dirkomwas.tanggal_akhir');
              })
              ->leftJoin('riwayat_pendidikan', 'riwayat_pendidikan.id_talenta', '=', 'talenta.id')
              ->leftJoin('jenjang_pendidikan', 'jenjang_pendidikan.id', '=', 'riwayat_pendidikan.id_jenjang_pendidikan')
              ->leftJoin('struktur_organ', 'struktur_organ.id', '=', 'view_organ_perusahaan.id_struktur_organ')
              ->leftJoin('perusahaan', 'perusahaan.id', '=', 'struktur_organ.id_perusahaan')
              ->leftJoin('jenis_asal_instansi', 'jenis_asal_instansi.id', '=', 'talenta.id_jenis_asal_instansi')
              ->leftJoin('instansi','instansi.id','=','talenta.id_asal_instansi')
              ->select(DB::raw("talenta.id, talenta.nama_lengkap, talenta.foto, talenta.jenis_kelamin,
                              talenta.tempat_lahir, talenta.tanggal_lahir,
                              perusahaan.nama_lengkap as nama_perusahaan,
                              jenis_asal_instansi.nama as jenis_asal_instansi,
                              instansi.nama as instansi,
                              max(jenjang_pendidikan.urutan) as urutan,
                              jenjang_pendidikan.nama as pendidikan,
                              struktur_organ.nomenklatur_jabatan as jabatan"))
              ->where('view_organ_perusahaan.aktif', '=', 't')
              ->where('talenta.id', $assesment->id_talenta)
              ->groupBy('talenta.id','perusahaan.nama_lengkap','jenis_asal_instansi.nama','instansi.nama','struktur_organ.nomenklatur_jabatan','jenjang_pendidikan.nama')
              ->first();
  
              $kelas = KelasBumn::get();
              $cluster = ClusterBumn::get();
              $keahlian = Keahlian::get();
              $konteks = KonteksOrganisasi::get();
  
              if($request->input('id')){
                  $trans_kelas = AssessmentNilai::find((int)$request->input('id'))->assessmentKelas()->get();
                  $trans_cluster = AssessmentNilai::find((int)$request->input('id'))->assessmentCluster()->get();
                  $trans_keahlian = AssessmentNilai::find((int)$request->input('id'))->assessmentKeahlian()->get();
                  $trans_konteks = AssessmentNilai::find((int)$request->input('id'))->assessmentOrganisasi()->get();
                  $trans_kompetensi = AssessmentKompetensi::Where('id_assessment_nilai', (int)$request->input('id'))->pluck('rating', 'id_kompetensi');
                  $trans_kualifikasi = AssessmentKualifikasi::Where('id_assessment_nilai', (int)$request->input('id'))->pluck('rating', 'id_kualifikasi_personal');
                  $trans_karakter = AssessmentKarakter::Where('id_assessment_nilai', (int)$request->input('id'))->pluck('rating', 'id_karakter');
              }
  
              return view($this->__route.'.show',[
                      'actionform' => 'update',
                      'id_talenta' => $assesment->id_talenta,
                      'id' => $request->input('id'),
                      'data' => AssessmentNilai::find($request->input('id')),
                      'assessment_nilai' => AssessmentNilai::find($request->input('id')),
                      'talenta' => $talenta,
                      'kompetensi' => RefKompetensi::get(),
                      'karakter' => Karakters::get(),
                      'kelas' => $kelas,
                      'cluster' => $cluster,
                      'keahlian' => $keahlian,
                      'konteks' => $konteks,
                      'trans_kompetensi' => $trans_kompetensi,
                      'trans_kualifikasi' => $trans_kualifikasi,
                      'trans_karakter' => $trans_karakter,
                      'trans_kelas' => $trans_kelas,
                      'trans_cluster' => $trans_cluster,
                      'trans_keahlian' => $trans_keahlian,
                      'trans_konteks' => $trans_konteks,
                      'kualifikasi' => KualifikasiPersonal::get()
              ]);
        }catch(Exception $e){}
      }
    }

    public function cancel(Request $request){

      try {
        $id = (int)$request->input('id');
        //code...
        DB::beginTransaction();

        $data = Talenta::find((int)$request->input('talenta_id'));
        $param['id_status_talenta'] = 6; //status eligible 2
        $param['id_lembaga_assessment'] = null; 
        $status = $data->update($param);

        if($status){
          RegisterController::store_log($data->id, $param['id_status_talenta']);
        }

        $assesment = AssessmentNilai::find($id);
        if($assesment){
          AssessmentKompetensi::where("id_assessment_nilai", $id)->delete();
          AssessmentKualifikasi::where("id_assessment_nilai", $id)->delete();
          AssessmentKarakter::where("id_assessment_nilai", $id)->delete();
          AssessmentKelas::where("id_assessment_nilai", $id)->delete();
          AssessmentCluster::where("id_assessment_nilai", $id)->delete();
          AssessmentKeahlian::where("id_assessment_nilai", $id)->delete();
          AssessmentOrganisasi::where("id_assessment_nilai", $id)->delete();
          $data = AssessmentNilai::find($id);
          $status = $data->delete();
        }

        DB::commit();
        $data = [];
        $data['jumlah_nominated'] = VerifikasiKbumnController::query_talenta(5)->get()->count();
        $data['jumlah_eligible1'] = VerifikasiKbumnController::query_talenta(6)->get()->count();
        $data['jumlah_eligible2'] = VerifikasiKbumnController::query_talenta(7)->get()->count();
        $data['jumlah_qualified'] = VerifikasiKbumnController::query_talenta(8)->get()->count();
          $result = [
              'flag'  => 'success',
              'msg' => 'Sukses batalkan assessment',
              'title' => 'Sukses',
              'data' => $data,
          ];
      } catch (\Throwable $th) {
        //throw $th;
        $result = [
          'flag'  => 'warning',
          'msg' => $e->getMessage(),
          'title' => 'Gagal'
        ];
      }
      return response()->json($result);

    }

    public function changeLembaga($id){

    }

    public function updateLembagaAssignment(Request $request){
      // dd($request);
        try {
          DB::beginTransaction();
          $assessment = AssessmentNilai::find((int)$request->input('assessment_id'));
          if($assessment){
            // $assessment->update(['id_lembaga_assessment' => (int)$request->input('id_lembaga_assessment')]);
          }
          $data = Talenta::find((int)$request->input('id'));
          $param['id_lembaga_assessment'] = (int)$request->input('id_lembaga_assessment'); 
          $status = $data->update($param);
          $data = [];
          $data['jumlah_nominated'] = VerifikasiKbumnController::query_talenta(5)->get()->count();
          $data['jumlah_eligible1'] = VerifikasiKbumnController::query_talenta(6)->get()->count();
          $data['jumlah_eligible2'] = VerifikasiKbumnController::query_talenta(7)->get()->count();
          $data['jumlah_qualified'] = VerifikasiKbumnController::query_talenta(8)->get()->count();
          DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses ganti lembaga assessment',
                'title' => 'Sukses',
                'data' => $data,
            ];
        } catch (\Throwable $th) {
          //throw $th;
          $result = [
            'flag'  => 'warning',
            'msg' => $e->getMessage(),
            'title' => 'Gagal'
          ];
        }

        return response()->json($result);

    }

    public function export(Request $request)
    {
      return Excel::download(new AssessmentReport, 'assessment.xlsx');
    }


}
