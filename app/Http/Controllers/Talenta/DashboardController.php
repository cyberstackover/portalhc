<?php
namespace App\Http\Controllers\Talenta;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Talenta\VerifikasiKbumnController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Talenta;
use App\Perusahaan;
use DB;
use Carbon\Carbon;
use App\User;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Exports\RekapStatusTalent;

class DashboardController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->__route = 'talenta.dashboard';
         $this->__title = "Dashboard";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        activity()->log('Menu Talenta Dashboard');
      

        return view($this->__route.'.index',[
            'pagetitle' => 'Dashboard',
            'perusahaan' => Perusahaan::orderBy('id', 'asc')->where('is_active', true)->get(),
            'talenta' => Talenta::orderBy('nama_lengkap', 'asc')->get(),
            'breadcrumb' => [
                            
            ]
            
        ]);

    }
    
    public function chartjumlahtalenta(Request $request)
    {
        try{
          $json = [];
          $data = DB::select("
                select 	SUM(per_bumn.total_talenta) as total_talenta,
                        SUM(per_bumn.jumlah_added) as jumlah_added,
                        SUM(per_bumn.jumlah_registered) as jumlah_registered,
                        SUM(per_bumn.jumlah_non_talent) as jumlah_non_talent,
                        SUM(per_bumn.jumlah_selected) as jumlah_selected,
                        SUM(per_bumn.jumlah_nominated) as jumlah_nominated,
                        SUM(per_bumn.jumlah_eligible) as jumlah_eligible,
                        SUM(per_bumn.jumlah_qualified) as jumlah_qualified
                FROM
                (
                    SELECT all_data.bumn, 
                            count(all_data.bumn) as total_talenta,
                            SUM(CASE WHEN all_data.id_status_talenta = 1 THEN 1 END) as jumlah_added,
                            SUM(CASE WHEN all_data.id_status_talenta = 2 THEN 1 END) as jumlah_registered,
                            SUM(CASE WHEN all_data.id_status_talenta = 3 THEN 1 END) as jumlah_non_talent,
                            SUM(CASE WHEN all_data.id_status_talenta = 4 THEN 1 END) as jumlah_selected,
                            SUM(CASE WHEN all_data.id_status_talenta = 5 THEN 1 END) as jumlah_nominated,
                            SUM(CASE WHEN all_data.id_status_talenta = 6 OR all_data.id_status_talenta = 7 THEN 1 END) as jumlah_eligible,
                            SUM(CASE WHEN all_data.id_status_talenta = 8 THEN 1 END) as jumlah_qualified
                    FROM 
                    (
                
                        SELECT distinct B.talenta_id, 
                                B.bumn, 
                                B.id_status_talenta
                        FROM 
                        (
                                SELECT  
                                    A.talenta_id,
                                    CASE 
                                      WHEN bumn_induk IS NULL THEN bumn
                                      ELSE bumn_induk
                                    END AS bumn,
                                    A.id_status_talenta
                                FROM 
                                (
                                      
                                    SELECT 
                                        bumn_induk.nama_lengkap as bumn_induk,
                                        CASE 
                                        WHEN bumn_0.level = 0 THEN bumn_0.nama_lengkap 
                                        WHEN bumn_1.level = 0 THEN bumn_1.nama_lengkap 
                                        WHEN bumn_2.level = 0 THEN bumn_2.nama_lengkap 
                                        WHEN bumn_3.level = 0 THEN bumn_3.nama_lengkap 
                                        WHEN bumn_4.level = 0 THEN bumn_4.nama_lengkap 
                                        END AS bumn,
                                        talenta.id_perusahaan,
                                        talenta.id AS talenta_id, 	
                                        talenta.nama_lengkap, 
                                        talenta.id_status_talenta,
                                        jabatan.nomenklatur_jabatan,
                                        jabatan.nama_lengkap as nama_perusahaan,
                                        jabatan.id_perusahaan
                                    FROM talenta
                                    LEFT JOIN perusahaan AS bumn_induk ON bumn_induk.ID = talenta.id_perusahaan
                                    LEFT JOIN lateral (select s.nomenklatur_jabatan, p.nama_lengkap, p.id, skp.nomenklatur_baru, s.id_perusahaan, sk.id_grup_jabatan
                                                from view_organ_perusahaan v
                                                left join struktur_organ s on s.id = v.id_struktur_organ 
                                                LEFT JOIN organ_perusahaan op ON op.id_struktur_organ = s.id
                                                LEFT JOIN surat_keputusan sk ON sk.id = op.id_surat_keputusan
                                                left join perusahaan p on p.id = s.id_perusahaan
                                                LEFT JOIN sk_perubahan_nomenklatur skp ON skp.id_struktur_organ = s.ID
                                                where v.id_talenta = talenta.id 
                                                and v.aktif = 't'
                                                and sk.id_grup_jabatan = 1
                                                and (v.tanggal_akhir >= now( ) 
                                                       or v.tanggal_akhir is null)
                                                order by v.id_struktur_organ ASC, s.urut ASC 
                                                limit 1) jabatan ON TRUE
                                    LEFT JOIN perusahaan AS bumn_0 ON bumn_0.ID = jabatan.id_perusahaan
                                    LEFT JOIN perusahaan AS bumn_1 ON bumn_1.ID = bumn_0.induk
                                    LEFT JOIN perusahaan AS bumn_2 ON bumn_2.ID = bumn_1.induk
                                    LEFT JOIN perusahaan AS bumn_3 ON bumn_3.ID = bumn_2.induk
                                    LEFT JOIN perusahaan AS bumn_4 ON bumn_4.ID = bumn_3.induk
                                    WHERE 
                                    bumn_induk.is_active is true 
                                    AND
                                    ((jabatan.id_grup_jabatan = 1 )
                                    OR talenta.is_talenta = true )
                                    GROUP BY talenta.id,
                                        bumn_0.nama_lengkap,
                                        bumn_1.nama_lengkap,
                                        bumn_2.nama_lengkap,
                                        bumn_3.nama_lengkap,
                                        bumn_4.nama_lengkap,
                                        bumn_0.level,
                                        bumn_1.level,
                                        bumn_2.level,
                                        bumn_3.level,
                                        bumn_4.level,
                                        bumn_induk.nama_lengkap,
                                        jabatan.nomenklatur_jabatan,
                                        jabatan.nama_lengkap,
                                        jabatan.id_perusahaan
                                    ORDER BY talenta.id
                                ) A
                        ) B where B.bumn is not null
                
                    ) all_data 
                    GROUP BY all_data.bumn
                    ORDER BY all_data.bumn
                ) per_bumn
          ");

          $row = ['Selected', intval($data[0]->jumlah_selected)];
          array_push($json,$row);
          $row = ['Nominated', intval($data[0]->jumlah_nominated)];
          array_push($json,$row);
          $row = ['Eligible', intval($data[0]->jumlah_eligible)];
          array_push($json,$row);
          $row = ['Qualified', intval($data[0]->jumlah_qualified)];
          array_push($json,$row);
          
        
          return response()->json($json);
        }catch(\Exception $e){
          $json = [];
          return response()->json($json);
        }
    }

    public function gettabledetail(Request $request)
    {
        try{
          return view($this->__route.'.detail',[
            'panel' => 'bg-primary',
            'title' => 'Detail Talenta',
            'idx' => $request->idx
          ]);
        }catch(\Exception $e){}
  
    }

    public function detail_rekap(Request $request)
    {
        try{
          return view($this->__route.'.detail_rekap',[
            'panel' => 'bg-primary',
            'title' => 'Detail Talenta',
            'bumn' => $request->input('bumn'),
            'id_status_talenta' => (int)$request->input('id_status_talenta')
          ]);
        }catch(\Exception $e){}
  
    }
    
    public function datatabletalenta(Request $request)
    {
      try{
        $idx = $request->idx;

        if($idx == 0){
            $id_status_talenta = 4;
        }else if($idx == 1){
            $id_status_talenta = 5;
        }else if($idx == 2){
            $id_status_talenta = implode(",", [6,7]);
        }else{
            $id_status_talenta = 8;
        }

        $query = "SELECT distinct B.talenta_id,
                            B.nama_lengkap,
                            B.bumn, 
                            B.id_status_talenta, 
                            status_talenta.nama AS status_talenta,
                            kategori_jabatan_talent.nama as kategori_jabatan,
                            B.jabatan
                    FROM 
                    (
                        SELECT  
                                A.nama_lengkap,
                                A.talenta_id,
                                CASE 
                                    WHEN bumn_induk IS NULL THEN bumn
                                    ELSE bumn_induk
                                END AS bumn,
                                A.id_kategori_jabatan_talent,
                                A.id_status_talenta,
                                A.jabatan
                            FROM 
                            (
                                SELECT 
                                        bumn_induk.nama_lengkap as bumn_induk,
                                        CASE 
                                        WHEN bumn_0.level = 0 THEN bumn_0.nama_lengkap 
                                        WHEN bumn_1.level = 0 THEN bumn_1.nama_lengkap 
                                        WHEN bumn_2.level = 0 THEN bumn_2.nama_lengkap 
                                        WHEN bumn_3.level = 0 THEN bumn_3.nama_lengkap 
                                        WHEN bumn_4.level = 0 THEN bumn_4.nama_lengkap 
                                        END AS bumn,
                                        talenta.id_kategori_jabatan_talent,
                                        talenta.id_perusahaan,
                                        talenta.id AS talenta_id, 	
                                        talenta.nama_lengkap, 
                                        talenta.id_status_talenta,
                                        jabatan.nomenklatur_jabatan,
                                        jabatan.nama_lengkap as nama_perusahaan,
                                        jabatan.id_perusahaan,
                                        case when jabatan.nomenklatur_baru is NULL then 
                                        jabatan.nomenklatur_jabatan else jabatan.nomenklatur_baru END as jabatan
                                FROM talenta
                                LEFT JOIN perusahaan AS bumn_induk ON bumn_induk.ID = talenta.id_perusahaan
                                LEFT JOIN lateral (select s.nomenklatur_jabatan, p.nama_lengkap, p.id, skp.nomenklatur_baru, s.id_perusahaan, sk.id_grup_jabatan
                                                from view_organ_perusahaan v
                                                left join struktur_organ s on s.id = v.id_struktur_organ 
                                                LEFT JOIN organ_perusahaan op ON op.id_struktur_organ = s.id
                                                LEFT JOIN surat_keputusan sk ON sk.id = op.id_surat_keputusan
                                                left join perusahaan p on p.id = s.id_perusahaan
                                                LEFT JOIN sk_perubahan_nomenklatur skp ON skp.id_struktur_organ = s.ID
                                                where v.id_talenta = talenta.id 
                                                and v.aktif = 't'
                                                and sk.id_grup_jabatan = 1
                                                and (v.tanggal_akhir >= now( ) 
                                                        or v.tanggal_akhir is null)
                                                order by v.id_struktur_organ ASC, s.urut ASC 
                                                limit 1) jabatan ON TRUE
                                LEFT JOIN perusahaan AS bumn_0 ON bumn_0.ID = jabatan.id_perusahaan
                                LEFT JOIN perusahaan AS bumn_1 ON bumn_1.ID = bumn_0.induk
                                LEFT JOIN perusahaan AS bumn_2 ON bumn_2.ID = bumn_1.induk
                                LEFT JOIN perusahaan AS bumn_3 ON bumn_3.ID = bumn_2.induk
                                LEFT JOIN perusahaan AS bumn_4 ON bumn_4.ID = bumn_3.induk
                                WHERE 
                                    bumn_induk.is_active is true 
                                    AND
                                    ((jabatan.id_grup_jabatan = 1 )
                                    OR talenta.is_talenta = true )
                                GROUP BY talenta.id,
                                        bumn_0.nama_lengkap,
                                        bumn_1.nama_lengkap,
                                        bumn_2.nama_lengkap,
                                        bumn_3.nama_lengkap,
                                        bumn_4.nama_lengkap,
                                        bumn_0.level,
                                        bumn_1.level,
                                        bumn_2.level,
                                        bumn_3.level,
                                        bumn_4.level,
                                        bumn_induk.nama_lengkap,
                                        jabatan.nomenklatur_jabatan,
                                        jabatan.nomenklatur_baru,
                                        jabatan.nama_lengkap,
                                        jabatan.id_perusahaan
                                ORDER BY talenta.id
                            ) A
                    ) B
                    LEFT JOIN status_talenta ON status_talenta.id = B.id_status_talenta
                    LEFT JOIN kategori_jabatan_talent ON kategori_jabatan_talent.id = B.id_kategori_jabatan_talent
                    WHERE B.id_status_talenta in ($id_status_talenta)
                    ORDER BY B.nama_lengkap
                 ";
         
         $talenta  = DB::select(DB::raw($query));
        
         return datatables()->of($talenta)
                ->editColumn('nama_lengkap', function ($row){
                    $return = '<b>'. $row->nama_lengkap . '</b><br>';
                    if($row->jabatan){
                        $return .= $row->jabatan;
                    }else{
                        $return .= "Tidak Sedang Menjabat";
                    }
                    return $return;
                })
                ->rawColumns(['nama_lengkap'])
               ->toJson();

      }catch(\Exception $e){
        return response([
            'draw'            => 0,
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
        ]);
      }
    }

    public function datatabletalentarekap(Request $request)
    {
      try{
        $id_status_talenta = $request->id_status_talenta;
        if($id_status_talenta == 6) $id_status_talenta = implode(",", [6,7]);
        $bumn = $request->bumn;

        $query = "SELECT distinct B.talenta_id,
                            B.nama_lengkap,
                            B.nik,
                            B.bumn, 
                            B.id_status_talenta, 
                            status_talenta.nama AS status_talenta,
                            kategori_jabatan_talent.nama as kategori_jabatan,
                            B.jabatan
                    FROM 
                    (
                        SELECT  
                                A.nama_lengkap,
                                A.nik,
                                A.talenta_id,
                                CASE 
                                    WHEN bumn_induk IS NULL THEN bumn
                                    ELSE bumn_induk
                                END AS bumn,
                                A.id_kategori_jabatan_talent,
                                A.id_status_talenta,
                                A.jabatan
                            FROM 
                            (
                                SELECT 
                                        bumn_induk.nama_lengkap as bumn_induk,
                                        CASE 
                                        WHEN bumn_0.level = 0 THEN bumn_0.nama_lengkap 
                                        WHEN bumn_1.level = 0 THEN bumn_1.nama_lengkap 
                                        WHEN bumn_2.level = 0 THEN bumn_2.nama_lengkap 
                                        WHEN bumn_3.level = 0 THEN bumn_3.nama_lengkap 
                                        WHEN bumn_4.level = 0 THEN bumn_4.nama_lengkap 
                                        END AS bumn,
                                        talenta.id_kategori_jabatan_talent,
                                        talenta.id_perusahaan,
                                        talenta.id AS talenta_id, 	
                                        talenta.nama_lengkap, 
                                        talenta.nik, 
                                        talenta.id_status_talenta,
                                        jabatan.nomenklatur_jabatan,
                                        jabatan.nama_lengkap as nama_perusahaan,
                                        jabatan.id_perusahaan,
                                        case when jabatan.nomenklatur_baru is NULL then 
                                        jabatan.nomenklatur_jabatan else jabatan.nomenklatur_baru END as jabatan
                                FROM talenta
                                LEFT JOIN perusahaan AS bumn_induk ON bumn_induk.ID = talenta.id_perusahaan
                                LEFT JOIN lateral (select s.nomenklatur_jabatan, p.nama_lengkap, p.id, skp.nomenklatur_baru, s.id_perusahaan, sk.id_grup_jabatan
                                                from view_organ_perusahaan v
                                                left join struktur_organ s on s.id = v.id_struktur_organ 
                                                LEFT JOIN organ_perusahaan op ON op.id_struktur_organ = s.id
                                                LEFT JOIN surat_keputusan sk ON sk.id = op.id_surat_keputusan
                                                left join perusahaan p on p.id = s.id_perusahaan
                                                LEFT JOIN sk_perubahan_nomenklatur skp ON skp.id_struktur_organ = s.ID
                                                where v.id_talenta = talenta.id 
                                                and v.aktif = 't'
                                                and sk.id_grup_jabatan = 1
                                                and (v.tanggal_akhir >= now( ) 
                                                        or v.tanggal_akhir is null)
                                                order by v.id_struktur_organ ASC, s.urut ASC 
                                                limit 1) jabatan ON TRUE
                                LEFT JOIN perusahaan AS bumn_0 ON bumn_0.ID = jabatan.id_perusahaan
                                LEFT JOIN perusahaan AS bumn_1 ON bumn_1.ID = bumn_0.induk
                                LEFT JOIN perusahaan AS bumn_2 ON bumn_2.ID = bumn_1.induk
                                LEFT JOIN perusahaan AS bumn_3 ON bumn_3.ID = bumn_2.induk
                                LEFT JOIN perusahaan AS bumn_4 ON bumn_4.ID = bumn_3.induk
                                WHERE 
                                    bumn_induk.is_active is true 
                                    AND
                                    ((jabatan.id_grup_jabatan = 1 )
                                    OR talenta.is_talenta = true )
                                GROUP BY talenta.id,
                                        bumn_0.nama_lengkap,
                                        bumn_1.nama_lengkap,
                                        bumn_2.nama_lengkap,
                                        bumn_3.nama_lengkap,
                                        bumn_4.nama_lengkap,
                                        bumn_0.level,
                                        bumn_1.level,
                                        bumn_2.level,
                                        bumn_3.level,
                                        bumn_4.level,
                                        bumn_induk.nama_lengkap,
                                        jabatan.nomenklatur_jabatan,
                                        jabatan.nomenklatur_baru,
                                        jabatan.nama_lengkap,
                                        jabatan.id_perusahaan
                                ORDER BY talenta.id
                            ) A
                    ) B
                    LEFT JOIN status_talenta ON status_talenta.id = B.id_status_talenta
                    LEFT JOIN kategori_jabatan_talent ON kategori_jabatan_talent.id = B.id_kategori_jabatan_talent
                    WHERE B.bumn = '$bumn' AND B.id_status_talenta IN ($id_status_talenta)
                    ORDER BY B.nama_lengkap
                 ";
         
         $talenta  = DB::select(DB::raw($query));
        
         return datatables()->of($talenta)
                ->editColumn('jabatan', function ($row){
                    if($row->jabatan){
                        return $row->jabatan;
                    }else{
                        return "Tidak Sedang Menjabat";
                    }
                })
                ->editColumn('nama_lengkap', function ($row){
                    $return = '<b>'. $row->nama_lengkap . '</b><br>' . $row->nik;
                    return $return;
                })
                ->rawColumns(['nama_lengkap'])
               ->toJson();

      }catch(\Exception $e){
        return response([
            'draw'            => 0,
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
        ]);
      }
    }

    public function datatablerekap(Request $request)
    {
      try{

        $query = "SELECT all_data.bumn, 
                            count(all_data.bumn) as total_talenta,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 1 THEN 1 END), 0) as jumlah_added,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 2 THEN 1 END), 0) as jumlah_registered,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 3 THEN 1 END), 0) as jumlah_non_talent,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 4 THEN 1 END), 0) as jumlah_selected,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 5 THEN 1 END), 0) as jumlah_nominated,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 6 OR all_data.id_status_talenta = 7 THEN 1 END), 0) as jumlah_eligible,
                            COALESCE( SUM(CASE WHEN all_data.id_status_talenta = 8 THEN 1 END), 0) as jumlah_qualified
                    FROM 
                    (
                
                        SELECT distinct B.talenta_id, 
                                B.bumn, 
                                B.id_status_talenta
                        FROM 
                        (
                                SELECT  
                                    A.talenta_id,
                                    CASE 
                                        WHEN bumn_induk IS NULL THEN bumn
                                        ELSE bumn_induk
                                    END AS bumn,
                                    A.id_status_talenta
                                FROM 
                                (
                                        
	                                SELECT bumn_induk.nama_lengkap as bumn_induk,
                                            CASE 
                                            WHEN bumn_0.level = 0 THEN bumn_0.nama_lengkap 
                                            WHEN bumn_1.level = 0 THEN bumn_1.nama_lengkap 
                                            WHEN bumn_2.level = 0 THEN bumn_2.nama_lengkap 
                                            WHEN bumn_3.level = 0 THEN bumn_3.nama_lengkap 
                                            WHEN bumn_4.level = 0 THEN bumn_4.nama_lengkap 
                                            END AS bumn,
                                            talenta.id_perusahaan,
                                            talenta.id AS talenta_id, 	
                                            talenta.nama_lengkap, 
                                            talenta.id_status_talenta,
                                            jabatan.nomenklatur_jabatan,
                                            jabatan.nama_lengkap as nama_perusahaan,
                                            jabatan.id_perusahaan
                                    FROM talenta
                                    LEFT JOIN perusahaan AS bumn_induk ON bumn_induk.ID = talenta.id_perusahaan
                                    LEFT JOIN lateral (select s.nomenklatur_jabatan, p.nama_lengkap, p.id, skp.nomenklatur_baru, s.id_perusahaan, sk.id_grup_jabatan
                                                    from view_organ_perusahaan v
                                                    left join struktur_organ s on s.id = v.id_struktur_organ 
                                                    LEFT JOIN organ_perusahaan op ON op.id_struktur_organ = s.id
                                                    LEFT JOIN surat_keputusan sk ON sk.id = op.id_surat_keputusan
                                                    left join perusahaan p on p.id = s.id_perusahaan
                                                    LEFT JOIN sk_perubahan_nomenklatur skp ON skp.id_struktur_organ = s.ID
                                                    where v.id_talenta = talenta.id 
                                                    and v.aktif = 't'
                                                    and sk.id_grup_jabatan = 1
                                                    and (v.tanggal_akhir >= now( ) 
                                                            or v.tanggal_akhir is null)
                                                    order by v.id_struktur_organ ASC, s.urut ASC 
                                                    limit 1) jabatan ON TRUE
                                    LEFT JOIN perusahaan AS bumn_0 ON bumn_0.ID = jabatan.id_perusahaan
                                    LEFT JOIN perusahaan AS bumn_1 ON bumn_1.ID = bumn_0.induk
                                    LEFT JOIN perusahaan AS bumn_2 ON bumn_2.ID = bumn_1.induk
                                    LEFT JOIN perusahaan AS bumn_3 ON bumn_3.ID = bumn_2.induk
                                    LEFT JOIN perusahaan AS bumn_4 ON bumn_4.ID = bumn_3.induk
                                    WHERE 
                                        bumn_induk.is_active is true 
                                        AND
                                        ((jabatan.id_grup_jabatan = 1 )
                                        OR talenta.is_talenta = true )
                                    GROUP BY talenta.id,
                                            bumn_0.nama_lengkap,
                                            bumn_1.nama_lengkap,
                                            bumn_2.nama_lengkap,
                                            bumn_3.nama_lengkap,
                                            bumn_4.nama_lengkap,
                                            bumn_0.level,
                                            bumn_1.level,
                                            bumn_2.level,
                                            bumn_3.level,
                                            bumn_4.level,
                                            bumn_induk.nama_lengkap,
                                            jabatan.nomenklatur_jabatan,
                                            jabatan.nama_lengkap,
                                            jabatan.id_perusahaan
                                    ORDER BY talenta.id
                                ) A
                        ) B where B.bumn is not null
                
                    ) all_data 
                    GROUP BY all_data.bumn
                    ORDER BY all_data.bumn
                 ";
         
         $talenta  = DB::select(DB::raw($query));
        
         return datatables()->of($talenta)
                ->editColumn('jumlah', function($row){
                    $jumlah = $row->jumlah_selected + $row->jumlah_nominated + $row->jumlah_eligible + $row->jumlah_qualified;
                    if($jumlah>0) return '<b>'.$jumlah.'</b>';
                    return $jumlah;
                })
                ->editColumn('jumlah_selected', function ($row){
                    $return = $row->jumlah_selected;
                    if($row->jumlah_selected > 0){
                        $return = '<b><a href="javascript:;" class="cls-detail_rekap" data-id_status_talenta="4" data-bumn="'.$row->bumn.'" data-toggle="tooltip" data-original-title="Lihat Detail">'.$row->jumlah_selected."</a></b>";
                    }
                    return $return;
                })
                ->editColumn('jumlah_nominated', function ($row){
                    $return = $row->jumlah_nominated;
                    if($row->jumlah_nominated > 0){
                        $return = '<b><a href="javascript:;" class="cls-detail_rekap" data-id_status_talenta="5" data-bumn="'.$row->bumn.'" data-toggle="tooltip" data-original-title="Lihat Detail">'.$row->jumlah_nominated."</a></b>";
                    }
                    return $return;
                })
                ->editColumn('jumlah_eligible', function ($row){
                    $return = $row->jumlah_eligible;
                    if($row->jumlah_eligible > 0){
                        $return = '<b><a href="javascript:;" class="cls-detail_rekap" data-id_status_talenta="6" data-bumn="'.$row->bumn.'" data-toggle="tooltip" data-original-title="Lihat Detail">'.$row->jumlah_eligible."</a></b>";
                    }
                    return $return;
                })
                ->editColumn('jumlah_qualified', function ($row){
                    $return = $row->jumlah_qualified;
                    if($row->jumlah_qualified > 0){
                        $return = '<b><a href="javascript:;" class="cls-detail_rekap" data-id_status_talenta="8" data-bumn="'.$row->bumn.'" data-toggle="tooltip" data-original-title="Lihat Detail">'.$row->jumlah_qualified."</a></b>";
                    }
                    return $return;
                })
                ->rawColumns(['jumlah', 'jumlah_selected', 'jumlah_nominated', 'jumlah_eligible', 'jumlah_qualified'])
               ->toJson();

      }catch(\Exception $e){
        return response([
            'draw'            => 0,
            'recordsTotal'    => 0,
            'recordsFiltered' => 0,
            'data'            => []
        ]);
      }
    }
    
    public function downloadrekap()
    {
      $namaFile = "Rekapitulasi Status Talent ".date('dmY').".xlsx";
      return Excel::download(new RekapStatusTalent(), $namaFile);
    }
    
}
