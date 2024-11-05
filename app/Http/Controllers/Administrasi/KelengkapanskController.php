<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Talenta;
use App\AsalInstansi;
use App\JenisAsalInstansi;
use App\AsalInstansiBaru;
use App\JenisAsalInstansiBaru;
use App\JenisJabatan;
use App\GrupJabatan;
use App\FaktorPenghasilan;
use App\MataUang;
use App\JenisFilePendukung;
use App\AssesmenDireksi;
use App\PenilaianDekom;
use App\Penghasilan;
use App\FilePendukung;
use App\OrganPerusahaan;
use App\SuratKeputusan;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Perusahaan;
use DB;

class KelengkapanskController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'administrasi.kelengkapansk';
         // $this->middleware('permission:kelengkapansk-list');
         // $this->middleware('permission:kelengkapansk-create');
         // $this->middleware('permission:kelengkapansk-edit');
         // $this->middleware('permission:kelengkapansk-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Administrasi Kelengkapan SK');

        $id_users = \Auth::user()->id;
        $id_users_bumn = \Auth::user()->id_bumn;
        $users = User::where('id', $id_users)->first();

        $cekbumns = Perusahaan::where('induk', $id_users_bumn)->get();
        $countbumns = $cekbumns->count();

        if ($users->kategori_user_id == 2) {

            $anakperush = DB::select( DB::raw("WITH RECURSIVE anak AS (
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
                                                  ) SELECT DISTINCT
                                                  ( P.urut ),
                                                  P.ID,
                                                  ak.perusahaan_id,
                                                  ak.perusahaan_induk_id,
                                                  P.nama_lengkap,
                                                  ak.tmt_awal,
                                                  ak.tmt_akhir 
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
                                                P.ID ASC"));

            $induks = Perusahaan::where('id', $users->id_bumn)->get();
            $anaks = $anakperush;
        } else {
            // $induks = Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get();
            $induks = Perusahaan::whereRaw("(induk = 0 AND LEVEL = 0 AND kepemilikan = 'BUMN') OR (kepemilikan = 'Minoritas' AND LEVEL = 0) AND is_active is true")->get();
            $anaks = Perusahaan::where('induk', 0)->get();
        }

        return view($this->__route.'.index',[
            'asal_instansi' => JenisAsalInstansi::orderBy('id', 'asc')->get(),
            'instansi' => Perusahaan::orderBy('id', 'asc')->get(),
            'jabatan' => JenisJabatan::orderBy('id', 'asc')->get(),
            'perusahaan' => $anaks,
            'pagetitle' => 'Administrasi Kelengkapan SK',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('administrasi.kelengkapansk.index'),
                    'menu' => 'Kelengkapan SK'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        $id_users = \Auth::user()->id;
        $id_users_bumn = \Auth::user()->id_bumn;
        $users = User::where('id', $id_users)->first();

        $cekbumns = Perusahaan::where('induk', $id_users_bumn)->get();
        $countbumns = $cekbumns->count();

        $where = '';

        if($request->id_bumn){
           $where .= " and perusahaan.id = ".$request->id_bumn." ";
        } else {
           if ($users->kategori_user_id == 2) {
               if($countbumns > 0){
                $where .= "and perusahaan.id in (
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
                                        P.ID ASC) OR perusahaan.id = ".$id_users_bumn." ";
                //$where .= " and perusahaan.induk = ".$id_users_bumn." ";
              } else {
                $where .= " and perusahaan.id = ".$users->id_bumn." ";
              }
           } else {
             $where .= " "; 
           }
           
        }

        if($request->nama){
               $where .= " and lower(talenta.nama_lengkap) like lower('%".$request->nama."%') ";
            } else {
               $where .= " ";
            }

        if($request->jabatan){
           $where .= " and jenis_jabatan.id = ".(int)$request->jabatan." ";
        } else {
           $where .= " ";
        }

        if($request->nomor){
           $where .= " and lower(surat_keputusan.nomor) like lower('%".$request->nomor."%') ";
        } else {
           $where .= " ";
        }

        if($request->tanggal_sk){
           $where .= " and surat_keputusan.tanggal_sk =  '".\Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_sk)->format('Y-m-d')."' ";
        } else {
           $where .= " ";
        }

        try{
            /*$str_query ="SELECT
                            talenta.ID,
                            talenta.nama_lengkap AS nama,
                            talenta.nik,
                            talenta.prosentase,
                            struktur_organ.nomenklatur_jabatan,
                            perusahaan.nama_lengkap AS nama_perusahaan,
                            talenta.email,
                            organ_perusahaan.aktif,
                            jenis_jabatan.id_grup_jabatan,
                            struktur_organ.id_perusahaan,
                            organ_perusahaan.id_struktur_organ,
                            organ_perusahaan.ID AS id_organ_perusahaan,
                            surat_keputusan.nomor,
                            surat_keputusan.tanggal_sk  
                          FROM
                            talenta
                            LEFT JOIN organ_perusahaan ON organ_perusahaan.id_talenta = talenta.
                            ID LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
                            LEFT JOIN surat_keputusan on surat_keputusan.id = organ_perusahaan.id_surat_keputusan
                            WHERE surat_keputusan.save = 't'
                            $where";*/
            $str_query = "SELECT
                              talenta.ID,
                              talenta.nama_lengkap AS nama,
                              talenta.nik,
                              talenta.prosentase,
                              struktur_organ.nomenklatur_jabatan,
                              perusahaan.nama_lengkap AS nama_perusahaan,
                              talenta.email,
                              organ_perusahaan.aktif,
                              jenis_jabatan.id_grup_jabatan,
                              struktur_organ.id_perusahaan,
                              organ_perusahaan.id_struktur_organ,
                              organ_perusahaan.ID AS id_organ_perusahaan,
                              surat_keputusan.nomor,
                              surat_keputusan.tanggal_sk 
                            FROM
                              organ_perusahaan
                              LEFT JOIN talenta ON talenta.id = organ_perusahaan.id_talenta
                              LEFT JOIN struktur_organ ON struktur_organ.id = organ_perusahaan.id_struktur_organ
                              LEFT JOIN perusahaan ON perusahaan.id = struktur_organ.id_perusahaan
                              LEFT JOIN surat_keputusan ON surat_keputusan.id = organ_perusahaan.id_surat_keputusan
                              LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                              LEFT JOIN LATERAL (
                              SELECT
                                s.nomenklatur_jabatan,
                                P.nama_lengkap 
                              FROM
                                organ_perusahaan v
                                LEFT JOIN struktur_organ s ON v.id_struktur_organ = s.
                                ID LEFT JOIN perusahaan P ON P.ID = s.id_perusahaan 
                              WHERE
                                v.id_talenta = talenta.ID 
                              ORDER BY
                                s.urut ASC 
                                LIMIT 1 
                              ) jabatan ON talenta.id = talenta.id
                            WHERE
                              organ_perusahaan.aktif = 't' and struktur_organ.aktif = 't'
                              $where 
                            GROUP BY
                              talenta.ID,
                              talenta.nama_lengkap,
                              talenta.nik,
                              talenta.prosentase,
                              struktur_organ.nomenklatur_jabatan,
                              perusahaan.nama_lengkap,
                              talenta.email,
                              organ_perusahaan.aktif,
                              jenis_jabatan.id_grup_jabatan,
                              struktur_organ.id_perusahaan,
                              organ_perusahaan.id_struktur_organ,
                              organ_perusahaan.ID,
                              surat_keputusan.nomor,
                              surat_keputusan.tanggal_sk 
                            ORDER BY
                              talenta.nama_lengkap ASC";
            $talenta  = DB::select(DB::raw($str_query));
            return datatables()->of($talenta)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $id_struktur_organ = (int)$row->id_struktur_organ;
                $button = '<div align="center">';

                /*$button .= '<button type="button" class="btn btn-outline-brand btn-icon cls-button-edit" data-id="'.$id.'" data-id_grup_jabatan="'.$id_grup_jabatan.'" data-id_organ_perusahaan="'.$id_organ_perusahaan.'"  data-id_perusahaan="'.$id_perusahaan.'" data-id_struktur_organ="'.$id_struktur_organ.'" data-toggle="tooltip" data-original-title="Ubah data Kelengkapan SK '.$row->nama.'"><i class="flaticon-edit"></i></button>';*/

                 $button .= '<a type="button" href="/administrasi/kelengkapansk/'.$id.'/editlengkapsk?id_struktur_organ='.$id_struktur_organ.'" class="btn btn-outline-brand btn-sm btn-icon cls-button" data-toggle="tooltip" data-original-title="Ubah data Kelengkapan SK '.$row->nama.'">
                                <i class="flaticon-edit"></i>
                            </a>';

                $button .= '</div>';
                return $button;
            })
            ->editColumn('nama', function($row) {
                return '<b>' . $row->nama . '</b><br>'.$row->nomenklatur_jabatan;
            })
            ->addColumn('perusahaan', function($row) {
                return $row->nama_perusahaan;
            })
            ->addColumn('nomorsk', function($row) {
                return '<b>' . $row->nomor . '</b><br>'.$row->tanggal_sk;
            })
            ->addColumn('prosentase', function($row) {
                return $row->prosentase . ' %';
            })
            ->rawColumns(['nama','perusahaan','nomorsk','prosentase','aktif','action'])
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
        $kelengkapansk = Talenta::get();
       
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'kelengkapansk' => $kelengkapansk
        ]);

    }

    public function store(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];      

        $validator = $this->validateform($request);   

        if (!$validator->fails()) {
            $param['nama'] = $request->input('nama');
            $param['keterangan'] = $request->input('keterangan');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $kelengkapansk = Talenta::create((array)$param);

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
                                  $kelengkapansk = Talenta::find((int)$request->input('id'));
                                  $kelengkapansk->update((array)$param);

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
                                    'msg' => 'Gagal ubah data',
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {

        try{

            $talenta = Talenta::find((int)$request->input('id'));
            $jenisasalinstansis = JenisAsalInstansi::all();
            $asalinstansis = AsalInstansi::all();
            $faktorPenghasilans = FaktorPenghasilan::all();
            $matauangs = MataUang::all();

            $id_grup_jabatan = $request->input('id_grup_jabatan');
            $id_perusahaan = $request->input('id_perusahaan');
            $id_struktur_organ = $request->input('id_struktur_organ');
            $id_organ_perusahaan = $request->input('id_organ_perusahaan');

            $assesmen_direksi = '';
            $penilaian_dekom = '';

            if($id_grup_jabatan == 1){
              $assesmen_direksi = AssesmenDireksi::where('id_talenta', $talenta->id)->first();
            }else{
              $penilaian_dekom = PenilaianDekom::where('id_talenta', $talenta->id)->where('id_perusahaan', $id_perusahaan)->first();
            }

            $penghasilans = Penghasilan::where('id_talenta', $talenta->id)->get();

            $jenis_file_pendukungs = JenisFilePendukung::all();

            $file_pendukungs = FilePendukung::where('id_talenta', $talenta->id)->get();

            return view($this->__route.'.form',[
                'actionform' => 'update',
                'talenta' => $talenta,
                'jenisasalinstansis' => $jenisasalinstansis,
                'asalinstansis' => $asalinstansis,
                'faktorPenghasilans' => $faktorPenghasilans,
                'matauangs' => $matauangs,
                'id_grup_jabatan' => $id_grup_jabatan,
                'id_perusahaan' => $id_perusahaan,
                'id_struktur_organ' => $id_struktur_organ,
                'id_organ_perusahaan' => $id_organ_perusahaan,
                'penghasilans' => $penghasilans,
                'assesmen_direksi' => $assesmen_direksi,
                'penilaian_dekom' => $penilaian_dekom,
                'jenis_file_pendukungs' => $jenis_file_pendukungs,
                'file_pendukungs' => $file_pendukungs
            ]);
        }catch(Exception $e){}

    }

    public function editlengkapsk(Request $request, $id)
    {
        $base_url = url('/');
        $talenta = Talenta::find($id);
        $jenisasalinstansis = JenisAsalInstansi::all();
        $asalinstansis = AsalInstansi::all();
        $faktorPenghasilans = FaktorPenghasilan::all();
        $matauangs = MataUang::all();

        $id_struktur_organ = (int)$request->id_struktur_organ;

        $organperusahaan = OrganPerusahaan::where('id_talenta', $talenta->id)->first();
        $suratkeputusan = SuratKeputusan::find((int)$organperusahaan->id_surat_keputusan);


        $id_grup_jabatan = (int)$suratkeputusan->id_grup_jabatan;
        $id_perusahaan = (int)$suratkeputusan->id_perusahaan;
        //$id_struktur_organ = (int)$organperusahaan->id_struktur_organ;
        $id_organ_perusahaan = (int)$organperusahaan->id;

        $namagrupjabat = GrupJabatan::where('id', $id_grup_jabatan)->first(); 

        $assesmen_direksi = '';
        $penilaian_dekom = '';

        if($id_grup_jabatan == 1){
          $assesmen_direksi = AssesmenDireksi::where('id_talenta', $talenta->id)->first();
        }else{
          $penilaian_dekom = PenilaianDekom::where('id_talenta', $talenta->id)->where('id_perusahaan', $id_perusahaan)->first();
        }

        $penghasilans = Penghasilan::where('id_talenta', $talenta->id)->get();

        $jenis_file_pendukungs = JenisFilePendukung::all();

        $file_pendukungs = FilePendukung::where('id_talenta', $talenta->id)->get();

        //dd($suratkeputusan->id_grup_jabatan);
        return view($this->__route.'.editlengkapsk',[
            'pagetitle' => 'Kelengkapan SK',
            'talenta' => $talenta,
            'jenisasalinstansis' => $jenisasalinstansis,
            'asalinstansis' => $asalinstansis,
            'faktorPenghasilans' => $faktorPenghasilans,
            'matauangs' => $matauangs,
            'id_grup_jabatan' => $id_grup_jabatan,
            'id_perusahaan' => $id_perusahaan,
            'id_struktur_organ' => $id_struktur_organ,
            'id_organ_perusahaan' => $id_organ_perusahaan,
            'penghasilans' => $penghasilans,
            'assesmen_direksi' => $assesmen_direksi,
            'penilaian_dekom' => $penilaian_dekom,
            'jenis_file_pendukungs' => $jenis_file_pendukungs,
            'file_pendukungs' => $file_pendukungs,
            'namagrupjabat' => $namagrupjabat,
            'base_url' => $base_url,
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Home'
                ],
                [
                    'url' => route('administrasi.kelengkapansk.index'),
                    'menu' => 'Kelengkapan SK'
                ]               
            ]
        ]);
    }

    public function datatableinstansi(Request $request)
    {

        try{

            $where = '';
            if($request->id_talenta){
                $where .= ' talenta.id = '.$request->id_talenta;
            }

            $id_sql = "SELECT
                          talenta.ID,
                          talenta.nama_lengkap,
                          instansi_baru.nama AS nama_instansi,
                          jenis_asal_instansi.nama AS nama_asal_instansi,
                          talenta.jabatan_asal_instansi 
                        FROM
                          talenta
                          LEFT JOIN instansi_baru ON instansi_baru.ID = talenta.id_asal_instansi
                          LEFT JOIN jenis_asal_instansi ON jenis_asal_instansi.ID = talenta.id_jenis_asal_instansi 
                        WHERE
                          $where";

            $isiinstansi  = DB::select(DB::raw($id_sql));

            return datatables()->of($isiinstansi)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit-instansi" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Instansi '.$row->nama_lengkap.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete-instansi" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Hapus Instansi '.$row->nama_lengkap.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama_lengkap','nama_instansi', 'nama_asal_instansi', 'jabatan_asal_instansi','action'])
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

    public function datatablesuminstansi(Request $request)
    {

        try{

            $where = '';
            if($request->id_talenta){
                $where .= ' talenta.id = '.$request->id_talenta;
            }

            $id_sql = "SELECT
                          talenta.ID,
                          talenta.nama_lengkap,
                          instansi.nama AS nama_instansi,
                          jenis_asal_instansi.nama AS nama_asal_instansi,
                          talenta.jabatan_asal_instansi 
                        FROM
                          talenta
                          LEFT JOIN instansi ON instansi.ID = talenta.id_asal_instansi
                          LEFT JOIN jenis_asal_instansi ON jenis_asal_instansi.ID = talenta.id_jenis_asal_instansi 
                        WHERE
                          $where";

            $isisuminstansi  = DB::select(DB::raw($id_sql));

            return datatables()->of($isisuminstansi)
            ->rawColumns(['nama_lengkap','nama_instansi', 'nama_asal_instansi', 'jabatan_asal_instansi'])
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

    public function datatableassesmen(Request $request)
    {

        try{

            $where = '';
            if($request->id_talenta){
                $where .= ' talenta.id = '.$request->id_talenta;
            }

            $id_sql = "SELECT
                          assesmen_direksi.id,
                          talenta.nama_lengkap,
                          talenta.id as id_talenta,
                          assesmen_direksi.nilai_asesmen_domestik,
                          assesmen_direksi.nilai_asesmen_global,
                          assesmen_direksi.penilaian 
                        FROM
                          assesmen_direksi
                          LEFT JOIN talenta ON talenta.ID = assesmen_direksi.id_talenta 
                        WHERE
                          $where";

            $isiassesmen  = DB::select(DB::raw($id_sql));

            return datatables()->of($isiassesmen)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit-assesmen" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Assesmen '.$row->nama_lengkap.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete-assesmen" data-id="'.$id.'" data-id_talenta="'.$row->id_talenta.'" data-toggle="tooltip" data-original-title="Hapus Assesmen '.$row->nama_lengkap.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama_lengkap','nilai_asesmen_domestik', 'nilai_asesmen_global', 'penilaian','action'])
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

    public function datatablesumassesmen(Request $request)
    {

        try{

            $where = '';
            if($request->id_talenta){
                $where .= ' talenta.id = '.$request->id_talenta;
            }

            $id_sql = "SELECT
                          assesmen_direksi.id,
                          talenta.nama_lengkap,
                          assesmen_direksi.nilai_asesmen_domestik,
                          assesmen_direksi.nilai_asesmen_global,
                          assesmen_direksi.penilaian 
                        FROM
                          assesmen_direksi
                          LEFT JOIN talenta ON talenta.ID = assesmen_direksi.id_talenta 
                        WHERE
                          $where";

            $isisumassesmen  = DB::select(DB::raw($id_sql));

            return datatables()->of($isisumassesmen)
            ->rawColumns(['nama_lengkap','nilai_asesmen_domestik', 'nilai_asesmen_global', 'penilaian'])
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

    public function datatablepenghasilan(Request $request)
    {

        try{

            $where = '';
            if($request->id_talenta){
                $where .= ' talenta.id = '.$request->id_talenta;
            }

            if($request->id_perusahaan){
                $where .= ' AND penghasilan.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_struktur_organ){
                $where .= ' AND penghasilan.id_struktur_organ = '.$request->id_struktur_organ;
            }

            $id_sql = "SELECT
                          penghasilan.id,
                          talenta.nama_lengkap,
                          penghasilan.tahun,
                          penghasilan.gaji_pokok,
                          penghasilan.tantiem,
                          penghasilan.tunjangan,
                          penghasilan.takehomepay,
                          mata_uang.kode as kode 
                        FROM
                          penghasilan
                          LEFT JOIN talenta ON talenta.ID = penghasilan.id_talenta
                          LEFT JOIN mata_uang on mata_uang.id = penghasilan.mata_uang
                        where 
                        $where";

            $isipenghasilan  = DB::select(DB::raw($id_sql));

            return datatables()->of($isipenghasilan)
            ->editColumn('gaji_pokok', function($row) {

                if(!empty($row->kode)){
                  return '<b>' . $row->kode . '</b><br>'.number_format($row->gaji_pokok,0,',',',');
                } else {
                  return '<b> IDR </b><br>'.number_format($row->gaji_pokok,0,',',',');
                }

            })
            ->editColumn('tantiem', function($row) {

                if(!empty($row->kode)){
                  return '<b>' . $row->kode . '</b><br>'.number_format($row->tantiem,0,',',',');
                } else {
                  return '<b> IDR </b><br>'.number_format($row->tantiem,0,',',',');
                }

                
            })
            ->editColumn('tunjangan', function($row) {

                if(!empty($row->kode)){
                  return '<b>' . $row->kode . '</b><br>'.number_format($row->tunjangan,0,',',',');
                } else {
                  return '<b> IDR </b><br>'.number_format($row->tunjangan,0,',',',');
                }
                
            })
            ->editColumn('takehomepay', function($row) {

                if(!empty($row->kode)){
                  return '<b>' . $row->kode . '</b><br>'.number_format($row->takehomepay,0,',',',');
                } else {
                  return '<b> IDR </b><br>'.number_format($row->takehomepay,0,',',',');
                }
                
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit-penghasilan" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Penghasilan '.$row->nama_lengkap.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete-penghasilan" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Hapus Penghasilan '.$row->nama_lengkap.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama_lengkap','tahun', 'gaji_pokok', 'tantiem', 'tunjangan', 'takehomepay','action'])
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

    public function datatablesumpenghasilan(Request $request)
    {

        try{

            $where = '';
            if($request->id_talenta){
                $where .= ' talenta.id = '.$request->id_talenta;
            }

            if($request->id_perusahaan){
                $where .= ' AND penghasilan.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_struktur_organ){
                $where .= ' AND penghasilan.id_struktur_organ = '.$request->id_struktur_organ;
            }

            $id_sql = "SELECT
                          penghasilan.id,
                          talenta.nama_lengkap,
                          penghasilan.tahun,
                          penghasilan.gaji_pokok,
                          penghasilan.tantiem,
                          penghasilan.tunjangan,
                          penghasilan.takehomepay 
                        FROM
                          penghasilan
                          LEFT JOIN talenta ON talenta.ID = penghasilan.id_talenta
                        where 
                        $where";

            $isisumpenghasilan  = DB::select(DB::raw($id_sql));

            return datatables()->of($isisumpenghasilan)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit-penghasilan" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Penghasilan '.$row->nama_lengkap.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete-penghasilan" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Hapus Penghasilan '.$row->nama_lengkap.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama_lengkap','tahun', 'gaji_pokok', 'tantiem', 'tunjangan', 'takehomepay'])
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

    public function datatablekelengkapan(Request $request)
    {

        try{

            $where = '';
            if($request->id_talenta){
                $where .= ' talenta.id = '.$request->id_talenta;
            }

            $id_sql = "SELECT
                          file_pendukung.ID,
                          talenta.nama_lengkap,
                          file_pendukung.filename,
                          jenis_file_pendukung.nama 
                        FROM
                          file_pendukung
                          LEFT JOIN talenta ON talenta.ID = file_pendukung.id_talenta
                          LEFT JOIN jenis_file_pendukung ON jenis_file_pendukung.ID = file_pendukung.id_jenis_file_pendukung 
                        WHERE
                          $where";

            $isikelengkapan  = DB::select(DB::raw($id_sql));

            return datatables()->of($isikelengkapan)
             ->addColumn('filename', function ($row){

               $html = '<a style="cursor:pointer" href="/'.$row->filename.'" target="_blank" data-keterangan="'.$row->nama.'" ><i class="flaticon2-file" ></i>&nbsp;</a>';

                
                return $html;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit-kelengkapan" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Kelengkapan '.$row->nama_lengkap.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete-kelengkapan" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Hapus Kelengkapan '.$row->nama_lengkap.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama_lengkap','nama', 'filename','action'])
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

    public function datatablesumkelengkapan(Request $request)
    {

        try{

            $where = '';
            if($request->id_talenta){
                $where .= ' talenta.id = '.$request->id_talenta;
            }

            $id_sql = "SELECT
                          file_pendukung.ID,
                          talenta.nama_lengkap,
                          file_pendukung.filename,
                          jenis_file_pendukung.nama 
                        FROM
                          file_pendukung
                          LEFT JOIN talenta ON talenta.ID = file_pendukung.id_talenta
                          LEFT JOIN jenis_file_pendukung ON jenis_file_pendukung.ID = file_pendukung.id_jenis_file_pendukung 
                        WHERE
                          $where";

            $isisumkelengkapan  = DB::select(DB::raw($id_sql));

            return datatables()->of($isisumkelengkapan)
             ->addColumn('filename', function ($row){

                $html = '<a style="cursor:pointer" href="/'.$row->filename.'" target="_blank" data-keterangan="'.$row->nama.'" ><i class="flaticon2-file" ></i>&nbsp;</a>';

                
                return $html;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit-kelengkapan" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Kelengkapan '.$row->nama_lengkap.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete-kelengkapan" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Hapus Kelengkapan '.$row->nama_lengkap.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nama_lengkap','nama', 'filename'])
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

    public function createinstansi(Request $request)
    {
        $id_talenta = $request->id_talenta;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;

        $talenta = Talenta::find((int)$id_talenta);

        /*if($grup_jabatan_id == 1){
          $jenisasalinstansis = JenisAsalInstansi::where('direksi_induk','=',true)->where('direksi_anak_cucu','=',true)->get();
        } else {
          $jenisasalinstansis = JenisAsalInstansi::where('dekom_induk','=',true)->where('dekom_anak_cucu','=',true)->get();
        }*/

        $jenisasalinstansis = JenisAsalInstansi::whereIn('id',[2,3,4,7,8,9,11,12,13,15,16])->get();
        $asalinstansis = AsalInstansi::get();
        
        
        return view($this->__route.'.forminstansi',[
            'actionform' => 'update',
            'id_talenta' => $id_talenta,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'talenta' => $talenta,
            'jenisasalinstansis' => $jenisasalinstansis,
            'asalinstansis' => $asalinstansis
        ]);
    }

    public function createkelengkapan(Request $request)
    {
        $id_talenta = $request->id_talenta;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;

        $talenta = Talenta::find((int)$id_talenta);
        $jenis_file_pendukungs = JenisFilePendukung::all();
        
        
        return view($this->__route.'.formkelengkapan',[
            'actionform' => 'insert',
            'id_talenta' => $id_talenta,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'talenta' => $talenta,
            'jenis_file_pendukungs' => $jenis_file_pendukungs
        ]);
    }

    public function editkelengkapan(Request $request)
    {
        $id_talenta = $request->id_talenta;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;

        $talenta = Talenta::find((int)$id_talenta);
        $jenis_file_pendukungs = JenisFilePendukung::all();
        $file_pendukungs = FilePendukung::find((int)$request->id);
        
        
        return view($this->__route.'.formkelengkapan',[
            'actionform' => 'update',
            'id_talenta' => $id_talenta,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'talenta' => $talenta,
            'jenis_file_pendukungs' => $jenis_file_pendukungs,
            'file_pendukungs' => $file_pendukungs
        ]);
    }

    public function createpenghasilan(Request $request)
    {
        $id_talenta = $request->id_talenta;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;
        $id_struktur_organ = $request->id_struktur_organ;
        $matauangs = MataUang::all();

        $talenta = Talenta::find((int)$id_talenta);
        
        
        return view($this->__route.'.formpenghasilan',[
            'actionform' => 'insert',
            'id_talenta' => $id_talenta,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'id_struktur_organ' => $id_struktur_organ,
            'talenta' => $talenta,
            'matauangs' => $matauangs
        ]);
    }

    public function createassesmen(Request $request)
    {
        $id_talenta = $request->id_talenta;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;

        $talenta = Talenta::find((int)$id_talenta);

        $assesmen_direksi = '';
        $penilaian_dekom = '';

        if($grup_jabatan_id == 1){
          $assesmen_direksi = AssesmenDireksi::where('id_talenta', $talenta->id)->first();
        }else{
          $penilaian_dekom = PenilaianDekom::where('id_talenta', $talenta->id)->where('id_perusahaan', $id_perusahaan)->first();
        }
        
        
        return view($this->__route.'.formassesmen',[
            'actionform' => 'insert',
            'id_talenta' => $id_talenta,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'talenta' => $talenta,
            'assesmen_direksi' => $assesmen_direksi,
            'penilaian_dekom' => $penilaian_dekom,
        ]);
    }

    public function editassesmen(Request $request)
    {
        $id_talenta = $request->id_talenta;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;

        $talenta = Talenta::find((int)$id_talenta);
        $assesmen_direksi = '';
        $penilaian_dekom = '';

        if($grup_jabatan_id == 1){
          $assesmen_direksi = AssesmenDireksi::where('id_talenta', $talenta->id)->first();
        }else{
          $penilaian_dekom = PenilaianDekom::where('id_talenta', $talenta->id)->where('id_perusahaan', $id_perusahaan)->first();
        }
        
        
        return view($this->__route.'.formassesmen',[
            'actionform' => 'update',
            'id_talenta' => $id_talenta,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'talenta' => $talenta,
            'assesmen_direksi' => $assesmen_direksi,
            'penilaian_dekom' => $penilaian_dekom,
        ]);
    }

    public function editinstansi(Request $request)
    {
        $id_talenta = $request->id_talenta;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;

        $talenta = Talenta::find((int)$id_talenta);
        $jenisasalinstansis = JenisAsalInstansi::whereIn('id',[2,3,4,7,8,9,11,12,13,15,16])->get();
        $asalinstansis = AsalInstansiBaru::get();
        
        
        
        return view($this->__route.'.forminstansi',[
            'actionform' => 'update',
            'id_talenta' => $id_talenta,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'talenta' => $talenta,
            'jenisasalinstansis' => $jenisasalinstansis,
            'asalinstansis' => $asalinstansis,
            
        ]);
    }

    public function editpenghasilan(Request $request)
    {
        $id_talenta = $request->id_talenta;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;

        $talenta = Talenta::find((int)$id_talenta);
        $penghasilans = Penghasilan::find((int)$request->input('id'));
        $matauangs = MataUang::all();
        
        
        return view($this->__route.'.formpenghasilan',[
            'actionform' => 'update',
            'id_talenta' => $id_talenta,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'talenta' => $talenta,
            'penghasilans' => $penghasilans,
            'matauangs' => $matauangs
        ]);
    }

    public function storeinstansi(Request $request)
    {
        $param['id_asal_instansi'] = $request->input('id_asal_instansi');
        $param['id_jenis_asal_instansi'] = $request->input('id_jenis_asal_instansi');
        $param['jabatan_asal_instansi'] =  $request->input('jabatan_asal_instansi');
        $param['prosentase'] = 25;

        DB::beginTransaction();
        try{
            $talenta = Talenta::find((int)$request->input('id_talenta'));
            $status = $talenta->update((array)$param);

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

        return response()->json($result);
    }

    public function storepenghasilan(Request $request)
    {

        switch ($request->input('actionform')) {
            case 'insert': DB::beginTransaction();
                           try{

                              $param['id_talenta'] = $request->input('id_talenta');
                              $param['id_perusahaan'] = $request->input('id_perusahaan');
                              $param['id_struktur_organ'] = $request->input('id_struktur_organ');
                              $param['tahun'] = $request->input('tahun');
                              $param['gaji_pokok'] = str_replace(',', '', $request->input('gaji_pokok'));
                              $param['tantiem'] = str_replace(',', '', $request->input('tantiem'));
                              $param['tunjangan'] = str_replace(',', '', $request->input('tunjangan'));
                              $param['takehomepay'] = str_replace(',', '', $request->input('takehomepay'));
                              $param['mata_uang'] = $request->input('mata_uang');

                              $status = Penghasilan::create((array)$param);

                              $status->talenta->prosentase = 75;
                              $status->talenta->save();

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
                              $param['id_talenta'] = $request->input('id_talenta');
                              $param['tahun'] = $request->input('tahun');
                              $param['gaji_pokok'] = str_replace(',', '', $request->input('gaji_pokok'));
                              $param['tantiem'] = str_replace(',', '', $request->input('tantiem'));
                              $param['tunjangan'] = str_replace(',', '', $request->input('tunjangan'));
                              $param['takehomepay'] = str_replace(',', '', $request->input('takehomepay'));

                              $status = Penghasilan::find((int)$request->id);
                              $status->talenta->prosentase = 75;
                              $status->talenta->save();

                              $status->update((array)$param);

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
                                'msg' => 'Gagal ubah data',
                                'title' => 'Gagal'
                              ];
                           }

            break;
        }   

        return response()->json($result);
    }

    public function storekelengkapan(Request $request)
    {

        switch ($request->input('actionform')) {
            case 'insert': DB::beginTransaction();
                           try{

                              $param['id_talenta'] = $request->input('id_talenta');
                              $param['id_jenis_file_pendukung'] = $request->input('id_jenis_file_pendukung');
                              $param['id_surat_keputusan'] = 1;
                              $param['id_jenis_sk'] = 1;

                              $file_path = '';
                              if ($request->file('file_pendukung')){
                                  $now = date("dmYHis");  
                                  $extension = explode('.',$request->file('file_pendukung')->getClientOriginalName());
                                  $name = $extension[0];
                                  $extension = end($extension);
                                  $file_name = substr($name, 0, 20).'_'.$now.'.'.$extension;
                                  $file_name = str_replace(" ","_",$file_name);

                                  $file_path = Storage::disk('public')->putFileAs(
                                      'kelengkapansk_files', $request->file('file_pendukung'), $file_name
                                  );
                                  $param['filename']      = 'storage/'.$file_path;
                              }

                              $status = FilePendukung::create((array)$param);

                              //$status->talenta->prosentase = 100;
                              $status->talenta->save();

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
                              $param['id_talenta'] = $request->input('id_talenta');
                              $param['id_jenis_file_pendukung'] = $request->input('id_jenis_file_pendukung');
                              $param['id_surat_keputusan'] = 1;
                              $param['id_jenis_sk'] = 1;

                              $file_path = '';
                              if ($request->file('file_pendukung')){
                                  $now = date("dmYHis");  
                                  $extension = explode('.',$request->file('file_pendukung')->getClientOriginalName());
                                  $name = $extension[0];
                                  $extension = end($extension);
                                  $file_name = substr($name, 0, 20).'_'.$now.'.'.$extension;
                                  $file_name = str_replace(" ","_",$file_name);

                                  $file_path = Storage::disk('public')->putFileAs(
                                      'kelengkapansk_files', $request->file('file_pendukung'), $file_name
                                  );
                                  $param['filename']      = 'storage/'.$file_path;
                              }

                              $status = FilePendukung::find((int)$request->id);

                              //$status->talenta->prosentase = 100;
                              $status->talenta->save();

                              $status->update((array)$param);

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
                                'msg' => 'Gagal ubah data',
                                'title' => 'Gagal'
                              ];
                           }

            break;
        }   

        return response()->json($result);
    }

    public function storeassesmen(Request $request)
    {
        $id_grup_jabatan = $request->input('grup_jabatan_id');
        $id_talenta = $request->input('id_talenta');
        $id_perusahaan = $request->input('id_perusahaan');
        

        switch ($request->input('actionform')) {
            case 'insert': DB::beginTransaction();
                           try{

                              $param['id_talenta'] = $request->input('id_talenta');
                              $param['id_perusahaan'] = $request->input('id_perusahaan');
                              if ($id_grup_jabatan == 1){
                                $param['nilai_asesmen_global'] = $request->input('nilai_asesmen_global');
                                $param['nilai_asesmen_domestik'] = $request->input('nilai_asesmen_domestik');
                                $param['penilaian'] = $request->input('penilaian');
                              }else{
                                $param['penilaian'] = $request->input('penilaian');
                              }

                              if ($id_grup_jabatan == 1)
                                $status = AssesmenDireksi::create((array)$param);
                              else
                                $status = AssesmenDireksi::create((array)$param);

                              $status->talenta->prosentase = 50;
                              $status->talenta->save();

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
                              $assesmen = AssesmenDireksi::where('id_talenta', $id_talenta)->first();

                              if ($id_grup_jabatan == 1){
                                $paramupdate['nilai_asesmen_global'] = $request->input('nilai_asesmen_global');
                                $paramupdate['nilai_asesmen_domestik'] = $request->input('nilai_asesmen_domestik');
                                $paramupdate['penilaian'] = $request->input('penilaian');
                                $assesmen = AssesmenDireksi::where('id_talenta', $id_talenta)->first();
                                $dataassesmen = AssesmenDireksi::find((int)$assesmen->id);
                                $dataassesmen->update((array)$paramupdate);
                              }else{
                                $paramupdate['penilaian'] = $request->input('penilaian');
                                $dataassesmen = AssesmenDireksi::find((int)$assesmen->id);
                                $dataassesmen->update((array)$paramupdate);
                              }

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
                                'msg' => 'Gagal ubah data',
                                'title' => 'Gagal'
                              ];
                           }

            break;
        }   

        return response()->json($result);
    }

    public function deleteassesmen(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = AssesmenDireksi::find((int)$request->id);
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

    public function deletepenghasilan(Request $request)
    {
        DB::beginTransaction();
        try{

            $data = Penghasilan::find((int)$request->id);
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

    public function deletekelengkapan(Request $request)
    {
        DB::beginTransaction();
        try{

            $data = FilePendukung::find((int)$request->id);
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

    public function deleteinstansi(Request $request)
    {
        DB::beginTransaction();
        try{

            $data = Talenta::find((int)$request->input('id'));
            $paramtalent['id_asal_instansi'] = NULL;
            $paramtalent['jabatan_asal_instansi'] = NULL;
            $paramtalent['id_jenis_asal_instansi'] = NULL;
            $data->update((array)$paramtalent);

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

    public function savetambah2(Request $request)
    {
        DB::beginTransaction();
        try{


            $data = Talenta::find($request->id);
            $id_talenta = (int)$data->id;

            //cek data instansi
            $instansi_cek = "select id_asal_instansi, id_jenis_asal_instansi, jabatan_asal_instansi from talenta where id = $id_talenta";
            $instansi = DB::select(DB::raw($instansi_cek));

            //cek data assesment
            $assesment_cek = "select nilai_asesmen_global, nilai_asesmen_domestik, penilaian from assesmen_direksi where id_talenta = $id_talenta";
            $assesment = DB::select(DB::raw($assesment_cek));

            //cek data penghasilan
            $penghasilan_cek = "select * from penghasilan where id_talenta = $id_talenta";
            $penghasilan = DB::select(DB::raw($penghasilan_cek));

            //cek data kelengkapan
            $kelengkapan_cek = "select * from file_pendukung where id_talenta = $id_talenta";
            $kelengkapan = DB::select(DB::raw($kelengkapan_cek));

            $tot1 = 0;
            $tot2 = 0;
            $tot3 = 0;
            $tot4 = 0;
            if(count($instansi) > 0){
              $tot1 .= 25;
            }

            if(count($assesment) > 0){
              $tot2 .= 25;
            }

            if(count($penghasilan) > 0){
              $tot3 .= 25;
            }

            if(count($kelengkapan) > 0){
              $tot4 .= 25;
            }

            $jumlah = $tot1+$tot2+$tot3+$tot4;

            $data->prosentase = $jumlah;
            $data->save();

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses Simpan data',
                'title' => 'Sukses'
            ];
        }catch(\Exception $e){
            DB::rollback();
            $result = [
                'flag'  => 'warning',
                'msg' => 'Gagal Simpan data',
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);       
    }

    protected function validateform($request)
    {
        $required['nama'] = 'required';

        $message['nama.required'] = 'Nama Kelengkapan SK wajib diinput';

        return Validator::make($request->all(), $required, $message);       
    }

    public function storeAsalInstansi(Request $request)
    {
        $param['id_jenis_asal_instansi'] = $request->input('id_jenis_asal_instansi');
        if($request->id_asal_instansi){
          if (is_numeric($request->id_asal_instansi)){
            $instansi = AsalInstansiBaru::where('id', $request->id_asal_instansi)->first();
          } else {
            $param_instansi['nama'] = $request->id_asal_instansi;
            $param_instansi['id_jenis_asal_instansi'] = $request->id_jenis_asal_instansi;
            $instansi = AsalInstansiBaru::create((array)$param_instansi);
          }
          $param['id_asal_instansi'] = (int)$instansi->id;
          
        }
        //$param['id_asal_instansi'] = $request->input('id_asal_instansi');
        


        $param['jabatan_asal_instansi'] =  $request->input('jabatan_asal_instansi');
        $param['prosentase'] = 25;

        DB::beginTransaction();
        try{
            $talenta = Talenta::find((int)$request->input('id_talenta'));
            $status = $talenta->update((array)$param);

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

        return response()->json($result);
    }

    public function storeDataAssesmen(Request $request)
    {
        $id_grup_jabatan = $request->input('id_grup_jabatan');
        $param['id_talenta'] = $request->input('id_talenta');
        $param['id_perusahaan'] = $request->input('id_perusahaan');
        if ($id_grup_jabatan == 1){
          $param['nilai_asesmen_global'] = $request->input('nilai_asesmen_global');
          $param['nilai_asesmen_domestik'] = $request->input('nilai_asesmen_domestik');
        }else{
          $param['penilaian'] = $request->input('penilaian');
        }

        DB::beginTransaction();
        try{
            if ($id_grup_jabatan == 1)
              $status = AssesmenDireksi::create((array)$param);
            else
              $status = PenilaianDekom::create((array)$param);

            $status->talenta->prosentase = 50;
            $status->talenta->save();

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
              'msg' => 'Gagal ubah data',
              'title' => 'Gagal'
            ];
        }   

        return response()->json($result);
    }

    public function storeDataPenghasilan(Request $request)
    {
        $param['id_talenta'] = $request->input('id_talenta');
        $param['tahun'] = $request->input('tahun');
        $param['gaji_pokok'] = $request->input('gaji_pokok');
        $param['tantiem'] = $request->input('tantiem');
        $param['tunjangan'] = $request->input('tunjangan');
        $param['takehomepay'] = $request->input('takehomepay');

        $status = Penghasilan::create((array)$param);

        $status->talenta->prosentase = 75;
        $status->talenta->save();

        if($status){
          $result = [
              'flag'  => 'success',
              'msg' => 'Sukses ubah data',
              'title' => 'Sukses',
              'data' => $status
            ];
        }else{
          $result = [
              'flag'  => 'warning',
              'msg' => 'Gagal ubah data',
              'title' => 'Gagal',
              'data' => ''
            ];
        }
        return response()->json($result);
    }

    public function storeDataFilekelengkapan(Request $request)
    {
        $param['id_talenta'] = $request->input('id_talenta');
        $param['id_jenis_file_pendukung'] = $request->input('id_jenis_file_pendukung');
        $param['id_surat_keputusan'] = 1;
        $param['id_jenis_sk'] = 1;

        $file_path = '';
        if ($request->file('file_pendukung')){
            $now = date("dmYHis");  
            $extension = explode('.',$request->file('file_pendukung')->getClientOriginalName());
            $name = $extension[0];
            $extension = end($extension);
            $file_name = substr($name, 0, 20).'_'.$now.'.'.$extension;
            $file_name = str_replace(" ","_",$file_name);

            $file_path = Storage::disk('public')->putFileAs(
                'kelengkapansk_files', $request->file('file_pendukung'), $file_name
            );
            $param['filename']      = 'storage/'.$file_path;
        }

        $status = FilePendukung::create((array)$param);

        $status->talenta->prosentase = 100;
        $status->talenta->save();

        if($status){
          $result = [
              'flag'  => 'success',
              'msg' => 'Sukses ubah data',
              'title' => 'Sukses',
              'data' => [
                  'filename' => $status->filename,
                  'jenis_file_pendukung' => $status->jenis_file_pendukung->nama
              ]
            ];
        }else{
          $result = [
              'flag'  => 'warning',
              'msg' => 'Gagal ubah data',
              'title' => 'Gagal',
              'data' => ''
            ];
        }
        return response()->json($result);
    }

    public function storeProsentase(Request $request)
    {
        $param['prosentase'] = $request->input('prosentase');

        $talenta = Talenta::find((int)$request->input('id_talenta'));
        $status = $talenta->update((array)$param);

        if($status){
          $result = [
              'flag'  => 'success',
              'msg' => 'Sukses ubah data',
              'title' => 'Sukses',
              'data' => $status
            ];
        }else{
          $result = [
              'flag'  => 'warning',
              'msg' => 'Gagal ubah data',
              'title' => 'Gagal',
              'data' => ''
            ];
        }
        return response()->json($result);
    }

    public function getasalinstansi(Request $request)
    {
        $id_jenis_asal_instansi = (int)$request->id_jenis_asal_instansi;

        if($id_jenis_asal_instansi == 7){
          $instansilist_sql = "SELECT ID
                            ,
                            nama 
                        FROM
                            instansi_baru where id_jenis_asal_instansi = 7 order by id asc";
        } else {
          $instansilist_sql = "SELECT ID
                            ,
                            nama 
                        FROM
                            instansi_baru where id_jenis_asal_instansi = $id_jenis_asal_instansi order by id asc";
        }

        
        $listinstansis  = DB::select(DB::raw($instansilist_sql));

        $json = array();
         for($i=0; $i < count($listinstansis); $i++){
             $json[] = array(
                   'id' => $listinstansis[$i]->id,
                   'nama' => $listinstansis[$i]->nama
             );         
         }

        return response()->json($json);
    }
}