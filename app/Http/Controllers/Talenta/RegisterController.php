<?php
namespace App\Http\Controllers\Talenta;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\KelasBumn;
use App\ClusterBumn;
use App\Keahlian;
use App\Talenta;
use App\TransactionTalentaKeahlian;
use App\TransactionTalentaCluster;
use App\TransactionTalentaKelas;
use App\StatusTalenta;
use App\Agama;
use App\DataKeluarga;
use App\DataKeluargaAnak;
use App\SocialMedia;
use App\TransactionTalentaSocialMedia;
use App\AsalInstansiBaru;
use App\JenisAsalInstansiBaru;
use App\JenisJabatan;
use App\Perusahaan;
use App\LogStatusTalenta;
use DB;
use App\Helpers\CVHelper;
use Carbon\Carbon;
use App\User;
use Illuminate\Validation\Rule;
use App\Imports\RowImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Exports\ExcelSheet;
use App\Exports\RekapCV;

class RegisterController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->__route = 'talenta.register';
         $this->__title = "Register";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
      activity()->log('Menu Talenta Register');
      $jumlah = RegisterController::query_talenta(2)->get()->count();

      return view($this->__route.'.index',[
          'pagetitle' => $this->__title,
          'route' => $this->__route,
            'talenta' => Talenta::orderBy('nama_lengkap', 'asc')->get(),
            'asal_instansi' => JenisAsalInstansiBaru::orderBy('id', 'asc')->get(),
            'instansi' => Perusahaan::orderBy('id', 'asc')->where('is_active',true)->get(),
            'status_talenta' => StatusTalenta::orderBy('id', 'asc')->get(),
            'jumlah' => $jumlah,
            'breadcrumb' => [
              [
                  'url' => '/',
                  'menu' => 'Homes'
              ],
              [
                  'url' => route('talenta.register.index'),
                  'menu' => 'Talent Management'
              ],
              [
                  'url' => route('talenta.register.index'),
                  'menu' => 'Register'
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
                      ->whereIn('talenta.id_status_talenta',array(1,3)) //status Added dam non talent
                      ->leftJoin('status_talenta', 'status_talenta.id', '=', 'talenta.id_status_talenta')
                      ->leftJoin('view_organ_perusahaan', 'talenta.id', '=', 'view_organ_perusahaan.id_talenta')
                      ->leftJoin('struktur_organ', 'struktur_organ.id', '=', 'view_organ_perusahaan.id_struktur_organ')
                      ->leftJoin('perusahaan', 'perusahaan.id', '=', 'struktur_organ.id_perusahaan')
                      ->leftJoin('perusahaan as p', 'p.id', '=', 'talenta.id_perusahaan')
                      ->leftJoin(DB::raw("lateral (select s.nomenklatur_jabatan, p.nama_lengkap, p.id, p.induk, sk.id_grup_jabatan
                                      from view_organ_perusahaan v
                                      left join struktur_organ s on v.id_struktur_organ = s.id
                                      LEFT JOIN organ_perusahaan op ON op.id_struktur_organ = s.id
                                      LEFT JOIN surat_keputusan sk ON sk.id = op.id_surat_keputusan
                                      left join perusahaan p on p.id = s.id_perusahaan
                                      where v.id_talenta = talenta.id 
                                      and v.aktif = 't'
                                      and sk.id_grup_jabatan = 1
                                      and (v.tanggal_akhir >= now( ) 
                                              or v.tanggal_akhir is null)
                                      order by v.id_struktur_organ ASC, s.urut ASC 
                                      limit 1) jabatan"), 'talenta.id', '=', 'talenta.id')
                      ->select(DB::raw("talenta.id,
                                    talenta.nama_lengkap, 
                                    talenta.nik, 
                                    talenta.persentase, 
                                    talenta.id_status_talenta, 
                                    talenta.id_perusahaan,
                                    talenta.file_ktp, 
                                    jabatan.nama_lengkap as nama_perusahaan, 
                                    jabatan.nomenklatur_jabatan as jabatan,
                                    status_talenta.nama as stalenta,
                                    talenta.id_jenis_asal_instansi,
                                    jabatan.id as id_bumn"))
                        ->where('p.is_active', true)
                        ->whereRaw('((jabatan.id_grup_jabatan = 1 )
                        OR talenta.is_talenta = true )')
                      ->GroupBy('talenta.id', 'jabatan.nomenklatur_jabatan', 'jabatan.nama_lengkap', 'status_talenta.nama','jabatan.id', 'jabatan.induk')
                      ->orderBy('talenta.nama_lengkap', 'ASC');

        /*$talenta = DB::table('talenta')
                      ->whereRaw('(surat_keputusan.id_grup_jabatan = 1 OR talenta.is_talenta = true)')
                      ->whereIn('talenta.id_status_talenta',array(1,3)) //status Added dam non talent
                      ->leftJoin('status_talenta', 'status_talenta.id', '=', 'talenta.id_status_talenta')
                      ->leftJoin('view_organ_perusahaan', 'talenta.id', '=', 'view_organ_perusahaan.id_talenta')
                      ->leftJoin('struktur_organ', 'struktur_organ.id', '=', 'view_organ_perusahaan.id_struktur_organ')
                      ->leftJoin('perusahaan', 'perusahaan.id', '=', 'struktur_organ.id_perusahaan')
                      ->leftJoin('organ_perusahaan','organ_perusahaan.id_struktur_organ','=','struktur_organ.id')
                      ->leftJoin('surat_keputusan','surat_keputusan.id','=','organ_perusahaan.id_surat_keputusan')
                      ->leftJoin(DB::raw("lateral (select s.nomenklatur_jabatan, p.nama_lengkap, p.id
                                      from view_organ_perusahaan v
                                      left join struktur_organ s on v.id_struktur_organ = s.id
                                      left join perusahaan p on p.id = s.id_perusahaan
                                      where v.id_talenta = talenta.id 
                                      and v.aktif = 't'
                                      order by v.id_struktur_organ ASC, s.urut ASC 
                                      limit 1) jabatan"), 'talenta.id', '=', 'talenta.id')
                      ->select(DB::raw("talenta.id,
                                    talenta.nama_lengkap, 
                                    talenta.nik, 
                                    talenta.persentase, 
                                    talenta.id_status_talenta, 
                                    talenta.id_perusahaan, 
                                    jabatan.nama_lengkap as nama_perusahaan, 
                                    jabatan.nomenklatur_jabatan as jabatan,
                                    status_talenta.nama as stalenta,
                                    talenta.id_jenis_asal_instansi,
                                    jabatan.id as id_bumn"))
                      ->GroupBy('talenta.id', 'jabatan.nomenklatur_jabatan', 'jabatan.nama_lengkap', 'status_talenta.nama','jabatan.id')
                      ->orderBy('talenta.nama_lengkap', 'ASC');*/
                      // ->orderBy(DB::raw("case when (talenta.id_status_talenta = 1 or talenta.id_status_talenta = 2)
                      //                       and talenta.persentase=100 then 1
                      //                       when (talenta.id_status_talenta = 1 or talenta.id_status_talenta = 2) then 2
                      //                       else 3 end"))

       if($users->kategori_user_id != 1){
        $talenta = $talenta->whereRaw("(( (p.id in (
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
          )
        ) OR p.id = ".$id_users_bumn.")
        OR
        talenta.id_perusahaan = ".$id_users_bumn.") ");
        }

        if($request->nama_lengkap && $request->nama_lengkap!=''){
          $talenta->where("talenta.nama_lengkap",'ilike', '%'.$request->nama_lengkap.'%');
        }

        if($request->nik){
          $talenta->where("talenta.nik",'ilike', '%'.$request->nik.'%');
        }

        if($request->jabatan){
          $jenis_jabatan = JenisJabatan::find($request->jabatan);
          $talenta->where("jabatan.nomenklatur_jabatan", $jenis_jabatan->nama);
        }

        if($request->instansi){
          $instansi = Perusahaan::find($request->instansi);
          $talenta->where("jabatan.nama_lengkap", $instansi->nama_lengkap);
        }

        if($request->id_status_talenta){
          $talenta->where("talenta.id_status_talenta", $request->id_status_talenta);
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
                    $indukperusah .= "<b>".$getPerusahaan->nama_lengkap."</span>";
                  }
                  
                } else {
                  $Induk = Perusahaan::where('id', $row->id_bumn)->first();
                  if($Induk->induk == 0){
                    $indukperusah .= "<b>".$Induk->nama_lengkap."</span>";
                  } else {
                    $anak = Perusahaan::where('id', $Induk->induk)->first();
                    $indukperusah .= "<b>".$anak->nama_lengkap."</span>";
                  }
                }
                
                $edit = '';

                $html .= '<a href="#" style="color:#4f4f4f;background-color:'.$color.';" class="cls-logstatus kt-badge kt-badge--inline kt-badge--pill kt-badge--rounded" data-id="'.(int)$row->id.'" data-toggle="tooltip" data-original-title="Log Status"><b>'.$row->stalenta.'</b></a></b>'.$edit.'</br><span>'.$indukperusah.'</span>';
                return $html;
            })
            ->addColumn('action', function ($row) use ($users) {
                $id = (int)$row->id;
                
                $button = '<label class="mt-checkbox mt-checkbox-outline checked_item">
                <input type="checkbox" data-id="'.(int)$row->id.'" data-nama_lengkap="'.$row->nama_lengkap.'" value="'.$id.'" form="form_group" class="checked_item" onchange="talenta_selected(this)"/>
                <span></span>
                </label>';
                
                if($row->persentase<100 && $users->kategori_user_id != 1){
                  $button = '<label class="mt-checkbox mt-checkbox-outline checked_item">
                  <input type="checkbox" value="'.$id.'" form="form_group" disabled/>
                  <span></span>
                  </label>';
                }

                if($row->id_status_talenta==2){ //status registered
                  $button = '<label class="mt-checkbox mt-checkbox-outline checked_item">
                  <input type="checkbox" checked="checked" value="'.$id.'" form="form_group" disabled/>
                  <span></span>
                  </label>';
                }

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
                  'perusahaan' => Perusahaan::where('is_active', true)->get(),
                  'id_perusahaan' => \Auth::user()->id_bumn
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
      $social_media = SocialMedia::get();
      $trans_social_media = TransactionTalentaSocialMedia::where("id_talenta", $id)->get();
      $kelas = KelasBumn::get();
      $trans_kelas = Talenta::find($id)->transactionTalentaKelas()->get();
      $cluster = ClusterBumn::get();
      $trans_cluster = Talenta::find($id)->transactionTalentaCluster()->get();
      $keahlian = Keahlian::get();
      $trans_keahlian = Talenta::find($id)->transactionTalentaKeahlian()->get();
      $id_users = \Auth::user()->id;
      $users = User::where('id', $id_users)->first();
      try{
          return view('talenta.minicv',[
                  'talenta' => $talenta,
                  'users' => $users,
                  'social_media' => $social_media,
                  'trans_social_media' => $trans_social_media,
                  'kelas' => $kelas,
                  'trans_kelas' => $trans_kelas,
                  'cluster' => $cluster,
                  'trans_cluster' => $trans_cluster,
                  'keahlian' => $keahlian,
                  'trans_keahlian' => $trans_keahlian
          ]);
      }catch(Exception $e){}
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

    public static function store_log($id_talenta, $id_status_talenta)
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

    public function store(Request $request)
    {
      $id_users = \Auth::user()->id;
      $id_users_bumn = \Auth::user()->id_bumn;
      $users = User::where('id', $id_users)->first();

      $result = [
          'flag' => 'error',
          'msg' => 'Error Systemx',
          'title' => 'Error'
      ];

      $validator = $this->validateform($request);

      if (!$validator->fails()) {
          $param = $request->except(['_token', '_method']);
          $param['tanggal_lahir'] = Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d');
          $param['id_status_talenta'] = 1; //status added
          $param['talenta_register'] = 1; //register added
          $param['is_talenta'] = 1; 
          $param['added_by_role'] = $users->kategori_user_id;
          if($users->kategori_user_id != 1){
            $param['id_perusahaan'] = $id_users_bumn;
          } 
          DB::beginTransaction();
          try{
            $insert = Talenta::create($param);
            
            RegisterController::store_log($insert->id, $param['id_status_talenta']);

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
        $required['nama_lengkap'] = 'required';
        $required['tanggal_lahir'] = 'required';
        $required['nik'] = 'required|regex:/(^[A-Za-z0-9]+$)+/u|unique:talenta';
        $required['npwp'] = 'required|numeric';
        $required['id_perusahaan'] = 'required';
        

        $message['nama_lengkap.required'] = 'Nama Lengkap wajib Diisi';
        $message['tanggal_lahir.required'] = 'Tanggal Lahir wajib Diisi';
        $message['nik.required'] = 'NIK wajib Diisi';
        $message['nik.unique'] = 'NIK Sudah Digunakan, Silakan Periksa Kembali!!';
        $message['nik.regex'] = 'NIK Tanpa Menggunakan tanda Baca (hanya Angka)!!';
        $message['npwp.required'] = 'NPWP Wajib dimasukan';
        $message['npwp.numeric'] = 'NPWP Tanpa Menggunakan tanda Baca (hanya Angka)!!';
        $message['id_perusahaan.required'] = 'BUMN wajib dipilih';

        return Validator::make($request->all(), $required, $message);
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Talenta::find((int)$request->input('id'));
            $data->delete();

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

    public function selected(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Talenta::find((int)$request->input('id'));
            $param['id_status_talenta'] = 2; //status registered
            $status = $data->update($param);

            if($status){
              RegisterController::store_log($data->id, 2);
            }

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses pilih talenta',
                'title' => 'Sukses',
                'jumlah' => RegisterController::query_talenta(2)->get()->count()
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal pilih talenta',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function update_talenta(Request $request)
    {
        DB::beginTransaction();
        try{
          
            $checked_list = explode(",", $request->input('checked_list'));
            $params['id_status_talenta'] = 4; //status selected
            $status = Talenta::WhereIn('id', $checked_list)->update($params);

            if($status) {
                foreach ($checked_list as $key => $value) {
                  RegisterController::store_log($value, $params['id_status_talenta']);
                }
            }

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses pilih talenta',
                'title' => 'Sukses',
                'jumlah' => RegisterController::query_talenta(2)->get()->count()
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal pilih talenta',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }
    
    public function cancel_selected(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Talenta::find((int)$request->input('id'));
            $param['id_status_talenta'] = 1; //status added
            $status = $data->update($param);

            if($status){
              RegisterController::store_log($data->id, 1);
            }

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses batalkan talenta',
                'title' => 'Sukses',
                'jumlah' => RegisterController::query_talenta(2)->get()->count()
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal batalkan talenta',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }
    
    public static function query_talenta($id_status_talenta){
      $id_users = \Auth::user()->id;
      $id_users_bumn = \Auth::user()->id_bumn;
      $users = User::where('id', $id_users)->first();

      $talenta = DB::table('talenta')
                  ->where('talenta.id_status_talenta', $id_status_talenta) 
                  ->leftJoin('status_talenta', 'status_talenta.id', '=', 'talenta.id_status_talenta')
                  ->leftJoin('view_organ_perusahaan', 'talenta.id', '=', 'view_organ_perusahaan.id_talenta')
                  ->leftJoin('struktur_organ', 'struktur_organ.id', '=', 'view_organ_perusahaan.id_struktur_organ')
                  ->leftJoin('perusahaan', 'perusahaan.id', '=', 'struktur_organ.id_perusahaan')
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
                                talenta.id_jenis_asal_instansi"))
                  ->GroupBy('talenta.id', 'jabatan.nomenklatur_jabatan', 'jabatan.nama_lengkap', 'status_talenta.nama')
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
    return $talenta;
  }
}
