<?php
namespace App\Http\Controllers\Talenta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Keahlian;
use App\Talenta;
use App\TransactionTalentaKeahlian;
use App\Agama;
use App\DataKeluarga;
use App\DataKeluargaAnak;
use App\SocialMedia;
use App\TransactionTalentaSocialMedia;
use App\Toastr;
use App\AsalInstansiBaru;
use App\JenisAsalInstansiBaru;
use App\JenisJabatan;
use App\Perusahaan;
use App\LogStatusTalenta;
use App\LembagaAssessment;
use DB;
use App\Helpers\CVHelper;
use Carbon\Carbon;
use App\User;
use Illuminate\Validation\Rule;

class AssessmentAssessorController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->__route = 'talenta.assessment_assessor';
         $this->__title = "Assessment";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
      activity()->log('Menu Talenta Assessment');

      return view($this->__route.'.index',[
          'pagetitle' => $this->__title,
          'route' => $this->__route,
            'talenta' => Talenta::orderBy('id', 'asc')->get(),
            'lembaga_assessment' => LembagaAssessment::orderBy('id', 'asc')->get(),
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
                  'url' => route('talenta.assessment_assessor.index'),
                  'menu' => 'Assessment'
              ]
          ]
      ]);

    }


    public function datatable(Request $request)
    {
        $id_users = \Auth::user()->id;
        $id_users_bumn = \Auth::user()->id_bumn;
        $id_assessment = \Auth::user()->id_assessment;

        $users = User::where('id', $id_users)->first();

        $talenta = DB::table('talenta')
                      ->where('talenta.id_status_talenta', 7)
                      ->whereNotNull('talenta.id_lembaga_assessment')
                      ->leftJoin('status_talenta', 'status_talenta.id', '=', 'talenta.id_status_talenta')
                      ->leftJoin('view_organ_perusahaan', 'talenta.id', '=', 'view_organ_perusahaan.id_talenta')
                      ->leftJoin('struktur_organ', 'struktur_organ.id', '=', 'view_organ_perusahaan.id_struktur_organ')
                      ->leftJoin('perusahaan', 'perusahaan.id', '=', 'struktur_organ.id_perusahaan')
                      ->leftJoin(DB::raw("lateral (SELECT assessment_nilai.* FROM assessment_nilai where assessment_nilai.id_talenta = talenta.id order by assessment_nilai.id desc limit 1) assessment_nilai"), 'assessment_nilai.id_talenta', '=', 'talenta.id')
                      ->leftJoin('lembaga_assessment', 'lembaga_assessment.id', '=', 'talenta.id_lembaga_assessment')
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
                                    jabatan.nama_lengkap as nama_perusahaan, 
                                    jabatan.nomenklatur_jabatan as jabatan,
                                    status_talenta.nama as status_talenta,
                                    talenta.id_jenis_asal_instansi,
                                    lembaga_assessment.nama as lembaga_assessment,
                                    assessment_nilai.hasil,
                                    assessment_nilai.id_lembaga_assessment"))
                      ->GroupBy('talenta.id', 'jabatan.nomenklatur_jabatan', 'jabatan.nama_lengkap', 'status_talenta.nama','assessment_nilai.id_lembaga_assessment','assessment_nilai.hasil','lembaga_assessment.nama')
                      ->orderBy(DB::raw("case when talenta.id_status_talenta = 3 then 1 else 2 end"))
                      ->orderBy('talenta.nama_lengkap', 'ASC');

       if($users->kategori_user_id != 1){
            $talenta = $talenta->where("talenta.id_lembaga_assessment",$id_assessment);
        }

        if($request->nama_lengkap){
          $talenta->where("talenta.nama_lengkap",'ilike', '%'.$request->nama_lengkap.'%');
        }

        if($request->lembaga_assessment){
          $talenta->where("lembaga_assessment.nama",$request->lembaga_assessment);
        }

        if($request->nik){
          $talenta->where("talenta.nik",'ilike', '%'.$request->nik.'%');
        }

        if($request->jabatan){
          $talenta->where("jabatan.nomenklatur_jabatan",'ilike', '%'.$request->jabatan.'%');
        }

        if($request->instansi){
          $instansi = Perusahaan::find($request->instansi);
          $talenta->where("jabatan.nama_lengkap", $instansi->nama_lengkap);
        }

        if($request->asal_instansi){
          $talenta->where("talenta.id_jenis_asal_instansi", $request->asal_instansi);
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
                    $return = '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row->hasil.'</span>';
                }else if($row->hasil=='Not Qualified'){
                    $return = '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row->hasil.'</span>';
                }
                return $return;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $route = route('talenta.assessment_nilai.index', ['id_talenta' => $id]) ;
                $button = '<a href="'.$route.'" target="_blank" class="btn btn-primary btn-sm cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="View Hasil Assessment"><i class="flaticon-edit"></i> View</a>';
                return $button;
            })
            ->rawColumns(['action', 'nama_lengkap','jabatan', 'hasil'])
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
      try{
          return view($this->__route.'.create',[
                  'agama' => Agama::get(),
          ]);
      }catch(Exception $e){}
    }

    public function import()
    {
      try{
          return view($this->__route.'.import',[
                  'actionform' => 'update',
                  'agama' => Agama::get(),
                  'social_media' => SocialMedia::get(),
          ]);
      }catch(Exception $e){}
    }

    public function minicv(Request $request)
    {
      $id = (int)$request->input('id');
      $talenta = Talenta::find($id);
      $keahlian = Keahlian::get();
      $trans_keahlian = Talenta::find($id)->transactionTalentaKeahlian()->get();
      try{
          return view('talenta.minicv',[
                  'talenta' => $talenta,
                  'keahlian' => $keahlian,
                  'trans_keahlian' => $trans_keahlian
          ]);
      }catch(Exception $e){}
    }

    public function cv(Request $request){

      $id = (int)$request->input('id');
      $talenta = DB::table('talenta')
      ->leftjoin('view_organ_perusahaan', function($query){
          $query->on('view_organ_perusahaan.id_talenta', '=', 'talenta.id')
          ->where('view_organ_perusahaan.aktif', '=', 't');
      })
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
      ->where('talenta.id', $id)
      ->groupBy('talenta.id','perusahaan.nama_lengkap','jenis_asal_instansi.nama','instansi.nama','struktur_organ.nomenklatur_jabatan','jenjang_pendidikan.nama')
      ->first();
      try {
        return view('talenta.assessment_assessor.cv',[
                'actionform' => 'update',
                'id_talenta' => $id,
                'talenta' => $talenta
        ]);
      } catch (\Throwable $th) {
        //throw $th;
      }
    }
    public function log_status(Request $request)
    {
      $id = (int)$request->input('id');
      $talenta = Talenta::find($id);
      try{
          return view('talenta.log_status',[
                  'talenta' => $talenta
          ]);
      }catch(Exception $e){}
    }

    public function table_log(Request $request, $id)
    {
        $data = LogStatusTalenta::select('*')->where('id_talenta', $id)->orderBy("created_at", 'desc');

        try{
            return datatables()->of($data)
            ->editColumn('user', function ($row){
                return @$row->user->name;
            })
            ->editColumn('status_talenta', function ($row){
                return @$row->statusTalenta->nama;
            })
            ->editColumn('waktu', function ($row){
                return $row->created_at;
            })
            ->rawColumns(['waktu'])
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

    public function store_log($id_talenta, $id_status_talenta)
    {
      $param['id_status_talenta']   = $id_status_talenta;
      $param['id_talenta']          = $id_talenta;
      $param['id_user']             = \Auth::user()->id;
      $insert = LogStatusTalenta::create($param);

      $result = [
          'flag' => 'berhasil',
          'msg' => 'data sudah diimport',
          'title' => 'beta testing'
      ];
    }

    public function store_import(Request $request)
    {
        $file = $request->file('file_name');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('uploads/cv',$nama_file);
        Excel::import(new RowImport($nama_file), public_path('uploads/cv/'.$nama_file));
        
        //delete after unused
        //File::delete('uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR . $nama_file);

      $result = [
          'flag' => 'berhasil',
          'msg' => 'sukses tambah data',
          'title' => 'Sukses'
      ];


      return back();
    }
    
    public function update_talenta(Request $request)
    {
        DB::beginTransaction();
        try{
            if(!empty($request->input('checked_list'))){
              $checked_list = explode(",", $request->input('checked_list'));
              $param['id_status_talenta'] = 5; //status nominated
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
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses submit talenta',
                'title' => 'Sukses'
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

    public function approve(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Talenta::find((int)$request->input('id'));
            $param['id_status_talenta'] = 5; //status nominated
            $status = $data->update($param);

            if($status){
              RegisterController::store_log($data->id, 1);
            }

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses approve talenta',
                'title' => 'Sukses'
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
    
    public function reject(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Talenta::find((int)$request->input('id'));
            $param['id_status_talenta'] = 3; //status non talent
            $status = $data->update($param);

            if($status){
              RegisterController::store_log($data->id, 1);
            }

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses reject talenta',
                'title' => 'Sukses'
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal reject talenta',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

}
