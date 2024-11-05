<?php
namespace App\Http\Controllers\Talenta;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\KelasBumn;
use App\ClusterBumn;
use App\Talenta;
use App\StatusTalenta;
use App\Perusahaan;
use App\DynamicFilter;
use App\DynamicOperator;
use App\RiwayatJabatanDirkomwas;
use DB;
use Carbon\Carbon;
use App\User;
use App\Exports\DynamicSearchExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class DynamicSearchController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->__route = 'talenta.dynamic_search';
         $this->__title = "Dynamic Search";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
      activity()->log('Menu Talenta Dynamic Search');
      
      return view($this->__route.'.index',[
          'pagetitle' => $this->__title,
          'route' => $this->__route,
            'dynamic_search' => DynamicFilter::orderBy('id', 'asc')->get(),
            'status_talenta' => StatusTalenta::select('nama')->groupBy('nama')->get(),
            'kelas' => KelasBumn::orderBy('id', 'asc')->get(),
            'cluster' => ClusterBumn::orderBy('id', 'asc')->get(),
            'perusahaan' => Perusahaan::where('induk', 0)->where('level', 0)->where('kepemilikan', 'BUMN')->where('is_active',true)->orderBy('id', 'asc')->get(),
            'breadcrumb' => [
              [
                  'url' => '/',
                  'menu' => 'Homes'
              ],
              [
                  'url' => route('talenta.dynamic_search.index'),
                  'menu' => 'Talent Management'
              ],
              [
                  'url' => route('talenta.dynamic_search.index'),
                  'menu' => 'Dynamic Search'
              ]
          ]
      ]);

    }

    public static function query_string(Request $request)
    {
      $query_string = '';
      $dynamic_filter_id = $request->dynamic_filter_id;
      
      for($i=0; $i<count($dynamic_filter_id); $i++){
        $dynamic_filter = DynamicFilter::Select('dynamic_filter.*', 'dynamic_tabel_sumber.tabel', 'dynamic_tabel_sumber.field', 'dynamic_tabel_sumber.query', 'dynamic_tabel_sumber.alias')
        ->leftJoin('dynamic_tabel_sumber', 'dynamic_tabel_sumber.id', 'dynamic_filter.dynamic_tabel_sumber_id')
        ->where('dynamic_filter.id', $dynamic_filter_id[$i])
        ->first();
        
        $operator = DynamicOperator::Select('*')->where("operator", $request->opsi[$i])->first();

        $opsi = $operator->nama;
        $nilai = $request->nilai[$i];

        if($i != 0){
          $operator = $request->operator[$i-1];
          $query_string .= $operator . ' ';
        }
        $query_string .= $dynamic_filter->submenu . ' ' . $opsi . ' ' . $nilai . ' ';
      }
      return $query_string;
    }

    public static function query_talenta(Request $request)
    {
        $talenta = DB::table('talenta')
                      ->whereRaw('(jabatan.id_grup_jabatan = 1 OR talenta.is_talenta = true)')
                      ->leftJoin('status_talenta', 'status_talenta.id', '=', 'talenta.id_status_talenta')
                      ->leftJoin(DB::raw("lateral (select s.nomenklatur_jabatan, p.nama_lengkap, p.id, skp.nomenklatur_baru, s.id_perusahaan, sk.id_grup_jabatan, case when skp.nomenklatur_baru is NULL then
                      s.nomenklatur_jabatan else skp.nomenklatur_baru END as jabatan
                                      from view_organ_perusahaan v
                                      left join struktur_organ s on v.id_struktur_organ = s.id
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
                                      limit 1) jabatan"), 'talenta.id', '=', 'talenta.id')
                      ->leftJoin('perusahaan', 'perusahaan.id', '=', 'jabatan.id_perusahaan')
                      ->leftJoin('perusahaan as p', 'p.id', '=', 'talenta.id_perusahaan')
                      ->leftJoin(DB::raw("perusahaan as perusahaan_talenta"), 'perusahaan_talenta.id', '=', 'talenta.id_perusahaan')
                      ->select(DB::raw("talenta.id,
                                    talenta.nama_lengkap,
                                    case when talenta.jenis_kelamin = 'P' then
                                    'Perempuan' else 'Laki-laki' END as jenis_kelamin,
                                    talenta.nik,
                                    talenta.persentase,
                                    talenta.id_status_talenta,
                                    talenta.id_perusahaan,
                                    talenta.tempat_lahir,
                                    talenta.tanggal_lahir,
                                    talenta.gol_darah,
                                    talenta.suku,
                                    talenta.kewarganegaraan,
                                    talenta.nomor_hp,
                                    talenta.email,
                                    talenta.alamat,
                                    talenta.npwp,
                                    talenta.foto,
                                    talenta.gelar,
                                    jabatan.nama_lengkap as nama_perusahaan,
                                    perusahaan_talenta.nama_lengkap as nama_perusahaan_talenta,
                                    jabatan.jabatan,
                                    status_talenta.nama as stalenta,
                                    status_talenta.nama as status_talenta,
                                    talenta.id_jenis_asal_instansi,
                                    EXTRACT(YEAR FROM age(cast(talenta.tanggal_lahir as date))) as usia,
                                    jabatan.id as id_bumn"))
                      // ->where('perusahaan.is_active', true)
                      ->where('p.is_active', true)
                      ->groupBy('talenta.id', 'jabatan.jabatan', 'jabatan.nama_lengkap', 'status_talenta.nama','jabatan.id','jabatan.nomenklatur_baru', 'perusahaan_talenta.nama_lengkap')
                      ->orderBy('talenta.nama_lengkap', 'ASC');

        if($request->kelas){
          if($request->kelas != 'All'){
            $talenta = $talenta->whereRaw("(perusahaan_talenta.kelas = ".$request->kelas." OR perusahaan.kelas = ".$request->kelas." )");
          }
        }

        if($request->cluster){
          if($request->cluster != 'All'){
            $talenta = $talenta->whereRaw("(perusahaan_talenta.id_klaster = ".$request->cluster." OR perusahaan.id_klaster = ".$request->cluster." )");
          }
        }

        if($request->status_talenta){
          if($request->status_talenta[0] != 'All'){
            $status_talenta = implode ("', '", $request->status_talenta);
            if($status_talenta){
              $talenta = $talenta->whereRaw("status_talenta.nama IN ('".$status_talenta."')");
            }
          }
        }

        if($request->id_perusahaan){
          if($request->id_perusahaan != 'All'){
            $id_users_bumn = $request->id_perusahaan;
            $talenta = $talenta->whereRaw("((( (perusahaan.id in (
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
                                      ) OR perusahaan.id = ".$id_users_bumn.")
                                      AND talenta.id_perusahaan IS NULL)
                                      OR
                                      talenta.id_perusahaan = ".$id_users_bumn.") ");
          }
        }

        if($request->nilai) {
          $dynamic_filter_id = $request->dynamic_filter_id;

          $query_filter = '';
          for($i=0; $i<count($dynamic_filter_id); $i++){
              $dynamic_filter = DynamicFilter::Select('dynamic_filter.*', 'dynamic_tabel_sumber.tabel', 'dynamic_tabel_sumber.field', 'dynamic_tabel_sumber.query', 'dynamic_tabel_sumber.alias')
                                ->leftJoin('dynamic_tabel_sumber', 'dynamic_tabel_sumber.id', 'dynamic_filter.dynamic_tabel_sumber_id')
                                ->where('dynamic_filter.id', $dynamic_filter_id[$i])
                                ->first();

              $column = $dynamic_filter->tabel . '.' . $dynamic_filter->field;
              $query = $dynamic_filter->query;
              $opsi = $request->opsi[$i];
              $nilai = $request->nilai[$i];

              if($query){
                $column = $query;
              }

              if($dynamic_filter->alias=='kategori'){
                $talenta = $talenta->leftJoin('kategori_jabatan_talent', 'kategori_jabatan_talent.id', '=', 'talenta.id_kategori_jabatan_talent')
                            ->addSelect('kategori_jabatan_talent.nama as kategori')
                            ->groupBy('kategori_jabatan_talent.nama');
              }else if($dynamic_filter->alias=='keahlian'){
                $talenta = $talenta->leftJoin('transaction_talenta_keahlian', 'transaction_talenta_keahlian.id_talenta', '=', 'talenta.id')
                            ->leftJoin('keahlian', 'keahlian.id', 'transaction_talenta_keahlian.id_keahlian')
                            ->whereRaw("keahlian.jenis_keahlian in ('".$nilai."')")
                            ->addSelect(DB::Raw("string_agg(keahlian.jenis_keahlian::text, ',')  as keahlians"))
                            ->addSelect(DB::Raw("'".$nilai."' as keahlian"))
                            ->groupBy('talenta.id');
              }else if($dynamic_filter->alias=='aspirasi_cluster'){
                $talenta = $talenta->leftJoin('transaction_talenta_cluster', 'transaction_talenta_cluster.id_talenta', '=', 'talenta.id')
                            ->leftJoin('cluster_bumn', 'cluster_bumn.id', 'transaction_talenta_cluster.id_cluster')
                            ->whereRaw("cluster_bumn.nama in ('".$nilai."')")
                            ->addSelect(DB::Raw("string_agg(cluster_bumn.nama::text, ',')  as aspirasi_clusters"))
                            ->addSelect(DB::Raw("'".$nilai."' as aspirasi_cluster"))
                            ->groupBy('talenta.id');
              }else if($dynamic_filter->alias=='aspirasi_kelas'){
                $talenta = $talenta->leftJoin('transaction_talenta_kelas', 'transaction_talenta_kelas.id_talenta', '=', 'talenta.id')
                            ->leftJoin('kelas_bumn', 'kelas_bumn.id', 'transaction_talenta_kelas.id_kelas')
                            ->whereRaw("kelas_bumn.nama in ('".$nilai."')")
                            ->addSelect(DB::Raw("string_agg(kelas_bumn.nama::text, ',')  as aspirasi_kelass"))
                            ->addSelect(DB::Raw("'".$nilai."' as aspirasi_kelas"))
                            ->groupBy('talenta.id');
              }else if($dynamic_filter->alias=='hasil_assessment'){
                $talenta = $talenta->leftJoin(DB::raw("(
                  select assessment_nilai.hasil, assessment_nilai.id_talenta from assessment_nilai
                  right join (
                    select max(tanggal_expired) tanggal_expired, id_talenta from assessment_nilai group by id_talenta
                    ) last_assessment on last_assessment.id_talenta = assessment_nilai.id_talenta and last_assessment.tanggal_expired = assessment_nilai.tanggal_expired
                ) assessment_nilai"), 'assessment_nilai.id_talenta', '=', 'talenta.id')
                            ->addSelect('assessment_nilai.hasil as hasil_assessment')
                            ->groupBy('assessment_nilai.hasil');
              }else if($dynamic_filter->alias=='tani'){
                $talenta = $talenta->leftJoin(DB::raw("(
                  select riwayat_tani.tani, riwayat_tani.id_talenta from riwayat_tani
                  right join (
                    select max(tgl_awal) tgl_awal, id_talenta from riwayat_tani group by id_talenta
                    ) last_tani on last_tani.id_talenta = riwayat_tani.id_talenta and last_tani.tgl_awal = riwayat_tani.tgl_awal
                ) riwayat_tani"), 'riwayat_tani.id_talenta', '=', 'talenta.id')
                            ->addSelect('riwayat_tani.tani as tani')
                            ->groupBy('riwayat_tani.tani');
              }else if($dynamic_filter->alias=='pendidikan'){
                $talenta = $talenta->leftJoin(DB::raw("lateral (
                                select rp.id_talenta, jp.urutan as urutan, rp.penjurusan, jp.nama as jenjang_pendidikan
                                from riwayat_pendidikan rp
                                left join jenjang_pendidikan jp on jp.id = rp.id_jenjang_pendidikan
                                where rp.id_talenta = talenta.id 
                                order by jp.urutan desc
                                limit 1) last_pendidikan"), 'talenta.id', '=', 'talenta.id')
                            ->leftJoin('jenjang_pendidikan', 'jenjang_pendidikan.urutan', '=', 'last_pendidikan.urutan')
                            ->addSelect('jenjang_pendidikan.nama as jenjang_pendidikan')
                            ->addSelect(DB::Raw("concat_ws(' - ', last_pendidikan.jenjang_pendidikan, last_pendidikan.penjurusan) AS pendidikan"))
                            ->groupBy('jenjang_pendidikan.nama')
                            ->groupBy('last_pendidikan.jenjang_pendidikan')
                            ->groupBy('last_pendidikan.penjurusan');
              }else if($dynamic_filter->alias=='jenis_kelamin'){
                if($nilai == 'Perempuan') $nilai = 'P';
                else $nilai = 'L';
              }

              if($i != 0){
                $operator = $request->operator[$i-1];
                $query_filter .= $operator . ' ';
              }

              if($dynamic_filter->alias=='aspirasi_kelas' || $dynamic_filter->alias=='aspirasi_cluster' || $dynamic_filter->alias=='keahlian'){
                $query_filter .= $column . " IN ('" .$nilai."') ";
              }else if($dynamic_filter->tipe == 'multi select'){
                if($column == 'kategori_jabatan_talent.nama'){
                  $column = 'talenta.id_kategori_jabatan_talent';
                }
                $nilai_str = implode ("', '", $nilai);
                $query_filter .= $column . " IN ('" .$nilai_str."') ";
              }else if($opsi == 'ilike'){
                $query_filter .= $column . '::text ' . $opsi. " '%".$nilai."%' ";
              }else if($dynamic_filter->is_number != true){
                $query_filter .= $column . ' ' . $opsi. " '".$nilai."' ";
              }else{
                $query_filter .= $column . ' ' . $opsi. ' '.$nilai.' ';
              }
          }
          $talenta = $talenta->whereRaw('( '.DB::raw($query_filter).' )');

          $query_talenta = 'Select * from ('.$talenta->toSql().') as talenta order by ';
          $is_sorting = false;
          for($i=0; $i<count($dynamic_filter_id); $i++){
            $dynamic_filter = DynamicFilter::Select('dynamic_filter.*', 'dynamic_tabel_sumber.tabel', 'dynamic_tabel_sumber.field', 'dynamic_tabel_sumber.alias')
                              ->leftJoin('dynamic_tabel_sumber', 'dynamic_tabel_sumber.id', 'dynamic_filter.dynamic_tabel_sumber_id')
                              ->where('dynamic_filter.id', $dynamic_filter_id[$i])
                              ->first();

            $sorting = $request->sorting[$i];
            $column = $dynamic_filter->alias;

            if($sorting) {
              $query_talenta .= ' talenta.'.$column.' '.$sorting.', ';
              $is_sorting = true;
            }
          }

          if($is_sorting){
            $query_talenta .= 'talenta.nama_lengkap asc';
            $talenta = DB::select($query_talenta);
          }
        }

        // dd($talenta->toSql());
        return $talenta;
    }

    public function datatable(Request $request)
    {
        $id_users = \Auth::user()->id;
        $id_users_bumn = \Auth::user()->id_bumn;
        $users = User::where('id', $id_users)->first();

        $talenta = DynamicSearchController::query_talenta($request);

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
            ->editColumn('bumn_induk', function ($row){
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
            ->editColumn('tani', function ($row){
                return number_format(@$row->tani,0,',',',');
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

                $button = '<label class="mt-checkbox mt-checkbox-outline">
                <input name="id_talenta" type="checkbox" data-id="'.(int)$row->id.'" data-nama_lengkap="'.$row->nama_lengkap.'" value="'.(int)$row->id.'" form="form_group" class="checked_item"  onchange="talenta_checked(this)"/>
                <span></span>
                </label>';

                return $button;
            })
            ->rawColumns(['action','nama_lengkap','jabatan', 'stalenta', 'nama_perusahaan', 'talent_jabatan', 'bumn_induk', 'status_pengisian'])
            ->toJson();
        }catch(Exception $e){console.log($e->getMessage());
            return response([
                'draw'            => 0,
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => []
            ]);
        }
    }

    public function export(Request $request)
    {
      $query_string = DynamicSearchController::query_string($request);
 
      $talenta = DynamicSearchController::query_talenta($request)
                    ->leftJoin(DB::raw("(
                        select riwayat_jabatan_lain.penugasan, riwayat_jabatan_lain.id_talenta from riwayat_jabatan_lain
                        right join (
                          select max(tanggal_awal) tanggal_awal, id_talenta from riwayat_jabatan_lain group by id_talenta
                        ) last_jabatan on last_jabatan.id_talenta = riwayat_jabatan_lain.id_talenta and last_jabatan.tanggal_awal = riwayat_jabatan_lain.tanggal_awal
                      ) riwayat_jabatan_lain"), 'riwayat_jabatan_lain.id_talenta', '=', 'talenta.id')
                      ->addSelect('riwayat_jabatan_lain.penugasan as riwayat_jabatan_lain')
                      ->groupBy('riwayat_jabatan_lain.penugasan')
                      ->get();

      $dynamic_filter_id = $request->dynamic_filter_id;
      $dynamic_filter = [];
      for($i=0; $i<count($dynamic_filter_id); $i++){
          $dynamic_filter[$i] = DynamicFilter::Select('dynamic_filter.*', 'dynamic_tabel_sumber.tabel', 'dynamic_tabel_sumber.field', 'dynamic_tabel_sumber.query', 'dynamic_tabel_sumber.alias')
                            ->leftJoin('dynamic_tabel_sumber', 'dynamic_tabel_sumber.id', 'dynamic_filter.dynamic_tabel_sumber_id')
                            ->where('dynamic_filter.id', $dynamic_filter_id[$i])
                            ->first();
      }
      $namaFile = "Data Talenta ".date('dmY').".xlsx";
      return Excel::download(new DynamicSearchExport($talenta, $dynamic_filter, $query_string), $namaFile);
    }

    public function compare(Request $request)
    {
      $id = explode(",",$request->input('id'));

      $talenta = DynamicSearchController::query_talenta($request);
      $talenta = $talenta->leftJoin(DB::raw("lateral (
                                    select rp.id_talenta, jp.urutan as urutan, rp.penjurusan, jp.nama as jenjang_pendidikan
                                    from riwayat_pendidikan rp
                                    left join jenjang_pendidikan jp on jp.id = rp.id_jenjang_pendidikan
                                    where rp.id_talenta = talenta.id 
                                    order by jp.urutan desc
                                    limit 1) last_pendidikan"), 'talenta.id', '=', 'talenta.id')
                  ->leftJoin('agamas', 'agamas.id', '=', 'talenta.id_agama')
                  ->addSelect(DB::Raw("concat_ws(' - ', last_pendidikan.jenjang_pendidikan, last_pendidikan.penjurusan) AS pendidikan"))
                  ->addSelect('agamas.nama as agama')
                  ->groupBy('last_pendidikan.jenjang_pendidikan')
                  ->groupBy('last_pendidikan.penjurusan')
                  ->groupBy('agamas.nama')
                  ->whereIn("talenta.id", $id);
                  
      $riwayat_jabatan = [];
      for($i=0; $i<count($id); $i++){
        $riwayat_jabatan[$i] = DB::select("select EXTRACT(YEAR FROM v.tanggal_awal) as tahun_awal, 
                        EXTRACT(YEAR FROM v.tanggal_akhir) as tahun_akhir, 
                        s.nomenklatur_jabatan, p.nama_lengkap as perusahaan, 
                        p.id, skp.nomenklatur_baru, s.id_perusahaan, 
                        sk.id_grup_jabatan, case when skp.nomenklatur_baru is NULL then
                        s.nomenklatur_jabatan else skp.nomenklatur_baru END as jabatan
                        from view_organ_perusahaan v
                        left join struktur_organ s on v.id_struktur_organ = s.id
                        LEFT JOIN organ_perusahaan op ON op.id_struktur_organ = s.id
                        LEFT JOIN surat_keputusan sk ON sk.id = op.id_surat_keputusan
                        left join perusahaan p on p.id = s.id_perusahaan
                        LEFT JOIN sk_perubahan_nomenklatur skp ON skp.id_struktur_organ = s.ID
                        where v.id_talenta = $id[$i]
                        and v.aktif = 't'
                        and sk.id_grup_jabatan = 1
                        and (v.tanggal_akhir >= now( )
                          or v.tanggal_akhir is null)
                        order by v.id_struktur_organ ASC, s.urut ASC
                        limit 3 offset 1");
      }

      $dynamic_filter_id = $request->input('dynamic_filter_id');
      $dynamic_filter = [];
      for($i=0; $i<count($dynamic_filter_id); $i++){
          $dynamic_filter[$i] = DynamicFilter::Select('dynamic_filter.*', 'dynamic_tabel_sumber.tabel', 'dynamic_tabel_sumber.field', 'dynamic_tabel_sumber.query', 'dynamic_tabel_sumber.alias')
                            ->leftJoin('dynamic_tabel_sumber', 'dynamic_tabel_sumber.id', 'dynamic_filter.dynamic_tabel_sumber_id')
                            ->where('dynamic_filter.id', $dynamic_filter_id[$i])
                            ->first();
                            
          if($dynamic_filter[$i]->alias=='kategori'){
            $talenta = $talenta->leftJoin('kategori_jabatan_talent', 'kategori_jabatan_talent.id', '=', 'talenta.id_kategori_jabatan_talent')
                        ->addSelect('kategori_jabatan_talent.nama as kategori')
                        ->groupBy('kategori_jabatan_talent.nama');
          }else if($dynamic_filter[$i]->alias=='keahlian'){
            $talenta = $talenta->leftJoin('transaction_talenta_keahlian', 'transaction_talenta_keahlian.id_talenta', '=', 'talenta.id')
                        ->leftJoin('keahlian', 'keahlian.id', 'transaction_talenta_keahlian.id_keahlian')
                        ->whereRaw("keahlian.jenis_keahlian in ('".$nilai."')")
                        ->addSelect(DB::Raw("string_agg(keahlian.jenis_keahlian::text, ',')  as keahlians"))
                        ->addSelect(DB::Raw("'".$nilai."' as keahlian"))
                        ->groupBy('talenta.id');
          }else if($dynamic_filter[$i]->alias=='aspirasi_cluster'){
            $talenta = $talenta->leftJoin('transaction_talenta_cluster', 'transaction_talenta_cluster.id_talenta', '=', 'talenta.id')
                        ->leftJoin('cluster_bumn', 'cluster_bumn.id', 'transaction_talenta_cluster.id_cluster')
                        ->whereRaw("cluster_bumn.nama in ('".$nilai."')")
                        ->addSelect(DB::Raw("string_agg(cluster_bumn.nama::text, ',')  as aspirasi_clusters"))
                        ->addSelect(DB::Raw("'".$nilai."' as aspirasi_cluster"))
                        ->groupBy('talenta.id');
          }else if($dynamic_filter[$i]->alias=='aspirasi_kelas'){
            $talenta = $talenta->leftJoin('transaction_talenta_kelas', 'transaction_talenta_kelas.id_talenta', '=', 'talenta.id')
                        ->leftJoin('kelas_bumn', 'kelas_bumn.id', 'transaction_talenta_kelas.id_kelas')
                        ->whereRaw("kelas_bumn.nama in ('".$nilai."')")
                        ->addSelect(DB::Raw("string_agg(kelas_bumn.nama::text, ',')  as aspirasi_kelass"))
                        ->addSelect(DB::Raw("'".$nilai."' as aspirasi_kelas"))
                        ->groupBy('talenta.id');
          }else if($dynamic_filter[$i]->alias=='hasil_assessment'){
            $talenta = $talenta->leftJoin(DB::raw("(
              select assessment_nilai.hasil, assessment_nilai.id_talenta from assessment_nilai
              right join (
                select max(tanggal_expired) tanggal_expired, id_talenta from assessment_nilai group by id_talenta
                ) last_assessment on last_assessment.id_talenta = assessment_nilai.id_talenta and last_assessment.tanggal_expired = assessment_nilai.tanggal_expired
            ) assessment_nilai"), 'assessment_nilai.id_talenta', '=', 'talenta.id')
                        ->addSelect('assessment_nilai.hasil as hasil_assessment')
                        ->groupBy('assessment_nilai.hasil');
          }else if($dynamic_filter[$i]->alias=='tani'){
            $talenta = $talenta->leftJoin(DB::raw("(
              select riwayat_tani.tani, riwayat_tani.id_talenta from riwayat_tani
              right join (
                select max(tgl_awal) tgl_awal, id_talenta from riwayat_tani group by id_talenta
                ) last_tani on last_tani.id_talenta = riwayat_tani.id_talenta and last_tani.tgl_awal = riwayat_tani.tgl_awal
            ) riwayat_tani"), 'riwayat_tani.id_talenta', '=', 'talenta.id')
                        ->addSelect('riwayat_tani.tani as tani')
                        ->groupBy('riwayat_tani.tani');
          }
      }

      try{
          return view('talenta.dynamic_search.compare',[
                  'talentas' => $talenta->get(),
                  'id' => $id,
                  'riwayat_jabatan' => $riwayat_jabatan,
                  'dynamic_filter' => $dynamic_filter,
                  'dynamic_filter_id' => $dynamic_filter_id,
          ]);
      }catch(Exception $e){}
    }

    public function compare_pdf(Request $request)
    {
      $id = $request->id;

      $talenta = DynamicSearchController::query_talenta($request);
      $talenta = $talenta->leftJoin(DB::raw("lateral (
                                    select rp.id_talenta, jp.urutan as urutan, rp.penjurusan, jp.nama as jenjang_pendidikan
                                    from riwayat_pendidikan rp
                                    left join jenjang_pendidikan jp on jp.id = rp.id_jenjang_pendidikan
                                    where rp.id_talenta = talenta.id 
                                    order by jp.urutan desc
                                    limit 1) last_pendidikan"), 'talenta.id', '=', 'talenta.id')
                  ->leftJoin('agamas', 'agamas.id', '=', 'talenta.id_agama')
                  ->addSelect(DB::Raw("concat_ws(' - ', last_pendidikan.jenjang_pendidikan, last_pendidikan.penjurusan) AS pendidikan"))
                  ->addSelect('agamas.nama as agama')
                  ->groupBy('last_pendidikan.jenjang_pendidikan')
                  ->groupBy('last_pendidikan.penjurusan')
                  ->groupBy('agamas.nama')
                  ->whereIn("talenta.id", $id);

      $riwayat_jabatan = [];
      for($i=0; $i<count($id); $i++){
        $riwayat_jabatan[$i] = DB::select("select EXTRACT(YEAR FROM v.tanggal_awal) as tahun_awal, 
                        EXTRACT(YEAR FROM v.tanggal_akhir) as tahun_akhir, 
                        s.nomenklatur_jabatan, p.nama_lengkap as perusahaan, 
                        p.id, skp.nomenklatur_baru, s.id_perusahaan, 
                        sk.id_grup_jabatan, case when skp.nomenklatur_baru is NULL then
                        s.nomenklatur_jabatan else skp.nomenklatur_baru END as jabatan
                        from view_organ_perusahaan v
                        left join struktur_organ s on v.id_struktur_organ = s.id
                        LEFT JOIN organ_perusahaan op ON op.id_struktur_organ = s.id
                        LEFT JOIN surat_keputusan sk ON sk.id = op.id_surat_keputusan
                        left join perusahaan p on p.id = s.id_perusahaan
                        LEFT JOIN sk_perubahan_nomenklatur skp ON skp.id_struktur_organ = s.ID
                        where v.id_talenta = $id[$i]
                        and v.aktif = 't'
                        and sk.id_grup_jabatan = 1
                        and (v.tanggal_akhir >= now( )
                          or v.tanggal_akhir is null)
                        order by v.id_struktur_organ ASC, s.urut ASC
                        limit 3 offset 1");
      }

      $dynamic_filter_id = $request->dynamic_filter_id;
      $dynamic_filter = [];
      for($i=0; $i<count($dynamic_filter_id); $i++){
          $dynamic_filter[$i] = DynamicFilter::Select('dynamic_filter.*', 'dynamic_tabel_sumber.tabel', 'dynamic_tabel_sumber.field', 'dynamic_tabel_sumber.query', 'dynamic_tabel_sumber.alias')
                            ->leftJoin('dynamic_tabel_sumber', 'dynamic_tabel_sumber.id', 'dynamic_filter.dynamic_tabel_sumber_id')
                            ->where('dynamic_filter.id', $dynamic_filter_id[$i])
                            ->first();
                            
          if($dynamic_filter[$i]->alias=='kategori'){
            $talenta = $talenta->leftJoin('kategori_jabatan_talent', 'kategori_jabatan_talent.id', '=', 'talenta.id_kategori_jabatan_talent')
                        ->addSelect('kategori_jabatan_talent.nama as kategori')
                        ->groupBy('kategori_jabatan_talent.nama');
          }else if($dynamic_filter[$i]->alias=='keahlian'){
            $talenta = $talenta->leftJoin('transaction_talenta_keahlian', 'transaction_talenta_keahlian.id_talenta', '=', 'talenta.id')
                        ->leftJoin('keahlian', 'keahlian.id', 'transaction_talenta_keahlian.id_keahlian')
                        ->whereRaw("keahlian.jenis_keahlian in ('".$nilai."')")
                        ->addSelect(DB::Raw("string_agg(keahlian.jenis_keahlian::text, ',')  as keahlians"))
                        ->addSelect(DB::Raw("'".$nilai."' as keahlian"))
                        ->groupBy('talenta.id');
          }else if($dynamic_filter[$i]->alias=='aspirasi_cluster'){
            $talenta = $talenta->leftJoin('transaction_talenta_cluster', 'transaction_talenta_cluster.id_talenta', '=', 'talenta.id')
                        ->leftJoin('cluster_bumn', 'cluster_bumn.id', 'transaction_talenta_cluster.id_cluster')
                        ->whereRaw("cluster_bumn.nama in ('".$nilai."')")
                        ->addSelect(DB::Raw("string_agg(cluster_bumn.nama::text, ',')  as aspirasi_clusters"))
                        ->addSelect(DB::Raw("'".$nilai."' as aspirasi_cluster"))
                        ->groupBy('talenta.id');
          }else if($dynamic_filter[$i]->alias=='aspirasi_kelas'){
            $talenta = $talenta->leftJoin('transaction_talenta_kelas', 'transaction_talenta_kelas.id_talenta', '=', 'talenta.id')
                        ->leftJoin('kelas_bumn', 'kelas_bumn.id', 'transaction_talenta_kelas.id_kelas')
                        ->whereRaw("kelas_bumn.nama in ('".$nilai."')")
                        ->addSelect(DB::Raw("string_agg(kelas_bumn.nama::text, ',')  as aspirasi_kelass"))
                        ->addSelect(DB::Raw("'".$nilai."' as aspirasi_kelas"))
                        ->groupBy('talenta.id');
          }else if($dynamic_filter[$i]->alias=='hasil_assessment'){
            $talenta = $talenta->leftJoin(DB::raw("(
              select assessment_nilai.hasil, assessment_nilai.id_talenta from assessment_nilai
              right join (
                select max(tanggal_expired) tanggal_expired, id_talenta from assessment_nilai group by id_talenta
                ) last_assessment on last_assessment.id_talenta = assessment_nilai.id_talenta and last_assessment.tanggal_expired = assessment_nilai.tanggal_expired
            ) assessment_nilai"), 'assessment_nilai.id_talenta', '=', 'talenta.id')
                        ->addSelect('assessment_nilai.hasil as hasil_assessment')
                        ->groupBy('assessment_nilai.hasil');
          }else if($dynamic_filter[$i]->alias=='tani'){
            $talenta = $talenta->leftJoin(DB::raw("(
              select riwayat_tani.tani, riwayat_tani.id_talenta from riwayat_tani
              right join (
                select max(tgl_awal) tgl_awal, id_talenta from riwayat_tani group by id_talenta
                ) last_tani on last_tani.id_talenta = riwayat_tani.id_talenta and last_tani.tgl_awal = riwayat_tani.tgl_awal
            ) riwayat_tani"), 'riwayat_tani.id_talenta', '=', 'talenta.id')
                        ->addSelect('riwayat_tani.tani as tani')
                        ->groupBy('riwayat_tani.tani');
          }
      }

      try{
        $pdf = PDF::loadView($this->__route . '.compare_pdf', [
                    'talentas' => $talenta->get(),
                    'riwayat_jabatan' => $riwayat_jabatan,
                    'dynamic_filter' => $dynamic_filter,
            ], [], [
            'title' => 'Data Talenta',
            'author' => 'Kementerian BUMN',
            'creator' => 'Kementerian BUMN',
            'margin_top' => 15,
            'margin_left' => 15,
            'margin_bottom' => 15,
            'margin_right' => 15,
            'margin_footer' => 5,
        ]);

        return $pdf->download('Data talent.pdf');

      }catch(Exception $e){}
    }
}
