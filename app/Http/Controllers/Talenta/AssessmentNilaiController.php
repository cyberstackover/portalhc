<?php

namespace App\Http\Controllers\Talenta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Talenta;
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
use App\KelasBumn;
use App\ClusterBumn;
use App\Keahlian;
use App\KonteksOrganisasi;
use App\Helpers\CVHelper;
use DB;
use Carbon\Carbon;
use File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AssessmentNilaiController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'talenta.assessment_nilai';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu Talenta Assessment Nilai');
      $talenta = Talenta::find($id);
      return view($this->__route.'.index',[
          'pagetitle' => $talenta->nama_lengkap,
          'talenta' => $talenta,
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
              ],
              [
                  'url' => route('talenta.assessment_nilai.index', ['id_talenta' => $id]),
                  'menu' => $talenta->nama_lengkap
              ]               
          ]
      ]);
    }

    public function datatable(Request $request, $id)
    {
        $data = AssessmentNilai::select('*')->where('id_talenta', $id)->orderBy("updated_at", 'asc');

        try{
            return datatables()->of($data)
            ->editColumn('spa_report', function ($row){
                $file = '';
                
                if($row->spa_report!=''){
                  $file .= '<a class="tooltips" title="Download SPA Report" href="'.\URL::to('uploads/talenta/assessment/'.$row->spa_report).'" download="'.$row->spa_report.'" ><span class="btn-icon-only btn btn-sm yellow-gold sbold"><i class="flaticon2-document"></i>SPA</a><br/>';
                }
                if($row->short_report!=''){
                    $file .= '<a class="tooltips" title="Download Short Report" href="'.\URL::to('uploads/talenta/assessment/'.$row->short_report).'" download="'.$row->short_report.'" ><span class="btn-icon-only btn btn-sm yellow-gold sbold"><i class="flaticon2-document"></i>Short Report</a><br/>';
                }
                if($row->full_report!=''){
                    $file .= '<a class="tooltips" title="Download Full Report" href="'.\URL::to('uploads/talenta/assessment/'.$row->full_report).'" download="'.$row->full_report.'" ><span class="btn-icon-only btn btn-sm yellow-gold sbold"><i class="flaticon2-document"></i>Full Report</a><br/>';
                }
                
                return $file;
            })
            // ->editColumn('short_report', function ($row){
            //     $file = '';
                
            //     if($row->short_report!=''){
            //         $file .= '<a class="tooltips" title="Download Short Report" href="'.\URL::to('uploads/talenta/assessment/'.$row->short_report).'" download="'.$row->short_report.'" ><span class="btn-icon-only btn btn-sm yellow-gold sbold"><i class="flaticon2-document"></i>Short Report</a><br/>';
            //     }
                
            //     return $file;
            // })
            // ->editColumn('full_report', function ($row){
            //     $file = '';
                
            //     if($row->full_report!=''){
            //         $file .= '<a class="tooltips" title="Download Full Report" href="'.\URL::to('uploads/talenta/assessment/'.$row->full_report).'" download="'.$row->full_report.'" ><span class="btn-icon-only btn btn-sm yellow-gold sbold"><i class="flaticon2-document"></i>Full Report</a><br/>';
            //     }
                
            //     return $file;
            // })
            ->editColumn('id_lembaga_assessment', function ($row){
                return @$row->lembagaAssessment->nama;
            })
            ->addColumn('hasil', function ($row){
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
                $tanggal = CVHelper::tglFormat($row->tanggal, 2);
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-primary btn-icon  cls-button-upload-spa btn-sm" data-id="'.$id.'" data-tanggal="'.$tanggal.'" data-toggle="tooltip" data-original-title="Surat Pengajuan Assessment"><i class="flaticon-upload"></i></button>'; 
                
                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-warning btn-icon  cls-button-upload-short btn-sm" data-id="'.$id.'" data-tanggal="'.$tanggal.'" data-toggle="tooltip" data-original-title="Short Report Assessment"><i class="flaticon-upload"></i></button>'; 
                
                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-success btn-icon  cls-button-upload-full btn-sm" data-id="'.$id.'" data-tanggal="'.$tanggal.'" data-toggle="tooltip" data-original-title="Full Report Assessment"><i class="flaticon-upload"></i></button>'; 
                
                $button .= '&nbsp;';

                $button .= '<a type="button" class="btn btn-outline-primary btn-sm btn-icon cls-button-edit" data-toggle="tooltip" data-id="'.$id.'" data-original-title="Ubah Hasil Assessment">
                                <i class="flaticon-edit"></i>
                            </a>';
                            
                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-icon cls-button-delete btn-sm" data-id="'.$id.'" data-tanggal="'.$tanggal.'" data-toggle="tooltip" data-original-title="Hapus data assessment"><i class="flaticon-delete"></i></button>';

                $button .= '</div>';
                return $button;
            })
            ->editColumn('tanggal', function ($row){
                return CVHelper::tglFormat($row->tanggal, 2);
            })
            ->editColumn('tanggal_expired', function ($row){
                return CVHelper::tglFormat($row->tanggal_expired, 2);
            })
            ->rawColumns(['action','spa_report','short_report', 'full_report', 'tanggal_expired', 'tanggal', 'hasil'])
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

    public function create(Request $request, $id)
    {
      try{
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

            $kelas = KelasBumn::get();
            $cluster = ClusterBumn::get();
            $keahlian = Keahlian::get();
            $konteks = KonteksOrganisasi::get();

            return view($this->__route.'.form',[
                    'actionform' => 'insert',
                    'id_talenta' => $id,
                    'talenta' => $talenta,
                    'kompetensi' => RefKompetensi::get(),
                    'karakter' => Karakters::get(),
                    'kelas' => $kelas,
                    'cluster' => $cluster,
                    'keahlian' => $keahlian,
                    'konteks' => $konteks,
                    'kualifikasi' => KualifikasiPersonal::get()
            ]);
      }catch(Exception $e){}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function upload_spa(Request $request, $id)
    {
      try{
            return view($this->__route.'.upload',[
                    'id_talenta' => $id,
                    'id' => $request->input('id'),
                    'data' => AssessmentNilai::find($request->input('id')), 
                    'name' => 'spa_report'
            ]);
      }catch(Exception $e){}
    }

    public function upload_short(Request $request, $id)
    {
      try{
            return view($this->__route.'.upload',[
                    'id_talenta' => $id,
                    'id' => $request->input('id'),
                    'data' => AssessmentNilai::find($request->input('id')), 
                    'name' => 'short_report'
            ]);
      }catch(Exception $e){}
    }

    public function upload_full(Request $request, $id)
    {
      try{
            return view($this->__route.'.upload',[
                    'id_talenta' => $id,
                    'id' => $request->input('id'),
                    'data' => AssessmentNilai::find($request->input('id')), 
                    'name' => 'full_report'
            ]);
      }catch(Exception $e){}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request, $id)
    {
      try{
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
            ->where('talenta.id', $id)
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

            return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'id_talenta' => $id,
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

    public function store(Request $request, $id)
    {
      $result = [
          'flag' => 'error',
          'msg' => 'Error System',
          'title' => 'Error'
      ];      

      $validator = $this->validateform($request);   

      if (!$validator->fails()) {          
          $id_talenta   = $id;
          $talenta      = Talenta::find($id_talenta);
          $id           = $request->input('id');

          switch ($request->input('actionform')) {
            case 'insert': DB::beginTransaction();
                            try{
                                $param['id_talenta'] = $id_talenta;
                                $param['id_lembaga_assessment'] = $talenta->id_lembaga_assessment;
                                $param['tanggal'] = Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');
                                $param['tanggal_expired'] = Carbon::createFromFormat('d/m/Y', $request->tanggal_expired)->format('Y-m-d');
                                $param['hasil'] = $request->hasil;
                                $param['area_kekuatan'] = $request->area_kekuatan;
                                $param['area_pengembangan'] = $request->area_pengembangan;
                                $data = AssessmentNilai::create($param);
                                $id_assessment_nilai = $data->id;
                                
                                if($request->kompetensi){
                                    $create = [''];
                                    foreach($request->kompetensi as $key=>$value) {
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_kompetensi'] = $key;
                                        $create['rating'] = $value;
                                        $data = AssessmentKompetensi::create($create);
                                    }
                                }
                                if($request->kualifikasi){
                                    $create = [''];
                                    foreach($request->kualifikasi as $key=>$value) {
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_kualifikasi_personal'] = $key;
                                        $create['rating'] = $value;
                                        $data = AssessmentKualifikasi::create($create);
                                    }
                                }
                                if($request->karakter){
                                    $create = [''];
                                    foreach($request->karakter as $key=>$value) {
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_karakter'] = $key;
                                        $create['rating'] = $value;
                                        $data = AssessmentKarakter::create($create);
                                    }
                                }
                                if($request->kelas){
                                    $create = [''];
                                    foreach($request->kelas as $key => $data){
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_kelas_bumn'] = $data;
                                        AssessmentKelas::create($create);
                                    }
                                }
                                if($request->cluster){
                                    $create = [''];
                                    foreach($request->cluster as $key => $data){
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_cluster_bumn'] = $data;
                                        AssessmentCluster::create($create);
                                    }
                                }
                                if($request->keahlian){
                                    $create = [''];
                                    foreach($request->keahlian as $key => $data){
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_keahlian'] = $data;
                                        AssessmentKeahlian::create($create);
                                    }
                                }
                                if($request->organisasi){
                                    $create = [''];
                                    foreach($request->organisasi as $key => $data){
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_konteks_organisasi'] = $data;
                                        AssessmentOrganisasi::create($create);
                                    }
                                }

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
                                #delete transaction old
                                AssessmentKompetensi::where("id_assessment_nilai", $id)->delete();
                                AssessmentKualifikasi::where("id_assessment_nilai", $id)->delete();
                                AssessmentKarakter::where("id_assessment_nilai", $id)->delete();
                                AssessmentKelas::where("id_assessment_nilai", $id)->delete();
                                AssessmentCluster::where("id_assessment_nilai", $id)->delete();
                                AssessmentKeahlian::where("id_assessment_nilai", $id)->delete();
                                AssessmentOrganisasi::where("id_assessment_nilai", $id)->delete();
                                
                                #create new transaction
                                $param['id_lembaga_assessment'] = $talenta->id_lembaga_assessment;
                                $param['tanggal'] = $request->tanggal;
                                $param['tanggal_expired'] = $request->tanggal_expired;
                                $param['hasil'] = $request->hasil;
                                $param['area_kekuatan'] = $request->area_kekuatan;
                                $param['area_pengembangan'] = $request->area_pengembangan;
                                $data = AssessmentNilai::find($id)->update($param);
                                $id_assessment_nilai = $id;

                                if($request->kompetensi){
                                    $create = [''];
                                    foreach($request->kompetensi as $key=>$value) {
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_kompetensi'] = $key;
                                        $create['rating'] = $value;
                                        $data = AssessmentKompetensi::create($create);
                                    }
                                }
                                if($request->kualifikasi){
                                    $create = [''];
                                    foreach($request->kualifikasi as $key=>$value) {
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_kualifikasi_personal'] = $key;
                                        $create['rating'] = $value;
                                        $data = AssessmentKualifikasi::create($create);
                                    }
                                }
                                if($request->karakter){
                                    $create = [''];
                                    foreach($request->karakter as $key=>$value) {
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_karakter'] = $key;
                                        $create['rating'] = $value;
                                        $data = AssessmentKarakter::create($create);
                                    }
                                }
                                if($request->kelas){
                                    $create = [''];
                                    foreach($request->kelas as $key => $data){
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_kelas_bumn'] = $data;
                                        AssessmentKelas::create($create);
                                    }
                                }
                                if($request->cluster){
                                    $create = [''];
                                    foreach($request->cluster as $key => $data){
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_cluster_bumn'] = $data;
                                        AssessmentCluster::create($create);
                                    }
                                }
                                if($request->keahlian){
                                    $create = [''];
                                    foreach($request->keahlian as $key => $data){
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_keahlian'] = $data;
                                        AssessmentKeahlian::create($create);
                                    }
                                }
                                if($request->organisasi){
                                    $create = [''];
                                    foreach($request->organisasi as $key => $data){
                                        $create['id_assessment_nilai'] = $id_assessment_nilai;
                                        $create['id_konteks_organisasi'] = $data;
                                        AssessmentOrganisasi::create($create);
                                    }
                                }

                                $result = [
                                    'flag'  => 'success',
                                    'msg' => 'Sukses Ubah data',
                                    'title' => 'Sukses'
                                ];
                                
                                DB::commit();
                            }catch(\Exception $e){
                            DB::rollback();
                            $result = [
                                'flag'  => 'warning',
                                'msg' => $e->getMessage().'-'.$e->getLine(),
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

    protected function validateform($request)
    {
        $required['hasil'] = 'required';

        $message['hasil.required'] = 'Kesimpulan wajib Diisi';

        return Validator::make($request->all(), $required, $message);       
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = AssessmentNilai::find((int)$request->input('id'));
            File::delete('uploads'.DIRECTORY_SEPARATOR.'talenta'.DIRECTORY_SEPARATOR .'assessment_nilai'.DIRECTORY_SEPARATOR . $data->short_report);
            File::delete('uploads'.DIRECTORY_SEPARATOR.'talenta'.DIRECTORY_SEPARATOR .'assessment_nilai'.DIRECTORY_SEPARATOR . $data->full_report);
            File::delete('uploads'.DIRECTORY_SEPARATOR.'talenta'.DIRECTORY_SEPARATOR .'assessment_nilai'.DIRECTORY_SEPARATOR . $data->spa_report);
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

    public function store_upload(Request $request, $id)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $id = $request->id;
        DB::beginTransaction();
        try{
            if($request->spa_report){
                $firstSubName = 'Assessment_spa_'.$id.'_';
                $dataUpload = $this->uploadFile($request->file('spa_report'), $firstSubName);
                $param['spa_report']  = $dataUpload->fileRaw;
            }
            if($request->short_report){
                $firstSubName = 'Assessment_short_'.$id.'_';
                $dataUpload = $this->uploadFile($request->file('short_report'), $firstSubName);
                $param['short_report']  = $dataUpload->fileRaw;
            }
            if($request->full_report){
                $firstSubName = 'Assessment_full_'.$id.'_';
                $dataUpload = $this->uploadFile($request->file('full_report'), $firstSubName);
                $param['full_report']  = $dataUpload->fileRaw;
            }

            $status = AssessmentNilai::find($id)->update($param);

            DB::commit();
            $result = [
                'flag'  => 'success',
                'msg' => 'Sukses upload file',
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

    protected function uploadFile(UploadedFile $file, $firstSubName)
    {
        $fileName = $file->getClientOriginalName();
        $ext = substr($file->getClientOriginalName(),strripos($file->getClientOriginalName(),'.'));
        $fileRaw  = $firstSubName.$fileName;
        $filePath = 'uploads'.DIRECTORY_SEPARATOR.'talenta'.DIRECTORY_SEPARATOR.'assessment'.DIRECTORY_SEPARATOR.$fileRaw;
        $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'talenta'.DIRECTORY_SEPARATOR.'assessment'.DIRECTORY_SEPARATOR;
        $fileUpload      = $file->move($destinationPath, $fileRaw);
        $data = (object) array('fileName' => $fileName, 'fileRaw' => $fileRaw, 'filePath' => $filePath);
        return $data;
    }
}