<?php

namespace App\Http\Controllers\Organ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use App\Perusahaan;
use App\JenisJabatan;
use App\BidangJabatan;
use App\GrupJabatan;
use App\StrukturOrgan;
use App\User;

class KomposisiController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'organ.komposisi';
         // $this->middleware('permission:komposisi-list');
         // $this->middleware('permission:komposisi-create');
         // $this->middleware('permission:komposisi-edit');
         // $this->middleware('permission:komposisi-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        activity()->log('Menu Komposisi Dirkomwas');
        $perusahaans = Perusahaan::get();
        return view($this->__route.'.index',[
            'pagetitle' => 'Organ Komposisi Dirkomwas',
            'perusahaans' => $perusahaans,
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Home'
                ],
                [
                    'url' => route('organ.komposisi.index'),
                    'menu' => 'Komposisi Dirkomwas'
                ]               
            ]
        ]);
    }

    public function datatable(Request $request)
    {

        try{

            $id_users = \Auth::user()->id;
            $id_users_bumn = \Auth::user()->id_bumn;
            $users = User::where('id', $id_users)->first();

            $cekbumns = Perusahaan::where('induk', $id_users_bumn)->get();
            $countbumns = $cekbumns->count();

            

            

            //dd($anakperush);

            if($users->kategori_user_id == 1){
                $id_sql = "SELECT
                                perusahaan.ID AS ID,
                                kelas_master.kelas_bumn_id,
                                kelas_bumn.nama AS kelas_nama,
                                perusahaan.nama_lengkap AS bumn_nama,
                                COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 1 THEN 1 END ) AS jumlah_direksi,
                                COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 4 THEN 1 END ) AS jumlah_dirkomwas 
                            FROM
                                perusahaan
                                LEFT JOIN struktur_organ ON struktur_organ.id_perusahaan = perusahaan.
                                ID LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                                LEFT JOIN kelas_has_bumn ON kelas_has_bumn.perusahaan_id = perusahaan.
                                ID LEFT JOIN kelas_master ON kelas_master.ID = kelas_has_bumn.kelas_master_id
                                LEFT JOIN kelas_bumn ON kelas_bumn.ID = kelas_master.kelas_bumn_id 
                            WHERE
                                struktur_organ.aktif = 't' AND
                                perusahaan.ID IN ( SELECT ID FROM perusahaan ORDER BY ID ASC ) 
                            GROUP BY
                                perusahaan.ID,
                                kelas_master.kelas_bumn_id,
                                kelas_bumn.nama,
                                perusahaan.nama_lengkap 
                            ORDER BY
                                kelas_master.kelas_bumn_id ASC,
                                perusahaan.ID ASC";
            } elseif ($users->kategori_user_id == 2) {

                $anakperush = DB::select( DB::raw("WITH RECURSIVE anak AS (
                                            SELECT
                                              perusahaan_id
                                            FROM
                                              perusahaan_relasi 
                                            WHERE
                                              perusahaan_induk_id = ".$id_users_bumn." UNION
                                              SELECT
                                                pr.perusahaan_id
                                              FROM
                                                perusahaan_relasi pr
                                                INNER JOIN anak A ON A.perusahaan_id = pr.perusahaan_induk_id 
                                              ) SELECT
                                              P.ID
                                            FROM
                                              anak ak
                                              LEFT JOIN perusahaan P ON ak.perusahaan_id = P.ID
                                              WHERE P.is_active is true
                                              GROUP BY
                                                P.ID;"));
                
                if($countbumns > 0){
                    $id_sql = "SELECT
                                    perusahaan.ID AS ID,
                                    kelas_master.kelas_bumn_id,
                                    kelas_bumn.nama AS kelas_nama,
                                    perusahaan.nama_lengkap AS bumn_nama,
                                    COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 1 THEN 1 END ) AS jumlah_direksi,
                                    COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 4 THEN 1 END ) AS jumlah_dirkomwas 
                                FROM
                                    perusahaan
                                    LEFT JOIN struktur_organ ON struktur_organ.id_perusahaan = perusahaan.
                                    ID LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                                    LEFT JOIN kelas_has_bumn ON kelas_has_bumn.perusahaan_id = perusahaan.
                                    ID LEFT JOIN kelas_master ON kelas_master.ID = kelas_has_bumn.kelas_master_id
                                    LEFT JOIN kelas_bumn ON kelas_bumn.ID = kelas_master.kelas_bumn_id 
                                WHERE
                                    struktur_organ.aktif = 't' AND
                                    perusahaan.id IN (WITH RECURSIVE anak AS (
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
                                                        P.ID ASC) 
                                GROUP BY
                                    perusahaan.ID,
                                    kelas_master.kelas_bumn_id,
                                    kelas_bumn.nama,
                                    perusahaan.nama_lengkap 
                                ORDER BY
                                    kelas_master.kelas_bumn_id ASC,
                                    perusahaan.ID ASC";
                } else {
                    $id_sql = "SELECT
                                perusahaan.ID AS ID,
                                kelas_master.kelas_bumn_id,
                                kelas_bumn.nama AS kelas_nama,
                                perusahaan.nama_lengkap AS bumn_nama,
                                COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 1 THEN 1 END ) AS jumlah_direksi,
                                COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 4 THEN 1 END ) AS jumlah_dirkomwas 
                            FROM
                                perusahaan
                                LEFT JOIN struktur_organ ON struktur_organ.id_perusahaan = perusahaan.
                                ID LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                                LEFT JOIN kelas_has_bumn ON kelas_has_bumn.perusahaan_id = perusahaan.
                                ID LEFT JOIN kelas_master ON kelas_master.ID = kelas_has_bumn.kelas_master_id
                                LEFT JOIN kelas_bumn ON kelas_bumn.ID = kelas_master.kelas_bumn_id 
                            WHERE
                                struktur_organ.aktif = 't' 
                                AND perusahaan.id = $id_users_bumn 
                            GROUP BY
                                perusahaan.ID,
                                kelas_master.kelas_bumn_id,
                                kelas_bumn.nama,
                                perusahaan.nama_lengkap 
                            ORDER BY
                                kelas_master.kelas_bumn_id ASC,
                                perusahaan.ID ASC";
                }
                
            } else {
                $id_sql = "SELECT
                                perusahaan.ID AS ID,
                                kelas_master.kelas_bumn_id,
                                kelas_bumn.nama AS kelas_nama,
                                perusahaan.nama_lengkap AS bumn_nama,
                                COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 1 THEN 1 END ) AS jumlah_direksi,
                                COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 4 THEN 1 END ) AS jumlah_dirkomwas 
                            FROM
                                perusahaan
                                LEFT JOIN struktur_organ ON struktur_organ.id_perusahaan = perusahaan.
                                ID LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                                LEFT JOIN kelas_has_bumn ON kelas_has_bumn.perusahaan_id = perusahaan.
                                ID LEFT JOIN kelas_master ON kelas_master.ID = kelas_has_bumn.kelas_master_id
                                LEFT JOIN kelas_bumn ON kelas_bumn.ID = kelas_master.kelas_bumn_id 
                            WHERE
                                struktur_organ.aktif = 't' AND
                                perusahaan.ID IN ( SELECT ID FROM perusahaan ORDER BY ID ASC ) 
                            GROUP BY
                                perusahaan.ID,
                                kelas_master.kelas_bumn_id,
                                kelas_bumn.nama,
                                perusahaan.nama_lengkap 
                            ORDER BY
                                kelas_master.kelas_bumn_id ASC,
                                perusahaan.ID ASC";
            }

            /*$id_sql = "SELECT
                            perusahaan.ID AS ID,
                            kelas_master.kelas_bumn_id,
                            kelas_bumn.nama as kelas_nama,
                            perusahaan.nama_lengkap AS bumn_nama,
                            COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 1 THEN 1 END ) AS jumlah_direksi,
                            COUNT ( CASE jenis_jabatan.id_grup_jabatan WHEN 4 THEN 1 END ) AS jumlah_dirkomwas 
                        FROM
                            perusahaan
                            LEFT JOIN struktur_organ ON struktur_organ.id_perusahaan = perusahaan.
                            ID LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN kelas_has_bumn ON kelas_has_bumn.perusahaan_id = perusahaan.
                            ID LEFT JOIN kelas_master ON kelas_master.ID = kelas_has_bumn.kelas_master_id
                            LEFT JOIN kelas_bumn ON kelas_bumn.ID = kelas_master.kelas_bumn_id
                        WHERE
                            struktur_organ.aktif = 't'
                        GROUP BY
                            perusahaan.ID,
                            kelas_master.kelas_bumn_id,
                            kelas_bumn.nama,
                            perusahaan.nama_lengkap 
                        ORDER BY
                            kelas_master.kelas_bumn_id ASC,
                            perusahaan.ID ASC";*/

            $listkomposisis  = DB::select(DB::raw($id_sql));

            return datatables()->of($listkomposisis)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<a class="btn btn-outline-brand btn-sm btn-icon cls-button-add" href="/organ/komposisi/tambah2?perusahaan_id='.$row->id.'" data-toggle="tooltip" data-original-title="Tambah Komposisi dirkomwas '.$row->bumn_nama.'"><i class="la la-plus"></i></a>';

                //$button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-add" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Tambah Komposisi dirkomwas '.$row->bumn_nama.'"><i class="la la-plus"></i></button>';

                /*$button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Komposisi dirkomwas '.$row->bumn_nama.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'" data-komposisidirkomwas="'.$row->bumn_nama.'" data-toggle="tooltip" data-original-title="Hapus Komposisi Dirkomwas '.$row->bumn_nama.'"><i class="flaticon-delete"></i></button>';*/ 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['kelas_nama','bumn_nama','jumlah_direksi','jumlah_dirkomwas','action'])
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

    public function datatable2(Request $request)
    {

        try{

            /*$id_sql = 'SELECT
                            struktur_organ.ID,
                            grup_jabatan.nama as grup_nama,
                            struktur_organ.parent_id,
                            struktur_organ.urut,
                            struktur_organ.id_perusahaan,
                            perusahaan.nama_lengkap AS bumn_nama,
                            jenis_jabatan.nama AS jabatan_nama,
                            struktur_organ.nomenklatur_jabatan AS nomenklatur_nama,
                            ARRAY_AGG ( bidang_jabatan.nama ) AS bidang_jabatan_nama,
                            struktur_organ.prosentase_gaji AS prosentase_gaji,
                            struktur_organ.aktif,
                            struktur_organ.keterangan 
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN struktur_bidang_jabatan ON struktur_bidang_jabatan.id_struktur_organ = struktur_organ.
                            ID LEFT JOIN bidang_jabatan ON bidang_jabatan.ID = struktur_bidang_jabatan.id_bidang_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan 
                        WHERE
                            struktur_organ.id_perusahaan = '.$request->perusahaan_id.' 
                        GROUP BY
                            struktur_organ.ID,
                            struktur_organ.parent_id,
                            struktur_organ.urut,
                            struktur_organ.id_perusahaan,
                            perusahaan.nama_lengkap,
                            jenis_jabatan.nama,
                            struktur_organ.nomenklatur_jabatan,
                            grup_jabatan.nama 
                        ORDER BY
                            struktur_organ.urut ASC';*/

            /*$id_sql = "SELECT
                            struktur_organ.ID,
                            grup_jabatan.nama AS grup_nama,
                            struktur_organ.parent_id,
                            struktur_organ.urut,
                            struktur_organ.id_perusahaan,
                            perusahaan.nama_lengkap AS bumn_nama,
                            jenis_jabatan.nama AS jabatan_nama,
                            struktur_organ.nomenklatur_jabatan AS nomenklatur_nama,
                            ARRAY_AGG ( bidang_jabatan.nama ) AS bidang_jabatan_nama,
                            struktur_organ.prosentase_gaji AS prosentase_gaji,
                            struktur_organ.aktif,
                            struktur_organ.keterangan,
                            grup_jabatan.id as id_grup_jabatan,
                            struktur_organ.kosong 
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN struktur_bidang_jabatan ON struktur_bidang_jabatan.id_struktur_organ = struktur_organ.
                            ID LEFT JOIN bidang_jabatan ON bidang_jabatan.ID = struktur_bidang_jabatan.id_bidang_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan 
                        WHERE
                            struktur_organ.id_perusahaan = ".$request->perusahaan_id."
                             
                        GROUP BY
                            struktur_organ.ID,
                            struktur_organ.parent_id,
                            struktur_organ.urut,
                            struktur_organ.id_perusahaan,
                            perusahaan.nama_lengkap,
                            jenis_jabatan.nama,
                            struktur_organ.nomenklatur_jabatan,
                            grup_jabatan.nama,
                            grup_jabatan.id 
                        ORDER BY
                            grup_jabatan.id ASC,
                            struktur_organ.urut ASC";*/

            /*$id_sql = "SELECT
                            struktur_organ.ID,
                            grup_jabatan.nama AS grup_nama,
                            struktur_organ.parent_id,
                            struktur_organ.urut,
                            struktur_organ.id_perusahaan,
                            perusahaan.nama_lengkap AS bumn_nama,
                            jenis_jabatan.nama AS jabatan_nama,
                            struktur_organ.nomenklatur_jabatan AS nomenklatur_nama,
                            ARRAY_AGG ( sk_perubahan_nomenklatur.nomenklatur_baru ) AS nomenklatur_baru,
                            ARRAY_AGG ( bidang_jabatan.nama ) AS bidang_jabatan_nama,
                            struktur_organ.prosentase_gaji AS prosentase_gaji,
                            struktur_organ.aktif,
                            struktur_organ.keterangan,
                            grup_jabatan.ID AS id_grup_jabatan,
                            struktur_organ.kosong 
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN struktur_bidang_jabatan ON struktur_bidang_jabatan.id_struktur_organ = struktur_organ.
                            ID LEFT JOIN bidang_jabatan ON bidang_jabatan.ID = struktur_bidang_jabatan.id_bidang_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID 
                        WHERE
                            struktur_organ.id_perusahaan = ".$request->perusahaan_id." 
                        GROUP BY
                            struktur_organ.ID,
                            struktur_organ.parent_id,
                            struktur_organ.urut,
                            struktur_organ.id_perusahaan,
                            perusahaan.nama_lengkap,
                            jenis_jabatan.nama,
                            struktur_organ.nomenklatur_jabatan,
                            grup_jabatan.nama,
                            grup_jabatan.ID
                        ORDER BY
                            grup_jabatan.ID ASC,
                            struktur_organ.urut ASC";*/

            /*$id_sql = "SELECT
                            struktur_organ.ID,
                            grup_jabatan.nama AS grup_nama,
                            struktur_organ.parent_id,
                            struktur_organ.urut,
                            struktur_organ.id_perusahaan,
                            perusahaan.nama_lengkap AS bumn_nama,
                            jenis_jabatan.nama AS jabatan_nama,
                            struktur_organ.nomenklatur_jabatan AS nomenklatur_nama,
                            klatur.nomenklatur_baru AS nomenklatur_baru,
                            ARRAY_AGG ( bidang_jabatan.nama ) AS bidang_jabatan_nama,
                            struktur_organ.prosentase_gaji AS prosentase_gaji,
                            struktur_organ.aktif,
                            struktur_organ.keterangan,
                            grup_jabatan.ID AS id_grup_jabatan,
                            struktur_organ.kosong 
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN struktur_bidang_jabatan ON struktur_bidang_jabatan.id_struktur_organ = struktur_organ.
                            ID LEFT JOIN bidang_jabatan ON bidang_jabatan.ID = struktur_bidang_jabatan.id_bidang_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
                            LEFT JOIN lateral (select sk_perubahan_nomenklatur.nomenklatur_baru, sk_perubahan_nomenklatur.id_struktur_organ from sk_perubahan_nomenklatur left join struktur_organ on struktur_organ.id = sk_perubahan_nomenklatur.id_struktur_organ where struktur_organ.id_perusahaan = ".$request->perusahaan_id." ORDER BY sk_perubahan_nomenklatur.created_at) klatur on klatur.id_struktur_organ = struktur_organ.id
                        WHERE
                            struktur_organ.id_perusahaan = ".$request->perusahaan_id."
                        GROUP BY
                            struktur_organ.ID,
                            struktur_organ.parent_id,
                            struktur_organ.urut,
                            struktur_organ.id_perusahaan,
                            perusahaan.nama_lengkap,
                            jenis_jabatan.nama,
                            struktur_organ.nomenklatur_jabatan,
                            grup_jabatan.nama,
                            grup_jabatan.ID,
                            klatur.nomenklatur_baru 
                        ORDER BY
                            grup_jabatan.ID ASC,
                            struktur_organ.urut ASC";*/

            $id_sql = "SELECT
                            struktur_organ.ID,
                            grup_jabatan.nama AS grup_nama,
                            struktur_organ.parent_id,
                            struktur_organ.urut,
                            struktur_organ.id_perusahaan,
                            perusahaan.nama_lengkap AS bumn_nama,
                            jenis_jabatan.nama AS jabatan_nama,
                            struktur_organ.nomenklatur_jabatan AS nomenklatur_nama,
                            klatur.nomenklatur_baru AS nomenklatur_baru,
                            ARRAY_AGG ( bidang_jabatan.nama ) AS bidang_jabatan_nama,
                            struktur_organ.prosentase_gaji AS prosentase_gaji,
                            struktur_organ.aktif,
                            struktur_organ.keterangan,
                            grup_jabatan.ID AS id_grup_jabatan,
                            struktur_organ.kosong 
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN struktur_bidang_jabatan ON struktur_bidang_jabatan.id_struktur_organ = struktur_organ.
                            ID LEFT JOIN bidang_jabatan ON bidang_jabatan.ID = struktur_bidang_jabatan.id_bidang_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
                            LEFT JOIN lateral (select sk_perubahan_nomenklatur.nomenklatur_baru, sk_perubahan_nomenklatur.id_struktur_organ from sk_perubahan_nomenklatur left join struktur_organ on struktur_organ.id = sk_perubahan_nomenklatur.id_struktur_organ where struktur_organ.id_perusahaan = ".$request->perusahaan_id." and sk_perubahan_nomenklatur.aktif = 't' ORDER BY sk_perubahan_nomenklatur.created_at) klatur on klatur.id_struktur_organ = struktur_organ.id
                        WHERE
                            struktur_organ.id_perusahaan = ".$request->perusahaan_id."
                        GROUP BY
                            struktur_organ.ID,
                            struktur_organ.parent_id,
                            struktur_organ.urut,
                            struktur_organ.id_perusahaan,
                            perusahaan.nama_lengkap,
                            jenis_jabatan.nama,
                            struktur_organ.nomenklatur_jabatan,
                            grup_jabatan.nama,
                            grup_jabatan.ID,
                            klatur.nomenklatur_baru 
                        ORDER BY
                            grup_jabatan.ID ASC,
                            struktur_organ.urut ASC";

            $listkomposisis  = DB::select(DB::raw($id_sql));

            return datatables()->of($listkomposisis)
            ->editColumn('aktif', function ($row){
                $html = '';

                $aktif = $row->aktif ? 'checked' : '';

                $html .= '<div align="center"><input type="checkbox" name="aktif" id="aktif" class="make-switch" data-on-text="Aktif" data-off-text="Tidak" data-size="mini" onchange="submitAktif('.$row->id.', this.checked);" '.$aktif.'></div>';

                return $html;
            })
            ->editColumn('bidang_jabatan_nama', function ($row){
                $html = '';

                if($row->bidang_jabatan_nama !== '{NULL}' && $row->bidang_jabatan_nama !== '{NULL,NULL}'){
                    $evhtml = explode(',',str_replace("}", "", str_replace("{","", str_replace('"', '', $row->bidang_jabatan_nama))));
                    $html .= '<ul>';
                    foreach ($evhtml as $key => $value) {
                       $html .= '<li>'.$value.'</li>';
                    }
                    $html .= '</ul>';
                } else {
                    $html .= '&nbsp;';
                }

                return $html;
            })
            ->editColumn('nomenklatur_baru', function ($row){
                //$html = '';

                /*if($row->nomenklatur_baru !== '{NULL}' && $row->nomenklatur_baru !== '{NULL,NULL}'){
                    $evhtml = explode(',',str_replace("}", "", str_replace("{","", str_replace('"', '', $row->nomenklatur_baru))));
                    $html .= '<ul>';
                    foreach ($evhtml as $key => $value) {
                       $html .= '<li>'.$value.'</li>';
                    }
                    $html .= '</ul>';
                } else {
                    $html .= '&nbsp;';
                }*/
                return '<b>' . $row->nomenklatur_baru . '</b>';
                //return $html;
            })
            ->editColumn('nomenklatur_nama', function ($row){

                /*if(!empty($row->nama_lengkap)){
                    if($row->organaktif == 't'){
                        return '<b>' . $row->nomenklatur_nama . '</b><br>'.$row->nama_lengkap;
                    } else {
                        return '<b>' . $row->nomenklatur_nama . '</b><br>'.$row->nama_lengkap.' <span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">TIDAK AKTIF</span>';
                    }
                } else {
                    return '<b>' . $row->nomenklatur_nama . '</b><br> <span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">KOSONG</span>';
                }*/
                /*if($row->kosong == 't'){
                    return '<b>' . $row->nomenklatur_nama . '</b><span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">KOSONG</span>';
                } else {
                    
                    return '<b>' . $row->nomenklatur_nama . '</b><br>'.$row->nama_lengkap.' <span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">TIDAK AKTIF</span>';
                }*/
                return '<b>' . $row->nomenklatur_nama . '</b>';

                
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Komposisi dirkomwas '.$row->bumn_nama.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                /*$button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete" data-id="'.$id.'" data-komposisidirkomwas="'.$row->bumn_nama.'" data-toggle="tooltip" data-original-title="Hapus Komposisi Dirkomwas '.$row->bumn_nama.'"><i class="flaticon-delete"></i></button>';*/

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['grup_nama','nomenklatur_nama','jabatan_nama','bidang_jabatan_nama','aktif', 'urut','keterangan','action','nomenklatur_baru'])
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

    public function tambah2(Request $request)
    {
        $perusahaan_id = $request->perusahaan_id;
        $nama_perusahaan = Perusahaan::where('id',$perusahaan_id)->first();
        return view($this->__route.'.tambah2',[
            'pagetitle' => 'Tambah Dirkomwas',
            'nama_perusahaan' => $nama_perusahaan,
            'perusahaan_id' => $perusahaan_id,
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Home'
                ],
                [
                    'url' => route('organ.komposisi.index'),
                    'menu' => 'Komposisi Dirkomwas'
                ]             
            ]
        ]);
    }

    public function showkomposisi(Request $request)
    {
        $perusahaan_id = $request->perusahaan_id;
        $nama_perusahaan = Perusahaan::where('id',$perusahaan_id)->first();
        
        $komposisitabel = $this->sortDirkomwas($perusahaan_id, 0);

        return view($this->__route.'.showkomposisi', ['perusahaan_id' => $perusahaan_id, 'komposisitabel' => $komposisitabel, 'nama_perusahaan' => $nama_perusahaan, 'perusahaan_id' => $perusahaan_id]);
    }

    private function sortDirkomwas($perusahaan_id,$parent_id){
        $result = new Collection;

        $sql_induks = 'SELECT
                        struktur_organ.ID,
                        struktur_organ.parent_id,
                        struktur_organ.urut,
                        struktur_organ.id_perusahaan,
                        perusahaan.nama_lengkap AS bumn_nama,
                        jenis_jabatan.nama AS jabatan_nama,
                        struktur_organ.nomenklatur_jabatan AS nomenklatur_nama,
                        array_agg(bidang_jabatan.nama) AS bidang_jabatan_nama,
                        struktur_organ.prosentase_gaji AS prosentase_gaji,
                        struktur_organ.aktif
                    FROM
                        struktur_organ
                        LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                        LEFT JOIN struktur_bidang_jabatan ON struktur_bidang_jabatan.id_struktur_organ = struktur_organ.
                        ID LEFT JOIN bidang_jabatan ON bidang_jabatan.ID = struktur_bidang_jabatan.id_bidang_jabatan
                        LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                        LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan 
                    WHERE
                        struktur_organ.parent_id = '.$parent_id.' and struktur_organ.id_perusahaan = '.$perusahaan_id.'
                    GROUP BY
                      struktur_organ.ID,
                        struktur_organ.parent_id,
                        struktur_organ.urut,
                        struktur_organ.id_perusahaan,
                        perusahaan.nama_lengkap,
                        jenis_jabatan.nama,
                        struktur_organ.nomenklatur_jabatan
                    ORDER BY
                        struktur_organ.urut ASC';
        $induks  = DB::select(DB::raw($sql_induks));

        foreach($induks as $val){

            $aktif = $val->aktif ? 'checked' : '';

            $html = '';

            if($val->bidang_jabatan_nama !== '{NULL}'){
                $evhtml = explode(',',str_replace("}", "", str_replace("{","", str_replace('"', '', $val->bidang_jabatan_nama))));
                $html .= '<ul>';
                foreach ($evhtml as $key => $value) {
                   $html .= '<li>'.$value.'</li>';
                }
                $html .= '</ul>';
            } else {
                $html .= '&nbsp;';
            }

            $result->push([
                'id' => $val->id,
                'urut' => $val->urut,
                'id_perusahaan' => $val->id_perusahaan,
                'parent_id' => $val->parent_id,
                'bumn_nama' => $val->bumn_nama,
                'jabatan_nama' => $val->jabatan_nama,
                'nomenklatur_nama' => $val->nomenklatur_nama,
                'bidang_jabatan_nama' => ($val->bidang_jabatan_nama === '{NULL}')?'&nbsp;':$html,
                'prosentase_gaji' => $val->prosentase_gaji,
                'aktif' => $aktif
            ]);

            $sql_anaks = 'SELECT
                            struktur_organ.ID,
                            struktur_organ.parent_id,
                            struktur_organ.urut,
                            struktur_organ.id_perusahaan,
                            perusahaan.nama_lengkap AS bumn_nama,
                            jenis_jabatan.nama AS jabatan_nama,
                            struktur_organ.nomenklatur_jabatan AS nomenklatur_nama,
                            array_agg(bidang_jabatan.nama) AS bidang_jabatan_nama,
                            struktur_organ.prosentase_gaji AS prosentase_gaji,
                            struktur_organ.aktif
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN struktur_bidang_jabatan ON struktur_bidang_jabatan.id_struktur_organ = struktur_organ.
                            ID LEFT JOIN bidang_jabatan ON bidang_jabatan.ID = struktur_bidang_jabatan.id_bidang_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan 
                        WHERE
                            struktur_organ.parent_id = '.$val->id.' and struktur_organ.id_perusahaan = '.$perusahaan_id.'
                        GROUP BY
                          struktur_organ.ID,
                            struktur_organ.parent_id,
                            struktur_organ.urut,
                            struktur_organ.id_perusahaan,
                            perusahaan.nama_lengkap,
                            jenis_jabatan.nama,
                            struktur_organ.nomenklatur_jabatan
                        ORDER BY
                            struktur_organ.urut ASC';

            $anaks  = DB::select(DB::raw($sql_anaks));

            if(count($anaks) > 0){
                $anaks = $this->sortDirkomwas($val->id_perusahaan,$val->id);
                foreach($anaks as $anak){

                    $aktif = $anak['aktif'] ? 'checked' : '';

                    $html1 = '';

                    if($anak['bidang_jabatan_nama'] !== '{NULL}'){
                        $evhtml1 = explode(',',str_replace("}", "", str_replace("{","", str_replace('"', '', $anak['bidang_jabatan_nama']))));
                        $html1 .= '<ul>';
                        foreach ($evhtml1 as $key1 => $value1) {
                           $html1 .= '<li>'.$value1.'</li>';
                           
                        }
                        $html1 .= '</ul>';
                    } else {
                        $html1 .= '&nbsp;';
                    }

                    $result->push([
                        'id' => $anak['id'],
                        'urut' => $anak['urut'],
                        'id_perusahaan' => $anak['id_perusahaan'],
                        'parent_id' => $anak['parent_id'],
                        'bumn_nama' => $anak['bumn_nama'],
                        'jabatan_nama' => $anak['jabatan_nama'],
                        'nomenklatur_nama' => $anak['nomenklatur_nama'],
                        'bidang_jabatan_nama' => $html1,
                        'prosentase_gaji' => $anak['prosentase_gaji'],
                        'aktif' => $aktif
                    ]);
                }
            }
        }
        return $result;
    }

    public function creategrupjabatan(Request $request)
    {
        $grupjabatans = GrupJabatan::get();
        $jenisjabatans = JenisJabatan::get();
        $bidangjabatans = BidangJabatan::get();
        $perusahaan_id = $request->perusahaan_id;
        return view($this->__route.'.formjabatan',[
            'actionform' => 'insert',
            'grupjabatans' => $grupjabatans,
            'perusahaan_id' => $perusahaan_id,
            'jenisjabatans' => $jenisjabatans,
            'bidangjabatans' => $bidangjabatans,
        ]);

    }

    public function createanak(Request $request)
    {
        $bidangjabatans = BidangJabatan::get();
        $jenisjabatans = JenisJabatan::get();
        $id = $request->id;
        $parent_id = $request->parent_id;
        $perusahaan_id = $request->perusahaan_id;
        return view($this->__route.'.formanak',[
            'actionform' => 'insert',
            'bidangjabatans' => $bidangjabatans,
            'jenisjabatans' => $jenisjabatans,
            'id' => $id,
            'parent_id' => $parent_id,
            'perusahaan_id' => $perusahaan_id
        ]);

    }

    public function editgrupjabatan(Request $request)
    {

        try{

            $perusahaan_id = $request->perusahaan_id;
            $grupjabatans = GrupJabatan::get();
            $bidangjabatans = BidangJabatan::get();
            $jenisjabatans = JenisJabatan::get();
            $strukturorgas = StrukturOrgan::find((int)$request->input('id'));

            $getjenisjabatan = JenisJabatan::where('id', $strukturorgas->id_jenis_jabatan)->first();

            $strukturjabatans = DB::table("struktur_bidang_jabatan")->where("struktur_bidang_jabatan.id_struktur_organ",(int)$request->input('id'))
                ->pluck('struktur_bidang_jabatan.id_bidang_jabatan','struktur_bidang_jabatan.id_bidang_jabatan')
                ->all();

                return view($this->__route.'.formjabatan',[
                    'actionform' => 'update',
                    'bidangjabatans' => $bidangjabatans,
                    'jenisjabatans' => $jenisjabatans,
                    'strukturorgas' => $strukturorgas,
                    'strukturjabatans' => $strukturjabatans,
                    'grupjabatans' => $grupjabatans,
                    'getjenisjabatan' => $getjenisjabatan,
                    'perusahaan_id' => $perusahaan_id,
                ]);
        }catch(Exception $e){}

    }

    public function editanak(Request $request)
    {

        try{

            $bidangjabatans = BidangJabatan::get();
            $jenisjabatans = JenisJabatan::get();
            $strukturorgas = StrukturOrgan::find((int)$request->input('id'));
            $strukturjabatans = DB::table("struktur_bidang_jabatan")->where("struktur_bidang_jabatan.id_struktur_organ",(int)$request->input('id'))
                ->pluck('struktur_bidang_jabatan.id_bidang_jabatan','struktur_bidang_jabatan.id_bidang_jabatan')
                ->all();

                return view($this->__route.'.formanak',[
                    'actionform' => 'update',
                    'bidangjabatans' => $bidangjabatans,
                    'jenisjabatans' => $jenisjabatans,
                    'strukturorgas' => $strukturorgas,
                    'strukturjabatans' => $strukturjabatans
                ]);
        }catch(Exception $e){}

    }

    public function storegrupjabatan(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateformjabatan($request);

        if (!$validator->fails()) {
            $param['id_perusahaan'] = $request->input('perusahaan_id');
            $grup_jabatan_id = $request->input('grup_jabatan_id');
            $param['id_jenis_jabatan'] = $request->input('id_jenis_jabatan');
            $param['nomenklatur_jabatan'] = $request->input('nomenklatur_jabatan');
            /*if($grup_jabatan_id == 1){
                $param['nomenklatur_jabatan'] = 'Direksi';
            } else {
                $param['nomenklatur_jabatan'] = 'Dekomwas';
            }*/
            $param['parent_id'] = 0;
            $param['level'] = 1;
            
            $param['keterangan'] = !empty($request->input('keterangan'))?$request->input('keterangan'):'-';

            $id_bidang_jabatan = $request->input('id_bidang_jabatan');
            $getstrukturorgas = StrukturOrgan::where('id_perusahaan',$request->input('perusahaan_id'))->orderBy('id','desc')->first();
            $urutlist = StrukturOrgan::where('id_perusahaan',$request->input('perusahaan_id'))->orderBy('id','desc')->get();
            $toturut = $urutlist->count();
            //dd($toturut);
            //$toturut = count($getstrukturorgas);

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{

                                  $jmlurut = $toturut+1;
                                  $urut_lama = empty($getstrukturorgas->urut)?0:$getstrukturorgas->urut;
                                  $level_lama = empty($getstrukturorgas->level)?0:$getstrukturorgas->level;
                                  $param['urut'] = $jmlurut;
                                  $param['aktif'] = 't';
                                  $param['kosong'] = 't';
                                  $strukturorgas = StrukturOrgan::create((array)$param);
                                  $strukturorgas->bidangjabatans()->sync($id_bidang_jabatan);

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
                                  $param['urut'] = $request->input('urut');
                                  $strukturorgas = StrukturOrgan::find((int)$request->input('id'));
                                  $param['aktif'] = $strukturorgas->aktif;
                                  $strukturorgas->bidangjabatans()->sync($id_bidang_jabatan);
                                  $strukturorgas->update((array)$param);

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

    public function storeanak(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateformanak($request);

        if (!$validator->fails()) {
            $param['id_perusahaan'] = $request->input('perusahaan_id');
            $param['id_jenis_jabatan'] = $request->input('id_jenis_jabatan');
            $param['nomenklatur_jabatan'] = $request->input('nomenklatur_jabatan');
            //$param['prosentase_gaji'] = $request->input('prosentase_gaji');
            
            $param['parent_id'] = $request->input('parent_id');
            $getstrukturorgas = StrukturOrgan::where('id_perusahaan',$request->input('perusahaan_id'))->where('parent_id',$request->input('parent_id'))->orderBy('id','desc')->first();
            
            
            $param['aktif'] = $request->input('aktif');
            $param['keterangan'] = !empty($request->input('keterangan'))?$request->input('keterangan'):'-';

            $id_bidang_jabatan = $request->input('id_bidang_jabatan');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $urut_lama = empty($getstrukturorgas->urut)?0:$getstrukturorgas->urut;
                                  $level_lama = empty($getstrukturorgas->level)?0:$getstrukturorgas->level;
                                  $param['urut'] = $urut_lama+1;
                                  $param['level'] = $level_lama+1;
                                  $strukturorgas = StrukturOrgan::create((array)$param);
                                  $strukturorgas->bidangjabatans()->sync($id_bidang_jabatan);

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
                                  $param['urut'] = $request->input('urut');
                                  $strukturorgas = StrukturOrgan::find((int)$request->input('id'));
                                  $strukturorgas->bidangjabatans()->sync($id_bidang_jabatan);
                                  //$role->units()->sync($unit);
                                  $strukturorgas->update((array)$param);

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

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $datadireksi = StrukturOrgan::find((int)$request->input('id'));
            $datadireksi->bidangjabatans()->detach();
            $datadireksi->delete();

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses hapus Jabatan',
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

    public function deleteanak(Request $request)
    {
        DB::beginTransaction();
        try{
            $datadireksi = StrukturOrgan::find((int)$request->input('id'));
            $datadireksi->delete();

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses hapus data organ',
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

    public function deleteall(Request $request)
    {
        DB::beginTransaction();
        try{
            $perusahaan_id = $request->perusahaan_id;
            $strukturorgans = StrukturOrgan::where('id_perusahaan', '=', $perusahaan_id)
            ->delete();

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses hapus Semua data organ',
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

    public function changeaktif(Request $request)
    {
        $id = $request->id;
        $required = $request->required;

        DB::beginTransaction();
        try {
            $strukturorgans = StrukturOrgan::find($id);
            $strukturorgans->aktif = $required;
            $strukturorgans->save();

            \DB::commit();
            $save = true;
        } catch (Exception $e) {
            \DB::rollback();
            $save = false;
        }

        if($save == true){
         $flag = 'success';
         $msg = 'Sukses Simpan Data';
         $title = 'Sukses';           
            } else {
         $flag = 'error';
         $msg = 'Gagal Simpan Data';
         $title = 'Error';          
        }

        return response()->json(['flag' => $flag, 'msg' => $msg, 'title' => $title]);
    }

    protected function validateformjabatan($request)
    {
        $required['grup_jabatan_id'] = 'required';

        $message['grup_jabatan_id.required'] = 'Perusahaan wajib dipilih';

        return Validator::make($request->all(), $required, $message);       
    }

    protected function validateformanak($request)
    {
        $required['id_jenis_jabatan'] = 'required';

        $message['id_jenis_jabatan.required'] = 'Jenis Jabatan wajib dipilih';

        return Validator::make($request->all(), $required, $message);       
    }
}
