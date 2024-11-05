<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Perusahaan;
use App\JenisSk;
use App\GrupJabatan;
use App\SuratKeputusan;
use App\StrukturOrgan;
use App\PeriodeJabatan;
use App\Periode;
use App\Rekomendasi;
use App\Talenta;
use App\JenisMobilityJabatan;
use App\RincianSK;
use App\JenisJabatan;
use App\OrganPerusahaan;
use App\User;
use App\UserGuide;
use App\RiwayatJabatanDirkomwas;
use App\Helpers\CVHelper;
use Carbon\Carbon;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; 

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->__route = 'home';
        $this->userguidefile_url = Config::get('folder.userguidefile_url');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        activity()->log('Dashboard');

        //app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        $user_guide = UserGuide::select('*')->orderBy('created_at', 'desc')->first();

        if($users->kategori_user_id == 1){
            $index = 'index';
        } elseif ($users->kategori_user_id == 2) {
            
            $index = 'index2';
        } else {
            $index = 'index2';
        }

        return view($this->__route.'.'.$index,[
            'pagetitle' => 'Home',
            'perusahaan' => Perusahaan::orderBy('id', 'asc')->get(),
            'talenta' => Talenta::orderBy('nama_lengkap', 'asc')->get(),
            'breadcrumb' => [
                            
            ],
            'user_guide' => asset($this->userguidefile_url.@$user_guide->filename)
            
        ]);
    }

    public function chartmasajabatans(Request $request)
    {
        try{
          $json = [];
          $title = [];

          $subtitle = [];
          $series = [];

          $subtitle['name'] = 'Berdasarkan Waktu Berakhir Jabatan';

          $id_filter = 0;

          if ($request->id_filter)
            $id_filter = $request->id_filter;

          switch ($id_filter) {
            case 0:
              $title['name'] = 'Chart Masa Jabatan Direksi/Komisaris BUMN';
              break;
            case 1:
              $title['name'] = 'Chart Masa Jabatan Direksi/Komisaris Anak';
              break;
            case 2:
              $title['name'] = 'Chart Masa Jabatan Direksi/Komisaris Cucu';
              break;
            
            default:
              $title['name'] = 'Chart Masa Jabatan Direksi/Komisaris BUMN';
              break;
          }
            
          $dir1 = DB::table('struktur_organ')
          ->leftJoin('organ_perusahaan','organ_perusahaan.id_struktur_organ','=','struktur_organ.id')
          ->leftJoin('talenta','talenta.id','=','organ_perusahaan.id_talenta')
          ->leftJoin('perusahaan','perusahaan.id','=','struktur_organ.id_perusahaan')
          ->leftJoin('surat_keputusan','surat_keputusan.id','=','organ_perusahaan.id_surat_keputusan')
          ->where('surat_keputusan.id_grup_jabatan', 1)
          ->where('struktur_organ.aktif',true)
          ->where('organ_perusahaan.aktif',true)
          ->where('perusahaan.level',$id_filter)
          ->where('organ_perusahaan.tanggal_akhir','>',Carbon::now()->format('Y-m-d'))
          ->where('organ_perusahaan.tanggal_akhir','<',Carbon::now()->addday('90')->format('Y-m-d'))
          ->count();
            
          $dir2 = DB::table('struktur_organ')
          ->leftJoin('organ_perusahaan','organ_perusahaan.id_struktur_organ','=','struktur_organ.id')
          ->leftJoin('talenta','talenta.id','=','organ_perusahaan.id_talenta')
          ->leftJoin('perusahaan','perusahaan.id','=','struktur_organ.id_perusahaan')
          ->leftJoin('surat_keputusan','surat_keputusan.id','=','organ_perusahaan.id_surat_keputusan')
          ->where('surat_keputusan.id_grup_jabatan', 1)
          ->where('struktur_organ.aktif',true)
          ->where('organ_perusahaan.aktif',true)
          ->where('perusahaan.level',$id_filter)
          ->where('organ_perusahaan.tanggal_akhir','>',Carbon::now()->addday('90')->format('Y-m-d'))
          ->where('organ_perusahaan.tanggal_akhir','<',Carbon::now()->addday('180')->format('Y-m-d'))
          ->count();

          $dir3 = DB::table('struktur_organ')
          ->leftJoin('organ_perusahaan','organ_perusahaan.id_struktur_organ','=','struktur_organ.id')
          ->leftJoin('talenta','talenta.id','=','organ_perusahaan.id_talenta')
          ->leftJoin('perusahaan','perusahaan.id','=','struktur_organ.id_perusahaan')
          ->leftJoin('surat_keputusan','surat_keputusan.id','=','organ_perusahaan.id_surat_keputusan')
          ->where('surat_keputusan.id_grup_jabatan', 1)
          ->where('struktur_organ.aktif',true)
          ->where('organ_perusahaan.aktif',true)
          ->where('perusahaan.level',$id_filter)
          ->where('organ_perusahaan.tanggal_akhir','<', Carbon::now()->format('Y-m-d'))
          ->count();

          $kom1 = DB::table('struktur_organ')
          ->leftJoin('organ_perusahaan','organ_perusahaan.id_struktur_organ','=','struktur_organ.id')
          ->leftJoin('talenta','talenta.id','=','organ_perusahaan.id_talenta')
          ->leftJoin('perusahaan','perusahaan.id','=','struktur_organ.id_perusahaan')
          ->leftJoin('surat_keputusan','surat_keputusan.id','=','organ_perusahaan.id_surat_keputusan')
          ->where('surat_keputusan.id_grup_jabatan', 4)
          ->where('struktur_organ.aktif',true)
          ->where('organ_perusahaan.aktif',true)
          ->where('perusahaan.level',$id_filter)
          ->where('organ_perusahaan.tanggal_akhir','>',Carbon::now()->format('Y-m-d'))
          ->where('organ_perusahaan.tanggal_akhir','<',Carbon::now()->addday('90')->format('Y-m-d'))
          ->count();

          $kom2 = DB::table('struktur_organ')
          ->leftJoin('organ_perusahaan','organ_perusahaan.id_struktur_organ','=','struktur_organ.id')
          ->leftJoin('talenta','talenta.id','=','organ_perusahaan.id_talenta')
          ->leftJoin('perusahaan','perusahaan.id','=','struktur_organ.id_perusahaan')
          ->leftJoin('surat_keputusan','surat_keputusan.id','=','organ_perusahaan.id_surat_keputusan')
          ->where('surat_keputusan.id_grup_jabatan', 4)
          ->where('struktur_organ.aktif',true)
          ->where('organ_perusahaan.aktif',true)
          ->where('perusahaan.level',$id_filter)
          ->where('organ_perusahaan.tanggal_akhir','>',Carbon::now()->addday('90')->format('Y-m-d'))
          ->where('organ_perusahaan.tanggal_akhir','<',Carbon::now()->addday('180')->format('Y-m-d'))
          ->count();
            
          $kom3 = DB::table('struktur_organ')
          ->leftJoin('organ_perusahaan','organ_perusahaan.id_struktur_organ','=','struktur_organ.id')
          ->leftJoin('talenta','talenta.id','=','organ_perusahaan.id_talenta')
          ->leftJoin('perusahaan','perusahaan.id','=','struktur_organ.id_perusahaan')
          ->leftJoin('surat_keputusan','surat_keputusan.id','=','organ_perusahaan.id_surat_keputusan')
          ->where('surat_keputusan.id_grup_jabatan', 4)
          ->where('struktur_organ.aktif',true)
          ->where('organ_perusahaan.aktif',true)
          ->where('perusahaan.level',$id_filter)
          ->where('organ_perusahaan.tanggal_akhir','<', Carbon::now()->format('Y-m-d'))
          ->count();


          // $str_rangkap1 = "SELECT count(DISTINCT pejabat) jml_rangkap
          //               from (
          //               SELECT
          //                 t1.nama_lengkap AS pejabat,
          //                 t1.ID AS talenta_id,
          //                 b1.ID AS bumn_id,
          //                 b1.nama_lengkap AS perusahaan,
          //                 so1.ID AS struktur_organ_id,
          //                 so1.nomenklatur_jabatan AS nama_jabatan,
          //                 sk1.nomor,
          //                 op1.tanggal_awal,
          //                 op1.tanggal_akhir,
          //                 t1.jenis_kelamin,
          //                 (select jj2.id_grup_jabatan FROM
          //                   organ_perusahaan as op2 
          //                   LEFT JOIN talenta as t2 ON t2.ID = op2.id_talenta
          //                   LEFT JOIN struktur_organ as so2 ON so2.ID = op2.id_struktur_organ
          //                   LEFT JOIN jenis_jabatan as jj2 ON jj2.ID = so2.id_jenis_jabatan 
          //                   where 
          //                   t2.nama_lengkap = t1.nama_lengkap
          //                   and now( ) <= op2.tanggal_akhir 
          //                   AND op2.aktif = TRUE
          //                   AND so2.aktif = TRUE 
          //                   order by op2.tanggal_awal ASC
          //                   limit 1
          //                   ) as jenis_rangkap
          //               FROM
          //                 (
          //                 SELECT
          //                   t3.nama_lengkap
          //                 FROM
          //                   organ_perusahaan as op3
          //                   LEFT JOIN talenta as t3  ON t3.ID = op3.id_talenta
          //                   LEFT JOIN struktur_organ as so3 ON so3.ID = op3.id_struktur_organ
          //                   LEFT JOIN perusahaan as b3 ON b3.ID = so3.id_perusahaan
          //                   LEFT JOIN jenis_jabatan as jj3 ON jj3.ID = so3.id_jenis_jabatan 
          //                 WHERE
          //                   now( ) <= op3.tanggal_akhir 
          //                   AND op3.aktif = TRUE
          //                   AND so3.aktif = TRUE
          //                 GROUP BY
          //                   t3.nama_lengkap 
          //                 HAVING
          //                   NOT (
          //                     (
          //                       COUNT ( DISTINCT jj3.id_grup_jabatan ) > 1 
          //                       AND ARRAY_AGG ( b3.induk :: smallint ) && ARRAY_AGG ( b3.ID ) 
          //                     ) 
          //                     OR (
          //                       COUNT ( DISTINCT b3.ID ) <= 1 
          //                       AND COUNT ( DISTINCT jj3.id_grup_jabatan ) <= 1 
          //                     ) 
          //                   ) 
          //                   AND ARRAY_AGG ( b3.level ) && ARRAY_AGG ( $id_filter )
          //                 ) AS rangkap
          //                 LEFT JOIN talenta as t1 ON t1.nama_lengkap = rangkap.nama_lengkap
          //                 LEFT JOIN organ_perusahaan as op1 ON t1.ID = op1.id_talenta
          //                 LEFT JOIN struktur_organ as so1 ON so1.ID = op1.id_struktur_organ
          //                 LEFT JOIN perusahaan as b1 ON b1.ID = so1.id_perusahaan
          //                 LEFT JOIN surat_keputusan as sk1 ON sk1.ID = op1.id_surat_keputusan
          //                 LEFT JOIN jenis_jabatan as jj1 ON jj1.ID = so1.id_jenis_jabatan 
          //                 WHERE
          //                   now( ) <= op1.tanggal_akhir 
          //                   AND op1.aktif = TRUE
          //                   AND so1.aktif = TRUE 
          //               ORDER BY
          //                 t1.nama_lengkap
          //                 ) as v_rangkap 
          //                 WHERE
          //                 jenis_rangkap = 1";

          $str_rangkap1 = "SELECT COUNT( DISTINCT pejabat ) jml_rangkap 
                    FROM
                      (
                      SELECT
                        t1.nama_lengkap AS pejabat,
                        t1.ID AS talenta_id,
                        b1.ID AS bumn_id,
                        b1.nama_lengkap AS perusahaan,
                        so1.ID AS struktur_organ_id,
                        so1.nomenklatur_jabatan AS nama_jabatan,
                        sk1.nomor,
                        op1.tanggal_awal,
                        op1.tanggal_akhir,
                        t1.jenis_kelamin,
                        jenis_rangkap.id_grup_jabatan as jenis_rangkap
                      FROM
                        (
                        SELECT
                          t3.nama_lengkap 
                        FROM
                          organ_perusahaan AS op3
                          LEFT JOIN talenta AS t3 ON t3.ID = op3.id_talenta
                          LEFT JOIN struktur_organ AS so3 ON so3.ID = op3.id_struktur_organ
                          LEFT JOIN perusahaan AS b3 ON b3.ID = so3.id_perusahaan
                          LEFT JOIN jenis_jabatan AS jj3 ON jj3.ID = so3.id_jenis_jabatan 
                        WHERE
                          now( ) <= op3.tanggal_akhir 
                          AND op3.aktif = TRUE 
                          AND so3.aktif = TRUE 
                        GROUP BY
                          t3.nama_lengkap 
                        HAVING
                          NOT (
                            ( COUNT ( DISTINCT jj3.id_grup_jabatan ) > 1 AND ARRAY_AGG ( b3.induk :: SMALLINT ) && ARRAY_AGG ( b3.ID ) ) 
                            OR ( COUNT ( DISTINCT b3.ID ) <= 1 AND COUNT ( DISTINCT jj3.id_grup_jabatan ) <= 1 ) 
                          ) 
                          AND ARRAY_AGG ( b3.LEVEL ) && ARRAY_AGG ( $id_filter ) 
                        ) AS rangkap
                        LEFT JOIN talenta AS t1 ON t1.nama_lengkap = rangkap.nama_lengkap
                        LEFT JOIN organ_perusahaan AS op1 ON t1.ID = op1.id_talenta
                        LEFT JOIN struktur_organ AS so1 ON so1.ID = op1.id_struktur_organ
                        LEFT JOIN perusahaan AS b1 ON b1.ID = so1.id_perusahaan
                        LEFT JOIN surat_keputusan AS sk1 ON sk1.ID = op1.id_surat_keputusan
                        LEFT JOIN jenis_jabatan AS jj1 ON jj1.ID = so1.id_jenis_jabatan
                        LEFT JOIN LATERAL (
                        SELECT
                          jj2.id_grup_jabatan 
                        FROM
                          organ_perusahaan AS op2
                          LEFT JOIN talenta AS t2 ON t2.ID = op2.id_talenta
                          LEFT JOIN struktur_organ AS so2 ON so2.ID = op2.id_struktur_organ
                          LEFT JOIN jenis_jabatan AS jj2 ON jj2.ID = so2.id_jenis_jabatan 
                        WHERE
                          t2.nama_lengkap = t1.nama_lengkap 
                          AND now( ) <= op2.tanggal_akhir 
                          AND op2.aktif = TRUE 
                          AND so2.aktif = TRUE 
                        ORDER BY
                          op2.tanggal_awal ASC 
                          LIMIT 1 
                        ) AS jenis_rangkap ON TRUE  
                      WHERE
                        now( ) <= op1.tanggal_akhir 
                        AND op1.aktif = TRUE 
                        AND so1.aktif = TRUE 
                      ORDER BY
                        t1.nama_lengkap 
                      ) AS v_rangkap 
                    WHERE
                      jenis_rangkap = 1";
          $query_rangkap1 = DB::select(DB::raw($str_rangkap1));

          // $str_rangkap4 = "SELECT count(DISTINCT pejabat) jml_rangkap
          //               from (
          //               SELECT
          //                 t1.nama_lengkap AS pejabat,
          //                 t1.ID AS talenta_id,
          //                 b1.ID AS bumn_id,
          //                 b1.nama_lengkap AS perusahaan,
          //                 so1.ID AS struktur_organ_id,
          //                 so1.nomenklatur_jabatan AS nama_jabatan,
          //                 sk1.nomor,
          //                 op1.tanggal_awal,
          //                 op1.tanggal_akhir,
          //                 t1.jenis_kelamin,
          //                 (select jj2.id_grup_jabatan FROM
          //                   organ_perusahaan as op2 
          //                   LEFT JOIN talenta as t2 ON t2.ID = op2.id_talenta
          //                   LEFT JOIN struktur_organ as so2 ON so2.ID = op2.id_struktur_organ
          //                   LEFT JOIN jenis_jabatan as jj2 ON jj2.ID = so2.id_jenis_jabatan 
          //                   where 
          //                   t2.nama_lengkap = t1.nama_lengkap
          //                   and now( ) <= op2.tanggal_akhir 
          //                   AND op2.aktif = TRUE
          //                   AND so2.aktif = TRUE 
          //                   order by op2.tanggal_awal ASC
          //                   limit 1
          //                   ) as jenis_rangkap
          //               FROM
          //                 (
          //                 SELECT
          //                   t3.nama_lengkap
          //                 FROM
          //                   organ_perusahaan as op3
          //                   LEFT JOIN talenta as t3  ON t3.ID = op3.id_talenta
          //                   LEFT JOIN struktur_organ as so3 ON so3.ID = op3.id_struktur_organ
          //                   LEFT JOIN perusahaan as b3 ON b3.ID = so3.id_perusahaan
          //                   LEFT JOIN jenis_jabatan as jj3 ON jj3.ID = so3.id_jenis_jabatan 
          //                 WHERE
          //                   now( ) <= op3.tanggal_akhir 
          //                   AND op3.aktif = TRUE
          //                   AND so3.aktif = TRUE 
          //                 GROUP BY
          //                   t3.nama_lengkap 
          //                 HAVING
          //                   NOT (
          //                     (
          //                       COUNT ( DISTINCT jj3.id_grup_jabatan ) > 1 
          //                       AND ARRAY_AGG ( b3.induk :: smallint ) && ARRAY_AGG ( b3.ID ) 
          //                     ) 
          //                     OR (
          //                       COUNT ( DISTINCT b3.ID ) <= 1 
          //                       AND COUNT ( DISTINCT jj3.id_grup_jabatan ) <= 1 
          //                     ) 
          //                   ) 
          //                   AND ARRAY_AGG ( b3.level ) && ARRAY_AGG ( $id_filter )
          //                 ) AS rangkap
          //                 LEFT JOIN talenta as t1 ON t1.nama_lengkap = rangkap.nama_lengkap
          //                 LEFT JOIN organ_perusahaan as op1 ON t1.ID = op1.id_talenta
          //                 LEFT JOIN struktur_organ as so1 ON so1.ID = op1.id_struktur_organ
          //                 LEFT JOIN perusahaan as b1 ON b1.ID = so1.id_perusahaan
          //                 LEFT JOIN surat_keputusan as sk1 ON sk1.ID = op1.id_surat_keputusan
          //                 LEFT JOIN jenis_jabatan as jj1 ON jj1.ID = so1.id_jenis_jabatan 
          //                 WHERE
          //                   now( ) <= op1.tanggal_akhir 
          //                   AND op1.aktif = TRUE
          //                   AND so1.aktif = TRUE 
          //               ORDER BY
          //                 t1.nama_lengkap
          //                 ) as v_rangkap 
          //                 WHERE
          //                 jenis_rangkap = 4";

          $str_rangkap4 = "SELECT COUNT
                        ( DISTINCT pejabat ) jml_rangkap 
                      FROM
                        (
                        SELECT
                          t1.nama_lengkap AS pejabat,
                          t1.ID AS talenta_id,
                          b1.ID AS bumn_id,
                          b1.nama_lengkap AS perusahaan,
                          so1.ID AS struktur_organ_id,
                          so1.nomenklatur_jabatan AS nama_jabatan,
                          sk1.nomor,
                          op1.tanggal_awal,
                          op1.tanggal_akhir,
                          t1.jenis_kelamin,
                          jenis_rangkap.id_grup_jabatan as jenis_rangkap
                        FROM
                          (
                          SELECT
                            t3.nama_lengkap 
                          FROM
                            organ_perusahaan AS op3
                            LEFT JOIN talenta AS t3 ON t3.ID = op3.id_talenta
                            LEFT JOIN struktur_organ AS so3 ON so3.ID = op3.id_struktur_organ
                            LEFT JOIN perusahaan AS b3 ON b3.ID = so3.id_perusahaan
                            LEFT JOIN jenis_jabatan AS jj3 ON jj3.ID = so3.id_jenis_jabatan 
                          WHERE
                            now( ) <= op3.tanggal_akhir 
                            AND op3.aktif = TRUE 
                            AND so3.aktif = TRUE 
                          GROUP BY
                            t3.nama_lengkap 
                          HAVING
                            NOT (
                              ( COUNT ( DISTINCT jj3.id_grup_jabatan ) > 1 AND ARRAY_AGG ( b3.induk :: SMALLINT ) && ARRAY_AGG ( b3.ID ) ) 
                              OR ( COUNT ( DISTINCT b3.ID ) <= 1 AND COUNT ( DISTINCT jj3.id_grup_jabatan ) <= 1 ) 
                            ) 
                            AND ARRAY_AGG ( b3.LEVEL ) && ARRAY_AGG ( 0 ) 
                          ) AS rangkap
                          LEFT JOIN talenta AS t1 ON t1.nama_lengkap = rangkap.nama_lengkap
                          LEFT JOIN organ_perusahaan AS op1 ON t1.ID = op1.id_talenta
                          LEFT JOIN struktur_organ AS so1 ON so1.ID = op1.id_struktur_organ
                          LEFT JOIN perusahaan AS b1 ON b1.ID = so1.id_perusahaan
                          LEFT JOIN surat_keputusan AS sk1 ON sk1.ID = op1.id_surat_keputusan
                          LEFT JOIN jenis_jabatan AS jj1 ON jj1.ID = so1.id_jenis_jabatan 
                          LEFT JOIN LATERAL
                          (
                          SELECT
                            jj2.id_grup_jabatan 
                          FROM
                            organ_perusahaan AS op2
                            LEFT JOIN talenta AS t2 ON t2.ID = op2.id_talenta
                            LEFT JOIN struktur_organ AS so2 ON so2.ID = op2.id_struktur_organ
                            LEFT JOIN jenis_jabatan AS jj2 ON jj2.ID = so2.id_jenis_jabatan 
                          WHERE
                            t2.nama_lengkap = t1.nama_lengkap 
                            AND now( ) <= op2.tanggal_akhir 
                            AND op2.aktif = TRUE 
                            AND so2.aktif = TRUE 
                          ORDER BY
                            op2.tanggal_awal ASC 
                            LIMIT 1 
                          ) AS jenis_rangkap ON TRUE 
                        WHERE
                          now( ) <= op1.tanggal_akhir 
                          AND op1.aktif = TRUE 
                          AND so1.aktif = TRUE 
                        ORDER BY
                          t1.nama_lengkap 
                        ) AS v_rangkap 
                      WHERE
                        jenis_rangkap = 4";
          $query_rangkap4 = DB::select(DB::raw($str_rangkap4));

          $str_kosong1 = "SELECT struktur_organ.aktif,
                          perusahaan.ID,
                          perusahaan.nama_lengkap AS perusahaan,
                          struktur_organ.nomenklatur_jabatan AS nama_jabatan,
                          organ_perusahaan.tanggal_akhir 
                        FROM
                          struktur_organ
                        LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                        LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
                        LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
                        WHERE (struktur_organ.aktif = false OR organ_perusahaan.tanggal_akhir < now( ))
                        AND perusahaan.level = $id_filter 
                        AND jenis_jabatan.id_grup_jabatan  = 1 ";

          $query_kosong1 = DB::select(DB::raw($str_kosong1));
          
          $str_kosong4 = "SELECT struktur_organ.aktif,
                          perusahaan.ID,
                          perusahaan.nama_lengkap AS perusahaan,
                          struktur_organ.nomenklatur_jabatan AS nama_jabatan,
                          organ_perusahaan.tanggal_akhir 
                        FROM
                          struktur_organ
                        LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                        LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
                        LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
                        WHERE (struktur_organ.aktif = false OR organ_perusahaan.tanggal_akhir < now( ))
                        AND perusahaan.level = $id_filter 
                        AND jenis_jabatan.id_grup_jabatan  = 4 ";

          $query_kosong4 = DB::select(DB::raw($str_kosong4));

          $series = [
            [
              'id' => 1,
              'name' => 'Direksi',
              'data' => [
                      
                      $dir1,
                      $dir2,
                      $dir3,
                      $query_rangkap1[0]->jml_rangkap,
                      count($query_kosong1)

                    ]
            ],
            [
              'id' => 4,
              'name' => 'Komisaris',
              'data' => [
                      $kom1,
                      $kom2,
                      $kom3,
                      $query_rangkap4[0]->jml_rangkap,
                      count($query_kosong4)
                    ]
            ]
          ];

          array_push($json,$series);
          array_push($json,$title);
          array_push($json,$subtitle);
          return response()->json($json);
        }catch(\Exception $e){
          $json = [];
          array_push($json,[]);
          array_push($json,[]);
          array_push($json,[]);
          return response()->json($json);
        }
    }

    public function gettabledetail(Request $request, $id, $kategori, $id_filter)
    {
        try{
          $jabatan = GrupJabatan::find($id);
          $kategori_text = '';
          $tableview = '';
          switch ((int)$kategori) {
                 case 1: $kategori_text = '< 3 Bulan'; 
                          $tableview = 'tabletigabln';
                          break;
                 case 2: $kategori_text = '< 6 Bulan'; 
                          $tableview = 'tableenambln';
                          break;
                 case 3: $kategori_text = 'Expired'; 
                          $tableview = 'tableexpired';
                          break;
                 case 4: $kategori_text = 'Rangkap'; 
                          $tableview = 'tablerangkap';
                          break;
                 case 5: $kategori_text = 'Kosong'; 
                          $tableview = 'tablekosong';
                          break;
          }
          return view($this->__route.'.'.$tableview,[
            'panel' => $id == 1? 'bg-primary' : 'bg-success',
            'title' => 'Detail Masa Jabatan <strong>'.ucfirst($jabatan->nama).'</strong> dengan waktu masa jabatan <strong>'.$kategori_text.'</strong>',
            'id' => $id,
            'id_filter' => $request->id_filter
          ]);
        }catch(\Exception $e){}

    }

    public function datatabletigabln(Request $request,$id)
    {
        try{

          $id_filter = 0;

          if ($request->id_filter)
            $id_filter = $request->id_filter;

          $query = DB::table('struktur_organ')
                   ->select(['talenta.nama_lengkap as pejabat', 'perusahaan.nama_lengkap AS perusahaan',  'struktur_organ.nomenklatur_jabatan as nama_jabatan', 'surat_keputusan.nomor', 'organ_perusahaan.tanggal_awal', 'organ_perusahaan.tanggal_akhir'])
                   ->leftJoin('organ_perusahaan','organ_perusahaan.id_struktur_organ','=','struktur_organ.id')
                   ->leftJoin('talenta','talenta.id','=','organ_perusahaan.id_talenta')
                   ->leftJoin('perusahaan','perusahaan.id','=','struktur_organ.id_perusahaan')
                   ->leftJoin('surat_keputusan','surat_keputusan.id','=','organ_perusahaan.id_surat_keputusan')
                   ->where('surat_keputusan.id_grup_jabatan', (int)$id)
                   ->where('struktur_organ.aktif',true)
                   ->where('organ_perusahaan.aktif',true)
                   ->where('perusahaan.level',$id_filter)
                   ->where('organ_perusahaan.tanggal_akhir','>',Carbon::now()->format('Y-m-d'))
                   ->where('organ_perusahaan.tanggal_akhir','<',Carbon::now()->addday('90')->format('Y-m-d'))
                   ->orderBy('perusahaan.id','ASC')
                   ->orderBy('struktur_organ.urut','ASC')
                   ->get();

           return datatables()->of($query)
                 ->editColumn('pejabat', function($row){
                           return '<strong>'.$row->pejabat.'<strong>';
                 })
                 ->editColumn('tanggal_awal', function ($row) {
                     return ($row->tanggal_awal != '')? Carbon::createFromFormat('Y-m-d', $row->tanggal_awal)->formatLocalized('%d %B %Y') : '&nbsp;';
                 })
                 ->editColumn('tanggal_akhir', function ($row) {
                     return '<span class="label label-danger">'.($row->tanggal_akhir != ''? Carbon::createFromFormat('Y-m-d', $row->tanggal_akhir)->formatLocalized('%d %B %Y') : '&nbsp;').'</span>';
                 })
                 ->addColumn('perusahaan', function($row){
                           return $row->perusahaan != ''? '<strong>'.$row->perusahaan.'</strong>' : '';
                 })
                 ->addColumn('sisa_masa_jabatan', function($row){
                    return Carbon::parse($row->tanggal_akhir)->diffForHumans(Carbon::now(),true);
                 })
                 ->filter(function ($instance) use ($request) {
                    if (!empty($request->search['value'])) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['perusahaan']), Str::lower($request->search['value']))){
                                return true;
                            }else if (Str::contains(Str::lower($row['pejabat']), Str::lower($request->search['value']))){
                                return true;
                            }else if (Str::contains(Str::lower($row['nama_jabatan']), Str::lower($request->search['value']))){
                                return true;
                            }else if (Str::contains(Str::lower($row['nomor']), Str::lower($request->search['value']))){
                                return true;
                            }else if (Str::contains(Str::lower($row['sisa_masa_jabatan']), Str::lower($request->search['value']))) {
                                return true;
                            }
                            return false;
                        });
                    }
                 })
                 ->rawColumns(['perusahaan', 'pejabat', 'nama_pejabat', 'nomor', 'tanggal_awal', 'tanggal_akhir', 'nama_jabatan', 'periode', 'instansi', 'keterangan'])
                 ->make(true);
        }catch(\Exception $e){
          return response([
              'draw'            => 0,
              'recordsTotal'    => 0,
              'recordsFiltered' => 0,
              'data'            => []
          ]);
        }
    }

    public function datatableenambln(Request $request,$id)
    {
        try{

          $id_filter = 0;

          if ($request->id_filter)
            $id_filter = $request->id_filter;

          $query = DB::table('struktur_organ')
            ->select(['talenta.nama_lengkap as pejabat', 'perusahaan.nama_lengkap AS perusahaan',  'struktur_organ.nomenklatur_jabatan as nama_jabatan', 'surat_keputusan.nomor', 'organ_perusahaan.tanggal_awal', 'organ_perusahaan.tanggal_akhir'])
            ->leftJoin('organ_perusahaan','organ_perusahaan.id_struktur_organ','=','struktur_organ.id')
            ->leftJoin('talenta','talenta.id','=','organ_perusahaan.id_talenta')
            ->leftJoin('perusahaan','perusahaan.id','=','struktur_organ.id_perusahaan')
            ->leftJoin('surat_keputusan','surat_keputusan.id','=','organ_perusahaan.id_surat_keputusan')
            ->where('surat_keputusan.id_grup_jabatan', (int)$id)
            ->where('struktur_organ.aktif',true)
            ->where('organ_perusahaan.aktif',true)
            ->where('perusahaan.level',$id_filter)
            ->where('organ_perusahaan.tanggal_akhir','>',Carbon::now()->addday('90')->format('Y-m-d'))
            ->where('organ_perusahaan.tanggal_akhir','<',Carbon::now()->addday('180')->format('Y-m-d'))
            ->orderBy('perusahaan.id','ASC')
            ->orderBy('struktur_organ.urut','ASC')
            ->get();

           return datatables()->of($query)
                 ->editColumn('pejabat', function($row){
                           return '<strong>'.$row->pejabat.'<strong>';
                 })
                 ->editColumn('tanggal_awal', function ($row) {
                     return ($row->tanggal_awal != '')? Carbon::createFromFormat('Y-m-d', $row->tanggal_awal)->formatLocalized('%d %B %Y') : '&nbsp;';
                 })
                 ->editColumn('tanggal_akhir', function ($row) {
                     return '<span class="label label-danger">'.($row->tanggal_akhir != ''? Carbon::createFromFormat('Y-m-d', $row->tanggal_akhir)->formatLocalized('%d %B %Y') : '&nbsp;').'</span>';
                 })
                 ->addColumn('perusahaan', function($row){
                           return $row->perusahaan != ''? '<strong>'.$row->perusahaan.'</strong>' : '';
                 })
                 ->addColumn('sisa_masa_jabatan', function($row){
                    return Carbon::parse($row->tanggal_akhir)->diffForHumans(Carbon::now(),true);
                 })
                 ->filter(function ($instance) use ($request) {
                    if (!empty($request->search['value'])) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['perusahaan']), Str::lower($request->search['value']))){
                                return true;
                            }else if (Str::contains(Str::lower($row['pejabat']), Str::lower($request->search['value']))){
                                return true;
                            }else if (Str::contains(Str::lower($row['nama_jabatan']), Str::lower($request->search['value']))){
                                return true;
                            }else if (Str::contains(Str::lower($row['nomor']), Str::lower($request->search['value']))){
                                return true;
                            }else if (Str::contains(Str::lower($row['sisa_masa_jabatan']), Str::lower($request->search['value']))) {
                                return true;
                            }
                            return false;
                        });
                    }
                 })
                 ->rawColumns(['perusahaan', 'pejabat', 'nama_pejabat', 'nomor', 'tanggal_awal', 'tanggal_akhir', 'nama_jabatan', 'periode', 'instansi', 'keterangan'])
                 ->make(true);
        }catch(\Exception $e){
          return response([
              'draw'            => 0,
              'recordsTotal'    => 0,
              'recordsFiltered' => 0,
              'data'            => []
          ]);
        }
    }

    public function datatableexpired(Request $request, $id)
    {
        try{

          $id_filter = 0;

          if ($request->id_filter)
            $id_filter = $request->id_filter;

          $query = DB::table('struktur_organ')
            ->select(['talenta.nama_lengkap as pejabat', 'perusahaan.nama_lengkap AS perusahaan',  'struktur_organ.nomenklatur_jabatan as nama_jabatan', 'surat_keputusan.nomor', 'organ_perusahaan.tanggal_awal', 'organ_perusahaan.tanggal_akhir'])
            ->leftJoin('organ_perusahaan','organ_perusahaan.id_struktur_organ','=','struktur_organ.id')
            ->leftJoin('talenta','talenta.id','=','organ_perusahaan.id_talenta')
            ->leftJoin('perusahaan','perusahaan.id','=','struktur_organ.id_perusahaan')
            ->leftJoin('surat_keputusan','surat_keputusan.id','=','organ_perusahaan.id_surat_keputusan')
            ->where('surat_keputusan.id_grup_jabatan', (int)$id)
            ->where('struktur_organ.aktif',true)
            ->where('organ_perusahaan.aktif',true)
            ->where('perusahaan.level',$id_filter)
            ->where('organ_perusahaan.tanggal_akhir','<', Carbon::now()->format('Y-m-d'))
            ->orderBy('perusahaan.id','ASC')
            ->orderBy('struktur_organ.urut','ASC')
            ->get();

           return datatables()->of($query)
                 ->editColumn('pejabat', function($row){
                           return '<strong>'.$row->pejabat.'<strong>';
                 })
                 ->editColumn('tanggal_awal', function ($row) {
                     return ($row->tanggal_awal != '')? Carbon::createFromFormat('Y-m-d', $row->tanggal_awal)->formatLocalized('%d %B %Y') : '&nbsp;';
                 })
                 ->editColumn('tanggal_akhir', function ($row) {
                     return '<span class="label label-danger">'.($row->tanggal_akhir != ''? Carbon::createFromFormat('Y-m-d', $row->tanggal_akhir)->formatLocalized('%d %B %Y') : '&nbsp;').'</span>';
                 })
                 ->addColumn('perusahaan', function($row){
                           return $row->perusahaan != ''? '<strong>'.$row->perusahaan.'</strong>' : '';
                 })
                 ->addColumn('lama_expired', function($row){
                    return Carbon::parse($row->tanggal_akhir)->diffForHumans(Carbon::now(),true);
                 })
                 ->filter(function ($instance) use ($request) {
                    if (!empty($request->search['value'])) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['perusahaan']), Str::lower($request->search['value']))){
                                return true;
                            }else if (Str::contains(Str::lower($row['pejabat']), Str::lower($request->search['value']))){
                                return true;
                            }else if (Str::contains(Str::lower($row['nama_jabatan']), Str::lower($request->search['value']))){
                                return true;
                            }else if (Str::contains(Str::lower($row['nomor']), Str::lower($request->search['value']))){
                                return true;
                            }else if (Str::contains(Str::lower($row['lama_expired']), Str::lower($request->search['value']))) {
                                return true;
                            }
                            return false;
                        });
                    }
                 })
                 ->rawColumns(['perusahaan', 'pejabat', 'nama_pejabat', 'nomor', 'tanggal_awal', 'tanggal_akhir', 'nama_jabatan', 'periode', 'instansi', 'keterangan'])
                 ->make(true);
        }catch(\Exception $e){
          return response([
              'draw'            => 0,
              'recordsTotal'    => 0,
              'recordsFiltered' => 0,
              'data'            => []
          ]);
        }
    }

    public function datatablekosong(Request $request, $id, $id_filter)
    {
        try{
          $query_str = "SELECT struktur_organ.aktif,
                          perusahaan.ID,
                          perusahaan.nama_lengkap AS perusahaan,
                          struktur_organ.nomenklatur_jabatan AS nama_jabatan,
                          organ_perusahaan.tanggal_akhir 
                        FROM
                          struktur_organ
                        LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                        LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
                        LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
                        WHERE (struktur_organ.aktif = false OR organ_perusahaan.tanggal_akhir < now( ))
                        AND perusahaan.level = $id_filter 
                        AND jenis_jabatan.id_grup_jabatan  = $id ";

          $query = DB::select(DB::raw($query_str));

           return datatables()->of($query)
                 ->editColumn('tanggal_akhir', function ($row) {
                     return '<span class="label label-danger">'.($row->tanggal_akhir != ''? Carbon::createFromFormat('Y-m-d', $row->tanggal_akhir)->formatLocalized('%d %B %Y') : '&nbsp;').'</span>';
                 })
                 ->addColumn('perusahaan', function($row){
                           return $row->perusahaan != ''? '<strong>'.$row->perusahaan.'</strong>' : '';
                 })
                 ->addColumn('lama_kosong', function($row){
                    if(Carbon::now()>$row->tanggal_akhir){
                      return Carbon::parse($row->tanggal_akhir)->diffForHumans(Carbon::now(),true);
                    }else return '';
                 })
                 ->filter(function ($instance) use ($request) {
                    if (!empty($request->search['value'])) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['perusahaan']), Str::lower($request->search['value']))){
                                return true;
                            }else if (Str::contains(Str::lower($row['nama_jabatan']), Str::lower($request->search['value']))) {
                                return true;
                            }
                            return false;
                        });
                    }
                 })
                 ->rawColumns(['perusahaan', 'pejabat', 'nama_pejabat', 'nomor', 'tanggal_awal', 'tanggal_akhir', 'nama_jabatan', 'periode', 'instansi', 'keterangan'])
                 ->make(true);
        }catch(\Exception $e){
          return response([
              'draw'            => 0,
              'recordsTotal'    => 0,
              'recordsFiltered' => 0,
              'data'            => []
          ]);
        }
    }

    public function datatablerangkap(Request $request, $id)
    {
        try{
          $id_filter = 0;

          if ($request->id_filter)
            $id_filter = $request->id_filter;

          $query_str = "SELECT *
                        from (
                        SELECT
                          t1.nama_lengkap AS pejabat,
                          t1.ID AS talenta_id,
                          b1.ID AS bumn_id,
                          b1.nama_lengkap AS perusahaan,
                          so1.ID AS struktur_organ_id,
                          so1.nomenklatur_jabatan AS nama_jabatan,
                          sk1.nomor,
                          op1.tanggal_awal,
                          op1.tanggal_akhir,
                          t1.jenis_kelamin,
                          (select jj2.id_grup_jabatan FROM
                            organ_perusahaan as op2 
                            LEFT JOIN talenta as t2 ON t2.ID = op2.id_talenta
                            LEFT JOIN struktur_organ as so2 ON so2.ID = op2.id_struktur_organ
                            LEFT JOIN jenis_jabatan as jj2 ON jj2.ID = so2.id_jenis_jabatan 
                            where 
                            t2.nama_lengkap = t1.nama_lengkap
                            and now( ) <= op2.tanggal_akhir 
                            AND op2.aktif = TRUE
                            AND so2.aktif = TRUE 
                            order by op2.tanggal_awal ASC
                            limit 1
                            ) as jenis_rangkap
                        FROM
                          (
                          SELECT
                            t3.nama_lengkap
                          FROM
                            organ_perusahaan as op3
                            LEFT JOIN talenta as t3  ON t3.ID = op3.id_talenta
                            LEFT JOIN struktur_organ as so3 ON so3.ID = op3.id_struktur_organ
                            LEFT JOIN perusahaan as b3 ON b3.ID = so3.id_perusahaan
                            LEFT JOIN jenis_jabatan as jj3 ON jj3.ID = so3.id_jenis_jabatan 
                          WHERE
                            now( ) <= op3.tanggal_akhir 
                            AND op3.aktif = TRUE
                            AND so3.aktif = TRUE
                          GROUP BY
                            t3.nama_lengkap 
                          HAVING
                            NOT (
                              (
                                COUNT ( DISTINCT jj3.id_grup_jabatan ) > 1 
                                AND ARRAY_AGG ( b3.induk :: smallint ) && ARRAY_AGG ( b3.ID ) 
                              ) 
                              OR (
                                COUNT ( DISTINCT b3.ID ) <= 1 
                                AND COUNT ( DISTINCT jj3.id_grup_jabatan ) <= 1 
                              ) 
                            )
                            AND ARRAY_AGG ( b3.level ) && ARRAY_AGG ( $id_filter )  
                          ) AS rangkap
                          LEFT JOIN talenta as t1 ON t1.nama_lengkap = rangkap.nama_lengkap
                          LEFT JOIN organ_perusahaan as op1 ON t1.ID = op1.id_talenta
                          LEFT JOIN struktur_organ as so1 ON so1.ID = op1.id_struktur_organ
                          LEFT JOIN perusahaan as b1 ON b1.ID = so1.id_perusahaan
                          LEFT JOIN surat_keputusan as sk1 ON sk1.ID = op1.id_surat_keputusan
                          LEFT JOIN jenis_jabatan as jj1 ON jj1.ID = so1.id_jenis_jabatan 
                          WHERE
                            now( ) <= op1.tanggal_akhir 
                            AND op1.aktif = TRUE
                            AND so1.aktif = TRUE 
                        ORDER BY
                          t1.nama_lengkap
                          ) as v_rangkap 
                          WHERE
                          jenis_rangkap = $id";

          $queris = DB::select(DB::raw($query_str));

           return datatables()->of($queris)
                 ->editColumn('pejabat', function($row){
                           return '<strong>'.$row->pejabat.'<strong>';
                 })
                 ->editColumn('tanggal_awal', function ($row) {
                     return ($row->tanggal_awal != '')? Carbon::createFromFormat('Y-m-d', $row->tanggal_awal)->formatLocalized('%d %B %Y') : '&nbsp;';
                 })
                 ->editColumn('tanggal_akhir', function ($row) {
                     return '<span class="label label-danger">'.($row->tanggal_akhir != ''? Carbon::createFromFormat('Y-m-d', $row->tanggal_akhir)->formatLocalized('%d %B %Y') : '&nbsp;').'</span>';
                 })
                 ->addColumn('perusahaan', function($row){
                           return $row->perusahaan != ''? '<strong>'.$row->perusahaan.'</strong>' : '';
                 })
                 ->filter(function ($instance) use ($request) {
                    if (!empty($request->search['value'])) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['pejabat']), Str::lower($request->search['value']))){
                                return true;
                            }
                            // else if (Str::contains(Str::lower($row['perusahaan']), Str::lower($request->search['value']))) {
                            //     return true;
                            // }
                            return false;
                        });
                    }
                 })
                 ->rawColumns(['pejabat', 'tanggal_awal', 'tanggal_akhir', 'perusahaan' ])
                 ->make(true);
        }catch(\Exception $e){
          return response([
              'draw'            => 0,
              'recordsTotal'    => 0,
              'recordsFiltered' => 0,
              'data'            => $e->getMessage()
          ]);
        }
    }

    public function chartkontribusi(Request $request)
    {
        try{
          $json = [];
          $data = DB::select("select instansi_baru.nama as instansi, count (instansi_baru.nama) as jumlah
                              FROM view_organ_perusahaan
                              LEFT JOIN talenta on talenta.id = view_organ_perusahaan.id_talenta 
                              LEFT JOIN instansi_baru on instansi_baru.id = talenta.id_asal_instansi
                              LEFT JOIN lateral (select s.nomenklatur_jabatan, p.nama_lengkap, v.tanggal_awal, v.tanggal_akhir, v.id_surat_keputusan, v.id_struktur_organ
                              from view_organ_perusahaan v
                              left join struktur_organ s on v.id_struktur_organ = s.id
                              left join perusahaan p on p.id = s.id_perusahaan
                              where v.id_talenta = talenta.id 
                              and v.aktif = 't'
                              order by s.urut ASC 
                              limit 1) jabatan on TRUE
                              WHERE instansi_baru.id_jenis_asal_instansi in (2, 3) 
                              and view_organ_perusahaan.aktif = true 
                              and (view_organ_perusahaan.tanggal_akhir >= now( ) or view_organ_perusahaan.tanggal_akhir is null)
                              and jabatan.id_struktur_organ = view_organ_perusahaan.id_struktur_organ
                              group by instansi_baru.nama
                              order by jumlah desc
                              limit 10");

          for($i=0; $i<count($data); $i++){
            $row = [];
            array_push($row, $data[$i]->instansi);
            array_push($row, $data[$i]->jumlah);
            array_push($json,$row);
          }
          
          return response()->json($json);
        }catch(\Exception $e){
          $json = [];
          return response()->json($json);
        }
    }
    
  public function gettablekontribusi(Request $request)
  {
      try{
        return view($this->__route.'.tablekontribusi',[
          'panel' => 'bg-primary',
          'title' => 'Detail Kontribusi Dekom dari Kementerian Lembaga',
          'idx' => $request->idx
        ]);
      }catch(\Exception $e){}

  }
    
  public function gettableperusahaan(Request $request)
  {
      try{
        return view($this->__route.'.tableperusahaan',[
          'panel' => 'bg-primary',
          'title' => 'Informasi Direksi Komisaris',
          'id' => $request->id
        ]);
      }catch(\Exception $e){}

  } 

  public function gettabletalenta(Request $request)
  {
    $talenta = DB::table('view_organ_perusahaan')
    ->leftJoin('talenta', 'talenta.id', '=', 'view_organ_perusahaan.id_talenta')
    ->leftjoin('riwayat_jabatan_dirkomwas', function($query){
        $query->on('riwayat_jabatan_dirkomwas.id_talenta', '=', 'talenta.id')
        ->whereNull('riwayat_jabatan_dirkomwas.tanggal_akhir');
    })
    ->leftJoin('struktur_organ', 'struktur_organ.id', '=', 'view_organ_perusahaan.id_struktur_organ')
    ->leftJoin('perusahaan', 'perusahaan.id', '=', 'struktur_organ.id_perusahaan')
    ->leftJoin('jenis_asal_instansi', 'jenis_asal_instansi.id', '=', 'talenta.id_jenis_asal_instansi')
    ->leftJoin('instansi_baru','instansi_baru.id','=','talenta.id_asal_instansi')
    ->select(DB::raw("talenta.*,
                     perusahaan.nama_lengkap as nama_perusahaan,
                     jenis_asal_instansi.nama as jenis_asal_instansi,
                     instansi_baru.nama as instansi,
                     struktur_organ.nomenklatur_jabatan as jabatan"))
    ->where('view_organ_perusahaan.aktif', '=', 't')
    ->where('talenta.id', $request->id)->first();
    
    $jabatan = RiwayatJabatanDirkomwas::select('*')->where('id_talenta', $request->id)->orderBy("tanggal_akhir", 'desc')->orderBy("tanggal_awal", 'desc')->get();

      try{
        return view($this->__route.'.tabletalenta',[
          'panel' => 'bg-primary',
          'title' => 'Histori Pejabat',
          'talenta' => $talenta,
          'id' => $request->id,
          'jabatan' => $jabatan
        ]);
      }catch(\Exception $e){}
  }
  
  public function datatablekontribusi(Request $request)
  {
      try{
        $idx = $request->idx;
        $data = DB::select("select instansi_baru.nama as instansi, count (instansi_baru.nama) as jumlah
                            FROM view_organ_perusahaan
                            LEFT JOIN talenta on talenta.id = view_organ_perusahaan.id_talenta 
                            LEFT JOIN instansi_baru on instansi_baru.id = talenta.id_asal_instansi
                            LEFT JOIN lateral (select s.nomenklatur_jabatan, p.nama_lengkap, v.tanggal_awal, v.tanggal_akhir, v.id_surat_keputusan, v.id_struktur_organ
                            from view_organ_perusahaan v
                            left join struktur_organ s on v.id_struktur_organ = s.id
                            left join perusahaan p on p.id = s.id_perusahaan
                            where v.id_talenta = talenta.id 
                            and v.aktif = 't'
                            order by s.urut ASC 
                            limit 1) jabatan on TRUE
                            WHERE instansi_baru.id_jenis_asal_instansi in (2, 3) 
                            and view_organ_perusahaan.aktif = true 
                            and (view_organ_perusahaan.tanggal_akhir >= now( ) or view_organ_perusahaan.tanggal_akhir is null)
                            and jabatan.id_struktur_organ = view_organ_perusahaan.id_struktur_organ
                            group by instansi_baru.nama
                            order by jumlah desc
                            limit 10");
        $nama = $data[$idx]->instansi;

        $query = DB::table('view_organ_perusahaan')
                 ->select(['talenta.nama_lengkap as pejabat', 'jabatan.nama_lengkap AS perusahaan', 'jabatan.nomenklatur_jabatan as nama_jabatan', 'jabatan.tanggal_awal', 'jabatan.tanggal_akhir', 'instansi_baru.nama AS instansi', 'surat_keputusan.nomor'])
                 ->leftJoin('talenta','talenta.id','=','view_organ_perusahaan.id_talenta')
                 ->leftJoin('instansi_baru','instansi_baru.id','=','talenta.id_asal_instansi')
                 ->leftJoin(DB::raw("lateral (select s.nomenklatur_jabatan, p.nama_lengkap, v.tanggal_awal, v.tanggal_akhir, v.id_surat_keputusan, v.id_struktur_organ
                                 from view_organ_perusahaan v
                                 left join struktur_organ s on v.id_struktur_organ = s.id
                                 left join perusahaan p on p.id = s.id_perusahaan
                                 where v.id_talenta = talenta.id 
                                 and v.aktif = 't'
                                 order by s.urut ASC 
                                 limit 1) jabatan"), 'talenta.id', '=', 'talenta.id')
                 ->leftJoin('surat_keputusan','surat_keputusan.id','=','jabatan.id_surat_keputusan')
                 ->where('instansi_baru.nama',$nama)
                 ->where('view_organ_perusahaan.aktif','true')
                 ->whereRaw('(view_organ_perusahaan.tanggal_akhir >= now( ) or view_organ_perusahaan.tanggal_akhir is null)')
                 ->GroupBy('talenta.nama_lengkap', 'jabatan.nama_lengkap', 'jabatan.nomenklatur_jabatan', 'jabatan.tanggal_awal', 'jabatan.tanggal_akhir', 'instansi_baru.nama', 'surat_keputusan.nomor')
                 ->orderBy('talenta.nama_lengkap','ASC')
                 ->get();

         return datatables()->of($query)
               ->editColumn('tanggal_awal', function ($row) {
                   return ($row->tanggal_awal != '')? Carbon::createFromFormat('Y-m-d', $row->tanggal_awal)->formatLocalized('%d %B %Y') : '&nbsp;';
               })
               ->editColumn('tanggal_akhir', function ($row) {
                   return ($row->tanggal_akhir != ''? Carbon::createFromFormat('Y-m-d', $row->tanggal_akhir)->formatLocalized('%d %B %Y') : '&nbsp;');
               })
               ->filter(function ($instance) use ($request) {
                  if (!empty($request->search['value'])) {
                      $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                          if (Str::contains(Str::lower($row['perusahaan']), Str::lower($request->search['value']))){
                              return true;
                          }else if (Str::contains(Str::lower($row['pejabat']), Str::lower($request->search['value']))){
                              return true;
                          }else if (Str::contains(Str::lower($row['nama_jabatan']), Str::lower($request->search['value']))){
                              return true;
                          }else if (Str::contains(Str::lower($row['nomor']), Str::lower($request->search['value']))){
                            return true;
                          }else if (Str::contains(Str::lower($row['instansi']), Str::lower($request->search['value']))){
                            return true;
                          }
                          return false;
                      });
                  }
               })
               ->make(true);
      }catch(\Exception $e){
        return response([
            'draw'            => 0,
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
        ]);
      }
  }
  
  public function datatableperusahaan(Request $request)
  {
    try{
          $id_sql = "SELECT
                        perusahaan.ID,
                      CASE
                          
                          WHEN ( view_organ_perusahaan.tanggal_akhir < CURRENT_DATE ) THEN
                        TRUE ELSE FALSE 
                        END AS expire,
                      CASE
                          
                          WHEN ( view_organ_perusahaan.tanggal_akhir < CURRENT_DATE - INTERVAL '3 months ago' ) THEN
                        TRUE ELSE FALSE 
                        END AS kurang3,
                      CASE
                          
                          WHEN ( view_organ_perusahaan.tanggal_akhir < CURRENT_DATE - INTERVAL '6 months ago' ) THEN
                        TRUE ELSE FALSE 
                        END AS kurang6,
                      CASE
                          
                          WHEN view_organ_perusahaan.aktif = 't' THEN
                          talenta.nama_lengkap ELSE talenta.nama_lengkap 
                        END AS pejabat,
                      CASE
                          
                          WHEN view_organ_perusahaan.aktif = 't' THEN
                          'AKTIF' ELSE'TIDAK AKTIF' 
                        END AS aktifpejabat,
                        perusahaan.nama_lengkap AS bumns,
                        grup_jabatan.ID AS grup_jabat_id,
                        grup_jabatan.nama AS grup_jabat_nama,
                      CASE
                          
                          WHEN view_organ_perusahaan.nomenklatur IS NULL THEN
                          struktur_organ.nomenklatur_jabatan ELSE view_organ_perusahaan.nomenklatur 
                        END AS nama_jabatan,
                        surat_keputusan.nomor,
                        surat_keputusan.tanggal_sk,
                        view_organ_perusahaan.tanggal_awal,
                        view_organ_perusahaan.tanggal_akhir,
                        view_organ_perusahaan.plt,
                        view_organ_perusahaan.komisaris_independen,
                        instansi_baru.nama AS instansi,
                        jenis_asal_instansi.nama AS asal_instansi,
                        view_organ_perusahaan.id_periode_jabatan AS periode,
                        struktur_organ.ID AS struktur_id 
                      FROM
                        view_organ_perusahaan
                        LEFT JOIN talenta ON talenta.ID = view_organ_perusahaan.id_talenta
                        LEFT JOIN struktur_organ ON struktur_organ.ID = view_organ_perusahaan.id_struktur_organ
                        LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
                        LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                        LEFT JOIN surat_keputusan ON surat_keputusan.ID = view_organ_perusahaan.id_surat_keputusan
                        LEFT JOIN instansi_baru ON instansi_baru.ID = talenta.id_asal_instansi
                        LEFT JOIN jenis_asal_instansi ON jenis_asal_instansi.ID = instansi_baru.id_jenis_asal_instansi
                        LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                        LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.
                        ID LEFT JOIN sk_kom_independen ON sk_kom_independen.id_struktur_organ = struktur_organ.ID 
                      WHERE
                        surat_keputusan.save = 't' and struktur_organ.aktif = 't' and perusahaan.id = $request->id
                      ORDER BY
                        perusahaan.ID ASC,
                        grup_jabatan.ID ASC,
                        struktur_organ.urut ASC";
        
        $isiadmin  = DB::select(DB::raw($id_sql));
        $collections = new Collection;
        foreach($isiadmin as $val){

            $collections->push([

                'id' => $val->id,
                'pejabat' => $val->pejabat,
                'bumns' => $val->bumns,
                'nama' => $val->nama_jabatan,
                'nomor' => $val->nomor,
                'tanggal_awal' => \Carbon\Carbon::createFromFormat('Y-m-d', $val->tanggal_awal)->format('d-m-Y'),
                'tanggal_akhir' => \Carbon\Carbon::createFromFormat('Y-m-d', $val->tanggal_akhir)->format('d-m-Y'),
                'instansi' => $val->instansi,
                'asal_instansi' => $val->asal_instansi,
                'periode' => $val->periode,
                'tanggal_sk' => \Carbon\Carbon::createFromFormat('Y-m-d', $val->tanggal_sk)->format('d-m-Y'),
                'grup_jabat_nama' => $val->grup_jabat_nama,
                'plt' => $val->plt,
                'komisaris_independen' => $val->komisaris_independen,
                'aktifpejabat' => $val->aktifpejabat,
                'expire' => $val->expire,
                'kurang3' => $val->kurang3,
                'kurang6' => $val->kurang6,
            ]);
        }
        return datatables()->of($collections)
        ->editColumn('pejabat', function($row){
                  $html = '';

                  if($row['periode'] == 2){
                    $html_periode = 'kt-badge--danger';
                  } else {
                    $html_periode = 'kt-badge--success';
                  }

                  if($row['expire'] == 't'){
                    $html_expire = '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">E</span>';
                  } elseif ($row['kurang3'] == 't') {
                    $html_expire = '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded"><3</span>';
                  } elseif ($row['kurang6'] == 't') {
                    $html_expire = '<span class="kt-badge kt-badge--primary kt-badge--inline kt-badge--pill kt-badge--rounded"><6</span>';
                  } else {
                    $html_expire = '&nbsp;';
                  }

                  if($row['plt'] == true){
                    $html .= '<b>'.$row['pejabat'].'</b>&nbsp;&nbsp;<span class="kt-badge '.$html_periode.' kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row['periode'].'</span>&nbsp;&nbsp;'.$html_expire.'&nbsp;&nbsp;<span class="kt-badge kt-badge--info kt-badge--inline kt-badge--pill kt-badge--rounded">PLT</span><br/>'.$row['nama'];
                  } elseif ($row['komisaris_independen'] == true) {
                    $html .= '<b>'.$row['pejabat'].'</b>&nbsp;&nbsp;<span class="kt-badge '.$html_periode.' kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row['periode'].'</span>&nbsp;&nbsp;&nbsp;&nbsp;'.$html_expire.'<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Komisaris Independen</span><br/>'.$row['nama'];
                  } else {
                    $html .= '<b>'.$row['pejabat'].'</b>&nbsp;&nbsp;<span class="kt-badge '.$html_periode.' kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row['periode'].'</span>&nbsp;&nbsp;'.$html_expire.'<br/>'.$row['nama'];
                  }
                  
                  return $html;
        })
        ->editColumn('nomor', function($row){
                  $html = '';
                  $html .= '<b>'.$row['nomor'].'</b><br/>'.$row['tanggal_sk'];
                  
                  return $html;
        })
        ->editColumn('grup_jabat_nama', function($row){
                  $html = '';
                  if($row['grup_jabat_nama'] == 'Direksi'){
                    $html .= '<span class="kt-badge kt-badge--primary kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row['grup_jabat_nama'].'</span>';
                  } else {
                    $html .= '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row['grup_jabat_nama'].'</span>';
                  }
                  //$html .= '<b>'.$row['nomor'].'</b><br/>'.$row['tanggal_sk'];
                  
                  return $html;
        })
        ->editColumn('instansi', function($row){
                  $html = '';
                  $html .= '<b>'.$row['asal_instansi'].'</b><br/>'.$row['instansi'];
                  
                  return $html;
        })
        ->filter(function ($instance) use ($request) {
            if ($request->has('pejabataktif')) {
                $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                    return ($row['aktifpejabat'] == $request->pejabataktif) ? true : false;
                });
            }
        })
        ->rawColumns(['bumns','grup_jabat_nama','pejabat','nomor','tanggal_awal', 'tanggal_akhir', 'instansi'])
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

  public function datatabletalenta(Request $request)
  {
      $data = RiwayatJabatanDirkomwas::select('*')->where('id_talenta', $request->id)->orderBy("tanggal_akhir", 'desc')->orderBy("tanggal_awal", 'desc');

      try{
          return datatables()->of($data)
          ->editColumn('jabatan', function ($row){
              return "<b>".$row->jabatan."</b></br><span>".$row->nama_perusahaan."</span>";
          })
          ->editColumn('tanggal_awal', function ($row){
              if($row->tanggal_akhir){
                return CVHelper::tglFormat($row->tanggal_awal, 2)." - ".CVHelper::tglFormat($row->tanggal_akhir, 2);
              }else{                  
                return CVHelper::tglFormat($row->tanggal_awal, 2)." - Saat Ini";
              }
          })
          ->rawColumns(['action','jabatan'])
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

  public function chartinstansi(Request $request)
  {
      try{
        $json = [];
        $chart1 = [];
        $chart2 = [];
        $chart3 = [];

        $data = DB::select("select jenis_asal_instansi.nama as instansi, count (struktur_organ.id) as jumlah
                          FROM struktur_organ
                          LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
                          LEFT JOIN surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
                          LEFT JOIN talenta on talenta.id = organ_perusahaan.id_talenta 
                          LEFT JOIN instansi_baru on instansi_baru.id = talenta.id_asal_instansi
                          LEFT JOIN jenis_asal_instansi on jenis_asal_instansi.id = instansi_baru.id_jenis_asal_instansi
                          WHERE instansi_baru.id_jenis_asal_instansi in (2, 7, 8, 9) 
                          and struktur_organ.aktif = true and organ_perusahaan.aktif = true
                          and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
                          and struktur_organ.id_jenis_jabatan in (1,6,7,10)
                          group by jenis_asal_instansi.nama");

        for($i=0; $i<count($data); $i++){
          $row = [];
          array_push($row, $data[$i]->instansi);
          array_push($row, $data[$i]->jumlah);
          array_push($chart1,$row);
        }
        array_push($json,$chart1);
        
        $data = DB::select("select jenis_asal_instansi.nama as instansi, count (struktur_organ.id) as jumlah
                          FROM struktur_organ
                          LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
                          LEFT JOIN surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
                          LEFT JOIN talenta on talenta.id = organ_perusahaan.id_talenta 
                          LEFT JOIN instansi_baru on instansi_baru.id = talenta.id_asal_instansi
                          LEFT JOIN jenis_asal_instansi on jenis_asal_instansi.id = instansi_baru.id_jenis_asal_instansi
                          WHERE instansi_baru.id_jenis_asal_instansi in (2, 3, 9, 15, 16) 
                          and struktur_organ.aktif = true and organ_perusahaan.aktif = true
                          and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
                          and struktur_organ.id_jenis_jabatan in (3,4,8,9,11,12,13,14,17)
                          group by jenis_asal_instansi.nama");

        for($i=0; $i<count($data); $i++){
          $row = [];
          array_push($row, $data[$i]->instansi);
          array_push($row, $data[$i]->jumlah);
          array_push($chart2,$row);
        }
        array_push($json,$chart2);
        
        $data = DB::select("select jenis_asal_instansi.nama as instansi, 
                            count(data.id_jenis_asal_instansi) as jumlah
                            FROM jenis_asal_instansi 
                            LEFT JOIN (
                              select instansi_baru.id_jenis_asal_instansi
                              FROM struktur_organ
                              LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
                              LEFT JOIN surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
                              LEFT JOIN talenta on talenta.id = organ_perusahaan.id_talenta 
                              LEFT JOIN instansi_baru on instansi_baru.id = talenta.id_asal_instansi
                              WHERE struktur_organ.aktif = true and organ_perusahaan.aktif = true
                              and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
                              and struktur_organ.id_jenis_jabatan in (3,4,8,9,11,12,13,14,17)
                            ) data on data.id_jenis_asal_instansi = jenis_asal_instansi.id
                            where jenis_asal_instansi.id in (11, 12, 13) 
                            group by jenis_asal_instansi.nama, jenis_asal_instansi.id
                            order by jenis_asal_instansi.id");

        for($i=0; $i<count($data); $i++){
          $row = [];
          array_push($row, $data[$i]->instansi);
          array_push($row, $data[$i]->jumlah);
          array_push($chart3,$row);
        }
        array_push($json,$chart3);

        return response()->json($json);
      }catch(\Exception $e){
        $json = [];
        return response()->json($json);
      }
  }

  public function chartdemografi(Request $request)
  {
      try{
        $json = [];
        $chart1 = [];
        $chart2 = [];
        $chart3 = [];

        // GENDER DIREKSI
        $data = DB::select("select SUM(CASE WHEN talenta.jenis_kelamin='L' THEN 1 ELSE 0 END) AS jumlah_l, 
                          SUM(CASE WHEN talenta.jenis_kelamin='P' THEN 1 ELSE 0 END) AS jumlah_p
                          FROM struktur_organ
                          LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
                          LEFT JOIN surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
                          LEFT JOIN talenta on talenta.id = organ_perusahaan.id_talenta 
                          WHERE struktur_organ.aktif = true and organ_perusahaan.aktif = true
                          and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
                          and struktur_organ.id_jenis_jabatan in (1,6,7,10)");

        $row = [];
        array_push($row, 'Laki-Laki');
        array_push($row, $data[0]->jumlah_l);
        array_push($chart1,$row);
        $row2 = [];
        array_push($row2, 'Perempuan');
        array_push($row2, $data[0]->jumlah_p);
        array_push($chart1,$row2);
        array_push($json,$chart1);

        // GENDER KOMISARIS
        $data = DB::select("select SUM(CASE WHEN talenta.jenis_kelamin='L' THEN 1 ELSE 0 END) AS jumlah_l, 
                          SUM(CASE WHEN talenta.jenis_kelamin='P' THEN 1 ELSE 0 END) AS jumlah_p
                          FROM struktur_organ
                          LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
                          LEFT JOIN surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
                          LEFT JOIN talenta on talenta.id = organ_perusahaan.id_talenta 
                          WHERE struktur_organ.aktif = true and organ_perusahaan.aktif = true
                          and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
                          and struktur_organ.id_jenis_jabatan in (3,4,8,9,11,12,13,14,17)");

        $row = [];
        array_push($row, 'Laki-Laki');
        array_push($row, $data[0]->jumlah_l);
        array_push($chart2,$row);
        $row2 = [];
        array_push($row2, 'Perempuan');
        array_push($row2, $data[0]->jumlah_p);
        array_push($chart2,$row2);
        array_push($json,$chart2);

        // USIA
        $data_dir = DB::select("select SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=30 AND date_part('year',age(talenta.tanggal_lahir)) <40 THEN 1 ELSE 0 END) AS jumlah_30, 
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=40 AND date_part('year',age(talenta.tanggal_lahir)) <50 THEN 1 ELSE 0 END) AS jumlah_40,
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=50 AND date_part('year',age(talenta.tanggal_lahir)) <60 THEN 1 ELSE 0 END) AS jumlah_50,
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=60 AND date_part('year',age(talenta.tanggal_lahir)) <70 THEN 1 ELSE 0 END) AS jumlah_60,
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=70 AND date_part('year',age(talenta.tanggal_lahir)) <80 THEN 1 ELSE 0 END) AS jumlah_70
        FROM struktur_organ
        LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
        LEFT JOIN surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
        LEFT JOIN talenta on talenta.id = organ_perusahaan.id_talenta 
        WHERE struktur_organ.aktif = true and organ_perusahaan.aktif = true
        and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
        and struktur_organ.id_jenis_jabatan in (1,6,7,10)");

        $data_kom = DB::select("select SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=30 AND date_part('year',age(talenta.tanggal_lahir)) <40 THEN 1 ELSE 0 END) AS jumlah_30, 
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=40 AND date_part('year',age(talenta.tanggal_lahir)) <50 THEN 1 ELSE 0 END) AS jumlah_40,
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=50 AND date_part('year',age(talenta.tanggal_lahir)) <60 THEN 1 ELSE 0 END) AS jumlah_50,
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=60 AND date_part('year',age(talenta.tanggal_lahir)) <70 THEN 1 ELSE 0 END) AS jumlah_60,
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=70 AND date_part('year',age(talenta.tanggal_lahir)) <80 THEN 1 ELSE 0 END) AS jumlah_70
        FROM struktur_organ
        LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
        LEFT JOIN surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
        LEFT JOIN talenta on talenta.id = organ_perusahaan.id_talenta 
        WHERE struktur_organ.aktif = true and organ_perusahaan.aktif = true
        and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
        and struktur_organ.id_jenis_jabatan in (3,4,8,9,11,12,13,14,17)");
        $series = [
          [
            'id' => 1,
            'name' => 'Direksi',
            'data' => [
                    $data_dir[0]->jumlah_30,
                    $data_dir[0]->jumlah_40,
                    $data_dir[0]->jumlah_50,
                    $data_dir[0]->jumlah_60,
                    $data_dir[0]->jumlah_70
                  ]
          ],
          [
            'id' => 4,
            'name' => 'Komisaris',
            'data' => [
                    $data_kom[0]->jumlah_30,
                    $data_kom[0]->jumlah_40,
                    $data_kom[0]->jumlah_50,
                    $data_kom[0]->jumlah_60,
                    $data_kom[0]->jumlah_70
                  ]
          ]
        ];
        array_push($json,$series);
        
        // JENJANG PENDIDIKAN
        $data_dir = DB::select("SELECT COALESCE(pendidikan.jumlah, 0) as jumlah, jenjang_pendidikan.nama from jenjang_pendidikan
        left join 
        (select count (jenjang_pendidikan.nama) as jumlah, jenjang_pendidikan.nama, last_pendidikan.urutan from jenjang_pendidikan
        inner join (
          select id_talenta, max(jenjang_pendidikan.urutan) as urutan from riwayat_pendidikan
          left join jenjang_pendidikan on jenjang_pendidikan.id = riwayat_pendidikan.id_jenjang_pendidikan
          GROUP BY
              id_talenta
          ) last_pendidikan on jenjang_pendidikan.urutan = last_pendidikan.urutan
        left join talenta on talenta.id = last_pendidikan.id_talenta
        left join organ_perusahaan on organ_perusahaan.id_talenta = talenta.id
        left join surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
        left join struktur_organ on struktur_organ.id = organ_perusahaan.id_struktur_organ
        where struktur_organ.aktif = true and organ_perusahaan.aktif = true
        and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
        and struktur_organ.id_jenis_jabatan in (1,6,7,10)
        GROUP BY jenjang_pendidikan.nama, last_pendidikan.urutan
         ) pendidikan on jenjang_pendidikan.urutan = pendidikan.urutan
        order by jenjang_pendidikan.urutan");

        $data_kom = DB::select("SELECT COALESCE(pendidikan.jumlah, 0) as jumlah, jenjang_pendidikan.nama from jenjang_pendidikan
        left join 
        (select count (jenjang_pendidikan.nama) as jumlah, jenjang_pendidikan.nama, last_pendidikan.urutan from jenjang_pendidikan
        inner join (
          select id_talenta, max(jenjang_pendidikan.urutan) as urutan from riwayat_pendidikan
          left join jenjang_pendidikan on jenjang_pendidikan.id = riwayat_pendidikan.id_jenjang_pendidikan
          GROUP BY
              id_talenta
          ) last_pendidikan on jenjang_pendidikan.urutan = last_pendidikan.urutan
        left join talenta on talenta.id = last_pendidikan.id_talenta
        left join organ_perusahaan on organ_perusahaan.id_talenta = talenta.id
        left join surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
        left join struktur_organ on struktur_organ.id = organ_perusahaan.id_struktur_organ
        where struktur_organ.aktif = true and organ_perusahaan.aktif = true
        and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
        and struktur_organ.id_jenis_jabatan in (3,4,8,9,11,12,13,14,17)
        GROUP BY jenjang_pendidikan.nama, last_pendidikan.urutan
         ) pendidikan on jenjang_pendidikan.urutan = pendidikan.urutan
        order by jenjang_pendidikan.urutan");
        
        $row_dir = [];
        foreach($data_dir as $data){
          array_push($row_dir, $data->jumlah);
        }
        $row_kom = [];
        foreach($data_kom as $data){
          array_push($row_kom, $data->jumlah);
        }

        $series = [
          [
            'id' => 1,
            'name' => 'Direksi',
            'data' => $row_dir
          ],
          [
            'id' => 4,
            'name' => 'Komisaris',
            'data' => $row_kom
          ]
        ];
        array_push($json,$series);
        
        return response()->json($json);
      }catch(\Exception $e){dd($e);
        $json = [];
        return response()->json($json);
      }
  }

  public function chartdemografijk(Request $request)
  {
      try{
        $json = [];
        $chart1 = [];
        $chart2 = [];

        // GENDER DIREKSI
        $data = DB::select("select SUM(CASE WHEN talenta.jenis_kelamin='L' THEN 1 ELSE 0 END) AS jumlah_l, 
                          SUM(CASE WHEN talenta.jenis_kelamin='P' THEN 1 ELSE 0 END) AS jumlah_p
                          FROM struktur_organ
                          LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
                          LEFT JOIN surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
                          LEFT JOIN talenta on talenta.id = organ_perusahaan.id_talenta 
                          WHERE struktur_organ.aktif = true and organ_perusahaan.aktif = true
                          and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
                          and struktur_organ.id_jenis_jabatan in (1,6,7,10)");

        $row = [];
        array_push($row, 'Laki-Laki');
        array_push($row, $data[0]->jumlah_l);
        array_push($chart1,$row);
        $row2 = [];
        array_push($row2, 'Perempuan');
        array_push($row2, $data[0]->jumlah_p);
        array_push($chart1,$row2);
        array_push($json,$chart1);

        // GENDER KOMISARIS
        $data = DB::select("select SUM(CASE WHEN talenta.jenis_kelamin='L' THEN 1 ELSE 0 END) AS jumlah_l, 
                          SUM(CASE WHEN talenta.jenis_kelamin='P' THEN 1 ELSE 0 END) AS jumlah_p
                          FROM struktur_organ
                          LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
                          LEFT JOIN surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
                          LEFT JOIN talenta on talenta.id = organ_perusahaan.id_talenta 
                          WHERE struktur_organ.aktif = true and organ_perusahaan.aktif = true
                          and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
                          and struktur_organ.id_jenis_jabatan in (3,4,8,9,11,12,13,14,17)");

        $row = [];
        array_push($row, 'Laki-Laki');
        array_push($row, $data[0]->jumlah_l);
        array_push($chart2,$row);
        $row2 = [];
        array_push($row2, 'Perempuan');
        array_push($row2, $data[0]->jumlah_p);
        array_push($chart2,$row2);
        array_push($json,$chart2);
        
        return response()->json($json);
      }catch(\Exception $e){dd($e);
        $json = [];
        return response()->json($json);
      }
  }
  
  public function chartdemografiusia(Request $request)
  {
      try{
        $json = [];

        // USIA
        $data_dir = DB::select("select SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=30 AND date_part('year',age(talenta.tanggal_lahir)) <40 THEN 1 ELSE 0 END) AS jumlah_30, 
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=40 AND date_part('year',age(talenta.tanggal_lahir)) <50 THEN 1 ELSE 0 END) AS jumlah_40,
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=50 AND date_part('year',age(talenta.tanggal_lahir)) <60 THEN 1 ELSE 0 END) AS jumlah_50,
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=60 AND date_part('year',age(talenta.tanggal_lahir)) <70 THEN 1 ELSE 0 END) AS jumlah_60,
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=70 AND date_part('year',age(talenta.tanggal_lahir)) <80 THEN 1 ELSE 0 END) AS jumlah_70
        FROM struktur_organ
        LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
        LEFT JOIN surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
        LEFT JOIN talenta on talenta.id = organ_perusahaan.id_talenta 
        WHERE struktur_organ.aktif = true and organ_perusahaan.aktif = true
        and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
        and struktur_organ.id_jenis_jabatan in (1,6,7,10)");

        $data_kom = DB::select("select SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=30 AND date_part('year',age(talenta.tanggal_lahir)) <40 THEN 1 ELSE 0 END) AS jumlah_30, 
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=40 AND date_part('year',age(talenta.tanggal_lahir)) <50 THEN 1 ELSE 0 END) AS jumlah_40,
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=50 AND date_part('year',age(talenta.tanggal_lahir)) <60 THEN 1 ELSE 0 END) AS jumlah_50,
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=60 AND date_part('year',age(talenta.tanggal_lahir)) <70 THEN 1 ELSE 0 END) AS jumlah_60,
        SUM(CASE WHEN date_part('year',age(talenta.tanggal_lahir))>=70 AND date_part('year',age(talenta.tanggal_lahir)) <80 THEN 1 ELSE 0 END) AS jumlah_70
        FROM struktur_organ
        LEFT JOIN organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
        LEFT JOIN surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
        LEFT JOIN talenta on talenta.id = organ_perusahaan.id_talenta 
        WHERE struktur_organ.aktif = true and organ_perusahaan.aktif = true 
        and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
        and struktur_organ.id_jenis_jabatan in (3,4,8,9,11,12,13,14,17)");
        $series = [
          [
            'id' => 1,
            'name' => 'Direksi',
            'data' => [
                    $data_dir[0]->jumlah_30,
                    $data_dir[0]->jumlah_40,
                    $data_dir[0]->jumlah_50,
                    $data_dir[0]->jumlah_60,
                    $data_dir[0]->jumlah_70
                  ]
          ],
          [
            'id' => 4,
            'name' => 'Komisaris',
            'data' => [
                    $data_kom[0]->jumlah_30,
                    $data_kom[0]->jumlah_40,
                    $data_kom[0]->jumlah_50,
                    $data_kom[0]->jumlah_60,
                    $data_kom[0]->jumlah_70
                  ]
          ]
        ];
        array_push($json,$series);
        
        return response()->json($json);
      }catch(\Exception $e){dd($e);
        $json = [];
        return response()->json($json);
      }
  }
  
  public function chartdemografipendidikan(Request $request)
  {
      try{
        $json = [];

        // JENJANG PENDIDIKAN
        $data_dir = DB::select("SELECT COALESCE(pendidikan.jumlah, 0) as jumlah, jenjang_pendidikan.nama from jenjang_pendidikan
        left join 
        (select count (jenjang_pendidikan.nama) as jumlah, jenjang_pendidikan.nama, last_pendidikan.urutan from jenjang_pendidikan
        inner join (
          select id_talenta, max(jenjang_pendidikan.urutan) as urutan from riwayat_pendidikan
          left join jenjang_pendidikan on jenjang_pendidikan.id = riwayat_pendidikan.id_jenjang_pendidikan
          GROUP BY
              id_talenta
          ) last_pendidikan on jenjang_pendidikan.urutan = last_pendidikan.urutan
        left join talenta on talenta.id = last_pendidikan.id_talenta
        left join organ_perusahaan on organ_perusahaan.id_talenta = talenta.id
        left join surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
        left join struktur_organ on struktur_organ.id = organ_perusahaan.id_struktur_organ
        where struktur_organ.aktif = true and organ_perusahaan.aktif = true
        and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
        and struktur_organ.id_jenis_jabatan in (1,6,7,10)
        GROUP BY jenjang_pendidikan.nama, last_pendidikan.urutan
         ) pendidikan on jenjang_pendidikan.urutan = pendidikan.urutan
        order by jenjang_pendidikan.urutan");

        $data_kom = DB::select("SELECT COALESCE(pendidikan.jumlah, 0) as jumlah, jenjang_pendidikan.nama from jenjang_pendidikan
        left join 
        (select count (jenjang_pendidikan.nama) as jumlah, jenjang_pendidikan.nama, last_pendidikan.urutan from jenjang_pendidikan
        inner join (
          select id_talenta, max(jenjang_pendidikan.urutan) as urutan from riwayat_pendidikan
          left join jenjang_pendidikan on jenjang_pendidikan.id = riwayat_pendidikan.id_jenjang_pendidikan
          GROUP BY
              id_talenta
          ) last_pendidikan on jenjang_pendidikan.urutan = last_pendidikan.urutan
        left join talenta on talenta.id = last_pendidikan.id_talenta
        left join organ_perusahaan on organ_perusahaan.id_talenta = talenta.id
        left join surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
        left join struktur_organ on struktur_organ.id = organ_perusahaan.id_struktur_organ
        where struktur_organ.aktif = true and organ_perusahaan.aktif = true
        and (organ_perusahaan.tanggal_akhir >= now( ) or organ_perusahaan.tanggal_akhir is null)
        and struktur_organ.id_jenis_jabatan in (3,4,8,9,11,12,13,14,17)
        GROUP BY jenjang_pendidikan.nama, last_pendidikan.urutan
         ) pendidikan on jenjang_pendidikan.urutan = pendidikan.urutan
        order by jenjang_pendidikan.urutan");

        
        $row_dir = [];
        foreach($data_dir as $data){
          array_push($row_dir, $data->jumlah);
        }
        $row_kom = [];
        foreach($data_kom as $data){
          array_push($row_kom, $data->jumlah);
        }

        $series = [
          [
            'id' => 1,
            'name' => 'Direksi',
            'data' => $row_dir,
            'color'=> 'red'
          ],
          [
            'id' => 4,
            'name' => 'Komisaris',
            'data' => $row_kom,
            'color'=> 'cyan'
          ]
        ];
        array_push($json,$series);
        
        return response()->json($json);
      }catch(\Exception $e){dd($e);
        $json = [];
        return response()->json($json);
      }
  }

  public function chartjumlah(Request $request)
  {
      try{
        $json = [];

        $where = "";
        if ($request->id_filter==1){
          $where = " and perusahaan.level = 0 and perusahaan.induk = 0 and perusahaan.kepemilikan = 'BUMN' ";
        } else if ($request->id_filter==2){
          $where = " and perusahaan.level != 0 ";
        }

        $data_dir = DB::select("select sum(case when date_part('year', organ_perusahaan.tanggal_akhir) >= 2016  THEN 1 ELSE 0 END) as jumlah_2016,
        sum(case when date_part('year', organ_perusahaan.tanggal_akhir) >= 2017  THEN 1 ELSE 0 END) as jumlah_2017,
        sum(case when date_part('year', organ_perusahaan.tanggal_akhir) >= 2018  THEN 1 ELSE 0 END) as jumlah_2018,
        sum(case when date_part('year', organ_perusahaan.tanggal_akhir) >= 2019  THEN 1 ELSE 0 END) as jumlah_2019,
        sum(case when date_part('year', organ_perusahaan.tanggal_akhir) >= 2020  THEN 1 ELSE 0 END) as jumlah_2020,
        sum(case when date_part('year', organ_perusahaan.tanggal_akhir) >= 2021  THEN 1 ELSE 0 END) as jumlah_2021
        from struktur_organ
        left join organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
        left join talenta on talenta.id = organ_perusahaan.id_talenta
        left join perusahaan on perusahaan.id = struktur_organ.id_perusahaan
        left join surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
        where struktur_organ.id_jenis_jabatan in (1,6,7,10) and organ_perusahaan.aktif = 't' and struktur_organ.aktif = 't' ".$where);

        $data_kom = DB::select("select sum(case when date_part('year', organ_perusahaan.tanggal_akhir) >= 2016  THEN 1 ELSE 0 END) as jumlah_2016,
        sum(case when date_part('year', organ_perusahaan.tanggal_akhir) >= 2017  THEN 1 ELSE 0 END) as jumlah_2017,
        sum(case when date_part('year', organ_perusahaan.tanggal_akhir) >= 2018  THEN 1 ELSE 0 END) as jumlah_2018,
        sum(case when date_part('year', organ_perusahaan.tanggal_akhir) >= 2019  THEN 1 ELSE 0 END) as jumlah_2019,
        sum(case when date_part('year', organ_perusahaan.tanggal_akhir) >= 2020  THEN 1 ELSE 0 END) as jumlah_2020,
        sum(case when date_part('year', organ_perusahaan.tanggal_akhir) >= 2021  THEN 1 ELSE 0 END) as jumlah_2021
        from struktur_organ
        left join organ_perusahaan on organ_perusahaan.id_struktur_organ = struktur_organ.id
        left join talenta on talenta.id = organ_perusahaan.id_talenta
        left join perusahaan on perusahaan.id = struktur_organ.id_perusahaan
        left join surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
        where struktur_organ.id_jenis_jabatan in (3,4,8,9,11,12,13,14,17) and organ_perusahaan.aktif = 't' and struktur_organ.aktif = 't' ".$where);

        $series = [
          [
            'id' => 1,
            'name' => 'Direksi',
            'data' => [
                    $data_dir[0]->jumlah_2016,
                    $data_dir[0]->jumlah_2017,
                    $data_dir[0]->jumlah_2018,
                    $data_dir[0]->jumlah_2019,
                    $data_dir[0]->jumlah_2020,
                    $data_dir[0]->jumlah_2021
                  ]
          ],
          [
            'id' => 4,
            'name' => 'Komisaris',
            'data' => [
                    $data_kom[0]->jumlah_2016,
                    $data_kom[0]->jumlah_2017,
                    $data_kom[0]->jumlah_2018,
                    $data_kom[0]->jumlah_2019,
                    $data_kom[0]->jumlah_2020,
                    $data_kom[0]->jumlah_2021
                  ]
          ]
        ];
        array_push($json,$series);
        
        return response()->json($json);
      }catch(\Exception $e){dd($e);
        $json = [];
        return response()->json($json);
      }
  }
}