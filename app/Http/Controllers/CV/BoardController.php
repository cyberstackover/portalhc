<?php
namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Talenta;
use App\Agama;
use App\DataKeluarga;
use App\DataKeluargaAnak;
use App\SocialMedia;
use App\TransactionTalentaSocialMedia;
use App\Toastr;
use App\AsalInstansiBaru;
use App\JenisAsalInstansiBaru;
use App\JenisJabatan;
use App\StatusTalenta;
use App\Perusahaan;
use DB;
use App\Helpers\CVHelper;
use Carbon\Carbon;
use App\User;
use Illuminate\Validation\Rule;
use App\Imports\RowImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
//use App\Exports\ExcelSheet;
use App\Exports\ExcelSheet2;
use App\Exports\RekapCV;
use App\KategoriDataTalent;
use App\KategoriJabatanTalent;
use App\KategoriNonTalent;
use App\ClusterBumn;
use App\Http\Controllers\Talenta\RegisterController;

class BoardController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         ini_set( 'max_execution_time', 0);
         ini_set('memory_limit', '-1');
         $this->__route = 'cv.board';
         $this->__title = "CV Board";
         $this->middleware('permission:talent-list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
      activity()->log('Menu CV Board');

      return view($this->__route.'.index',[
          'pagetitle' => $this->__title,
          'route' => $this->__route,
            'asal_instansi' => JenisAsalInstansiBaru::orderBy('id', 'asc')->get(),
            'instansi' => Perusahaan::orderBy('id', 'asc')->where('is_active', true)->get(),
            'perusahaans' => Perusahaan::orderBy('id', 'asc')->where('is_active', true)->get(),
            'jabatan' => JenisJabatan::orderBy('id', 'asc')->get(),
            'kategori_jabatan' => KategoriJabatanTalent::orderBy('id', 'asc')->get(),
            'klaster' => ClusterBumn::orderBy('id', 'asc')->get(),
          'breadcrumb' => [
              [
                  'url' => '/',
                  'menu' => 'Homes'
              ],
              [
                  'url' => route('cv.board.index'),
                  'menu' => 'Board'
              ]
          ]
      ]);

    }


    public function datatable(Request $request)
    {
        $id_users = \Auth::user()->id;
        $id_users_bumn = \Auth::user()->id_bumn;
        $users = User::where('id', $id_users)->first();

        if($request->perusahaan){
          $talenta = DB::table('talenta')
                      ->leftJoin('status_talenta', 'status_talenta.id', '=', 'talenta.id_status_talenta')
                      ->leftJoin('kategori_jabatan_talent', 'kategori_jabatan_talent.id', '=', 'talenta.id_kategori_jabatan_talent')
                      ->leftJoin(DB::raw("lateral (select s.nomenklatur_jabatan, p.nama_lengkap, p.id, skp.nomenklatur_baru, s.id_perusahaan, sk.id_grup_jabatan, p.id_klaster
                                      from view_organ_perusahaan v
                                      left join struktur_organ s on v.id_struktur_organ = s.id
                                      LEFT JOIN organ_perusahaan op ON op.id_struktur_organ = s.id
                                      LEFT JOIN surat_keputusan sk ON sk.id = op.id_surat_keputusan
                                      left join perusahaan p on p.id = s.id_perusahaan
                                      LEFT JOIN sk_perubahan_nomenklatur skp ON skp.id_struktur_organ = s.ID
                                      where v.id_talenta = talenta.id
                                      and p.is_active is true
                                      and v.aktif = 't' and s.aktif = 't'
                                      and (v.tanggal_akhir >= now( )
                                        or v.tanggal_akhir is null)
                                      order by v.id_struktur_organ ASC, s.urut ASC
                                      ) jabatan"), 'talenta.id', '=', 'talenta.id')
                      ->leftJoin('perusahaan', 'perusahaan.id', '=', 'jabatan.id_perusahaan')
                      ->leftJoin('perusahaan as p', 'p.id', '=', 'talenta.id_perusahaan')
                      ->select(DB::raw("talenta.id,
                                    talenta.nama_lengkap,
                                    talenta.nik,
                                    talenta.persentase,
                                    talenta.id_perusahaan as talent_bumn,
                                    talenta.id_kategori_jabatan_talent,
                                    jabatan.nama_lengkap as nama_perusahaan,
                                    case when jabatan.nomenklatur_baru is NULL then
                                    jabatan.nomenklatur_jabatan else jabatan.nomenklatur_baru END as jabatan,
                                    talenta.id_jenis_asal_instansi,
                                    status_talenta.nama as stalenta,
                                    talenta.id_status_talenta,
                                    kategori_jabatan_talent.nama as talent_jabatan,
                                    jabatan.id as id_bumn,
                                    jabatan.id_perusahaan,
                                    jabatan.id_klaster as id_klaster"))
                      ->where('p.is_active', true)
                      ->where('perusahaan.id', $request->perusahaan)
                      ->GroupBy('talenta.id', 'jabatan.nomenklatur_jabatan', 'jabatan.nama_lengkap','status_talenta.nama','kategori_jabatan_talent.nama','jabatan.id','jabatan.nomenklatur_baru','talenta.id_kategori_jabatan_talent', 'jabatan.id_klaster', 'jabatan.id_perusahaan')
                      ->orderBy('talenta.nama_lengkap', 'ASC')
                      ->limit(300);
        } else {
          $talenta = DB::table('talenta')
                      ->leftJoin('status_talenta', 'status_talenta.id', '=', 'talenta.id_status_talenta')
                      ->leftJoin('kategori_jabatan_talent', 'kategori_jabatan_talent.id', '=', 'talenta.id_kategori_jabatan_talent')
                      ->leftJoin(DB::raw("lateral (select s.nomenklatur_jabatan, p.nama_lengkap, p.id, skp.nomenklatur_baru, s.id_perusahaan, sk.id_grup_jabatan, p.id_klaster
                                      from view_organ_perusahaan v
                                      left join struktur_organ s on v.id_struktur_organ = s.id
                                      LEFT JOIN organ_perusahaan op ON op.id_struktur_organ = s.id
                                      LEFT JOIN surat_keputusan sk ON sk.id = op.id_surat_keputusan
                                      left join perusahaan p on p.id = s.id_perusahaan
                                      LEFT JOIN sk_perubahan_nomenklatur skp ON skp.id_struktur_organ = s.ID
                                      where v.id_talenta = talenta.id
                                      and p.is_active is true
                                      and v.aktif = 't' and s.aktif = 't'
                                      and (v.tanggal_akhir >= now( )
                                        or v.tanggal_akhir is null)
                                      order by v.id_struktur_organ ASC, s.urut ASC
                                      limit 1) jabatan"), 'talenta.id', '=', 'talenta.id')
                      ->leftJoin('perusahaan', 'perusahaan.id', '=', 'jabatan.id_perusahaan')
                      ->leftJoin('perusahaan as p', 'p.id', '=', 'talenta.id_perusahaan')
                      ->select(DB::raw("talenta.id,
                                    talenta.nama_lengkap,
                                    talenta.nik,
                                    talenta.persentase,
                                    talenta.id_perusahaan as talent_bumn,
                                    talenta.id_kategori_jabatan_talent,
                                    jabatan.nama_lengkap as nama_perusahaan,
                                    case when jabatan.nomenklatur_baru is NULL then
                                    jabatan.nomenklatur_jabatan else jabatan.nomenklatur_baru END as jabatan,
                                    talenta.id_jenis_asal_instansi,
                                    status_talenta.nama as stalenta,
                                    talenta.id_status_talenta,
                                    kategori_jabatan_talent.nama as talent_jabatan,
                                    jabatan.id as id_bumn,
                                    jabatan.id_perusahaan,
                                    p.id as perusahaan_id,
                                    p.nama_lengkap as p_nama_perusahaan,
                                    jabatan.id_klaster as id_klaster"))
                      // ->where('perusahaan.is_active', true)
                      ->where('p.is_active', true)
                      ->GroupBy('talenta.id', 'jabatan.nomenklatur_jabatan', 'jabatan.nama_lengkap','status_talenta.nama','kategori_jabatan_talent.nama','jabatan.id','jabatan.nomenklatur_baru','talenta.id_kategori_jabatan_talent', 'jabatan.id_klaster', 'jabatan.id_perusahaan','p.id')
                      ->orderBy('talenta.nama_lengkap', 'ASC')
                      ->limit(300);
        }

       if($users->kategori_user_id != 1){

            /*$talenta = $talenta->whereRaw("( (perusahaan.id in (
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
                                          AND (
                                            tmt_awal <= NOW( ) :: DATE
                                            AND (
                                            CASE

                                                WHEN tmt_akhir IS NOT NULL THEN
                                                tmt_akhir >= NOW( ) :: DATE ELSE NOW( ) :: DATE = NOW( ) :: DATE
                                              END
                                              )
                                            ) UNION
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
                                          (
                                            ak.tmt_awal <= NOW( ) :: DATE
                                            AND (
                                            CASE

                                                WHEN ak.tmt_akhir IS NOT NULL THEN
                                                ak.tmt_akhir >= NOW( ) :: DATE ELSE NOW( ) :: DATE = NOW( ) :: DATE
                                              END
                                              )
                                            )
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
                                      ) OR perusahaan.id = ".$id_users_bumn.")
                                      ");*/
            
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
            talenta.id_perusahaan = ".$id_users_bumn.")");


            /*$talenta = $talenta->whereRaw("(perusahaan.id in (
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
                                          AND (
                                            tmt_awal <= NOW( ) :: DATE
                                            AND (
                                            CASE

                                                WHEN tmt_akhir IS NOT NULL THEN
                                                tmt_akhir >= NOW( ) :: DATE ELSE NOW( ) :: DATE = NOW( ) :: DATE
                                              END
                                              )
                                            ) UNION
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
                                          (
                                            ak.tmt_awal <= NOW( ) :: DATE
                                            AND (
                                            CASE

                                                WHEN ak.tmt_akhir IS NOT NULL THEN
                                                ak.tmt_akhir >= NOW( ) :: DATE ELSE NOW( ) :: DATE = NOW( ) :: DATE
                                              END
                                              )
                                            )
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
                                      )");*/
        }

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

        if($request->kategori){
            $k_jabatan = KategoriJabatanTalent::find($request->kategori);
            $talenta->where("talenta.id_kategori_jabatan_talent", $k_jabatan->id);
        }

        if($request->klaster){
            $c_perusahaan = ClusterBumn::find($request->klaster);
            $talenta->where("jabatan.id_klaster", $c_perusahaan->id);
        }

        if($request->instansi){
          $instansi = Perusahaan::find($request->instansi);
          $talenta->where("jabatan.nama_lengkap", $instansi->nama_lengkap);
        }

        if($request->asal_instansi){
          $talenta->where("talenta.id_jenis_asal_instansi", $request->asal_instansi);
        }

        // if($request->perusahaan){
        //   $talenta->where("jabatan.id_perusahaan", $request->perusahaan);
        //   /*$talenta->whereRaw("((( (perusahaan.id in (
        //                                 WITH RECURSIVE anak AS (
        //                                 SELECT
        //                                   perusahaan_id,
        //                                   perusahaan_induk_id,
        //                                   tmt_awal,
        //                                   tmt_akhir
        //                                 FROM
        //                                   perusahaan_relasi
        //                                 WHERE
        //                                   perusahaan_induk_id = ".$request->perusahaan."
        //                                   AND (
        //                                     tmt_awal <= NOW( ) :: DATE
        //                                     AND (
        //                                     CASE

        //                                         WHEN tmt_akhir IS NOT NULL THEN
        //                                         tmt_akhir >= NOW( ) :: DATE ELSE NOW( ) :: DATE = NOW( ) :: DATE
        //                                       END
        //                                       )
        //                                     ) UNION
        //                                   SELECT
        //                                     pr.perusahaan_id,
        //                                     pr.perusahaan_induk_id,
        //                                     pr.tmt_awal,
        //                                     pr.tmt_akhir
        //                                   FROM
        //                                     perusahaan_relasi pr
        //                                     INNER JOIN anak A ON A.perusahaan_id = pr.perusahaan_induk_id
        //                                   ) SELECT
        //                                   P.ID
        //                                 FROM
        //                                   anak ak
        //                                   LEFT JOIN perusahaan P ON ak.perusahaan_id = P.ID
        //                                 WHERE
        //                                   (
        //                                     ak.tmt_awal <= NOW( ) :: DATE
        //                                     AND (
        //                                     CASE

        //                                         WHEN ak.tmt_akhir IS NOT NULL THEN
        //                                         ak.tmt_akhir >= NOW( ) :: DATE ELSE NOW( ) :: DATE = NOW( ) :: DATE
        //                                       END
        //                                       )
        //                                     )
        //                                   GROUP BY
        //                                     P.urut,
        //                                     P.ID,
        //                                     P.nama_lengkap,
        //                                     ak.perusahaan_induk_id,
        //                                     ak.perusahaan_id,
        //                                     ak.tmt_awal,
        //                                     ak.tmt_akhir
        //                                   ORDER BY
        //                                   P.urut ASC,
        //                                 P.ID ASC
        //                                 )
        //                               ) OR perusahaan.id = ".$request->perusahaan.")
        //                               AND talenta.id_perusahaan IS NULL)
        //                               OR
        //                               talenta.id_perusahaan = ".$request->perusahaan.") ");*/
        // }

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
            ->editColumn('talent_jabatan', function ($row){
                $html = '';
                if(\Auth::user()->hasRole('Admin') || \Auth::user()->hasRole('Bumn')){
                    if($row->talent_jabatan){
                        $html .= '<a style="cursor:pointer" class="cls-talentjabatan" data-id="'.(int)$row->id.'">'.$row->talent_jabatan.' <i class="icon-2x text-dark-50 flaticon-edit" ></i></a>';
                    }else{
                        $html .= '<a style="cursor:pointer" class="cls-talentjabatan" data-id="'.(int)$row->id.'"><i class="icon-2x text-dark-50 flaticon-edit" ></i>&nbsp;</a>';
                    }
                } else {
                    if($row->talent_jabatan){
                        $html .= $row->talent_jabatan;
                    }else{
                        $html .= '&nbsp;';
                    }
                }


                return $html;
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

                  if(empty($row->perusahaan_id)){
                    $indukperusah .= 'Tidak Mempunyai Perusahaan';
                  } else{
                    $indukperusah .= "<b>".$row->p_nama_perusahaan."</b>";
                  }

                $edit = '';
                if(\Auth::user()->hasRole('Admin') || \Auth::user()->hasRole('Bumn')){
                  $edit = ' <a style="cursor:pointer" class="cls-editstatus" data-id="'.(int)$row->id.'"><i class="icon-2x text-dark-50 flaticon-edit" ></i>&nbsp;</a>';
                }

                $html .= '<a href="#" style="color:#4f4f4f;background-color:'.$color.';" class="cls-logstatus kt-badge kt-badge--inline kt-badge--pill kt-badge--rounded" data-id="'.(int)$row->id.'" data-toggle="tooltip" data-original-title="Log Status"><b>'.$row->stalenta.'</b></a></b>'.$edit.'</br><span>'.$indukperusah.'</span>';
                return $html;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';
                if(\Auth::user()->can('talent-edit')){
                    $button .= '<a type="button" href="/cv/biodata/'.$id.'/index" class="btn btn-outline-primary btn-sm btn-icon cls-button" data-toggle="tooltip" data-original-title="Ubah CV">
                                <i class="flaticon-edit"></i>
                            </a>';
                } else {
                    $button .= '&nbsp';
                }


                $button .= '&nbsp;';

                if(\Auth::user()->can('talent-print')){
                    $button .= '<a type="button" href="/cv/board/export2/'.$id.'" class="btn btn-outline-success btn-sm btn-icon " data-toggle="tooltip" data-original-title="Export CV">
                                <i class="far fa-file-excel"></i>
                            </a>';
                } else {
                    $button .= '&nbsp';
                }


                $button .= '&nbsp;';

                if(\Auth::user()->can('talent-delete')){
                    $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete btn-sm" data-id="'.$id.'" data-periode="'.$row->nama_lengkap.'" data-toggle="tooltip" data-original-title="Hapus data talenta '.$row->nama_lengkap.'"><i class="flaticon-delete"></i></button>';
                } else {
                    $button .= '&nbsp';
                }

                $button .= '</div>';
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
                  'perusahaan' => Perusahaan::where('level', 0)->get(),
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

    public function jabatantalent(Request $request)
    {

       $id_users = \Auth::user()->id;
       $id_users_bumn = \Auth::user()->id_bumn;
       $users = User::where('id', $id_users)->first();
       $kategori_user = $users->kategori_user_id;

       $id = (int)$request->input('id');
       $talenta = Talenta::find($id);

       if($kategori_user == 1){
        $kategori_jabatan_talent = KategoriJabatanTalent::all();
        $kategori_non_talent = KategoriNonTalent::all();
      } else {
        $kategori_jabatan_talent = KategoriJabatanTalent::where('hak_akses',true)->get();
        $kategori_non_talent = KategoriNonTalent::where('hak_akses',true)->get();
      }

       try{
          return view($this->__route.'.jabatantalent',[
                  'talenta' => $talenta,
                  'kategoridatas' => KategoriDataTalent::all(),
                  'kategorijabatans' => $kategori_jabatan_talent,
                  'kategorinons' => $kategori_non_talent,
          ]);
      }catch(Exception $e){}
    }

    public function editstatus(Request $request)
    {
       $id = (int)$request->input('id');
       $talenta = Talenta::find($id);
       $status_talenta = StatusTalenta::all();

       try{
          return view($this->__route.'.edit_status',[
                  'talenta' => $talenta,
                  'id' => $id,
                  'status_talenta' => $status_talenta,
          ]);
      }catch(Exception $e){}
    }

    public function datatalent(Request $request)
    {
       $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

       $id_kategori_data_talent = (int)$request->input('id');
       $id_talenta = (int)$request->input('id_talenta');

       try{
          $talent = Talenta::find($id_talenta);
          $param['id_kategori_data_talent'] = $id_kategori_data_talent;
          $talent->update((array)$param);

          DB::commit();
          $result = [
            'flag'  => 'success',
            'msg' => 'Sukses ubah data',
            'title' => 'Sukses'
          ];
      }catch(Exception $e){
        DB::rollback();
          $result = [
            'flag'  => 'warning',
            'msg' => 'Gagal ubah data',
            'title' => 'Gagal'
          ];
      }
      return response()->json($result);
    }

    public function datajabatantalent(Request $request)
    {
       $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

       $id_kategori_jabatan_talent = (int)$request->input('id');
       $id_talenta = (int)$request->input('id_talenta');

       try{
          $talent = Talenta::find($id_talenta);
          if($id_kategori_jabatan_talent == 7){
            $param['is_talenta'] = false;
          } else {
            $param['is_talenta'] = true;
          }
          $param['id_kategori_jabatan_talent'] = $id_kategori_jabatan_talent;
          $talent->update((array)$param);

          DB::commit();
          $result = [
            'flag'  => 'success',
            'msg' => 'Sukses ubah data',
            'title' => 'Sukses'
          ];
      }catch(Exception $e){
        DB::rollback();
          $result = [
            'flag'  => 'warning',
            'msg' => 'Gagal ubah data',
            'title' => 'Gagal'
          ];
      }
      return response()->json($result);
    }

    public function datanontalent(Request $request)
    {
       $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

       $id_kategori_non_talent = (int)$request->input('id');
       $id_talenta = (int)$request->input('id_talenta');

       try{
          $talent = Talenta::find($id_talenta);
          $param['id_kategori_non_talent'] = $id_kategori_non_talent;
          $talent->update((array)$param);

          DB::commit();
          $result = [
            'flag'  => 'success',
            'msg' => 'Sukses ubah data',
            'title' => 'Sukses'
          ];
      }catch(Exception $e){
        DB::rollback();
          $result = [
            'flag'  => 'warning',
            'msg' => 'Gagal ubah data',
            'title' => 'Gagal'
          ];
      }
      return response()->json($result);
    }

    public function minicv(Request $request)
    {
      $id = (int)$request->input('id');

      $talenta = DB::table('view_organ_perusahaan')
      ->leftJoin('talenta', 'talenta.id', '=', 'view_organ_perusahaan.id_talenta')
      ->leftjoin('riwayat_jabatan_dirkomwas', function($query){
          $query->on('riwayat_jabatan_dirkomwas.id_talenta', '=', 'talenta.id')
          ->whereNull('riwayat_jabatan_dirkomwas.tanggal_akhir');
      })
      ->leftJoin('struktur_organ', 'struktur_organ.id', '=', 'view_organ_perusahaan.id_struktur_organ')
      ->leftJoin('perusahaan', 'perusahaan.id', '=', 'struktur_organ.id_perusahaan')
      ->leftJoin('jenis_asal_instansi', 'jenis_asal_instansi.id', '=', 'talenta.id_jenis_asal_instansi')
      ->leftJoin('instansi','instansi.id','=','talenta.id_asal_instansi')
      ->select(DB::raw("talenta.*,
                       perusahaan.nama_lengkap as nama_perusahaan,
                       jenis_asal_instansi.nama as jenis_asal_instansi,
                       instansi.nama as instansi,
                       struktur_organ.nomenklatur_jabatan as jabatan"))
      ->where('view_organ_perusahaan.aktif', '=', 't')
      ->where('talenta.id', $id)->first();

      try{
          return view($this->__route.'.minicv',[
                  'talenta' => $talenta,
          ]);
      }catch(Exception $e){}
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
          'msg' => 'data sudah diimport',
          'title' => 'beta testing'
      ];


      return back();
    }

    /**
     * Import excel talenta versi 2
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store_import2(Request $request)
    {
        $file = $request->file('file_name');
        $nama_file = rand().$file->getClientOriginalName();
        $file->move('uploads/cv',$nama_file);
        Excel::import(new RowImport($nama_file), public_path('uploads/cv/'.$nama_file));

        //delete after unused
        //File::delete('uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR . $nama_file);

      $result = [
          'flag' => 'berhasil',
          'msg' => 'data sudah diimport',
          'title' => 'beta testing'
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

    public function store_status(Request $request)
    {
      $id_users = \Auth::user()->id;
      $id_users_bumn = \Auth::user()->id_bumn;
      $users = User::where('id', $id_users)->first();

      $id = (int)$request->input('id');
      $param['id_status_talenta'] = $request->input('id_status_talenta');
      DB::beginTransaction();
      try{
        $status = Talenta::find($id)->update($param);
        RegisterController::store_log($id, $param['id_status_talenta']);

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

      return response()->json($result);
    }

    protected function validateform($request)
    {
        $required['nama_lengkap'] = 'required';
        $required['tanggal_lahir'] = 'required';
        $required['nik'] = 'required|regex:/(^[A-Za-z0-9]+$)+/u|unique:talenta';
        $required['npwp'] = 'required|numeric';


        $message['nama_lengkap.required'] = 'Nama Lengkap wajib Diisi';
        $message['tanggal_lahir.required'] = 'Tanggal Lahir wajib Diisi';
        $message['nik.required'] = 'NIK wajib Diisi';
        $message['nik.unique'] = 'NIK Sudah Digunakan, Silakan Periksa Kembali!!';
        $message['nik.regex'] = 'NIK Tanpa Menggunakan tanda Baca (hanya Angka)!!';
        $message['npwp.required'] = 'NPWP Wajib dimasukan';
        $message['npwp.numeric'] = 'NPWP Tanpa Menggunakan tanda Baca (hanya Angka)!!';

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
    public function export($id)
    {
      $talenta = Talenta::find($id);
      if($talenta->nik){
        $namaFile = "CV-".$talenta->nama_lengkap."-".$talenta->nik.".xlsx";
      }else{
        $namaFile = "CV-".$talenta->nama_lengkap.".xlsx";
      }
      return Excel::download(new ExcelSheet($id), $namaFile);
    }

    public function export2($id)
    {
      $talenta = Talenta::find($id);
      if($talenta->nik){
        $namaFile = "CV-".$talenta->nama_lengkap."-".$talenta->nik.".xlsx";
      }else{
        $namaFile = "CV-".$talenta->nama_lengkap.".xlsx";
      }
      return Excel::download(new ExcelSheet2($id), $namaFile);
    }

    public function template()
    {
      $namaFile = "CV-Template.xlsx";
      return Excel::download(new ExcelSheet2(0), $namaFile);
    }

    public function generate()
    {
      $namaFile = "Rekap pengisian CV Talenta ".date('dmY').".xlsx";
      return Excel::download(new RekapCV(), $namaFile);
    }

    /**
     * @return string
     */
    public function fill_persentase()
    {
      $talenta = Talenta::WhereNull('persentase')->get();
      foreach($talenta as $val){
          $param['persentase'] = CVHelper::fillPercentage($val->id);
          $status = $val->update($param);
      }
      return "OK";
    }

    public function fill_cv()
    {
      $talenta = Talenta::whereNull('fill_organisasinonformal')->get();

      foreach($talenta as $val){
          // $param['fill_biodata'] = CVHelper::biodataFillCheck($val->id);
          // $param['fill_organisasi'] = CVHelper::organisasiFillCheck($val->id);
          $param['fill_organisasinonformal'] = CVHelper::organisasinonformalFillCheck($val->id);
          // $param['fill_pajak'] = CVHelper::pajakFillCheck($val->id);
          // $param['fill_summary'] = CVHelper::summaryFillCheck($val->id);
          // $param['fill_pengalaman_lain'] = CVHelper::pengalamanLainFillCheck($val->id);
          // $param['fill_karya_ilmiah'] = CVHelper::karyaIlmiahFillCheck($val->id);
          // $param['fill_penghargaan'] = CVHelper::penghargaanFillCheck($val->id);
          // $param['fill_keluarga'] = CVHelper::keluargaFillCheck($val->id_talenta);
          // $param['fill_pendidikan'] = CVHelper::pendidikanFillCheck($val->id);
          // $param['fill_pelatihan'] = CVHelper::pelatihanFillCheck($val->id);
          // $param['fill_jabatan'] = CVHelper::jabatanFillCheck($val->id);
          // $param['fill_keahlian'] = CVHelper::keahlianFillCheck($val->id);
          // $param['fill_interest'] = CVHelper::interestFillCheck($val->id);
          // $param['fill_referensi_cv'] = CVHelper::referensiCVFillCheck($val->id);

          $param['persentase'] = CVHelper::fillPercentage($val->id);
          $status = $val->update($param);
      }
      return $status;
    }

    #import
    protected function validateImport($request)
    {
        $required['file_name'] = 'required|mimes:xlsx,xls';

        $message['file_name.required'] = 'File wajib Diisi';
        $message['file_name.mimes'] = 'File wajib dalam format excel wajib Diisi';

        return Validator::make($request->all(), $required, $message);
    }

    public function checknik(Request $request)
    {
        return response()->json(Talenta::where('nik', $request->input('nik'))->count() > 0? false : true);
    }

}
