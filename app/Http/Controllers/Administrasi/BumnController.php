<?php

namespace App\Http\Controllers\Administrasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Perusahaan;
use App\JenisSk;
use App\KategoriMobility;
use App\MobilityJabatan;
use App\KategoriPemberhentian;
use App\AlasanPemberhentian;
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
use App\SKPengangkatan;
use App\SKPemberhentian;
use App\SKNomenklatur;
use App\SKPenetapanplt;
use App\SKAlihtugas;
use App\SKKomIndependen;
use App\User;
use App\RiwayatJabatanDirkomwas;
use DB;
use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;

class BumnController extends Controller
{
    protected $__route;
    protected $suratkeputusanfile = '';
    protected $suratkeputusanfile_url = '';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         ini_set( 'max_execution_time', 0);
         $this->__route = 'administrasi.bumn';
         $this->middleware('permission:adbumn-list');

         $this->suratkeputusanfile = Config::get('folder.suratkeputusanfile');
         $this->suratkeputusanfile_url = Config::get('folder.suratkeputusanfile_url');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
    	activity()->log('Menu Administrasi SK BUMN');
        //cek kategori user
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();
        if($users->kategori_user_id == 1){
            // $list_bumn = Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get();
            $list_bumn = Perusahaan::whereRaw("(induk = 0 AND LEVEL = 0 AND kepemilikan = 'BUMN') OR (kepemilikan = 'Minoritas' AND LEVEL = 0) AND is_active is true")->get();
        } elseif ($users->kategori_user_id == 2) {
            $list_bumn = Perusahaan::where('id', (int)$users->id_bumn)->get();
        } else {
            // $list_bumn = Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('id', 'asc')->get();
            $list_bumn = Perusahaan::whereRaw("(induk = 0 AND LEVEL = 0 AND kepemilikan = 'BUMN') OR (kepemilikan = 'Minoritas' AND LEVEL = 0) AND is_active is true")->get();
        }

