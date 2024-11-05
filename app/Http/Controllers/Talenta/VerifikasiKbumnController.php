<?php
namespace App\Http\Controllers\Talenta;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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

class VerifikasiKbumnController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->__route = 'talenta.verifikasi_kbumn';
         $this->__title = "Verifikasi KBUMN";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
      activity()->log('Menu Talenta Verifikasi KBUMN');
      $data['jumlah_nominated'] = VerifikasiKbumnController::query_talenta(5)->get()->count();
      $data['jumlah_eligible1'] = VerifikasiKbumnController::query_talenta(6)->get()->count();
      $data['jumlah_eligible2'] = VerifikasiKbumnController::query_talenta(7)->get()->count();
      $data['jumlah_qualified'] = VerifikasiKbumnController::query_talenta(8)->get()->count();


      // $data['jumlah_nominated'] = count(VerifikasiKbumnController::query_talenta(5));
      // $data['jumlah_eligible1'] = count(VerifikasiKbumnController::query_talenta(6));
      // $data['jumlah_eligible2'] = count(VerifikasiKbumnController::query_talenta(7));
      // $data['jumlah_qualified'] = count(VerifikasiKbumnController::query_talenta(8));

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
                  'url' => route('talenta.verifikasi_kbumn.index'),
                  'menu' => 'Verifikasi KBUMN'
              ]
          ]
      ]);

    }

    public static function query_talenta($id_status_talenta){
      $id_users = \Auth::user()->id;
      $id_users_bumn = \Auth::user()->id_bumn;
      $users = User::where('id', $id_users)->first();

      $talenta = DB::table('talenta')
      ->where('talenta.id_status_talenta', $id_status_talenta) // status nominated
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
                    p.id as perusahaan_id,
                    p.nama_lengkap as p_nama_perusahaan,
                    jabatan.id as id_bumn,
                    CASE                          
                      WHEN jabatan.induk is null THEN
                    talenta.id_perusahaan ELSE jabatan.induk 
                    END AS induk_bumn"))
        ->where('p.is_active', true)
        ->whereRaw('((jabatan.id_grup_jabatan = 1 )
        OR talenta.is_talenta = true )')
      ->GroupBy('talenta.id', 'jabatan.nomenklatur_jabatan', 'jabatan.nama_lengkap', 'status_talenta.nama','jabatan.id', 'jabatan.induk','p.id')
      ->orderBy(DB::raw("case when talenta.id_status_talenta = ".$id_status_talenta." then 1 else 2 end"))
      ->orderBy('talenta.nama_lengkap', 'ASC');


//       $query = "
// SELECT distinct B.talenta_id as id,
//       B.nama_lengkap,
//       B.nik,
//       B.persentase,
//       B.id_status_talenta, 
//       B.bumn as nama_perusahaan, 
//       B.id_perusahaan,
//       B.file_ktp,
//       B.jabatan,
//       status_talenta.nama AS stalenta,
//       B.id_jenis_asal_instansi,
//       B.id_bumn,
//       B.induk_bumn
// FROM 
// (
//   SELECT  
//           A.nama_lengkap,
//           A.talenta_id,
//           CASE 
//               WHEN bumn_induk IS NULL THEN bumn
//               ELSE bumn_induk
//           END AS bumn,
//           A.id_kategori_jabatan_talent,
//           A.id_status_talenta,
//           A.jabatan,
//           A.nik,
//           A.id_perusahaan,
//           A.file_ktp,
//           A.persentase,
//           A.id_jenis_asal_instansi,
//           A.induk_bumn,
//           A.id_bumn
//       FROM 
//       (
//           SELECT 
//                   bumn_induk.nama_lengkap as bumn_induk,
//                   CASE 
//                   WHEN bumn_0.level = 0 THEN bumn_0.nama_lengkap 
//                   WHEN bumn_1.level = 0 THEN bumn_1.nama_lengkap 
//                   WHEN bumn_2.level = 0 THEN bumn_2.nama_lengkap 
//                   WHEN bumn_3.level = 0 THEN bumn_3.nama_lengkap 
//                   WHEN bumn_4.level = 0 THEN bumn_4.nama_lengkap 
//                   END AS bumn,
//                   talenta.id_kategori_jabatan_talent,
//                   talenta.nik,
//                   talenta.persentase,
//                   talenta.file_ktp,
//                   talenta.id AS talenta_id, 	
//                   talenta.nama_lengkap, 
//                   talenta.id_status_talenta,
//                   talenta.id_jenis_asal_instansi,
//                   -- jabatan.nomenklatur_jabatan as jabatan,
//                   jabatan.id as id_bumn,
//                   jabatan.nama_lengkap as nama_perusahaan,
//                   talenta.id_perusahaan,
//                   CASE                          
//                       WHEN jabatan.induk is null THEN
//                     talenta.id_perusahaan ELSE jabatan.induk 
//                     END AS induk_bumn,
//                   case when jabatan.nomenklatur_baru is NULL then 
//                   jabatan.nomenklatur_jabatan else jabatan.nomenklatur_baru END as jabatan
//           FROM talenta
//           LEFT JOIN perusahaan AS bumn_induk ON bumn_induk.ID = talenta.id_perusahaan
//           LEFT JOIN lateral (select s.nomenklatur_jabatan, p.nama_lengkap, p.id, skp.nomenklatur_baru, s.id_perusahaan, sk.id_grup_jabatan, p.induk
//                           from view_organ_perusahaan v
//                           left join struktur_organ s on s.id = v.id_struktur_organ 
//                           LEFT JOIN organ_perusahaan op ON op.id_struktur_organ = s.id
//                           LEFT JOIN surat_keputusan sk ON sk.id = op.id_surat_keputusan
//                           left join perusahaan p on p.id = s.id_perusahaan
//                           LEFT JOIN sk_perubahan_nomenklatur skp ON skp.id_struktur_organ = s.ID
//                           where v.id_talenta = talenta.id 
//                           and v.aktif = 't'
//                           and sk.id_grup_jabatan = 1
//                           and (v.tanggal_akhir >= now( ) 
//                                   or v.tanggal_akhir is null)
//                           order by v.id_struktur_organ ASC, s.urut ASC 
//                           limit 1) jabatan ON TRUE
//           LEFT JOIN perusahaan AS bumn_0 ON bumn_0.ID = jabatan.id_perusahaan
//           LEFT JOIN perusahaan AS bumn_1 ON bumn_1.ID = bumn_0.induk
//           LEFT JOIN perusahaan AS bumn_2 ON bumn_2.ID = bumn_1.induk
//           LEFT JOIN perusahaan AS bumn_3 ON bumn_3.ID = bumn_2.induk
//           LEFT JOIN perusahaan AS bumn_4 ON bumn_4.ID = bumn_3.induk
//           WHERE 
//               bumn_induk.is_active is true AND
//               ((jabatan.id_grup_jabatan = 1 )
//               OR talenta.is_talenta = true )
//           GROUP BY talenta.id,
//                   bumn_0.nama_lengkap,
//                   bumn_1.nama_lengkap,
//                   bumn_2.nama_lengkap,
//                   bumn_3.nama_lengkap,
//                   bumn_4.nama_lengkap,
//                   bumn_0.level,
//                   bumn_1.level,
//                   bumn_2.level,
//                   bumn_3.level,
//                   bumn_4.level,
//                   bumn_induk.nama_lengkap,
//                   jabatan.id,
//                   jabatan.nomenklatur_jabatan,
//                   jabatan.nomenklatur_baru,
//                   jabatan.nama_lengkap,
//                   jabatan.id_perusahaan,
//                   jabatan.induk
//           ORDER BY talenta.id
//       ) A
// ) B
// LEFT JOIN status_talenta ON status_talenta.id = B.id_status_talenta
// LEFT JOIN view_organ_perusahaan ON view_organ_perusahaan.id_talenta = B.talenta_id
// LEFT JOIN perusahaan ON perusahaan.id = B.id_perusahaan
// WHERE B.id_status_talenta in ($id_status_talenta)
// ORDER BY B.nama_lengkap";

// dd($talenta);

       if($users->kategori_user_id != 1){
        //  dd($users->kategori_user_id);
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

                                  // $talenta  = DB::select(DB::raw($query));
                                  // dd($talenta);
                                }
      // $talenta  = DB::select(DB::raw($query));
      return $talenta;
    }

    public function datatable(Request $request)
    {
        $id_users = \Auth::user()->id;
        $id_users_bumn = \Auth::user()->id_bumn;
        $users = User::where('id', $id_users)->first();

        $talenta = $this->query_talenta(5); //status nominated

        if($request->nama_lengkap){
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
          $talenta->where("talenta.id_perusahaan", $instansi->id);
        }

        if($request->asal_instansi){
          $talenta->where("talenta.id_jenis_asal_instansi", $request->asal_instansi);
        }

        try{
            return datatables()->of($talenta)
            ->editColumn('nama_lengkap', function ($row){
                if($row->nik != '')  {
                  if($row->file_ktp != ''){
                    $return = '<a href="javascript:;" class="cls-minicv" data-id="'.(int)$row->id.'" data-toggle="tooltip" data-original-title="CV">
                              <b>'.$row->nama_lengkap.'</b></br><span>'.$row->nik.'
                          </span></a>';
                  } else {
                    $return = '<a href="javascript:;" class="cls-minicv" data-id="'.(int)$row->id.'" data-toggle="tooltip" data-original-title="CV">
                              <b>'.$row->nama_lengkap."</b></br><span>".$row->nik.'
                          </span><span style="color:red"><i>File KTP belum ada</i>
                                      </span></a>';
                  }
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
                $indukperusah = '';
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
                return $indukperusah;
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
            ->addColumn('approve', function ($row){
                $id = (int)$row->id;
                
                $button = '<label class="mt-checkbox mt-checkbox-outline">
                <input type="checkbox" data-id="'.(int)$row->id.'" data-nama_lengkap="'.$row->nama_lengkap.'" value="'.$id.'" form="form_group" class="checked_item"/>
                <span></span>
                </label>';
                
                if($row->id_status_talenta!=5){ //status nominated
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
                
                if($row->id_status_talenta!=5){ //status nominated
                  $button = '<label class="mt-checkbox mt-checkbox-outline">
                  <input type="checkbox" value="'.$id.'" form="form_group" disabled/>
                  <span></span>
                  </label>';
                }

                return $button;
            })
            ->rawColumns(['approve', 'reject','action','nama_lengkap','jabatan', 'stalenta', 'nama_perusahaan', 'talent_jabatan', 'bumn_induk', 'status_pengisian'])
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
              $param['id_status_talenta'] = 6; //status eligible
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
                'data' => $data
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

}