        return view($this->__route.'.index',[
            'pagetitle' => 'Administrasi SK BUMN',
            'bumns' => $list_bumn,
            'grupjabats' => GrupJabatan::orderBy('id', 'asc')->get(),
            'jenissks' => JenisSk::orderBy('urut', 'asc')->get(),
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Home'
                ],
                [
                    'url' => route('administrasi.bumn.index'),
                    'menu' => 'Administrasi SK BUMN'
                ]
            ]
        ]);
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = SuratKeputusan::find((int)$request->input('id'));

            $id_jenis_sk_angkat = RincianSK::where('id_jenis_sk', 1)->where('id_surat_keputusan', $data->id)->first();
            $id_jenis_sk_henti = RincianSK::where('id_jenis_sk', 2)->where('id_surat_keputusan', $data->id)->first();
            $id_jenis_sk_plt = RincianSK::where('id_jenis_sk', 3)->where('id_surat_keputusan', $data->id)->first();
            $id_jenis_sk_klatur = RincianSK::where('id_jenis_sk', 4)->where('id_surat_keputusan', $data->id)->first();
            $id_jenis_sk_tugas = RincianSK::where('id_jenis_sk', 5)->where('id_surat_keputusan', $data->id)->first();
            $id_jenis_sk_independen = RincianSK::where('id_jenis_sk', 7)->where('id_surat_keputusan', $data->id)->first();

            if(!empty($id_jenis_sk_angkat)){
                $data_angkat = SKPengangkatan::where('id_rincian_sk',$id_jenis_sk_angkat->id)->delete();
            }

            if(!empty($id_jenis_sk_henti)){
                $data_henti = SKPemberhentian::where('id_rincian_sk',$id_jenis_sk_henti->id)->delete();
            }

            if(!empty($id_jenis_sk_plt)){
                $data_plt = SKPenetapanplt::where('id_rincian_sk',$id_jenis_sk_plt->id)->delete();
            }

            if(!empty($id_jenis_sk_klatur)){
                $data_klatur = SKNomenklatur::where('id_rincian_sk',$id_jenis_sk_klatur->id)->delete();
            }

            if(!empty($id_jenis_sk_tugas)){
                $data_tugas = SKAlihtugas::where('id_rincian_sk',$id_jenis_sk_tugas->id)->delete();
            }

            if(!empty($id_jenis_sk_independen)){
                $data_independen = SKKomIndependen::where('id_rincian_sk',$id_jenis_sk_independen->id)->delete();
            }

            $data_organ = OrganPerusahaan::where('id_surat_keputusan',$data->id)->delete();
            $data->rinciansk()->detach();
            $data->filesk()->detach();
            $data->delete();

            $request->session()->forget(['perusahaan_id', 'jenis_sk_id', 'grup_jabatan_id', 'nomor_sk', 'tgl_sk']);

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
                'msg' => $e->getMessage(),
                'title' => 'Gagal'
            ];
        }
        return response()->json($result);
    }

    public function datatable(Request $request)
    {
        try{

            $id_users = \Auth::user()->id;
            $users = User::where('id', $id_users)->first();

            if ($users->kategori_user_id == 2) {
              $id_sqls = DB::table('surat_keputusan')
                       ->leftJoin('rincian_sk', 'surat_keputusan.id', '=', 'rincian_sk.id_surat_keputusan')
                       ->leftJoin('jenis_sk', 'jenis_sk.id', '=', 'rincian_sk.id_jenis_sk')
                       ->leftJoin('bumns', 'bumns.id', '=', 'surat_keputusan.id_perusahaan')
                       ->leftJoin('grup_jabatan', 'grup_jabatan.id', '=', 'surat_keputusan.id_grup_jabatan')
                       ->select(DB::raw("surat_keputusan.id,
                            bumns.id AS id_bumn,
                            bumns.jenis_perusahaan,
                            bumns.nama_lengkap,
                            bumns.nama_singkat,
                            surat_keputusan.nomor,
                            surat_keputusan.tanggal_sk,
                            grup_jabatan.id AS id_grup_jabat,
                            grup_jabatan.nama AS nama_grup_jabatan,
                            surat_keputusan.file_name,
                        CASE

                                WHEN surat_keputusan.save = 't' THEN
                                'SUBMIT' ELSE 'DRAFT'
                            END AS status,
                            ARRAY_AGG ( jenis_sk.nama ) AS jenis_sk_nama,
                            ARRAY_AGG ( jenis_sk.ID ) AS id_jenis_sk_nama,
                            surat_keputusan.updated_at,
                            surat_keputusan.user_log"))
                       ->where('bumns.level', '=', 0)
                       ->where('bumns.induk', '=', 0)
                       ->where('surat_keputusan.save', '=', 't')
                       ->groupBy('surat_keputusan.id', 'bumns.id', 'bumns.jenis_perusahaan', 'bumns.nama_lengkap', 'bumns.nama_singkat', 'surat_keputusan.nomor', 'surat_keputusan.tanggal_sk', 'surat_keputusan.file_name', 'grup_jabatan.nama', 'grup_jabatan.id', 'surat_keputusan.created_at', 'surat_keputusan.user_log')
                       ->orderBy('surat_keputusan.tanggal_sk', 'DESC');
            } else {
              $id_sqls = DB::table('surat_keputusan')
                       ->leftJoin('rincian_sk', 'surat_keputusan.id', '=', 'rincian_sk.id_surat_keputusan')
                       ->leftJoin('jenis_sk', 'jenis_sk.id', '=', 'rincian_sk.id_jenis_sk')
                       ->leftJoin('bumns', 'bumns.id', '=', 'surat_keputusan.id_perusahaan')
                       ->leftJoin('grup_jabatan', 'grup_jabatan.id', '=', 'surat_keputusan.id_grup_jabatan')
                       ->select(DB::raw("surat_keputusan.id,
                            bumns.id AS id_bumn,
                            bumns.jenis_perusahaan,
                            bumns.nama_lengkap,
                            bumns.nama_singkat,
                            surat_keputusan.nomor,
                            surat_keputusan.tanggal_sk,
                            grup_jabatan.id AS id_grup_jabat,
                            grup_jabatan.nama AS nama_grup_jabatan,
                            surat_keputusan.file_name,
                        CASE

                                WHEN surat_keputusan.save = 't' THEN
                                'SUBMIT' ELSE 'DRAFT'
                            END AS status,
                            ARRAY_AGG ( jenis_sk.nama ) AS jenis_sk_nama,
                            ARRAY_AGG ( jenis_sk.ID ) AS id_jenis_sk_nama,
                            surat_keputusan.updated_at,
                            surat_keputusan.user_log"))
                       ->where('bumns.level', '=', 0)
                       ->where('bumns.induk', '=', 0)
                       ->groupBy('surat_keputusan.id', 'bumns.id', 'bumns.jenis_perusahaan', 'bumns.nama_lengkap', 'bumns.nama_singkat', 'surat_keputusan.nomor', 'surat_keputusan.tanggal_sk', 'surat_keputusan.file_name', 'grup_jabatan.nama', 'grup_jabatan.id', 'surat_keputusan.created_at', 'surat_keputusan.user_log')
                       ->orderBy('surat_keputusan.tanggal_sk', 'DESC');
            }

            if ($request->id_grup_jabat) {
                $id_sqls->where('grup_jabatan.id', $request->id_grup_jabat);
            }

            if($request->id_bumn){
               $id_sqls->where('bumns.id', $request->id_bumn);
            } else {
               if ($users->kategori_user_id == 2) {
                   $id_sqls->where('bumns.id', $users->id_bumn);
               }

            }

            if($request->tanggal_sk){
               $id_sqls->where('surat_keputusan.tanggal_sk', \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_sk)->format('Y-m-d'));
            }

            if($request->id_jenis_sk){
               $id_sqls->where('jenis_sk.id', $request->id_jenis_sk);
            }

            if($request->nomor){
               $id_sqls->whereRaw("lower(surat_keputusan.nomor) like lower('%".$request->nomor."%')");
            }

            if($request->status){
               if ($request->status == 'SUBMIT') {
                    $s_status = 't';
                } else {
                    $s_status = 'f';
                }
               $id_sqls->where('surat_keputusan.save', $s_status);
            }

            $sk_lists = $id_sqls->paginate(100);

            $collections = new Collection;
            foreach($sk_lists as $val){
                $collections->push([
                    'id' => $val->id,
                    'id_bumn' => $val->id_bumn,
                    'jenis_perusahaan' => $val->jenis_perusahaan,
                    'nama_lengkap' => $val->nama_lengkap,
                    'nama_singkat' => $val->nama_singkat,
                    'nomor' => $val->nomor,
                    'tanggal_sk' => $val->tanggal_sk ? \Carbon\Carbon::createFromFormat('Y-m-d', $val->tanggal_sk)->format('d-m-Y') : '',
                    'id_grup_jabat' => $val->id_grup_jabat,
                    'nama_grup_jabatan' => $val->nama_grup_jabatan,
                    'file_name' => $val->file_name,
                    'status' => $val->status,
                    'jenis_sk_nama' => $val->jenis_sk_nama,
                    'id_jenis_sk_nama' => $val->id_jenis_sk_nama,
                    'updated_at' => $val->updated_at,
                    'user_log' => $val->user_log
                ]);
            }
            return datatables()->of($collections)
            ->editColumn('nomor', function($row){
                      $id = (int)$row['id'];
                      $id_perusahaan = (int)$row['id_bumn'];
                      $FilePendukung = SuratKeputusan::where('id',(int)$row['id'])->first();
                      $html = '';

                      $isExists = $this->checkFile($row['file_name']);

                        if($isExists){

                            $html .= '<b style="cursor:pointer" data-id_keputusan="'.$id.'" data-id_perusahaan="'.$id_perusahaan.'" data-nomor_sk="'.$row['nomor'].'" class="cls-button-detail">'.$row['nomor'].'</b>&nbsp;<a style="cursor:pointer" class="cls-urlpendukung" data-url="'.asset($this->suratkeputusanfile_url.$row['file_name']).'" data-keterangan="'.$row['nomor'].'" ><i class="flaticon2-file" ></i>&nbsp;</a>
                            <br/>'.$row['tanggal_sk'];
                        } else {
                            $html .= '<b data-id_keputusan="'.$id.'" data-id_perusahaan="'.$id_perusahaan.'" data-nomor_sk="'.$row['nomor'].'" class="cls-button-detail">'.$row['nomor'].'</b>&nbsp;<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill kt-badge--rounded">Belum Ada PDF</span>
                            <br/>'.$row['tanggal_sk'];
                        }


                      return $html;
            })
            ->editColumn('nama_lengkap', function($row){
                      $html = '';
                      if($row['id_grup_jabat'] == 1){
                        $html .= $row['nama_lengkap'].'<br/><span class="kt-badge kt-badge--primary kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row['nama_grup_jabatan'].'</span>';
                      } else {
                        if($row['nama_grup_jabatan'] == 'Dekom/Dewas'){
                            $html .= $row['nama_lengkap'].'<br/><span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">Dekom</span>';
                        } else {
                            $html .= $row['nama_lengkap'].'<br/><span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row['nama_grup_jabatan'].'</span>';
                        }

                      }

                      return $html;
            })
            ->editColumn('jenis_sk_nama', function ($row){
                $html = '';

                $html .= '<ol style="margin-right:-5px;margin-left:-30px">';
                if($row['jenis_sk_nama'] !== '{NULL}'){
                    $evhtml = explode(',',str_replace("}", "", str_replace("{","", str_replace('"', '', $row['jenis_sk_nama']))));

                    foreach ($evhtml as $key => $value) {
                       //$html .= $value;
                       $html .= '<li>'.$value.'</li>';
                    }
                    $html .= '</ol>';
                } else {
                    $html .= '&nbsp;';
                }

                return $html;
            })
            ->editColumn('status', function($row){
                      $html = '';
                      if($row['status']=='SUBMIT'){
                        $html .= '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row['status'].'</span><br/>'.$row['user_log'].'&nbsp;, '.$row['updated_at'];
                      } else {
                        $html .= '<span class="kt-badge kt-badge--warning kt-badge--inline kt-badge--pill kt-badge--rounded">'.$row['status'].'</span><br/>'.$row['user_log'].'&nbsp;, '.$row['updated_at'];
                      }

                      return $html;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row['id'];
                $button = '<div align="center">';

                if(\Auth::user()->can('adbumn-edit')){
                    $button .= '<a type="button" href="/administrasi/bumn/'.$id.'/edittambah" class="btn btn-outline-brand btn-sm btn-icon cls-button" data-toggle="tooltip" data-original-title="Ubah Administrasi SK '.$row['nomor'].'">
                                <i class="flaticon-edit"></i>
                            </a>';
                } else {
                    $button .= '&nbsp';
                }

                $button .= '&nbsp;';

                if($row['status']!='SUBMIT'){
                    if(\Auth::user()->can('adbumn-delete')){
                        $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete" data-id="'.$id.'" data-kelasbumn="'.$row['nomor'].'" data-toggle="tooltip" data-original-title="Hapus Administrasi SK '.$row['nomor'].'"><i class="flaticon-delete"></i></button>';
                    } else {
                        $button .= '&nbsp;';
                    }
                } else {
                    $button .= '&nbsp;';
                }

                $button .= '</div>';
                return $button;
            })

            ->rawColumns(['nama_lengkap','nomor','tanggal_sk','jenis_sk_nama', 'status','action'])
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

    public function checkFile($filename)
    {

        $path = './uploads/suratkeputusanfile/'.$filename;

        $isExists = file_exists($path);

        return $isExists;

    }

    public function detailjo(Request $request)
    {
        $id_surat_keputusan = (int)$request->id;
        $id_perusahaan = (int)$request->id_perusahaan;
        $id_talenta = (int)$request->id_talenta;
        $namaperusahaan = Perusahaan::where('id', $id_perusahaan)->first();

        $jenis_sk_id = RincianSK::where('id_surat_keputusan', $id_surat_keputusan)->get();

        $id_sql =       'select 
        * from (
        SELECT DISTINCT on 
            (surat_keputusan.ID) surat_keputusan.id,
            talenta.nama_lengkap AS pejabat,
            perusahaan.nama_lengkap AS bumn,
        CASE
                
                WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru 
            END AS nomenklatur_jabatan,
            surat_keputusan.ID AS surat_keputusan_id,
            surat_keputusan.nomor,
            surat_keputusan.tanggal_sk,
            organ_perusahaan.tanggal_awal,
            organ_perusahaan.tanggal_akhir 
        FROM
            organ_perusahaan
            LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
            LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
            LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
            LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
            LEFT JOIN rincian_sk ON rincian_sk.id_surat_keputusan = surat_keputusan.
            ID LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_rincian_sk = rincian_sk.ID 
            AND sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID 
        WHERE
            talenta.ID = '.$id_talenta.' 
        ) a 	
        ORDER BY
            a.tanggal_awal DESC';


        $isiadmin  = DB::select(DB::raw($id_sql));
        $collections = new Collection;
        
        foreach($isiadmin as $key => $val){

            $collections->push([
                'id' => $val->id,
                'pejabat' => $val->pejabat,
                'bumn' => $val->bumn,
                'nomenklatur_jabatan' => $val->nomenklatur_jabatan,
                'nomor' => $val->nomor,
                'tanggal_sk' => $val->tanggal_sk,
                'tanggal_awal' => $val->tanggal_awal,
                'tanggal_akhir' => $val->tanggal_akhir
            ]);
            
            // if($key>0){
            //     if($isiadmin[$key-1]->id==$val->id){
            //         unset($isiadmin[$key-1]);
            //     }
            // }
            
        }

        return view($this->__route.'.detailjo',[
            'id_surat_keputusan' => $id_surat_keputusan,
            'id_perusahaan' => $id_perusahaan,
            'jenis_sk_id' => $jenis_sk_id,
            'namaperusahaan' => $namaperusahaan,
            'collections' => $isiadmin
        ]);
    }

    public function detail(Request $request)
    {
        $id_surat_keputusan = (int)$request->id;
        $id_perusahaan = (int)$request->id_perusahaan;
        $namaperusahaan = Perusahaan::where('id', $id_perusahaan)->first();

        $jenis_sk_id = RincianSK::where('id_surat_keputusan', $id_surat_keputusan)->get();

        return view($this->__route.'.detail',[
            'id_surat_keputusan' => $id_surat_keputusan,
            'id_perusahaan' => $id_perusahaan,
            'jenis_sk_id' => $jenis_sk_id,
            'namaperusahaan' => $namaperusahaan
        ]);
    }

    public function tambah(Request $request)
    {
        $id_users = \Auth::user()->id;
        $users = User::where('id', $id_users)->first();

        if ($users->kategori_user_id == 2) {
            $perusahaans = Perusahaan::where('id', $users->id_bumn)->get();
        } else {
            // $perusahaans = Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->orderBy('nama_lengkap', 'asc')->get();
            $perusahaans = Perusahaan::whereRaw("(induk = 0 AND LEVEL = 0 AND kepemilikan = 'BUMN') OR (kepemilikan = 'Minoritas' AND LEVEL = 0) AND is_active is true")->get();
        }

        $jenissks = JenisSk::orderBy('urut', 'asc')->get();
        $grupjabatans = GrupJabatan::orderBy('id','asc')->get();
        return view($this->__route.'.tambah',[
            'pagetitle' => 'Data Pokok SK BUMN',
            'perusahaans' => $perusahaans,
            'grupjabatans' => $grupjabatans,
            'jenissks' => $jenissks,
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Home'
                ],
                [
                    'url' => route('administrasi.bumn.index'),
                    'menu' => 'Administrasi SK BUMN'
                ]
            ]
        ]);
    }

    public function edittambah(Request $request, $id)
    {
        $suratkeputusan = SuratKeputusan::find($id);
        $id_perusahaan = $suratkeputusan->id_perusahaan;
        $grup_jabatan_id = $suratkeputusan->id_grup_jabatan;
        $nomor_sk = $suratkeputusan->nomor_sk;
        $tanggal_sk = \Carbon\Carbon::createFromFormat('Y-m-d', $suratkeputusan->tanggal_sk)->format('d/m/Y');
        $tanggal_serah_terima = !empty($suratkeputusan->tanggal_serah_terima) ? \Carbon\Carbon::createFromFormat('Y-m-d', $suratkeputusan->tanggal_serah_terima)->format('d/m/Y') : \Carbon\Carbon::createFromFormat('Y-m-d', $suratkeputusan->tanggal_sk)->format('d/m/Y');
        $keterangan = $suratkeputusan->keterangan;
        $file_sk = $suratkeputusan->file_name;

        $rinsianhassk = DB::table("rincian_sk")->where("rincian_sk.id_surat_keputusan",$id)->orderBy('id','ASC')
                ->pluck('rincian_sk.id_jenis_sk','rincian_sk.id_jenis_sk')
                ->all();

        $perusahaans = Perusahaan::whereRaw("(induk = 0 AND LEVEL = 0 AND kepemilikan = 'BUMN') OR (kepemilikan = 'Minoritas' AND LEVEL = 0) AND is_active is true")->get();
        $jenissks = JenisSk::orderBy('urut', 'asc')->get();

        $grupjabatans = GrupJabatan::orderBy('id','asc')->get();

        return view($this->__route.'.edittambah',[
            'pagetitle' => 'Data Pokok SK BUMN',
            'perusahaans' => $perusahaans,
            'grupjabatans' => $grupjabatans,
            'jenissks' => $jenissks,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'nomor_sk' => $nomor_sk,
            'tanggal_sk' => $tanggal_sk,
            'suratkeputusan' => $suratkeputusan,
            'rinsianhassk' => $rinsianhassk,
            'tanggal_serah_terima' => $tanggal_serah_terima,
            'keterangan' => $keterangan,
            'file_sk' => $file_sk,
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Home'
                ],
                [
                    'url' => route('administrasi.bumn.index'),
                    'menu' => 'Administrasi SK BUMN'
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storetambah(Request $request)
    {
        DB::beginTransaction();
        try {
            $dir = $this->suratkeputusanfile;
            $uploaded_file = false;
            $file_sk = '';

            $perusahaan_id = $request->id_perusahaan;
            $jenis_sk_id = $request->jenis_sk_id;
            $grup_jabatan_id = $request->grup_jabatan_id;
            $nomor_sk = $request->nomor_sk;
            $tgl_sk = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tgl_sk)->format('Y-m-d');
            $tgl_serah_sk = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_serah_terima)->format('Y-m-d');
            $keterangan = !empty($request->keterangan)?$request->keterangan:'-';


            $ceksk = SuratKeputusan::where('nomor', $request->nomor_sk)->where('id_grup_jabatan', $request->grup_jabatan_id)->where('id_perusahaan', $request->id_anak)->get()->count();

            if($ceksk > 1){
               return redirect('/administrasi/bumn/tambah')->with('error','Nomor SK Sudah digunakan, Harap Periksa Kembali!!');
            }

            $request->session()->put('perusahaan_id', $request->id_perusahaan);
            $request->session()->put('jenis_sk_id', $jenis_sk_id);
            $request->session()->put('grup_jabatan_id', $grup_jabatan_id);
            $request->session()->put('nomor_sk', $nomor_sk);
            $request->session()->put('tgl_sk', $tgl_sk);

            $param['id_perusahaan'] = $perusahaan_id;
            $param['id_grup_jabatan'] = $grup_jabatan_id;
            $param['nomor'] = $nomor_sk;
            $param['tanggal_sk'] = $tgl_sk;
            $param['tanggal_serah_terima'] = $tgl_serah_sk;
            $param['keterangan'] = $keterangan;
            $param['save'] = 'f';
            $param['user_log'] = \Auth::user()->name;

            if($grup_jabatan_id == 1){
                $namagrup = 'Direksi';
            } else {
                $namagrup = 'Dekom';
            }

            $namabumns = Perusahaan::where('id', $perusahaan_id)->first();
            $today = date("Ymd");

            if($request->hasFile('file_name')){
                if ($request->file('file_name')->isValid()) {
                    if (!file_exists ($dir)){
                        mkdir($dir, 0755, true);
                    }

                    $ext = strtolower(pathinfo($request->file('file_name')->getClientOriginalName(), PATHINFO_EXTENSION));
                    $file_sk = 'SK-'.$namagrup.'-'.$namabumns->id_huruf.'-'.$today.'.'.uniqid().'.'.$ext;
                    if($request->file('file_name')->move($dir, $file_sk)){
                       $uploaded_file = true;
                       $param['file_name'] = $file_sk;
                    }
                }
            }

            if($uploaded_file){
                $suratkeputusan = SuratKeputusan::create((array)$param);
                $suratkeputusan->rinciansk()->sync($jenis_sk_id);
                $suratkeputusan->filesk()->attach($jenis_sk_id, [
                    'filename' => $file_sk]
                );
            }

        } catch(\Exception $e){
            DB::rollback();

            return redirect('/administrasi/bumn/tambah')->with('error','Simpan Data Gagal');
        }

        DB::commit();

        return redirect('/administrasi/bumn/tambah3')->with('success','Simpan Data Berhasil');
    }

    public function updatetambah(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $dir = $this->suratkeputusanfile;
            $uploaded_file = false;
            $file_sk = '';
            $jenis_sk_id = $request->jenis_sk_id;
            $perusahaan_id = $request->id_perusahaan;
            $grup_jabatan_id = $request->grup_jabatan_id;

            $suratkeputusan = SuratKeputusan::find($id);
            $param['id_perusahaan'] = $request->id_perusahaan;
            $param['id_grup_jabatan'] = $request->grup_jabatan_id;
            $param['nomor'] = $request->nomor_sk;
            $param['tanggal_sk'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tgl_sk)->format('Y-m-d');
            $param['tgl_serah_sk'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_serah_terima)->format('Y-m-d');
            $param['keterangan'] = !empty($request->keterangan)?$request->keterangan:'-';
            $tgl_sk = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tgl_sk)->format('Y-m-d');
            $param['user_log'] = \Auth::user()->name;

            $request->session()->put('perusahaan_id', $request->id_perusahaan);
            $request->session()->put('jenis_sk_id', $jenis_sk_id);
            $request->session()->put('grup_jabatan_id', $request->grup_jabatan_id);
            $request->session()->put('nomor_sk', $request->nomor_sk);
            $request->session()->put('tgl_sk', $tgl_sk);

            if($grup_jabatan_id == 1){
                $namagrup = 'Direksi';
            } else {
                $namagrup = 'Dekom';
            }

            $namabumns = Perusahaan::where('id', $perusahaan_id)->first();
            $today = date("Ymd");

            if($request->hasFile('file_name')){
                if ($request->file('file_name')->isValid()) {
                    if (!file_exists ($dir)){
                        mkdir($dir, 0755, true);
                    }

                    $ext = strtolower(pathinfo($request->file('file_name')->getClientOriginalName(), PATHINFO_EXTENSION));
                    $file_sk = 'SK-'.$namagrup.'-'.$namabumns->id_huruf.'-'.$today.'.'.uniqid().'.'.$ext;

                    if($request->file('file_name')->move($dir, $file_sk)){
                       $uploaded_file = true;
                       $param['file_name'] = $file_sk;
                    }
                }
            } else {
                $file_sk = $suratkeputusan->file_name;
                $param['file_name'] = $suratkeputusan->file_name;
                $uploaded_file = true;
            }

            if($uploaded_file){

                $suratkeputusan->rinciansk()->sync($jenis_sk_id);
                $suratkeputusan->filesk()->detach();
                $suratkeputusan->filesk()->attach($jenis_sk_id, [
                    'filename' => $file_sk]
                );

                $suratkeputusan->update((array)$param);
            }

        } catch(\Exception $e){
            DB::rollback();

            return redirect('/administrasi/bumn/'.$id.'/edittambah')->with('error','Update Data Gagal');
        }

        DB::commit();

        return redirect('/administrasi/bumn/tambah3')->with('success','Update Data Berhasil');
    }

    public function tambah2(Request $request)
    {

        $perusahaan_id = $request->session()->get('perusahaan_id');
        $jenis_sk_id = $request->session()->get('jenis_sk_id');
        $grup_jabatan_id = $request->session()->get('grup_jabatan_id');
        $nomor_sk = $request->session()->get('nomor_sk');
        $tgl_sk = $request->session()->get('tgl_sk');

        $id_surat_keputusan = SuratKeputusan::where('nomor', $nomor_sk)->where('id_perusahaan', $perusahaan_id)->where('id_grup_jabatan', $grup_jabatan_id)->first();

        $namaperusahaan = Perusahaan::where('id', $perusahaan_id)->first();
        $namagrupjabat = GrupJabatan::where('id', $grup_jabatan_id)->first();

        $jenissks = JenisSk::orderBy('urut', 'asc')->get();

        return view($this->__route.'.tambah2',[
            'pagetitle' => 'Jenis SK BUMN',
            'jenissks' => $jenissks,
            'jenis_sk_id' => $jenis_sk_id,
            'id_perusahaan' => $perusahaan_id,
            'id_surat_keputusan' => $id_surat_keputusan->id,
            'grup_jabatan_id' => $grup_jabatan_id,
            'nomor_sk' => $nomor_sk,
            'namaperusahaan' => $namaperusahaan,
            'namagrupjabat' => $namagrupjabat,
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Home'
                ],
                [
                    'url' => route('administrasi.bumn.index'),
                    'menu' => 'Administrasi SK BUMN'
                ]
            ]
        ]);
    }

    public function tambah3(Request $request)
    {
        $perusahaan_id = $request->session()->get('perusahaan_id');
        $jenis_sk_id = $request->session()->get('jenis_sk_id');
        $grup_jabatan_id = $request->session()->get('grup_jabatan_id');
        $nomor_sk = $request->session()->get('nomor_sk');
        $tgl_sk = $request->session()->get('tgl_sk');

        $id_surat_keputusan = SuratKeputusan::where('nomor', $nomor_sk)->where('id_perusahaan', $perusahaan_id)->where('id_grup_jabatan', $grup_jabatan_id)->first();

        $namaperusahaan = Perusahaan::where('id', $perusahaan_id)->first();
        $namagrupjabat = GrupJabatan::where('id', $grup_jabatan_id)->first();

        $jenissks = JenisSk::orderBy('urut', 'asc')->get();
        $tabjenisks = JenisSK::whereIn('id', $jenis_sk_id)->orderBy('urut', 'asc')->get();

        return view($this->__route.'.tambah3',[
            'pagetitle' => 'Jenis SK BUMN',
            'jenissks' => $jenissks,
            'jenis_sk_id' => $jenis_sk_id,
            'id_perusahaan' => $perusahaan_id,
            'id_surat_keputusan' => $id_surat_keputusan->id,
            'grup_jabatan_id' => $grup_jabatan_id,
            'nomor_sk' => $nomor_sk,
            'namaperusahaan' => $namaperusahaan,
            'namagrupjabat' => $namagrupjabat,
            'tabjenisks' => $tabjenisks,
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Home'
                ],
                [
                    'url' => route('administrasi.bumn.index'),
                    'menu' => 'Administrasi SK BUMN'
                ]
            ]
        ]);
    }

    public function datatableangkat(Request $request)
    {

        try{

            $where = '';
            if($request->id_perusahaan){
                $where .= 'struktur_organ.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_surat_keputusan){
                $id_jenis_sk = RincianSK::where('id_jenis_sk', 1)->where('id_surat_keputusan', $request->id_surat_keputusan)->first();
                $where .= ' AND sk_pengangkatan.id_rincian_sk = '.$id_jenis_sk->id;
            }

            $id_sql = "SELECT
                            sk_pengangkatan.ID,
                            CASE

                                WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS nomenklatur_jabatan,
                            talenta.nama_lengkap,
                            periode_jabatan.nama,
                            sk_pengangkatan.tanggal_awal_menjabat,
                            sk_pengangkatan.tanggal_akhir_menjabat
                        FROM
                            sk_pengangkatan
                            LEFT JOIN rincian_sk ON rincian_sk.ID = sk_pengangkatan.id_rincian_sk
                            LEFT JOIN struktur_organ ON struktur_organ.ID = sk_pengangkatan.id_struktur_organ
                            LEFT JOIN periode_jabatan ON periode_jabatan.ID = sk_pengangkatan.id_periode_jabatan
                            LEFT JOIN talenta ON talenta.ID = sk_pengangkatan.id_talenta
                            LEFT JOIN kategori_mobility ON kategori_mobility.ID = sk_pengangkatan.id_kategori_mobility
                            LEFT JOIN jenis_mobility_jabatan ON jenis_mobility_jabatan.ID = sk_pengangkatan.id_jenis_mobility
                            LEFT JOIN rekomendasi ON rekomendasi.ID = sk_pengangkatan.id_rekomendasi
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID



                        WHERE
                            $where
                        ORDER BY
                            sk_pengangkatan.ID ASC";

            $isiangkat  = DB::select(DB::raw($id_sql));

            return datatables()->of($isiangkat)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit-angkat" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Pengangkatan '.$row->nama_lengkap.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete-angkat" data-id="'.$id.'" data-kelasbumn="'.$row->nama_lengkap.'" data-toggle="tooltip" data-original-title="Hapus Pengangkatan '.$row->nama_lengkap.'"><i class="flaticon-delete"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nomenklatur_jabatan','nama_lengkap','nama','tanggal_awal_menjabat', 'tanggal_akhir_menjabat','action'])
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

    public function datatablesumangkat(Request $request)
    {

        try{

            $where = '';
            if($request->id_perusahaan){
                $where .= 'struktur_organ.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_surat_keputusan){
                $id_jenis_sk = RincianSK::where('id_jenis_sk', 1)->where('id_surat_keputusan', $request->id_surat_keputusan)->first();
                $where .= ' AND sk_pengangkatan.id_rincian_sk = '.$id_jenis_sk->id;
            }

            $id_sql = "SELECT
                            sk_pengangkatan.ID,
                            CASE

                                WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS nomenklatur_jabatan,
                            talenta.nama_lengkap,
                            periode_jabatan.nama,
                            sk_pengangkatan.tanggal_awal_menjabat,
                            sk_pengangkatan.tanggal_akhir_menjabat
                        FROM
                            sk_pengangkatan
                            LEFT JOIN rincian_sk ON rincian_sk.ID = sk_pengangkatan.id_rincian_sk
                            LEFT JOIN struktur_organ ON struktur_organ.ID = sk_pengangkatan.id_struktur_organ
                            LEFT JOIN periode_jabatan ON periode_jabatan.ID = sk_pengangkatan.id_periode_jabatan
                            LEFT JOIN talenta ON talenta.ID = sk_pengangkatan.id_talenta
                            LEFT JOIN kategori_mobility ON kategori_mobility.ID = sk_pengangkatan.id_kategori_mobility
                            LEFT JOIN jenis_mobility_jabatan ON jenis_mobility_jabatan.ID = sk_pengangkatan.id_jenis_mobility
                            LEFT JOIN rekomendasi ON rekomendasi.ID = sk_pengangkatan.id_rekomendasi
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID

                        WHERE
                            $where
                        ORDER BY
                            sk_pengangkatan.ID ASC";

            $isiangkat  = DB::select(DB::raw($id_sql));

            return datatables()->of($isiangkat)
            ->rawColumns(['nomenklatur_jabatan','nama_lengkap','nama','tanggal_awal_menjabat', 'tanggal_akhir_menjabat'])
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

    public function datatablehenti(Request $request)
    {

        try{

            $where = '';
            if($request->id_perusahaan){
                $where .= 'struktur_organ.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_surat_keputusan){
                $id_jenis_sk = RincianSK::where('id_jenis_sk', 2)->where('id_surat_keputusan', $request->id_surat_keputusan)->first();
                $where .= ' AND sk_pemberhentian.id_rincian_sk = '.$id_jenis_sk->id;
            }

            $id_sql = "SELECT
                            sk_pemberhentian.ID,
                            struktur_organ.nomenklatur_jabatan,
                            talenta.nama_lengkap,
                            alasan_pemberhentian.keterangan,
                            sk_pemberhentian.tanggal_akhir_menjabat
                        FROM
                            sk_pemberhentian
                            LEFT JOIN rincian_sk ON rincian_sk.ID = sk_pemberhentian.id_rincian_sk
                            LEFT JOIN struktur_organ ON struktur_organ.ID = sk_pemberhentian.id_struktur_organ
                            LEFT JOIN talenta ON talenta.ID = sk_pemberhentian.id_talenta
                            LEFT JOIN alasan_pemberhentian ON alasan_pemberhentian.ID = sk_pemberhentian.id_alasan_pemberhentian
                        WHERE
                            $where
                        ORDER BY
                            sk_pemberhentian.ID ASC";

            $isihenti  = DB::select(DB::raw($id_sql));

            return datatables()->of($isihenti)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit-henti" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Pemberhentian '.$row->nama_lengkap.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete-henti" data-id="'.$id.'" data-kelasbumn="'.$row->nama_lengkap.'" data-toggle="tooltip" data-original-title="Hapus Pemberhentian '.$row->nama_lengkap.'"><i class="flaticon-delete"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nomenklatur_jabatan','nama_lengkap', 'keterangan', 'tanggal_akhir_menjabat','action'])
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

    public function datatablesumhenti(Request $request)
    {

        try{

            $where = '';
            if($request->id_perusahaan){
                $where .= 'struktur_organ.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_surat_keputusan){
                $id_jenis_sk = RincianSK::where('id_jenis_sk', 2)->where('id_surat_keputusan', $request->id_surat_keputusan)->first();
                $where .= ' AND sk_pemberhentian.id_rincian_sk = '.$id_jenis_sk->id;
            }

            $id_sql = "SELECT
                            sk_pemberhentian.ID,
                            CASE

                                    WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                    struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS nomenklatur_jabatan,
                            talenta.nama_lengkap,
                            alasan_pemberhentian.keterangan,
                            sk_pemberhentian.tanggal_akhir_menjabat
                        FROM
                            sk_pemberhentian
                            LEFT JOIN rincian_sk ON rincian_sk.ID = sk_pemberhentian.id_rincian_sk
                            LEFT JOIN struktur_organ ON struktur_organ.ID = sk_pemberhentian.id_struktur_organ
                            LEFT JOIN talenta ON talenta.ID = sk_pemberhentian.id_talenta
                            LEFT JOIN alasan_pemberhentian ON alasan_pemberhentian.ID = sk_pemberhentian.id_alasan_pemberhentian
                            left join sk_perubahan_nomenklatur on sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.id
                        WHERE
                            $where
                        ORDER BY
                            sk_pemberhentian.ID ASC";

            $isihenti  = DB::select(DB::raw($id_sql));

            return datatables()->of($isihenti)
            ->rawColumns(['nomenklatur_jabatan','nama_lengkap', 'keterangan', 'tanggal_akhir_menjabat'])
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

    public function datatableklatur(Request $request)
    {

        try{

            $where = '';
            if($request->id_perusahaan){
                $where .= 'struktur_organ.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_surat_keputusan){
                $id_jenis_sk = RincianSK::where('id_jenis_sk', 4)->where('id_surat_keputusan', $request->id_surat_keputusan)->first();
                $where .= ' AND sk_perubahan_nomenklatur.id_rincian_sk = '.$id_jenis_sk->id;
            }

            $id_sql = "SELECT
                            sk_perubahan_nomenklatur.ID,
                            struktur_organ.nomenklatur_jabatan,
                            sk_perubahan_nomenklatur.nomenklatur_baru
                        FROM
                            sk_perubahan_nomenklatur
                            LEFT JOIN rincian_sk ON rincian_sk.ID = sk_perubahan_nomenklatur.id_rincian_sk
                            LEFT JOIN struktur_organ ON struktur_organ.ID = sk_perubahan_nomenklatur.id_struktur_organ
                            LEFT JOIN kategori_mobility ON kategori_mobility.ID = sk_perubahan_nomenklatur.id_kategori_mobility
                        WHERE
                            $where
                        ORDER BY
                            sk_perubahan_nomenklatur.ID ASC";

            $isiklatur  = DB::select(DB::raw($id_sql));

            return datatables()->of($isiklatur)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit-klatur" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Nomenklatur '.$row->nomenklatur_baru.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete-klatur" data-id="'.$id.'" data-kelasbumn="'.$row->nomenklatur_baru.'" data-toggle="tooltip" data-original-title="Hapus Nomenklatur '.$row->nomenklatur_baru.'"><i class="flaticon-delete"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nomenklatur_jabatan','nomenklatur_baru','action'])
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

    public function datatablesumklatur(Request $request)
    {

        try{

            $where = '';
            if($request->id_perusahaan){
                $where .= 'struktur_organ.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_surat_keputusan){
                $id_jenis_sk = RincianSK::where('id_jenis_sk', 4)->where('id_surat_keputusan', $request->id_surat_keputusan)->first();
                $where .= ' AND sk_perubahan_nomenklatur.id_rincian_sk = '.$id_jenis_sk->id;
            }

            $id_sql = "SELECT
                            sk_perubahan_nomenklatur.ID,
                            struktur_organ.nomenklatur_jabatan,
                            sk_perubahan_nomenklatur.nomenklatur_baru
                        FROM
                            sk_perubahan_nomenklatur
                            LEFT JOIN rincian_sk ON rincian_sk.ID = sk_perubahan_nomenklatur.id_rincian_sk
                            LEFT JOIN struktur_organ ON struktur_organ.ID = sk_perubahan_nomenklatur.id_struktur_organ
                            LEFT JOIN kategori_mobility ON kategori_mobility.ID = sk_perubahan_nomenklatur.id_kategori_mobility
                        WHERE
                            $where
                        ORDER BY
                            sk_perubahan_nomenklatur.ID ASC";

            $isisumklatur  = DB::select(DB::raw($id_sql));

            return datatables()->of($isisumklatur)
            ->rawColumns(['nomenklatur_jabatan','nomenklatur_baru'])
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

    public function datatableplt(Request $request)
    {

        try{

            $where = '';
            if($request->id_perusahaan){
                $where .= 'struktur_organ.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_surat_keputusan){
                $id_jenis_sk = RincianSK::where('id_jenis_sk', 3)->where('id_surat_keputusan', $request->id_surat_keputusan)->first();
                $where .= ' AND sk_penetapan_plt.id_rincian_sk = '.$id_jenis_sk->id;
            }

            $id_sql = "SELECT
                            sk_penetapan_plt.ID,
                            struktur_organ.nomenklatur_jabatan,
                            talenta.nama_lengkap,
                            organ_perusahaan.tanggal_awal,
                            organ_perusahaan.tanggal_akhir
                        FROM
                            sk_penetapan_plt
                            LEFT JOIN rincian_sk ON rincian_sk.ID = sk_penetapan_plt.id_rincian_sk
                            LEFT JOIN organ_perusahaan on organ_perusahaan.id = sk_penetapan_plt.id_organ_perusahaan
                            LEFT JOIN struktur_organ ON struktur_organ.ID = sk_penetapan_plt.id_struktur_organ
                            LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                        WHERE
                            $where
                        ORDER BY
                            sk_penetapan_plt.ID ASC";

            $isiplt  = DB::select(DB::raw($id_sql));

            return datatables()->of($isiplt)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit-plt" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Penempatan Tugas '.$row->nama_lengkap.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete-plt" data-id="'.$id.'" data-kelasbumn="'.$row->nama_lengkap.'" data-toggle="tooltip" data-original-title="Hapus Penempatan Tugas '.$row->nama_lengkap.'"><i class="flaticon-delete"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nomenklatur_jabatan','nama_lengkap','tanggal_awal','tanggal_akhir','action'])
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

    public function datatablesumplt(Request $request)
    {

        try{

            $where = '';
            if($request->id_perusahaan){
                $where .= 'struktur_organ.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_surat_keputusan){
                $id_jenis_sk = RincianSK::where('id_jenis_sk', 3)->where('id_surat_keputusan', $request->id_surat_keputusan)->first();
                $where .= ' AND sk_penetapan_plt.id_rincian_sk = '.$id_jenis_sk->id;
            }

            $id_sql = "SELECT
                            sk_penetapan_plt.ID,
                            struktur_organ.nomenklatur_jabatan,
                            talenta.nama_lengkap,
                            organ_perusahaan.tanggal_awal,
                            organ_perusahaan.tanggal_akhir
                        FROM
                            sk_penetapan_plt
                            LEFT JOIN rincian_sk ON rincian_sk.ID = sk_penetapan_plt.id_rincian_sk
                            LEFT JOIN organ_perusahaan on organ_perusahaan.id = sk_penetapan_plt.id_organ_perusahaan
                            LEFT JOIN struktur_organ ON struktur_organ.ID = sk_penetapan_plt.id_struktur_organ
                            LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                        WHERE
                            $where
                        ORDER BY
                            sk_penetapan_plt.ID ASC";

            $isisumplt  = DB::select(DB::raw($id_sql));

            return datatables()->of($isisumplt)
            ->rawColumns(['nomenklatur_jabatan','nama_lengkap','tanggal_awal','tanggal_akhir'])
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

    public function datatablealt(Request $request)
    {

        try{

            $where = '';
            if($request->id_perusahaan){
                $where .= 'struktur_organ.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_surat_keputusan){
                $id_jenis_sk = RincianSK::where('id_jenis_sk', 5)->where('id_surat_keputusan', $request->id_surat_keputusan)->first();
                $where .= ' AND sk_alih_tugas.id_rincian_sk = '.$id_jenis_sk->id;
            }
        
            $id_sql = "SELECT
                            sk_alih_tugas.ID,
                            talenta.nama_lengkap AS pejabat,
                            CASE

                                    WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                    struktur_angkat.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS jabatan_awal,
                            CASE

                                    WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                    struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS jabatan_alih_tugas,
                            organ_perusahaan.tanggal_awal,
                            organ_perusahaan.tanggal_akhir
                        FROM
                            sk_alih_tugas
                            LEFT JOIN rincian_sk ON rincian_sk.ID = sk_alih_tugas.id_rincian_sk
                            LEFT JOIN struktur_organ ON struktur_organ.ID = sk_alih_tugas.id_struktur_organ
                            LEFT JOIN organ_perusahaan ON organ_perusahaan.ID = sk_alih_tugas.id_organ_perusahaan
                            LEFT JOIN struktur_organ as struktur_angkat on struktur_angkat.id = organ_perusahaan.id_struktur_organ
                            LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                            left join sk_perubahan_nomenklatur on sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.id
                        WHERE
                        CASE 
                            WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NOT NULL THEN 
                            sk_perubahan_nomenklatur.aktif = 't'
                        ELSE 	
                            TRUE		
                        END 
                        AND 
                            $where
                        ORDER BY
                            sk_alih_tugas.ID ASC";

            $isialt  = DB::select(DB::raw($id_sql));

            return datatables()->of($isialt)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit-alt" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Penempatan Tugas '.$row->pejabat.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete-alt" data-id="'.$id.'" data-kelasbumn="'.$row->pejabat.'" data-toggle="tooltip" data-original-title="Hapus Alih Tugas '.$row->pejabat.'"><i class="flaticon-delete"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['pejabat','jabatan_awal','jabatan_alih_tugas','tanggal_awal', 'tanggal_akhir','action'])
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

    public function datatablesumalt(Request $request)
    {

        try{

            $where = '';
            if($request->id_perusahaan){
                $where .= 'struktur_organ.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_surat_keputusan){
                $id_jenis_sk = RincianSK::where('id_jenis_sk', 5)->where('id_surat_keputusan', $request->id_surat_keputusan)->first();
                $where .= ' AND sk_alih_tugas.id_rincian_sk = '.$id_jenis_sk->id;
            }

            $id_sql = "SELECT
                            sk_alih_tugas.ID,
                            talenta.nama_lengkap AS pejabat,
                            CASE

                                    WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                    struktur_angkat.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS jabatan_awal,
                            CASE

                                    WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                    struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS jabatan_alih_tugas,
                            organ_perusahaan.tanggal_awal,
                            organ_perusahaan.tanggal_akhir
                        FROM
                            sk_alih_tugas
                            LEFT JOIN rincian_sk ON rincian_sk.ID = sk_alih_tugas.id_rincian_sk
                            LEFT JOIN struktur_organ ON struktur_organ.ID = sk_alih_tugas.id_struktur_organ
                            LEFT JOIN organ_perusahaan ON organ_perusahaan.ID = sk_alih_tugas.id_organ_perusahaan
                            LEFT JOIN struktur_organ as struktur_angkat on struktur_angkat.id = organ_perusahaan.id_struktur_organ
                            LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                            left join sk_perubahan_nomenklatur on sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.id
                        WHERE
                            sk_perubahan_nomenklatur.aktif = 't' AND 
                            $where
                        ORDER BY
                            sk_alih_tugas.ID ASC";

            $isialt  = DB::select(DB::raw($id_sql));

            return datatables()->of($isialt)
            ->rawColumns(['pejabat','jabatan_awal','jabatan_alih_tugas','tanggal_awal', 'tanggal_akhir'])
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

    public function datatableindependen(Request $request)
    {

        try{

            $where = '';
            if($request->id_perusahaan){
                $where .= 'struktur_organ.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_surat_keputusan){
                $id_jenis_sk = RincianSK::where('id_jenis_sk', 7)->where('id_surat_keputusan', $request->id_surat_keputusan)->first();
                $where .= ' AND sk_kom_independen.id_rincian_sk = '.$id_jenis_sk->id;
            }

            $id_sql = "SELECT
                            sk_kom_independen.ID,
                            struktur_organ.nomenklatur_jabatan,
                            talenta.nama_lengkap,
                            periode_jabatan.nama,
                            sk_kom_independen.tanggal_awal_menjabat,
                            sk_kom_independen.tanggal_akhir_menjabat
                        FROM
                            sk_kom_independen
                            LEFT JOIN rincian_sk ON rincian_sk.ID = sk_kom_independen.id_rincian_sk
                            LEFT JOIN struktur_organ ON struktur_organ.ID = sk_kom_independen.id_struktur_organ
                            LEFT JOIN periode_jabatan ON periode_jabatan.ID = sk_kom_independen.id_periode_jabatan
                            LEFT JOIN talenta ON talenta.ID = sk_kom_independen.id_talenta
                            LEFT JOIN kategori_mobility ON kategori_mobility.ID = sk_kom_independen.id_kategori_mobility
                            LEFT JOIN jenis_mobility_jabatan ON jenis_mobility_jabatan.ID = sk_kom_independen.id_jenis_mobility
                            LEFT JOIN rekomendasi ON rekomendasi.ID = sk_kom_independen.id_rekomendasi
                        WHERE
                            $where
                        ORDER BY
                            sk_kom_independen.ID ASC";

            $isiindependen  = DB::select(DB::raw($id_sql));

            return datatables()->of($isiindependen)
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit-independen" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah Pengangkatan '.$row->nama_lengkap.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete-independen" data-id="'.$id.'" data-kelasbumn="'.$row->nama_lengkap.'" data-toggle="tooltip" data-original-title="Hapus Pengangkatan '.$row->nama_lengkap.'"><i class="flaticon-delete"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['nomenklatur_jabatan','nama_lengkap','nama','tanggal_awal_menjabat', 'tanggal_akhir_menjabat','action'])
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

    public function datatablesumindependen(Request $request)
    {

        try{

            $where = '';
            if($request->id_perusahaan){
                $where .= 'struktur_organ.id_perusahaan = '.$request->id_perusahaan;
            }

            if($request->id_surat_keputusan){
                $id_jenis_sk = RincianSK::where('id_jenis_sk', 7)->where('id_surat_keputusan', $request->id_surat_keputusan)->first();
                $where .= ' AND sk_kom_independen.id_rincian_sk = '.$id_jenis_sk->id;
            }

            $id_sql = "SELECT
                            sk_kom_independen.ID,
                            struktur_organ.nomenklatur_jabatan,
                            talenta.nama_lengkap,
                            periode_jabatan.nama,
                            sk_kom_independen.tanggal_awal_menjabat,
                            sk_kom_independen.tanggal_akhir_menjabat
                        FROM
                            sk_kom_independen
                            LEFT JOIN rincian_sk ON rincian_sk.ID = sk_kom_independen.id_rincian_sk
                            LEFT JOIN struktur_organ ON struktur_organ.ID = sk_kom_independen.id_struktur_organ
                            LEFT JOIN periode_jabatan ON periode_jabatan.ID = sk_kom_independen.id_periode_jabatan
                            LEFT JOIN talenta ON talenta.ID = sk_kom_independen.id_talenta
                            LEFT JOIN kategori_mobility ON kategori_mobility.ID = sk_kom_independen.id_kategori_mobility
                            LEFT JOIN jenis_mobility_jabatan ON jenis_mobility_jabatan.ID = sk_kom_independen.id_jenis_mobility
                            LEFT JOIN rekomendasi ON rekomendasi.ID = sk_kom_independen.id_rekomendasi
                        WHERE
                            $where
                        ORDER BY
                            sk_kom_independen.ID ASC";

            $isiindependen  = DB::select(DB::raw($id_sql));

            return datatables()->of($isiindependen)

            ->rawColumns(['nomenklatur_jabatan','nama_lengkap','nama','tanggal_awal_menjabat', 'tanggal_akhir_menjabat'])
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

    public function createangkat(Request $request)
    {
        $id_surat_keputusan = $request->id_surat_keputusan;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;

        $pejabat_sql = "SELECT
                            talenta.ID,
                        CASE

                                WHEN ( organ_perusahaan.aktif = 't' ) THEN
                                talenta.nama_lengkap || ' (' || struktur_organ.nomenklatur_jabatan || ') ' || perusahaan.nama_lengkap ELSE talenta.nama_lengkap
                            END AS nama_lengkap,
                            talenta.nik
                        FROM
                            talenta
                            LEFT JOIN organ_perusahaan ON organ_perusahaan.id_talenta = talenta.ID
                            LEFT join struktur_organ on struktur_organ.id = organ_perusahaan.id_struktur_organ
                            LEFT join perusahaan on perusahaan.id = struktur_organ.id_perusahaan
                        ORDER BY
                            talenta.nama_lengkap ASC";
        $pejabats = DB::select(DB::raw($pejabat_sql));
        $periodes = Periode::orderBy('id','asc')->get();

        $jabatan_sql = "SELECT
                            struktur_organ.ID,
                        CASE

                                WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS jabatan
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID
                        WHERE
                            struktur_organ.id_perusahaan = $id_perusahaan
                            AND grup_jabatan.ID = $grup_jabatan_id
                            AND struktur_organ.aktif = 't'
                        ORDER BY
                            struktur_organ.ID ASC";
        $jabatans  = DB::select(DB::raw($jabatan_sql));

        return view($this->__route.'.formangkat',[
            'actionform' => 'insert',
            'id_surat_keputusan' => $id_surat_keputusan,
            'id_perusahaan' => $id_perusahaan,
            'perusahaan' => Perusahaan::where('is_active',true)->get(),
            'grup_jabatan_id' => $grup_jabatan_id,
            'pejabats' => $pejabats,
            'periodes' => $periodes,
            'jabatans' => $jabatans
        ]);
    }

    public function createorang(Request $request)
    {

        return view($this->__route.'.formorang',[
            'actionform' => 'insert'
        ]);
    }

    public function createhenti(Request $request)
    {
        $id_surat_keputusan = $request->id_surat_keputusan;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;

        $pejabat_sql = "SELECT
                            talenta.ID,
                        CASE

                                WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                talenta.nama_lengkap || ' ( ' || struktur_organ.nomenklatur_jabatan || ' )' || ' - ' || ' (' || surat_keputusan.nomor || ') ' ELSE talenta.nama_lengkap || ' ( ' || sk_perubahan_nomenklatur.nomenklatur_baru || ' )' || ' - ' || ' (' || surat_keputusan.nomor || ') '
                            END AS nama
                        FROM
                            organ_perusahaan
                            LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                            LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID
                        WHERE
                            struktur_organ.id_perusahaan = $id_perusahaan
                            AND grup_jabatan.ID = $grup_jabatan_id
                            AND organ_perusahaan.aktif = 't'
                        ORDER BY
                            struktur_organ.urut ASC";

        $pejabats  = DB::select(DB::raw($pejabat_sql));

        $alasan_sql = "SELECT
                            alasan_pemberhentian.id,
                          alasan_pemberhentian.keterangan || ' (' || kategori_pemberhentian.nama || ')' as keterangan
                        FROM
                            alasan_pemberhentian
                            LEFT JOIN kategori_pemberhentian ON kategori_pemberhentian.ID = alasan_pemberhentian.id_kategori_pemberhentian
                        ORDER BY
                        ID ASC";
        $alasanberhentis  = DB::select(DB::raw($alasan_sql));

        return view($this->__route.'.formhenti',[
            'actionform' => 'insert',
            'id_surat_keputusan' => $id_surat_keputusan,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'pejabats' => $pejabats,
            'alasanberhentis' => $alasanberhentis
        ]);
    }

    public function createklatur(Request $request)
    {
        $id_surat_keputusan = $request->id_surat_keputusan;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;

        $jabatan_sql = "SELECT
                            struktur_organ.ID,
                        CASE

                                WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS jabatan
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID
                        WHERE
                            struktur_organ.id_perusahaan = $id_perusahaan
                            AND grup_jabatan.ID = $grup_jabatan_id
                            AND struktur_organ.aktif = 't'
                        ORDER BY
                            struktur_organ.ID ASC";
        $jabatan_baru= " select so.id, so.nomenklatur_jabatan as jabatan from struktur_organ so 
                    LEFT JOIN jenis_jabatan jj ON jj.ID = so.id_jenis_jabatan
                    LEFT JOIN grup_jabatan gj ON gj.ID = jj.id_grup_jabatan
                    where 
                    so.id_perusahaan = $id_perusahaan
                    AND gj.ID = $grup_jabatan_id
                    AND so.aktif = 't'
                    order by id asc ";
        $jabatans  = DB::select(DB::raw($jabatan_baru));
        return view($this->__route.'.formklatur',[
            'actionform' => 'insert',
            'id_surat_keputusan' => $id_surat_keputusan,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'jabatans' => $jabatans
        ]);
    }

    public function createplt(Request $request)
    {
        $id_surat_keputusan = $request->id_surat_keputusan;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;

        $pejabat_sql = "SELECT
                            talenta.id,
                          talenta.nama_lengkap || ' ( ' || struktur_organ.nomenklatur_jabatan || ' )'   as nama
                        FROM
                            organ_perusahaan
                            LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                            LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                            left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                            left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                        WHERE
                          struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id
                        ORDER BY
                          organ_perusahaan.id_struktur_organ asc";
        $pejabats  = DB::select(DB::raw($pejabat_sql));

        $jabatan_sql = "SELECT
                            struktur_organ.ID,
                        CASE

                                WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS jabatan
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID
                        WHERE
                            struktur_organ.id_perusahaan = $id_perusahaan
                            AND grup_jabatan.ID = $grup_jabatan_id
                            AND struktur_organ.aktif = 't'
                        ORDER BY
                            struktur_organ.ID ASC";
        $jabatans  = DB::select(DB::raw($jabatan_sql));

        return view($this->__route.'.formplt',[
            'actionform' => 'insert',
            'id_surat_keputusan' => $id_surat_keputusan,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'pejabats' => $pejabats,
            'jabatans' => $jabatans
        ]);
    }

    public function createalt(Request $request)
    {
        $id_surat_keputusan = $request->id_surat_keputusan;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;
        $pejabat_sql = "SELECT
                            talenta.id,
                          talenta.nama_lengkap || ' ( ' || struktur_organ.nomenklatur_jabatan || ' )'   as nama
                        FROM
                            organ_perusahaan
                            LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                            LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                            left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                            left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                        WHERE
                          struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id and organ_perusahaan.aktif = 't'
                        ORDER BY
                          organ_perusahaan.id_struktur_organ asc";
        $pejabats  = DB::select(DB::raw($pejabat_sql));

        $jabatan_sql = "SELECT
                            struktur_organ.ID,
                        CASE

                                WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS jabatan
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID
                        WHERE
                            struktur_organ.id_perusahaan = $id_perusahaan
                            AND grup_jabatan.ID = $grup_jabatan_id
                            and struktur_organ.aktif = 't' 
			--and sk_perubahan_nomenklatur.aktif = 't'
                        ORDER BY
                            struktur_organ.ID ASC";
        $jabatans  = DB::select(DB::raw($jabatan_sql));
        return view($this->__route.'.formalt',[
            'actionform' => 'insert',
            'id_surat_keputusan' => $id_surat_keputusan,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'pejabats' => $pejabats,
            'jabatans' => $jabatans
        ]);
    }

    public function createangkatlagi(Request $request)
    {
        $id_surat_keputusan = $request->id_surat_keputusan;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;
        $periodes = Periode::whereIN('id',array(2))->orderBy('id','asc')->get();

        $pejabats = DB::table('organ_perusahaan')
                       ->leftJoin('talenta', 'talenta.id', '=', 'organ_perusahaan.id_talenta')
                       ->leftJoin('struktur_organ', 'struktur_organ.id', '=', 'organ_perusahaan.id_struktur_organ')
                       ->leftJoin('jenis_jabatan', 'jenis_jabatan.id', '=', 'struktur_organ.id_jenis_jabatan')
                       ->leftJoin('grup_jabatan', 'grup_jabatan.id', '=', 'jenis_jabatan.id_grup_jabatan')
                       ->leftJoin('surat_keputusan', 'surat_keputusan.id', '=', 'organ_perusahaan.id_surat_keputusan')
                       ->leftJoin('sk_pengangkatan', 'sk_pengangkatan.id_talenta', '=', 'organ_perusahaan.id_talenta')
                       ->select(DB::raw("talenta.id ,talenta.nama_lengkap || ' ( ' || struktur_organ.nomenklatur_jabatan || ' )' AS nama"))
                       ->where('struktur_organ.id_perusahaan', $id_perusahaan)
                       ->where('grup_jabatan.id', $grup_jabatan_id)
                       ->where('sk_pengangkatan.id_periode_jabatan', '<>', 2)
                       ->where('organ_perusahaan.aktif', '=', 't')
                       ->orderBy('organ_perusahaan.id_struktur_organ', 'asc')
                       ->orderBy('talenta.nama_lengkap', 'asc')
                       ->get();

        return view($this->__route.'.formangkatlagi',[
            'actionform' => 'insert',
            'id_surat_keputusan' => $id_surat_keputusan,
            'id_perusahaan' => $id_perusahaan,
            'perusahaan' => Perusahaan::where('is_active',true)->get(),
            'grup_jabatan_id' => $grup_jabatan_id,
            'pejabats' => $pejabats,
            'periodes' => $periodes
        ]);
    }

    public function createindependen(Request $request)
    {
        $id_surat_keputusan = $request->id_surat_keputusan;
        $id_perusahaan = $request->id_perusahaan;
        $grup_jabatan_id = $request->grup_jabatan_id;

        $pejabat_sql = "SELECT
                            talenta.id,
                          talenta.nama_lengkap || ' ( ' || struktur_organ.nomenklatur_jabatan || ' )'   as nama_lengkap
                        FROM
                            organ_perusahaan
                            LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                            LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                            left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                            left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                        WHERE
                          struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id
                          AND struktur_organ.aktif = 't'
                        ORDER BY
                          organ_perusahaan.id_struktur_organ asc";
        $pejabats  = DB::select(DB::raw($pejabat_sql));
        $periodes = Periode::orderBy('id','asc')->get();

        return view($this->__route.'.formindependen',[
            'actionform' => 'insert',
            'id_surat_keputusan' => $id_surat_keputusan,
            'id_perusahaan' => $id_perusahaan,
            'grup_jabatan_id' => $grup_jabatan_id,
            'pejabats' => $pejabats,
            'periodes' => $periodes
        ]);
    }

    public function storeangkat(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateformangkat($request);

        if (!$validator->fails()) {
            $param['id_struktur_organ'] = $request->input('id_struktur_organ');
            $param['id_periode_jabatan'] = $request->input('id_periode_jabatan');
            $param['id_talenta'] = $request->input('id_talenta');
            $param['tanggal_awal_menjabat'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_awal_menjabat)->format('Y-m-d');
            $param['tanggal_akhir_menjabat'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');
            $param['id_kategori_mobility'] = 1;
            $param['id_jenis_mobility'] = 1;
            $param['id_rekomendasi'] = 1;

            $paramorgan['id_struktur_organ'] = $request->input('id_struktur_organ');
            $paramorgan['id_talenta'] = $request->input('id_talenta');
            $paramorgan['id_surat_keputusan'] = $request->input('id_surat_keputusan');
            $paramorgan['tanggal_awal'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_awal_menjabat)->format('Y-m-d');
            $paramorgan['tanggal_akhir'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');
            $paramorgan['aktif'] = 't';
            $paramorgan['id_periode_jabatan'] = $request->input('id_periode_jabatan');

            $id_surat_keputusan = $request->input('id_surat_keputusan');
            $rincian_sk = RincianSK::where('id_surat_keputusan', $id_surat_keputusan)->where('id_jenis_sk', 1)->first();

            $param['id_rincian_sk'] = $rincian_sk->id;

            /**
             * Update untuk riwayat jabatan
             */
            $id_struktur_organ = $request->input('id_struktur_organ');
            $id_perusahaan = $request->input('id_perusahaan');
            $id_talenta = $request->input('id_talenta');
            $strukturorgan = StrukturOrgan::where('id', $id_struktur_organ)->first();
            $perusahaan = Perusahaan::where('id', $id_perusahaan)->first();

            $paramriwayat['id_talenta'] = $request->input('id_talenta');
            $paramriwayat['masih_bekerja'] = 't';
            $paramriwayat['tanggal_awal'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_awal_menjabat)->format('Y-m-d');
            $paramriwayat['tanggal_akhir'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');
            $paramriwayat['jabatan'] = $strukturorgan->nomenklatur_jabatan;
            $paramriwayat['nama_perusahaan'] = $perusahaan->nama_lengkap;

            $paramtalenta['id_perusahaan'] = $id_perusahaan;

            /**
             * End Update riwayat jabatan
             */

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{

                                  $skangkatorgan = OrganPerusahaan::create((array)$paramorgan);
                                  $organid = OrganPerusahaan::where('id_surat_keputusan', $id_surat_keputusan)->where('id_struktur_organ', $request->input('id_struktur_organ'))->where('id_talenta', $request->input('id_talenta'))->first();
                                  $param['id_organ_perusahaan'] = $organid->id;
                                  $skangkat = SKPengangkatan::create((array)$param);
                                  $riwayatjabatan = RiwayatJabatanDirkomwas::create((array)$paramriwayat);

                                  $parastrukmorgan['kosong'] = 'f';
                                  $strukorgan = StrukturOrgan::find((int)$id_struktur_organ);
                                  $strukorgan->update((array)$parastrukmorgan);

                                  $talentadir = Talenta::find((int)$id_talenta);
                                  $talentadir->update((array)$paramtalenta);

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
                                    'msg' => 'Gagal ubah data',
                                    'title' => 'Gagal'
                                  ];
                               }

                break;

                case 'update': DB::beginTransaction();
                               try{
                                  $getriwayatjabatan = RiwayatJabatanDirkomwas::where('id_talenta', $id_talenta)->first();

                                  $skangkat = SKPengangkatan::find((int)$request->input('id'));
                                  $skangkatorgan = OrganPerusahaan::find((int)$skangkat->id_organ_perusahaan);
                                  $riwayatjabatan = RiwayatJabatanDirkomwas::find((int)$getriwayatjabatan->id);

                                  $parastrukmorgan['kosong'] = 'f';
                                  $strukorgan = StrukturOrgan::find((int)$id_struktur_organ);

                                  $skangkatorgan->update((array)$paramorgan);
                                  $skangkat->update((array)$param);
                                  $riwayatjabatan->update((array)$paramriwayat);
                                  $strukorgan->update((array)$parastrukmorgan);

                                  $talentadir = Talenta::find((int)$id_talenta);
                                  $talenta->update((array)$paramtalenta);

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

    public function storehenti(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateformhenti($request);

        if (!$validator->fails()) {

            $id_talenta = (int)$request->input('id_talenta');
            $param['id_talenta'] = $request->input('id_talenta');
            $param['tanggal_akhir_menjabat'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');
            $param['id_alasan_pemberhentian'] = $request->input('id_alasan_pemberhentian');

            $id_surat_keputusan = $request->input('id_surat_keputusan');
            $id_perusahaan = $request->input('id_perusahaan');
            $grup_jabatan_id = $request->input('grup_jabatan_id');

            $rincian_sk = RincianSK::where('id_surat_keputusan', $id_surat_keputusan)->where('id_jenis_sk', 2)->first();


            $struktur_sql = "SELECT
                                struktur_organ.id
                            FROM
                                organ_perusahaan
                                LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                                LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                                left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                                left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                                LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                            WHERE
                              struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id and talenta.id = $id_talenta
                            ORDER BY
                              organ_perusahaan.id_struktur_organ asc";
            $struktur_id  = DB::select(DB::raw($struktur_sql));
            $id_struktur_organ = $struktur_id[0]->id;

            $param['id_struktur_organ'] = $struktur_id[0]->id;

            $param['id_rincian_sk'] = $rincian_sk->id;

            $organ_sql = "SELECT
                                organ_perusahaan.id
                            FROM
                                organ_perusahaan
                                LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                                LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                                left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                                left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                                LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                            WHERE
                              struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id and talenta.id = $id_talenta
                            ORDER BY
                              organ_perusahaan.id_struktur_organ asc";
            $organ_id  = DB::select(DB::raw($organ_sql));

            $paramorgan['aktif'] = 'f';
            $parastrukmorgan['kosong'] = 't';
            $paramorgan['tanggal_akan_berakhir'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');
            $paramtalenta['id_perusahaan'] = 0;

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{

                                  $paramorgan['aktif'] = 'f';
                                  $paramorgan['tanggal_akan_berakhir'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');
                                  $skhenti = SKPemberhentian::create((array)$param);
                                  $organhenti = OrganPerusahaan::find((int)$organ_id[0]->id);
                                  $organhenti->update((array)$paramorgan);
                                  $strukorgan = StrukturOrgan::find((int)$id_struktur_organ);
                                  $strukorgan->update((array)$parastrukmorgan);
                                  $talentadir = Talenta::find((int)$id_talenta);
                                  $talentadir->update((array)$paramtalenta);

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
                                  $paramorgan['aktif'] = 'f';
                                  $paramorgan['tanggal_akan_berakhir'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');
                                  $skhenti = SKPemberhentian::find((int)$request->input('id'));
                                  $skhenti->update((array)$param);
                                  $organhenti = OrganPerusahaan::find((int)$organ_id[0]->id);
                                  $organhenti->update((array)$paramorgan);
                                  $strukorgan = StrukturOrgan::find((int)$id_struktur_organ);
                                  $strukorgan->update((array)$parastrukmorgan);
                                  $talentadir = Talenta::find((int)$id_talenta);
                                  $talentadir->update((array)$paramtalenta);

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

    public function storeklatur(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateformklatur($request);

        if (!$validator->fails()) {
            $param['id_struktur_organ'] = $request->input('id_struktur_organ');
            $param['nomenklatur_baru'] = $request->input('nomenklatur_baru');
            $param['id_kategori_mobility'] = 1;

            $id_surat_keputusan = $request->input('id_surat_keputusan');
            $rincian_sk = RincianSK::where('id_surat_keputusan', $id_surat_keputusan)->where('id_jenis_sk', 4)->first();

            $id_struktur_organ = $request->input('id_struktur_organ');
            $id_perusahaan = $request->input('id_perusahaan');
            $param['id_rincian_sk'] = $rincian_sk->id;
            $param['aktif'] = true;

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  DB::statement("UPDATE sk_perubahan_nomenklatur SET aktif = 'f' where id_struktur_organ = $id_struktur_organ");
                                  $skklatur = SKNomenklatur::create((array)$param);

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
                                  $skklatur = SKNomenklatur::find((int)$request->input('id'));
                                  $skklatur->update((array)$param);

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

    public function storeplt(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateformplt($request);

        if (!$validator->fails()) {
            $paramorgan['id_struktur_organ'] = $request->input('id_struktur_organ');
            $paramorgan['id_talenta'] = $request->input('id_talenta');
            $paramorgan['id_periode_jabatan'] = 1;

            $id_surat_keputusan = $request->input('id_surat_keputusan');
            $paramorgan['id_surat_keputusan'] = $id_surat_keputusan;
            $paramorgan['tanggal_awal'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_awal_menjabat)->format('Y-m-d');
            $paramorgan['tanggal_akhir'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');
            $paramorgan['tanggal_akan_berakhir'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');

            $rincian_sk = RincianSK::where('id_surat_keputusan', $id_surat_keputusan)->where('id_jenis_sk', 3)->first();

            $param['id_rincian_sk'] = $rincian_sk->id;
            $param['sk_id'] = $request->input('sk_id');


            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{

                                  $paramorgan['plt'] = 't';
                                  $paramorgan['aktif'] = 't';
                                  $skpltorgan = OrganPerusahaan::create((array)$paramorgan);
                                  $organid = OrganPerusahaan::where('id_surat_keputusan', $id_surat_keputusan)->where('id_struktur_organ', $request->input('id_struktur_organ'))->where('id_talenta', $request->input('id_talenta'))->first();
                                  $param['id_organ_perusahaan'] = $organid->id;
                                  $param['id_struktur_organ'] = $request->input('id_struktur_organ');
                                  $skplt = SKPenetapanplt::create((array)$param);

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
                                  $paramorgan['plt'] = 't';
                                  $paramorgan['aktif'] = 't';
                                  $skplt = SKPenetapanplt::find((int)$request->input('id'));
                                  $skpltorgan = OrganPerusahaan::find((int)$skplt->id_organ_perusahaan);
                                  $skpltorgan->update((array)$paramorgan);
                                  $skplt->update((array)$param);

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

    public function storealt(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateformalt($request);

        if (!$validator->fails()) {

            $paramorgan['id_talenta'] = $request->input('id_talenta');

            $id_perusahaan = $request->input('id_perusahaan');
            $grup_jabatan_id = $request->input('grup_jabatan_id');
            $id_talenta = $request->input('id_talenta');

            $id_surat_keputusan = $request->input('id_surat_keputusan');
            $paramorgan['id_surat_keputusan'] = $id_surat_keputusan;
            $paramorgan['tanggal_awal'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_awal_menjabat)->format('Y-m-d');
            $paramorgan['tanggal_akhir'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');
            $paramorgan['tanggal_akan_berakhir'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');

            $struktur_sql = "SELECT
                                struktur_organ.id
                            FROM
                                organ_perusahaan
                                LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                                LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                                left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                                left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                                LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                            WHERE
                              struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id and talenta.id = $id_talenta
                            ORDER BY
                              organ_perusahaan.id_struktur_organ asc";
            $struktur_id  = DB::select(DB::raw($struktur_sql));

            //dd($struktur_id);

            $organ_sql = "SELECT
                                organ_perusahaan.id
                            FROM
                                organ_perusahaan
                                LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                                LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                                left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                                left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                                LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                            WHERE
                              struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id and talenta.id = $id_talenta
                            ORDER BY
                              organ_perusahaan.id_struktur_organ asc";
            $organ_id  = DB::select(DB::raw($organ_sql));



            $organid = OrganPerusahaan::find((int)$organ_id[0]->id);
            $param1['aktif'] = 'f';
            $organid->update((array)$param1);

            $paramorgan['id_struktur_organ'] = $request->input('id_struktur_organ');
            $paramorgan['aktif'] = 't';
            $paramorgan['id_periode_jabatan'] = 1;

            $id_struktur_organ = $struktur_id[0]->id;

            $rincian_sk = RincianSK::where('id_surat_keputusan', $id_surat_keputusan)->where('id_jenis_sk', 5)->first();

            $param['id_rincian_sk'] = $rincian_sk->id;
            $param['id_kategori_mobility'] = 1;

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{

                                  $skaltorgan = OrganPerusahaan::create((array)$paramorgan);
                                  $organid = OrganPerusahaan::where('id_surat_keputusan', $id_surat_keputusan)->where('id_struktur_organ',$request->input('id_struktur_organ'))->where('id_talenta', $id_talenta)->first();

                                  $param['id_organ_perusahaan'] = $organid->id;
                                  $param['id_struktur_organ'] = $request->input('id_struktur_organ');
                                  $skalt = SKAlihtugas::create((array)$param);

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
                                  $skalt = SKAlihtugas::find((int)$request->input('id'));

                                  $skaltorgan = OrganPerusahaan::find((int)$skalt->id_organ_perusahaan);
                                  $skaltorgan->update((array)$paramorgan);
                                  $skalt->update((array)$param);

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

    public function storeangkatlagi(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateformangkatlagi($request);

        if (!$validator->fails()) {
            $id_surat_keputusan = $request->input('id_surat_keputusan');
            $id_perusahaan = $request->input('id_perusahaan');
            $id_periode_jabatan = $request->input('id_periode_jabatan');
            $grup_jabatan_id = $request->input('grup_jabatan_id');
            $id_talenta = $request->input('id_talenta');

            $param['id_talenta'] = $id_talenta;
            $param['tanggal_awal_menjabat'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_awal_menjabat)->format('Y-m-d');
            $param['tanggal_akhir_menjabat'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');

            $struktur_sql = "SELECT
                                struktur_organ.id
                            FROM
                                organ_perusahaan
                                LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                                LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                                left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                                left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                                LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                            WHERE
                              struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id and talenta.id = $id_talenta
                            ORDER BY
                              organ_perusahaan.id_struktur_organ asc";
            $struktur_id  = DB::select(DB::raw($struktur_sql));

            $id_struktur_organ = $struktur_id[0]->id;
            $param['id_struktur_organ'] = $id_struktur_organ;

            $rincian_sk = RincianSK::where('id_surat_keputusan', $id_surat_keputusan)->where('id_jenis_sk', 1)->first();

            $param['id_rincian_sk'] = $rincian_sk->id;

            $organ_sql = "SELECT
                                organ_perusahaan.id
                            FROM
                                organ_perusahaan
                                LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                                LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                                left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                                left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                                LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                            WHERE
                              struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id and talenta.id = $id_talenta
                            ORDER BY
                              organ_perusahaan.id_struktur_organ asc";
            $organ_id  = DB::select(DB::raw($organ_sql));

            $param['id_kategori_mobility'] = 1;
            $param['id_jenis_mobility'] = 1;
            $param['id_rekomendasi'] = 1;
            $param['id_periode_jabatan'] = $id_periode_jabatan;

            $paramorgan['id_struktur_organ'] = $struktur_id[0]->id;
            $paramorgan['id_talenta'] = $id_talenta;
            $paramorgan['id_surat_keputusan'] = $id_surat_keputusan;
            $paramorgan['tanggal_awal'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_awal_menjabat)->format('Y-m-d');
            $paramorgan['tanggal_akhir'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');
            $paramorgan['aktif'] = 't';
            $paramorgan['id_periode_jabatan'] = $request->input('id_periode_jabatan');

            $paramorganhenti['aktif'] = 'f';
            $paramorganhenti['tanggal_akan_berakhir'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{

                                  //berhentikan dulu sk lama
                                  $organhenti = OrganPerusahaan::find((int)$organ_id[0]->id);
                                  $organhenti->update((array)$paramorganhenti);

                                  //masukan sk baru
                                  $skangkatlagiorgan = OrganPerusahaan::create((array)$paramorgan);
                                  $organid = OrganPerusahaan::where('id_surat_keputusan', $id_surat_keputusan)->where('id_struktur_organ', $id_struktur_organ)->where('id_talenta', $id_talenta)->first();
                                  $param['id_organ_perusahaan'] = $organid->id;

                                  //insert ke sk pengangkatan
                                  $skangkatlagi = SKPengangkatan::create((array)$param);

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
                                  $skangkatlagi = SKPengangkatan::find((int)$request->input('id'));
                                  $skangkatlagiorgan = OrganPerusahaan::find((int)$skangkatlagi->id_organ_perusahaan);
                                  $skangkatlagiorgan->update((array)$paramorgan);
                                  $skangkatlagi->update((array)$param);

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

    public function storeindependen(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateformindependen($request);

        if (!$validator->fails()) {
            $param['id_periode_jabatan'] = $request->input('id_periode_jabatan');
            $param['id_talenta'] = $request->input('id_talenta');
            $param['tanggal_awal_menjabat'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_awal_menjabat)->format('Y-m-d');
            $param['tanggal_akhir_menjabat'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal_akhir_menjabat)->format('Y-m-d');
            $param['id_kategori_mobility'] = 1;
            $param['id_jenis_mobility'] = 1;
            $param['id_rekomendasi'] = 1;

            $organid = OrganPerusahaan::where('id_talenta', $request->input('id_talenta'))->where('aktif', true)->first();
            $strukturid = StrukturOrgan::where('id', $organid->id_struktur_organ)->first();
            $jenisjabatid = JenisJabatan::where('id', $strukturid->id_jenis_jabatan)->first();

            if($jenisjabatid->id == 3 && $jenisjabatid->id == 12){
                $paramorgan['nomenklatur'] = 'Komisaris Utama merangkap Komisaris Independen';
            } elseif ($jenisjabatid->id == 11) {
                $paramorgan['nomenklatur'] = 'Komisaris Independen';
            }

            $paramorgan['komisaris_independen'] = 't';
            $param['id_struktur_organ'] = $organid->id_struktur_organ;

            $id_surat_keputusan = $request->input('id_surat_keputusan');
            $rincian_sk = RincianSK::where('id_surat_keputusan', $id_surat_keputusan)->where('id_jenis_sk', 7)->first();

            $param['id_rincian_sk'] = $rincian_sk->id;

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{

                                  $skindependenorgan = OrganPerusahaan::find((int)$organid->id);
                                  $skindependenorgan->update((array)$paramorgan);
                                  $skindependen = SKKomIndependen::create((array)$param);

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
                                  $skindependen = SKKomIndependen::find((int)$request->input('id'));
                                  $skindependenorgan = OrganPerusahaan::find((int)$skindependen->id_organ_perusahaan);
                                  $skindependenorgan->update((array)$paramorgan);
                                  $skindependen->update((array)$param);

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

    public function editangkat(Request $request)
    {
        try{

            $id_surat_keputusan = $request->id_surat_keputusan;
            $id_perusahaan = $request->id_perusahaan;
            $grup_jabatan_id = $request->grup_jabatan_id;

            $pejabat_sql = "SELECT
                            talenta.ID,
                        CASE

                                WHEN ( organ_perusahaan.aktif = 't' ) THEN
                                talenta.nama_lengkap || ' (' || struktur_organ.nomenklatur_jabatan || ') ' || perusahaan.nama_lengkap ELSE talenta.nama_lengkap
                            END AS nama_lengkap,
                            talenta.nik
                        FROM
                            talenta
                            LEFT JOIN organ_perusahaan ON organ_perusahaan.id_talenta = talenta.ID
                            LEFT join struktur_organ on struktur_organ.id = organ_perusahaan.id_struktur_organ
                            LEFT join perusahaan on perusahaan.id = struktur_organ.id_perusahaan
                        ORDER BY
                            talenta.nama_lengkap ASC";
            $pejabats = DB::select(DB::raw($pejabat_sql));
            $periodes = Periode::orderBy('id','asc')->get();
            $jabatan_sql = "SELECT
                            struktur_organ.ID,
                        CASE

                                WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS jabatan
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID
                        WHERE
                            struktur_organ.id_perusahaan = $id_perusahaan
                            AND grup_jabatan.ID = $grup_jabatan_id
                            AND struktur_organ.aktif = 't'
                        ORDER BY
                            struktur_organ.ID ASC";
            $jabatans  = DB::select(DB::raw($jabatan_sql));
            $skangkat = SKPengangkatan::find((int)$request->input('id'));

            $sk_sql = "SELECT ID
                        ,
                        nomor
                    FROM
                        surat_keputusan
                    WHERE
                        id_perusahaan = $id_perusahaan and save='t' ";
            $listsks  = DB::select(DB::raw($sk_sql));

            return view($this->__route.'.formangkat',[
                'actionform' => 'update',
                'skangkat' => $skangkat,
                'pejabats' => $pejabats,
                'periodes' => $periodes,
                'jabatans' => $jabatans,
                'id_surat_keputusan' => $id_surat_keputusan,
                'id_perusahaan' => $id_perusahaan,
                'grup_jabatan_id' => $grup_jabatan_id,
                'listsks' => $listsks

            ]);
        }catch(Exception $e){}
    }

    public function edithenti(Request $request)
    {
        try{

            $id_surat_keputusan = $request->id_surat_keputusan;
            $id_perusahaan = $request->id_perusahaan;
            $grup_jabatan_id = $request->grup_jabatan_id;
            $pejabat_sql = "SELECT
                            talenta.ID,
                        CASE

                                WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                talenta.nama_lengkap || ' ( ' || struktur_organ.nomenklatur_jabatan || ' )' || ' - ' || ' (' || surat_keputusan.nomor || ') ' ELSE talenta.nama_lengkap || ' ( ' || sk_perubahan_nomenklatur.nomenklatur_baru || ' )' || ' - ' || ' (' || surat_keputusan.nomor || ') '
                            END AS nama
                        FROM
                            organ_perusahaan
                            LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                            LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID
                        WHERE
                            struktur_organ.id_perusahaan = $id_perusahaan
                            AND grup_jabatan.ID = $grup_jabatan_id
                            AND organ_perusahaan.aktif = 't'
                        ORDER BY
                            struktur_organ.urut ASC";
            $pejabats  = DB::select(DB::raw($pejabat_sql));

            $skhenti = SKPemberhentian::find((int)$request->input('id'));

            $alasan_sql = "SELECT
                            alasan_pemberhentian.id,
                          alasan_pemberhentian.keterangan || ' (' || kategori_pemberhentian.nama || ')' as keterangan
                        FROM
                            alasan_pemberhentian
                            LEFT JOIN kategori_pemberhentian ON kategori_pemberhentian.ID = alasan_pemberhentian.id_kategori_pemberhentian
                        ORDER BY
                        ID ASC";
            $alasanberhentis  = DB::select(DB::raw($alasan_sql));

            return view($this->__route.'.formhenti',[
                'actionform' => 'update',
                'skhenti' => $skhenti,
                'pejabats' => $pejabats,
                'id_surat_keputusan' => $id_surat_keputusan,
                'id_perusahaan' => $id_perusahaan,
                'grup_jabatan_id' => $grup_jabatan_id,
                'alasanberhentis' => $alasanberhentis

            ]);
        }catch(Exception $e){}
    }

    public function editklatur(Request $request)
    {
        try{

            $id_surat_keputusan = $request->id_surat_keputusan;
            $id_perusahaan = $request->id_perusahaan;
            $grup_jabatan_id = $request->grup_jabatan_id;

            $jabatan_sql = "SELECT
                            struktur_organ.ID,
                        CASE

                                WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS jabatan
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID
                        WHERE
                            struktur_organ.id_perusahaan = $id_perusahaan
                            AND grup_jabatan.ID = $grup_jabatan_id
                            AND struktur_organ.aktif = 't'
                        ORDER BY
                            struktur_organ.ID ASC";

            $jabatans  = DB::select(DB::raw($jabatan_sql));
            $skklatur = SKNomenklatur::find((int)$request->input('id'));

                return view($this->__route.'.formklatur',[
                    'actionform' => 'update',
                    'skklatur' => $skklatur,
                    'jabatans' => $jabatans,
                    'id_surat_keputusan' => $id_surat_keputusan,
                    'id_perusahaan' => $id_perusahaan,
                    'grup_jabatan_id' => $grup_jabatan_id

                ]);
        }catch(Exception $e){}
    }

    public function editplt(Request $request)
    {
        try{

            $id_surat_keputusan = $request->id_surat_keputusan;
            $id_perusahaan = $request->id_perusahaan;
            $grup_jabatan_id = $request->grup_jabatan_id;
            $pejabat_sql = "SELECT
                            talenta.id,
                          talenta.nama_lengkap || ' ( ' || struktur_organ.nomenklatur_jabatan || ' )'   as nama
                        FROM
                            organ_perusahaan
                            LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                            LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                            left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                            left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                        WHERE
                          struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id and organ_perusahaan.aktif = 't'
                        ORDER BY
                          organ_perusahaan.id_struktur_organ asc";
            $pejabats  = DB::select(DB::raw($pejabat_sql));

            $jabatan_sql = "SELECT
                            struktur_organ.ID,
                        CASE

                                WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS jabatan
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID
                        WHERE
                            struktur_organ.id_perusahaan = $id_perusahaan
                            AND grup_jabatan.ID = $grup_jabatan_id
                            AND struktur_organ.aktif = 't'
                        ORDER BY
                            struktur_organ.ID ASC";
            $jabatans  = DB::select(DB::raw($jabatan_sql));
            $skplt = SKPenetapanplt::find((int)$request->input('id'));
            $skpltorgan = OrganPerusahaan::find((int)$skplt->id_organ_perusahaan);

            return view($this->__route.'.formplt',[
                'actionform' => 'update',
                'skplt' => $skplt,
                'pejabats' => $pejabats,
                'jabatans' => $jabatans,
                'id_surat_keputusan' => $id_surat_keputusan,
                'id_perusahaan' => $id_perusahaan,
                'grup_jabatan_id' => $grup_jabatan_id,
                'skpltorgan' => $skpltorgan

            ]);
        }catch(Exception $e){}
    }

    public function editalt(Request $request)
    {
        try{

            $id_surat_keputusan = $request->id_surat_keputusan;
            $id_perusahaan = $request->id_perusahaan;
            $grup_jabatan_id = $request->grup_jabatan_id;
            $pejabat_sql = "SELECT
                                talenta.id,
                              talenta.nama_lengkap || ' ( ' || struktur_organ.nomenklatur_jabatan || ' )'   as nama
                            FROM
                                organ_perusahaan
                                LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                                LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                                left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                                left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                                LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                            WHERE
                              struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id and organ_perusahaan.aktif = 't'
                            ORDER BY
                              organ_perusahaan.id_struktur_organ asc";
            $pejabats  = DB::select(DB::raw($pejabat_sql));

            $jabatan_sql = "SELECT
                            struktur_organ.ID,
                        CASE

                                WHEN sk_perubahan_nomenklatur.nomenklatur_baru IS NULL THEN
                                struktur_organ.nomenklatur_jabatan ELSE sk_perubahan_nomenklatur.nomenklatur_baru
                            END AS jabatan
                        FROM
                            struktur_organ
                            LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                            LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
                            LEFT JOIN sk_perubahan_nomenklatur ON sk_perubahan_nomenklatur.id_struktur_organ = struktur_organ.ID
                        WHERE
                            struktur_organ.id_perusahaan = $id_perusahaan
                            AND grup_jabatan.ID = $grup_jabatan_id
                            AND struktur_organ.aktif = 't'
                        ORDER BY
                            struktur_organ.ID ASC";
            $jabatans  = DB::select(DB::raw($jabatan_sql));
            $skalt = SKAlihtugas::find((int)$request->input('id'));
            $skaltorgan = OrganPerusahaan::find((int)$skalt->id_organ_perusahaan);

                return view($this->__route.'.formalt',[
                    'actionform' => 'update',
                    'skalt' => $skalt,
                    'pejabats' => $pejabats,
                    'jabatans' => $jabatans,
                    'id_surat_keputusan' => $id_surat_keputusan,
                    'id_perusahaan' => $id_perusahaan,
                    'grup_jabatan_id' => $grup_jabatan_id,
                    'skaltorgan' => $skaltorgan

                ]);
        }catch(Exception $e){}
    }

    public function editindependen(Request $request)
    {
        try{

            $id_surat_keputusan = $request->id_surat_keputusan;
            $id_perusahaan = $request->id_perusahaan;
            $grup_jabatan_id = $request->grup_jabatan_id;

            $pejabat_sql = "SELECT
                                talenta.id,
                              talenta.nama_lengkap || ' ( ' || struktur_organ.nomenklatur_jabatan || ' )'   as nama_lengkap
                            FROM
                                organ_perusahaan
                                LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                                LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                                left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                                left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                                LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                            WHERE
                              struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id
                              AND struktur_organ.aktif = 't'
                            ORDER BY
                              organ_perusahaan.id_struktur_organ asc";
            $pejabats  = DB::select(DB::raw($pejabat_sql));
            $periodes = Periode::orderBy('id','asc')->get();

            $skindependen = SKKomIndependen::find((int)$request->input('id'));

            return view($this->__route.'.formindependen',[
                'actionform' => 'update',
                'skindependen' => $skindependen,
                'pejabats' => $pejabats,
                'periodes' => $periodes,
                'id_surat_keputusan' => $id_surat_keputusan,
                'id_perusahaan' => $id_perusahaan,
                'grup_jabatan_id' => $grup_jabatan_id

            ]);
        }catch(Exception $e){}
    }

    public function deleteangkat(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = SKPengangkatan::find((int)$request->input('id'));
            $dataorgan = OrganPerusahaan::find((int)$data->id_organ_perusahaan);
            $dataorgan->delete();
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

    public function deletehenti(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = SKPemberhentian::find((int)$request->input('id'));

            $id_r_sk = $data->id_rincian_sk;
            $rinciansk = RincianSK::find((int)$id_r_sk);

            $id_sk = $rinciansk->id_surat_keputusan;
            $surat_sk = SuratKeputusan::find((int)$id_sk);

            $id_talenta = $data->id_talenta;
            $id_perusahaan = $surat_sk->id_perusahaan;
            $grup_jabatan_id = $surat_sk->id_grup_jabatan;

            $struktur_sql = "SELECT
                                struktur_organ.id
                            FROM
                                organ_perusahaan
                                LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                                LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                                left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                                left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                                LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                            WHERE
                              struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id and talenta.id = $id_talenta
                            ORDER BY
                              organ_perusahaan.id_struktur_organ asc";
            $struktur_id  = DB::select(DB::raw($struktur_sql));

            $id_struktur_organ = $struktur_id[0]->id;

            $organ_sql = "SELECT
                                organ_perusahaan.id
                            FROM
                                organ_perusahaan
                                LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                                LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                                left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                                left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                                LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                            WHERE
                              struktur_organ.id_perusahaan = $id_perusahaan and grup_jabatan.id = $grup_jabatan_id and talenta.id = $id_talenta
                            ORDER BY
                              organ_perusahaan.id_struktur_organ asc";
            $organ_id  = DB::select(DB::raw($organ_sql));

            $paramorgan['aktif'] = 't';
            $paramstrukorgan['kosong'] = 'f';
            $organhenti = OrganPerusahaan::find((int)$organ_id[0]->id);
            $organhenti->update((array)$paramorgan);

            $strukorgan = StrukturOrgan::find((int)$id_struktur_organ);
            $strukorgan->update((array)$paramstrukorgan);


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

    public function deleteklatur(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = SKNomenklatur::find((int)$request->input('id'));
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

    public function deleteplt(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = SKPenetapanplt::find((int)$request->input('id'));
            $dataorgan = OrganPerusahaan::find((int)$data->id_organ_perusahaan);
            $data->delete();
            $dataorgan->delete();

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

    public function deletealt(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = SKAlihtugas::find((int)$request->input('id'));
            $dataorgan = OrganPerusahaan::find((int)$data->id_organ_perusahaan);
            $data->delete();
            $dataorgan->delete();

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

    public function deleteindependen(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = SKKomIndependen::find((int)$request->input('id'));
            $dataorgan = OrganPerusahaan::find((int)$data->id_organ_perusahaan);
            $dataorgan->delete();
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

            $data = SuratKeputusan::find($request->id);
            $data->save = 't';
            $data->save();

            DB::statement('TRUNCATE TABLE view_organ_perusahaan');

            DB::insert("INSERT INTO view_organ_perusahaan (
                            id_struktur_organ,
                            id_talenta,
                            id_surat_keputusan,
                            tanggal_awal,
                            tanggal_akan_berakhir,
                            tanggal_akhir,
                            plt,
                            aktif,
                            komisaris_independen,
                            nomenklatur,
                            created_at,
                            updated_at,
                            prosentase_kelengkapan_sk,
                            id_periode_jabatan
                        ) SELECT
                        id_struktur_organ,
                        id_talenta,
                        id_surat_keputusan,
                        tanggal_awal,
                        tanggal_akan_berakhir,
                        tanggal_akhir,
                        plt,
                        aktif,
                        komisaris_independen,
                        nomenklatur,
                        created_at,
                        updated_at,
                        prosentase_kelengkapan_sk,
                        id_periode_jabatan
                        FROM
                            organ_perusahaan AS ppl");

            $request->session()->forget(['perusahaan_id', 'jenis_sk_id', 'grup_jabatan_id', 'nomor_sk', 'tgl_sk']);

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

    protected function validateformorang($request)
    {
        $required['nama_lengkap'] = 'required';
        $required['nik'] = 'required|regex:/(^[A-Za-z0-9]+$)+/u|unique:talenta|max:16';


        $message['nama_lengkap.required'] = 'Nama Lengkap wajib Diisi';
        $message['nik.required'] = 'NIK wajib Diisi';
        $message['nik.unique'] = 'NIK Sudah Digunakan, Silakan Periksa Kembali!!';
        $message['nik.regex'] = 'NIK Tanpa Menggunakan tanda Baca (hanya Angka)!!';
        $message['nik.max'] = 'NIK harus 16 karakter !!';

        return Validator::make($request->all(), $required, $message);
    }

    protected function validateformangkat($request)
    {
        $required['id_struktur_organ'] = 'required';
        $required['id_perusahaan'] = 'required';

        $message['id_struktur_organ.required'] = 'Jabatan wajib dipilih';
        $message['id_perusahaan.required'] = 'BUMN wajib dipilih';

        return Validator::make($request->all(), $required, $message);
    }

    protected function validateformindependen($request)
    {
        $required['id_talenta'] = 'required';

        $message['id_talenta.required'] = 'Pejabat wajib dipilih';

        return Validator::make($request->all(), $required, $message);
    }

    protected function validateformhenti($request)
    {
        $required['id_talenta'] = 'required';

        $message['id_talenta.required'] = 'Nama wajib dipilih';

        return Validator::make($request->all(), $required, $message);
    }

    protected function validateformklatur($request)
    {
        $required['id_struktur_organ'] = 'required';

        $message['id_struktur_organ.required'] = 'Jabatan wajib dipilih';

        return Validator::make($request->all(), $required, $message);
    }

    protected function validateformplt($request)
    {
        $required['id_struktur_organ'] = 'required';

        $message['id_struktur_organ.required'] = 'Jabatan wajib dipilih';

        return Validator::make($request->all(), $required, $message);
    }

    protected function validateformalt($request)
    {
        $required['id_struktur_organ'] = 'required';

        $message['id_struktur_organ.required'] = 'Jabatan wajib dipilih';

        return Validator::make($request->all(), $required, $message);
    }

    protected function validateformangkatlagi($request)
    {
        $required['id_talenta'] = 'required';

        $message['id_talenta.required'] = 'Orang wajib dipilih';

        return Validator::make($request->all(), $required, $message);
    }

    public function getjabatan(Request $request)
    {
        $sk_id = $request->sk_id;
        $grup_id = $request->grup_id;

        $sklistpejabat_sql = "SELECT
                            struktur_organ.ID,
                            CASE

                                WHEN talenta.nama_lengkap IS NULL THEN
                                struktur_organ.nomenklatur_jabatan || ' ' || '(KOSONG)' ELSE struktur_organ.nomenklatur_jabatan || ' ' || '(ISI)'
                            END AS jabatan
                        FROM
                            surat_keputusan
                            LEFT JOIN rincian_sk ON rincian_sk.id_surat_keputusan = surat_keputusan.
                            ID LEFT JOIN struktur_organ ON struktur_organ.id_perusahaan = surat_keputusan.id_perusahaan
                            left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                            left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                            left join sk_pengangkatan on sk_pengangkatan.id_rincian_sk = rincian_sk.id and sk_pengangkatan.id_struktur_organ = struktur_organ.id
                            LEFT JOIN talenta on talenta.id = sk_pengangkatan.id_talenta
                        WHERE
                          surat_keputusan.id = $sk_id and grup_jabatan.id = $grup_id";
        $listjabatan  = DB::select(DB::raw($sklistpejabat_sql));

        $json = array();
         for($i=0; $i < count($listjabatan); $i++){
             $json[] = array(
                   'id' => $listjabatan[$i]->id,
                   'nama' => $listjabatan[$i]->jabatan
             );
         }

        return response()->json($json);
    }

    /**
     * [service untuk ambil tanggal awal jabat]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function gettgljabat(Request $request)
    {
        $id_talenta = $request->id_talenta;
        $id_perusahaan = $request->id_perusahaan;

        $organlist_sql = "SELECT
                                organ_perusahaan.tanggal_awal,
                                organ_perusahaan.tanggal_akhir
                            FROM
                                organ_perusahaan
                                LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                            WHERE
                                organ_perusahaan.aktif = 't'
                                AND organ_perusahaan.id_talenta = $id_talenta
                                AND struktur_organ.id_perusahaan = $id_perusahaan";
        $listorgan  = DB::select(DB::raw($organlist_sql));

        $json = array();

        $json[] = array(
               'tanggal_awal' => \Carbon\Carbon::createFromFormat('Y-m-d', $listorgan[0]->tanggal_awal)->format('d/m/Y'),
               'tanggal_akhir' => \Carbon\Carbon::createFromFormat('Y-m-d', $listorgan[0]->tanggal_akhir)->format('d/m/Y')
         );

        return response()->json($json);
    }

    public function getnamajabat(Request $request)
    {
        $sk_id = $request->sk_id;

        $sklist_sql = "SELECT
                        talenta.ID,
                        talenta.nama_lengkap || '-' || struktur_organ.nomenklatur_jabatan AS nama
                    FROM
                        surat_keputusan
                        LEFT JOIN grup_jabatan ON grup_jabatan.ID = surat_keputusan.id_grup_jabatan
                        LEFT JOIN perusahaan ON perusahaan.ID = surat_keputusan.id_perusahaan
                        LEFT JOIN rincian_sk ON rincian_sk.id_surat_keputusan = surat_keputusan.
                        ID LEFT JOIN sk_pengangkatan ON sk_pengangkatan.id_rincian_sk = rincian_sk.
                        ID LEFT JOIN struktur_organ ON struktur_organ.ID = sk_pengangkatan.id_struktur_organ
                        LEFT JOIN talenta ON talenta.ID = sk_pengangkatan.id_talenta
                    WHERE
                        surat_keputusan.id = $sk_id";
        $listnama  = DB::select(DB::raw($sklist_sql));

        $json = array();
         for($i=0; $i < count($listnama); $i++){
             $json[] = array(
                   'id' => $listnama[$i]->id,
                   'nama' => $listnama[$i]->nama
             );
         }

        return response()->json($json);
    }

    public function getjenissk(Request $request)
    {
        $grup_jabatan_id = $request->grup_jabatan_id;

        if($grup_jabatan_id == 1){
            $sklist_sql = "SELECT ID
                                ,
                                nama
                            FROM
                                jenis_sk where id in (1,2,3,4,5)";
        } else {
            $sklist_sql = "SELECT ID
                                ,
                                nama
                            FROM
                                jenis_sk where id in (1,2,3,4,5,7)";
        }


        $listsks  = DB::select(DB::raw($sklist_sql));

        $json = array();
         for($i=0; $i < count($listsks); $i++){
             $json[] = array(
                   'id' => $listsks[$i]->id,
                   'nama' => $listsks[$i]->nama
             );
         }

        return response()->json($json);
    }

    public function newOrang(Request $request)
    {

        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateformorang($request);

        if (!$validator->fails()) {

            $orang = new Talenta;
            $orang->nama_lengkap = $request->nama_lengkap;
            $orang->jenis_kelamin = $request->jenis_kelamin;
            $orang->nik = $request->nik;
            $orang->npwp = $request->npwp?$request->npwp:'';
            $orang->email = $request->email?$request->email:'';
            $orang->nomor_hp = $request->nomor_hp?$request->nomor_hp:'';
            $orang->id_perusahaan = \Auth::user()->id_bumn;
            $orang->talenta_register = 1;
            $orang->id_status_talenta = 1;
            $orang->save();

            $result = [
                'key' => $orang->id,
                'text' => $orang->nama_lengkap,
                'flag'  => 'success',
                'msg' => 'Sukses Simpan data',
                'title' => 'Sukses'
            ];

        } else{
            $messages = $validator->errors()->all('<li>:message</li>');
            $result = [
                'key' => 0,
                'text' => '',
                'flag'  => 'warning',
                'msg' => '<ul>'.implode('', $messages).'</ul>',
                'title' => 'Gagal proses data'
            ];
        }

        return response()->json($result);
    }

    public function checknik(Request $request)
    {
        return response()->json(Talenta::where('nik', $request->input('nik'))->count() > 0? false : true);
    }

    public function checknpwp(Request $request)
    {
        return response()->json(Talenta::where('npwp', $request->input('npwp'))->count() > 0? false : true);
    }

}
