<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;
use App\User;
use App\Talenta;
use Route;
use Mail;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Collection;
use App\CVInterest;
use App\CVPajak;
use App\CVSummary;
use App\DataKeluarga;
use App\DataKaryaIlmiah;
use App\DataPenghargaan;
use App\TransactionTalentaKeahlian;
use App\PengalamanLain;
use App\RiwayatPendidikan;
use App\RiwayatPelatihan;
use App\RiwayatJabatanLain;
use App\RiwayatJabatanDirkomwas;
use App\RiwayatOrganisasi;
use App\LembagaAssessment;
use App\JenisJabatan;

class GeneralModel extends Model
{

	function __construct()
  {
       ini_set( 'max_execution_time', 0);
  }

	public function getparentmenu($search)
	{
		return Menu::where('label','ilike','%'.$search.'%')->orderBy('parent_id','asc')->orderBy('order','asc')->get();
	}

	public function getassidemenu()
	{

		try{
			$html = '<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">';
			$html .= '<div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">';
			$html .= '<ul class="kt-menu__nav ">';
			$html .= $this->getrecursivemenu(0, Menu::where('status', true)->orderBy('order','ASC')->get(), User::find((int)Auth::user()->id)->getmenuaccess());
			$html .= '</ul>';
			$html .= '</div>';
			$html .= '</div>';

			return $html;
		}catch(Exception $e){}
	}

	protected function setsvgproperty($icon)
	{
		if(empty($icon)){
			$iconsvg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <circle fill="#000000" cx="5" cy="12" r="2"/>
        <circle fill="#000000" cx="12" cy="12" r="2"/>
        <circle fill="#000000" cx="19" cy="12" r="2"/>
    </g>
</svg>';
		}
		else {
			$iconsvg = $icon;
		}
		return '<span class="kt-menu__link-icon">'.$iconsvg.'</span>';
	}

	protected function getrecursivemenu($parent_id, $menu, $data)
	{
		$html = '';
		$result = $menu->where('parent_id', (int)$parent_id)->sortBy('order');
		foreach ($result as $value) {
			$child = $menu->where('parent_id', (int)$value->id)->sortBy('order');
			$childData = $data->where('parent_id', (int)$value->id)->sortBy('order');

			$routing = $value->route_name != '#'? (Route::has($value->route_name)? route($value->route_name) : 'javascript:;') : '#';

			if((bool)$child->count() && (bool)$childData->count()){
				//jika ada child
				$class = (bool)$menu->where('parent_id', (int)$value->id)->where('route_name',Route::currentRouteName())->count()? 'kt-menu__item  kt-menu__item--submenu kt-menu__item--open kt-menu__item--here' : 'kt-menu__item  kt-menu__item--submenu';
				if(strpos(Route::currentRouteName(), 'referensi') !== false && (int)$value->id == 8){
					$class = 'kt-menu__item  kt-menu__item--submenu kt-menu__item--open kt-menu__item--here';
				}
				$html .= '<li class="'.$class.'" aria-haspopup="true" data-ktmenu-submenu-toggle="hover"><a href="'.$routing.'" class="kt-menu__link kt-menu__toggle">'.$this->setsvgproperty($value->icon).'<span class="kt-menu__link-text">'.$value->label.'</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>';
				$html .= '<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>';
				$html .= '<ul class="kt-menu__subnav">';
				$html .= '<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text">'.$value->label.'</span></span></li>';

				$html .= $this->getrecursivemenu((int)$value->id, $menu, $data);
				$html .= '</ul>';
				$html .= '</div>';
				$html .= '</li>';

			}else{
				//jika tidak ada child
				if((bool)$data->where('id', (int)$value->id)->count()){
					$class = Route::currentRouteName() === $value->route_name? 'kt-menu__item--active' : '';
					$html .= '<li class="kt-menu__item '.$class.'" aria-haspopup="true"><a href="'.$routing.'" class="kt-menu__link ">'.$this->setsvgproperty($value->icon).'<span class="kt-menu__link-text">'.$value->label.'</span></a></li>';
				}
			}
		}
		return $html;
	}

	public function getkategoriuser($search)
	{
		return KategoriUser::where('kategori','ilike','%'.$search.'%')->get();
	}

	public function getlembagaassessment($search)
	{
		return DB::table('lembaga_assessment')
		       ->select([
		       	'lembaga_assessment.id',
		       	'lembaga_assessment.nama'
		       ])
		       ->get();
	}

	public function getbumnactive($search)
	{
		return DB::table('perusahaan_status_history')
		       ->select([
		       	'perusahaan_status_history.id',
		       	'perusahaan_status_history.perusahaan_id',
		       	'perusahaan_status_history.tmt_awal',
		       	'perusahaan_status_history.tmt_akhir',
		       	'perusahaan.id_angka',
		       	'perusahaan.id_huruf',
		       	'perusahaan.nama_lengkap',
		       	'perusahaan.nama_singkat'
		       ])
		       ->join('perusahaan','perusahaan.id','=','perusahaan_status_history.perusahaan_id')
		       ->where('status_perusahaan_id', 1)
		       ->where(function($query) use($search) {
		       	   $query->where('perusahaan.nama_lengkap','ilike','%'.$search.'%')
		       	         ->orWhere('perusahaan.nama_singkat','ilike','%'.$search.'%');
		       })
		       ->whereRaw('tmt_awal <= NOW()::DATE')
		       ->whereRaw('(CASE WHEN tmt_akhir IS NOT NULL THEN tmt_akhir >= NOW()::DATE ELSE NOW()::DATE = NOW()::DATE END)')
		       ->get();
	}

	public function monitoringpejabat()
	{
		try {
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
	                      talenta.id as id_talenta,
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
	                        view_organ_perusahaan.aktif = 't' and struktur_organ.aktif = 't'
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
	                    'id_talenta' => $val->id_talenta
	                ]);
	            }

				return response()->json([
					'status' => (bool)$collections->count(),
					'msg' => null,
					'data' => $collections
				]);
		}catch(Exception $e){
			return response()->json([
				'status' => false,
				'msg' => 'Data tidak ditemukan',
				'data' => []
			]);
		}
	}

	/*public function monitoringpejabat($id_angka)
	{
		try{

			$where = " ";

            if($id_angka){
               $where .= " and perusahaan.id_angka = '".$id_angka."' ";
            }

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
                      talenta.id as id_talenta,
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
                        view_organ_perusahaan.aktif = 't' and struktur_organ.aktif = 't' $where
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
                    'id_talenta' => $val->id_talenta
                ]);
            }

			return response()->json([
				'status' => (bool)$collections->count(),
				'msg' => null,
				'data' => $collections
			]);
		}catch(Exception $e){
			return response()->json([
				'status' => false,
				'msg' => 'Data tidak ditemukan',
				'data' => []
			]);
		}
	}*/

	public function biodatatalent()
	{
		try{

	        $data = Talenta::select([
	        	'id',
	        	'nama_lengkap',
	        	'jenis_kelamin',
	        	'nik',
	        	'npwp',
	        	'email',
	        	'nomor_hp',
	        	'alamat',
	        	'suku',
	        	'gol_darah',
	        	'tanggal_lahir',
	        	'tempat_lahir',
	        	'gelar'

	        ])
	        ->orderBy('nama_lengkap', 'ASC')->get();
	        // ->paginate();

	        return response()->json([
	          'status' => (bool)$data->count(),
	          'msg' => null,
	          'data' => $data
	        ]);
		}catch(Exception $e){
			return response()->json([
				'status' => false,
				'msg' => 'Data tidak ditemukan',
				'data' => []
			]);
		}
	}

	/*public function cvpejabat()
	{
		try{

			$id_sql = "SELECT
						  talenta.*,
						  instansi_baru.nama as instansi,
						  jenis_asal_instansi.nama as asalinstansi,
						  status_kawin.nama as statuskawin,
						  status_talenta.nama as statustalenta,
						  kategori_jabatan_talent.nama as kategoritalent,
						  kategori_non_talent.nama as kategorinontalent,
						  perusahaan.nama_lengkap as talentaasal
						FROM
							talenta
							LEFT JOIN instansi_baru ON instansi_baru.ID = talenta.id_asal_instansi
							LEFT JOIN jenis_asal_instansi ON jenis_asal_instansi.ID = talenta.id_jenis_asal_instansi
							LEFT JOIN status_kawin ON status_kawin.ID = talenta.id_status_kawin
							LEFT JOIN status_talenta on status_talenta.id = talenta.id_status_talenta
							LEFT JOIN kategori_jabatan_talent on kategori_jabatan_talent.id = talenta.id_kategori_jabatan_talent
							LEFT JOIN kategori_non_talent on kategori_non_talent.id = talenta.id_kategori_non_talent
							LEFT JOIN perusahaan on perusahaan.id = talenta.id_perusahaan
						ORDER BY
						  talenta.nama_lengkap asc";

            $isicv  = DB::select(DB::raw($id_sql));
            $collections = new Collection;
            foreach($isicv as $val){

            	$cvinterest = CVInterest::where("id_talenta", $val->id)->get();
            	$cvpajak = CVPajak::where("id_talenta", $val->id)->get();
            	$cvsummary = CVSummary::where("id_talenta", $val->id)->get();
            	$datakeluarga = DataKeluarga::where("id_talenta", $val->id)->get();
            	$datakaryailmiah = DataKaryaIlmiah::where("id_talenta", $val->id)->get();
            	$datapenghargaan = DataPenghargaan::where("id_talenta", $val->id)->get();

            	//$datakeahlian = TransactionTalentaKeahlian::where("id_talenta", $val->id)->get();

            	$datakeahlian = DB::table('transaction_talenta_keahlian')
                       ->leftJoin('keahlian', 'keahlian.id', '=', 'transaction_talenta_keahlian.id_keahlian')
                       ->select(DB::raw("transaction_talenta_keahlian.*,
										  keahlian.deskripsi,
										  keahlian.jenis_keahlian"))
                       ->where('transaction_talenta_keahlian.id_talenta', '=', $val->id)
                       ->orderBy('transaction_talenta_keahlian.id_keahlian', 'ASC')
                       ->get();

            	$datapengalamanlain = PengalamanLain::where("id_talenta", $val->id)->get();
            	//$datariwayatpendidikan = RiwayatPendidikan::where("id_talenta", $val->id)->get();


            	$datariwayatpendidikan = DB::table('riwayat_pendidikan')
                       ->leftJoin('jenjang_pendidikan', 'jenjang_pendidikan.id', '=', 'riwayat_pendidikan.id_jenjang_pendidikan')
                       ->select(DB::raw("riwayat_pendidikan.*,
										  jenjang_pendidikan.nama"))
                       ->where('riwayat_pendidikan.id_talenta', '=', $val->id)
                       ->orderBy('riwayat_pendidikan.id_jenjang_pendidikan', 'ASC')
                       ->get();

            	$datariwayatpelatihan = RiwayatPelatihan::where("id_talenta", $val->id)->get();
            	$datariwayatjabatanlain = RiwayatJabatanLain::where("id_talenta", $val->id)->get();
            	$datariwayatorganisasi = RiwayatOrganisasi::where("id_talenta", $val->id)->get();


                $collections->push([

                    'id' => $val->id,
                    'pejabat' => $val->nama_lengkap,
                    'status_talenta' => $val->statustalenta,
                    'kategori_talenta' => $val->kategoritalent,
                    'kategori_non_talenta' => $val->kategorinontalent,
                    'talenta_asal' => $val->talentaasal,
                    'instansi' => $val->instansi,
                    'asalinstansi' => $val->asalinstansi,
                    'cvinterest' => $cvinterest,
                    'cvpajak' => $cvpajak,
                    'cvsummary' => $cvsummary,
                    'datakeluarga' => $datakeluarga,
                    'datakaryailmiah' => $datakaryailmiah,
                    'datapenghargaan' => $datapenghargaan,
                    'datakeahlian' => $datakeahlian,
                    'datapengalamanlain' => $datapengalamanlain,
                    'datariwayatpendidikan' => $datariwayatpendidikan,
                    'datariwayatpelatihan' => $datariwayatpelatihan,
                    'datariwayatjabatanlain' => $datariwayatjabatanlain,
                    'datariwayatorganisasi' => $datariwayatorganisasi,
                ]);
            }

			return response()->json([
				'status' => (bool)$collections->count(),
				'msg' => null,
				'data' => $collections
			]);
		}catch(Exception $e){
			return response()->json([
				'status' => false,
				'msg' => 'Data tidak ditemukan',
				'data' => []
			]);
		}
	}*/

	public function cvpejabat($id_talenta)
	{
		// try{

			$where = " ";

            if($id_talenta){
               $where .= " talenta.id = ".$id_talenta." ";
            }

			$id_sql = "SELECT
						  talenta.*,
						  instansi_baru.nama as instansi,
						  jenis_asal_instansi.nama as asalinstansi,
						  status_kawin.nama as statuskawin,
						  status_talenta.nama as statustalenta,
						  kategori_jabatan_talent.nama as kategoritalent,
						  kategori_non_talent.nama as kategorinontalent,
						  perusahaan.nama_lengkap as talentaasal,
						  agamas.nama as talentaagama,
						  jabatan.nama_lengkap as nama_perusahaan,
						  date_part( 'year', age( talenta.tanggal_lahir ) ) AS usia,
						  case when jabatan.nomenklatur_baru is NULL then
                                    jabatan.nomenklatur_jabatan else jabatan.nomenklatur_baru END as jabatan
						FROM
							talenta
							LEFT JOIN instansi_baru ON instansi_baru.ID = talenta.id_asal_instansi
							LEFT JOIN jenis_asal_instansi ON jenis_asal_instansi.ID = talenta.id_jenis_asal_instansi
							LEFT JOIN status_kawin ON status_kawin.ID = talenta.id_status_kawin
							LEFT JOIN status_talenta on status_talenta.id = talenta.id_status_talenta
							LEFT JOIN kategori_jabatan_talent on kategori_jabatan_talent.id = talenta.id_kategori_jabatan_talent
							LEFT JOIN kategori_non_talent on kategori_non_talent.id = talenta.id_kategori_non_talent
							LEFT JOIN perusahaan on perusahaan.id = talenta.id_perusahaan
							LEFT JOIN agamas on agamas.id = talenta.id_agama
							LEFT JOIN lateral (select s.nomenklatur_jabatan, p.nama_lengkap, p.id, skp.nomenklatur_baru, s.id_perusahaan, sk.id_grup_jabatan, p.id_klaster
                                      from view_organ_perusahaan v
                                      left join struktur_organ s on v.id_struktur_organ = s.id
                                      LEFT JOIN organ_perusahaan op ON op.id_struktur_organ = s.id
                                      LEFT JOIN surat_keputusan sk ON sk.id = op.id_surat_keputusan
                                      left join perusahaan p on p.id = s.id_perusahaan
                                      LEFT JOIN sk_perubahan_nomenklatur skp ON skp.id_struktur_organ = s.ID
                                      where v.id_talenta = talenta.id
                                      and v.aktif = 't' and s.aktif = 't'
                                      and (v.tanggal_akhir >= now( )
                                        or v.tanggal_akhir is null)
                                      order by v.id_struktur_organ ASC, s.urut ASC
                                      ) jabatan ON talenta.id = talenta.id
					    WHERE
						$where
						ORDER BY
						  talenta.nama_lengkap asc";

            $isicv  = DB::select(DB::raw($id_sql));
            $collections = new Collection;
            foreach($isicv as $val){

            	$cvinterest = CVInterest::where("id_talenta", $val->id)->get();
            	$cvpajak = CVPajak::where("id_talenta", $val->id)->get();
            	$cvsummary = CVSummary::where("id_talenta", $val->id)->get();
            	$datakeluarga = DataKeluarga::where("id_talenta", $val->id)->get();
            	$datakaryailmiah = DataKaryaIlmiah::where("id_talenta", $val->id)->get();
            	$datapenghargaan = DataPenghargaan::where("id_talenta", $val->id)->get();

            	//$datakeahlian = TransactionTalentaKeahlian::where("id_talenta", $val->id)->get();

            	$datakeahlian = DB::table('transaction_talenta_keahlian')
                       ->leftJoin('keahlian', 'keahlian.id', '=', 'transaction_talenta_keahlian.id_keahlian')
                       ->select(DB::raw("transaction_talenta_keahlian.*,
										  keahlian.deskripsi,
										  keahlian.jenis_keahlian"))
                       ->where('transaction_talenta_keahlian.id_talenta', '=', $val->id)
                       ->orderBy('transaction_talenta_keahlian.id_keahlian', 'ASC')
                       ->get();

            	$datapengalamanlain = PengalamanLain::where("id_talenta", $val->id)->get();
            	//$datariwayatpendidikan = RiwayatPendidikan::where("id_talenta", $val->id)->get();


            	$datariwayatpendidikan = DB::table('riwayat_pendidikan')
                       ->leftJoin('jenjang_pendidikan', 'jenjang_pendidikan.id', '=', 'riwayat_pendidikan.id_jenjang_pendidikan')
                       ->select(DB::raw("riwayat_pendidikan.*,
										  jenjang_pendidikan.nama"))
                       ->where('riwayat_pendidikan.id_talenta', '=', $val->id)
                       ->orderBy('riwayat_pendidikan.id_jenjang_pendidikan', 'ASC')
                       ->get();

            	$datariwayatpelatihan = RiwayatPelatihan::where("id_talenta", $val->id)->get();
            	$datariwayatjabatanlain = RiwayatJabatanLain::where("id_talenta", $val->id)->get();
            	$datariwayatorganisasi = RiwayatOrganisasi::where("id_talenta", $val->id)->get();
				$jabatanLain = RiwayatJabatanLain::select('riwayat_jabatan_lain.*','bidang_jabatan.nama as kategori_keahlian')
				->leftJoin('bidang_jabatan','bidang_jabatan.id', '=','riwayat_jabatan_lain.bidang_jabatan_id')
				->where('riwayat_jabatan_lain.id_talenta', $val->id)->orderBy('tanggal_awal','desc')->orderBy('tanggal_akhir','desc')->first();


                $collections->push([

                    'id' => $val->id,
                    'pejabat' => $val->nama_lengkap,
                    'status_talenta' => $val->statustalenta,
                    'status_kawin' => $val->statuskawin,
                    'usia' => $val->usia,
                    'kategori_talenta' => $val->kategoritalent,
                    'kategori_non_talenta' => $val->kategorinontalent,
                    'talenta_asal' => $val->talentaasal,
                    'instansi' => $val->instansi,
                    'asalinstansi' => $val->asalinstansi,
					'jabatan_lain' => [
						'penugasan' => $jabatanLain->penugasan??'',
						'perusahaan' => $jabatanLain->instansi??'',
						'tupoksi'=> $jabatanLain->tupoksi??'',
						'tanggal_awal'=>$jabatanLain->anggal_awal??'',
						'tanggal_akhir'=>$jabatanLain->anggal_akhir??'',
					],
                    'cvinterest' => $cvinterest,
                    'cvpajak' => $cvpajak,
                    'cvsummary' => $cvsummary,
                    'datakeluarga' => $datakeluarga,
                    'datakaryailmiah' => $datakaryailmiah,
                    'datapenghargaan' => $datapenghargaan,
                    'datakeahlian' => $datakeahlian,
                    'datapengalamanlain' => $datapengalamanlain,
                    'datariwayatpendidikan' => $datariwayatpendidikan,
                    'datariwayatpelatihan' => $datariwayatpelatihan,
                    // 'daftarRiwayatJabatan' => $daftarRiwayatJabatan,
                    'datariwayatjabatanlain' => $datariwayatjabatanlain,
                    'datariwayatorganisasi' => $datariwayatorganisasi,
                    'agama_talenta' => $val->talentaagama,
                    'kewarganegaraan' => $val->kewarganegaraan,
                    'foto' => 'https://hc.bumn.go.id/img/foto_talenta/'.$val->foto,
                ]);
            }

			return response()->json([
				'status' => (bool)$collections->count(),
				'msg' => null,
				'data' => $collections
			]);
		// }catch(Exception $e){
		// 	return response()->json([
		// 		'status' => false,
		// 		'msg' => 'Data tidak ditemukan',
		// 		'data' => []
		// 	]);
		// }
	}

	public function cvpejabatbyname($nama_talenta)
	{
		try{

			$where = " ";

            if($nama_talenta){
               $where .= " lower(talenta.nama_lengkap) like lower('%".$nama_talenta."%') ";
            }

			$id_sql = "SELECT
						  talenta.*,
						  instansi_baru.nama as instansi,
						  jenis_asal_instansi.nama as asalinstansi,
						  status_kawin.nama as statuskawin,
						  status_talenta.nama as statustalenta,
						  kategori_jabatan_talent.nama as kategoritalent,
						  kategori_non_talent.nama as kategorinontalent,
						  perusahaan.nama_lengkap as talentaasal,
						  agamas.nama as talentaagama,
						  date_part( 'year', age( talenta.tanggal_lahir ) ) AS usia
						FROM
							talenta
							LEFT JOIN instansi_baru ON instansi_baru.ID = talenta.id_asal_instansi
							LEFT JOIN jenis_asal_instansi ON jenis_asal_instansi.ID = talenta.id_jenis_asal_instansi
							LEFT JOIN status_kawin ON status_kawin.ID = talenta.id_status_kawin
							LEFT JOIN status_talenta on status_talenta.id = talenta.id_status_talenta
							LEFT JOIN kategori_jabatan_talent on kategori_jabatan_talent.id = talenta.id_kategori_jabatan_talent
							LEFT JOIN kategori_non_talent on kategori_non_talent.id = talenta.id_kategori_non_talent
							LEFT JOIN perusahaan on perusahaan.id = talenta.id_perusahaan
							LEFT JOIN agamas on agamas.id = talenta.id_agama
					    WHERE
						$where
						ORDER BY
						  talenta.nama_lengkap asc";

            $isicv  = DB::select(DB::raw($id_sql));
            $collections = new Collection;
            foreach($isicv as $val){

            	$cvinterest = CVInterest::where("id_talenta", $val->id)->get();
            	$cvpajak = CVPajak::where("id_talenta", $val->id)->get();
            	$cvsummary = CVSummary::where("id_talenta", $val->id)->get();
            	$datakeluarga = DataKeluarga::where("id_talenta", $val->id)->get();
            	$datakaryailmiah = DataKaryaIlmiah::where("id_talenta", $val->id)->get();
            	$datapenghargaan = DataPenghargaan::where("id_talenta", $val->id)->get();

            	//$datakeahlian = TransactionTalentaKeahlian::where("id_talenta", $val->id)->get();

            	$datakeahlian = DB::table('transaction_talenta_keahlian')
                       ->leftJoin('keahlian', 'keahlian.id', '=', 'transaction_talenta_keahlian.id_keahlian')
                       ->select(DB::raw("transaction_talenta_keahlian.*,
										  keahlian.deskripsi,
										  keahlian.jenis_keahlian"))
                       ->where('transaction_talenta_keahlian.id_talenta', '=', $val->id)
                       ->orderBy('transaction_talenta_keahlian.id_keahlian', 'ASC')
                       ->get();

            	$datapengalamanlain = PengalamanLain::where("id_talenta", $val->id)->get();
            	//$datariwayatpendidikan = RiwayatPendidikan::where("id_talenta", $val->id)->get();


            	$datariwayatpendidikan = DB::table('riwayat_pendidikan')
                       ->leftJoin('jenjang_pendidikan', 'jenjang_pendidikan.id', '=', 'riwayat_pendidikan.id_jenjang_pendidikan')
                       ->select(DB::raw("riwayat_pendidikan.*,
										  jenjang_pendidikan.nama"))
                       ->where('riwayat_pendidikan.id_talenta', '=', $val->id)
                       ->orderBy('riwayat_pendidikan.id_jenjang_pendidikan', 'ASC')
                       ->get();

            	$datariwayatpelatihan = RiwayatPelatihan::where("id_talenta", $val->id)->get();
            	$datariwayatjabatanlain = RiwayatJabatanLain::where("id_talenta", $val->id)->get();
            	$datariwayatorganisasi = RiwayatOrganisasi::where("id_talenta", $val->id)->get();


                $collections->push([

                    'id' => $val->id,
                    'pejabat' => $val->nama_lengkap,
                    'status_talenta' => $val->statustalenta,
                    'status_kawin' => $val->statuskawin,
                    'usia' => $val->usia,
                    'kategori_talenta' => $val->kategoritalent,
                    'kategori_non_talenta' => $val->kategorinontalent,
                    'talenta_asal' => $val->talentaasal,
                    'instansi' => $val->instansi,
                    'asalinstansi' => $val->asalinstansi,
                    'cvinterest' => $cvinterest,
                    'cvpajak' => $cvpajak,
                    'cvsummary' => $cvsummary,
                    'datakeluarga' => $datakeluarga,
                    'datakaryailmiah' => $datakaryailmiah,
                    'datapenghargaan' => $datapenghargaan,
                    'datakeahlian' => $datakeahlian,
                    'datapengalamanlain' => $datapengalamanlain,
                    'datariwayatpendidikan' => $datariwayatpendidikan,
                    'datariwayatpelatihan' => $datariwayatpelatihan,
                    'datariwayatjabatanlain' => $datariwayatjabatanlain,
                    'datariwayatorganisasi' => $datariwayatorganisasi,
                    'agama_talenta' => $val->talentaagama,
                    'kewarganegaraan' => $val->kewarganegaraan,
                    'foto' => 'https://hc.bumn.go.id/img/foto_talenta/'.$val->foto
                ]);
            }

			return response()->json([
				'status' => (bool)$collections->count(),
				'msg' => null,
				'data' => $collections
			]);
		}catch(Exception $e){
			return response()->json([
				'status' => false,
				'msg' => 'Data tidak ditemukan',
				'data' => []
			]);
		}
	}

	public function pejabatbykepemilikan($jenis_perusahaan)
	{
		try{

      $where = '';
      if($jenis_perusahaan=='bumn'){
        $where = "perusahaan.induk = 0 AND perusahaan.level = 0 AND perusahaan.kepemilikan = 'BUMN' AND ";
      }else if($jenis_perusahaan=='anak'){
        $where = 'perusahaan.level = 1 AND';
      }else if($jenis_perusahaan=='cucu'){
        $where = 'perusahaan.level = 2 AND';
      }

				$id_sql = "SELECT
		                      perusahaan.ID,
		                    CASE
		                        
		                        WHEN ( organ_perusahaan.tanggal_akhir < CURRENT_DATE ) THEN
		                      TRUE ELSE FALSE 
		                      END AS expire,
		                    CASE
		                        
		                        WHEN ( organ_perusahaan.tanggal_akhir < CURRENT_DATE - INTERVAL '3 months ago' ) THEN
		                      TRUE ELSE FALSE 
		                      END AS kurang3,
		                    CASE
		                        
		                        WHEN ( organ_perusahaan.tanggal_akhir < CURRENT_DATE - INTERVAL '6 months ago' ) THEN
		                      TRUE ELSE FALSE 
		                      END AS kurang6,
		                      talenta.nama_lengkap AS pejabat,
		                      grup_jabatan.ID AS grup_jabat_id,
		                      grup_jabatan.nama AS grup_jabat_nama,
		                    CASE
		                        
		                        WHEN organ_perusahaan.nomenklatur IS NULL THEN
		                        struktur_organ.nomenklatur_jabatan ELSE organ_perusahaan.nomenklatur 
		                      END AS nama_jabatan,
		                      surat_keputusan.nomor AS surat_keputusan,
		                      surat_keputusan.tanggal_sk,
		                      surat_keputusan.tanggal_serah_terima,
		                      surat_keputusan.id_grup_jabatan,
		                      organ_perusahaan.tanggal_awal,
		                      organ_perusahaan.tanggal_akhir,
		                      age( organ_perusahaan.tanggal_akhir, organ_perusahaan.tanggal_awal ) AS lama_menjabat,
		                    CASE
		                        
		                        WHEN date_part( 'year', age( organ_perusahaan.tanggal_akhir, organ_perusahaan.tanggal_awal ) ) < 1 THEN
		                        '< 1 tahun' 
		                        WHEN date_part( 'year', age( organ_perusahaan.tanggal_akhir, organ_perusahaan.tanggal_awal ) ) < 2 THEN
		                        '1 - 2 tahun' 
		                        WHEN date_part( 'year', age( organ_perusahaan.tanggal_akhir, organ_perusahaan.tanggal_awal ) ) < 3 THEN
		                        '2 - 3 tahun' 
		                        WHEN date_part( 'year', age( organ_perusahaan.tanggal_akhir, organ_perusahaan.tanggal_awal ) ) < 4 THEN
		                        '3 - 4 tahun' 
		                        WHEN date_part( 'year', age( organ_perusahaan.tanggal_akhir, organ_perusahaan.tanggal_awal ) ) < 5 THEN
		                        '4 - 5 tahun' 
		                      END AS kelompok_masa_jabatan,
		                      organ_perusahaan.plt,
		                      organ_perusahaan.komisaris_independen,
		                      instansi.nama AS instansi,
		                      jenis_asal_instansi.nama AS asal_instansi,
		                      talenta.jabatan_asal_instansi,
		                      organ_perusahaan.id_periode_jabatan AS periode,
		                      struktur_organ.ID AS struktur_id,
		                      talenta.*,
		                      agamas.nama AS talenta_agama,
		                      status_kawin.nama AS talenta_status_kawin,
		                      cluster_bumn.nama as klaster_bumn,
		                      perusahaan.kelas as kelas_bumn,
		                      perusahaan.wamen as wamen_bumn,
		                    CASE
		                        
		                        WHEN perusahaan.LEVEL = 0 THEN
		                        bumn_0.nama_lengkap 
		                        WHEN perusahaan.LEVEL = 1 THEN
		                        bumn_1.nama_lengkap 
		                        WHEN perusahaan.LEVEL = 2 THEN
		                        bumn_2.nama_lengkap 
		                        WHEN perusahaan.LEVEL = 3 THEN
		                        bumn_3.nama_lengkap 
		                        WHEN perusahaan.LEVEL = 4 THEN
		                        bumn_4.nama_lengkap 
		                      END AS bumn_induk,
		                      CASE
		                        
		                        WHEN perusahaan.LEVEL = 0 THEN
		                        bumn_0.id_angka 
		                        WHEN perusahaan.LEVEL = 1 THEN
		                        bumn_1.id_angka 
		                        WHEN perusahaan.LEVEL = 2 THEN
		                        bumn_2.id_angka 
		                        WHEN perusahaan.LEVEL = 3 THEN
		                        bumn_3.id_angka 
		                        WHEN perusahaan.LEVEL = 4 THEN
		                        bumn_4.id_angka 
		                      END AS bumn_id,
		                    CASE
		                        
		                        WHEN perusahaan.LEVEL = 1 THEN
		                        bumn_0.nama_lengkap 
		                        WHEN perusahaan.LEVEL = 2 THEN
		                        bumn_1.nama_lengkap 
		                        WHEN perusahaan.LEVEL = 3 THEN
		                        bumn_2.nama_lengkap 
		                        WHEN perusahaan.LEVEL = 4 THEN
		                        bumn_3.nama_lengkap 
		                      END AS bumn_anak,
		                    CASE
		                        
		                        WHEN perusahaan.LEVEL = 1 THEN
		                        bumn_0.id_angka 
		                        WHEN perusahaan.LEVEL = 2 THEN
		                        bumn_1.id_angka 
		                        WHEN perusahaan.LEVEL = 3 THEN
		                        bumn_2.id_angka 
		                        WHEN perusahaan.LEVEL = 4 THEN
		                        bumn_3.id_angka 
		                      END AS bumn_anak_id,
		                    CASE
		                        
		                        WHEN perusahaan.LEVEL >= 2 THEN
		                        bumn_0.nama_lengkap 
		                      END AS bumn_cucu,
		                    CASE
		                        
		                        WHEN perusahaan.LEVEL >= 2 THEN
		                        bumn_0.id_angka
		                      END AS bumn_cucu_id,
		                      date_part( 'year', age( talenta.tanggal_lahir ) ) AS usia,
		                    CASE
		                        
		                        WHEN date_part( 'year', age( talenta.tanggal_lahir ) ) < 41 THEN
		                        '< 40 tahun' 
		                        WHEN date_part( 'year', age( talenta.tanggal_lahir ) ) > 40 
		                        AND date_part( 'year', age( talenta.tanggal_lahir ) ) < 51 THEN
		                          '41 - 50 tahun' 
		                          WHEN date_part( 'year', age( talenta.tanggal_lahir ) ) > 50 
		                          AND date_part( 'year', age( talenta.tanggal_lahir ) ) < 61 THEN
		                            '51 - 60 tahun' 
		                            WHEN date_part( 'year', age( talenta.tanggal_lahir ) ) > 60 THEN
		                            '> 60 tahun' 
		                          END AS kelompok_usia,
		                          pendidikan_slta.perguruan_tinggi AS pendidikan_slta,
		                          pendidikan_s1.perguruan_tinggi AS pendidikan_s1,
		                          pendidikan_s1.penjurusan AS pendidikan_s1_jurusan,
		                          pendidikan_s2.perguruan_tinggi AS pendidikan_s2,
		                          pendidikan_s2.penjurusan AS pendidikan_s2_jurusan,
		                          pendidikan_s3.perguruan_tinggi AS pendidikan_s3,
		                          pendidikan_s3.penjurusan AS pendidikan_s3_jurusan,
		                          array_to_string( ARRAY_AGG ( keahlian.jenis_keahlian ), ',' ) AS keahlian,
		                          concat_ws ( ' - ', jabatan_2.penugasan, jabatan_2.instansi ) AS jabatan_2,
		                          concat_ws ( ' - ', jabatan_1.penugasan, jabatan_1.instansi ) AS jabatan_1,
		                        CASE
		                            
		                            WHEN surat_keputusan.id_grup_jabatan = 1 THEN
		                            'Dekom/Dewas' 
		                            WHEN surat_keputusan.id_grup_jabatan = 4 THEN
		                            'Komisaris' 
		                          END AS grup_jabatan,
		                          jenis_jabatan.nama AS jenis_jabatan,
		                          assesmen_direksi.nilai_asesmen_global AS nilai_assesmen,
		                          penghasilan.gaji_pokok,
		                          penghasilan.tantiem,
		                          penghasilan.tunjangan 
		                        FROM
		                          organ_perusahaan
		                          LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
		                          LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
		                          LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
		                          LEFT JOIN perusahaan AS bumn_0 ON bumn_0.ID = struktur_organ.id_perusahaan
		                          LEFT JOIN perusahaan AS bumn_1 ON bumn_1.ID = bumn_0.induk
		                          LEFT JOIN perusahaan AS bumn_2 ON bumn_2.ID = bumn_1.induk
		                          LEFT JOIN perusahaan AS bumn_3 ON bumn_3.ID = bumn_2.induk
		                          LEFT JOIN perusahaan AS bumn_4 ON bumn_4.ID = bumn_3.induk
		                          LEFT JOIN jenis_jabatan ON jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
		                          LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
		                          LEFT JOIN instansi ON instansi.ID = talenta.id_asal_instansi
		                          LEFT JOIN cluster_bumn on cluster_bumn.id = perusahaan.id_klaster
		                          LEFT JOIN jenis_asal_instansi ON jenis_asal_instansi.ID = instansi.id_jenis_asal_instansi
		                          LEFT JOIN grup_jabatan ON grup_jabatan.ID = jenis_jabatan.id_grup_jabatan
		                          LEFT JOIN agamas ON agamas.ID = talenta.id_agama
		                          LEFT JOIN status_kawin ON status_kawin.ID = talenta.id_status_kawin
		                          LEFT JOIN riwayat_pendidikan AS pendidikan_slta ON pendidikan_slta.id_talenta = talenta.ID 
		                          AND pendidikan_slta.id_jenjang_pendidikan = 5
		                          LEFT JOIN riwayat_pendidikan AS pendidikan_s1 ON pendidikan_s1.id_talenta = talenta.ID 
		                          AND ( pendidikan_s1.id_jenjang_pendidikan = 2 OR pendidikan_s1.id_jenjang_pendidikan = 10 )
		                          LEFT JOIN riwayat_pendidikan AS pendidikan_s2 ON pendidikan_s2.id_talenta = talenta.ID 
		                          AND ( pendidikan_s2.id_jenjang_pendidikan = 3 )
		                          LEFT JOIN riwayat_pendidikan AS pendidikan_s3 ON pendidikan_s3.id_talenta = talenta.ID 
		                          AND ( pendidikan_s3.id_jenjang_pendidikan = 4 )
		                          LEFT JOIN transaction_talenta_keahlian ON transaction_talenta_keahlian.id_talenta = talenta.
		                          ID LEFT JOIN keahlian ON keahlian.ID = transaction_talenta_keahlian.id_keahlian
		                          LEFT JOIN LATERAL ( SELECT riwayat_jabatan_lain.* FROM riwayat_jabatan_lain WHERE riwayat_jabatan_lain.id_talenta = talenta.ID ORDER BY tanggal_awal LIMIT 1 ) AS jabatan_2
		                          ON TRUE LEFT JOIN LATERAL ( SELECT riwayat_jabatan_lain.* FROM riwayat_jabatan_lain WHERE riwayat_jabatan_lain.id_talenta = talenta.ID ORDER BY tanggal_awal LIMIT 1 OFFSET 1 ) AS jabatan_1
		                          ON TRUE LEFT JOIN LATERAL ( SELECT assesmen_direksi.* FROM assesmen_direksi WHERE assesmen_direksi.id_talenta = talenta.ID ORDER BY assesmen_direksi.updated_at DESC LIMIT 1 ) AS assesmen_direksi
		                          ON TRUE LEFT JOIN LATERAL (
		                          SELECT
		                            penghasilan.* 
		                          FROM
		                            penghasilan 
		                          WHERE
		                            penghasilan.id_talenta = talenta.ID 
		                            AND penghasilan.id_struktur_organ = organ_perusahaan.id_struktur_organ 
		                          ORDER BY
		                            penghasilan.tahun DESC 
		                            LIMIT 1 
		                          ) AS penghasilan ON TRUE 
		                        WHERE
		                        $where
		                          surat_keputusan.save = 't' 
		                          AND struktur_organ.aktif = 't' 
		                          AND organ_perusahaan.aktif = 't'
		                          
		                        GROUP BY
		                          perusahaan.ID,
		                          organ_perusahaan.tanggal_akhir,
		                          organ_perusahaan.aktif,
		                          talenta.nama_lengkap,
		                          talenta.ID,
		                          grup_jabatan.ID,
		                          organ_perusahaan.nomenklatur,
		                          struktur_organ.nomenklatur_jabatan,
		                          surat_keputusan.nomor,
		                          surat_keputusan.tanggal_sk,
		                          organ_perusahaan.tanggal_awal,
		                          organ_perusahaan.plt,
		                          organ_perusahaan.komisaris_independen,
		                          instansi.nama,
		                          jenis_asal_instansi.nama,
		                          organ_perusahaan.id_periode_jabatan,
		                          struktur_organ.ID,
		                          talenta.jenis_kelamin,
		                          talenta.nik,
		                          talenta.npwp,
		                          talenta.email,
		                          talenta.nomor_hp,
		                          talenta.kewarganegaraan,
		                          talenta.gol_darah,
		                          talenta.suku,
		                          agamas.nama,
		                          talenta.tanggal_lahir,
		                          talenta.tempat_lahir,
		                          status_kawin.nama,
		                          bumn_1.nama_lengkap,
		                          bumn_2.nama_lengkap,
		                          bumn_3.nama_lengkap,
		                          bumn_4.nama_lengkap,
		                          bumn_0.nama_lengkap,
		                          bumn_1.id_angka,
															bumn_2.id_angka,
															bumn_3.id_angka,
															bumn_4.id_angka,
															bumn_0.id_angka,
		                          pendidikan_slta.perguruan_tinggi,
		                          pendidikan_s1.perguruan_tinggi,
		                          pendidikan_s1.penjurusan,
		                          pendidikan_s2.perguruan_tinggi,
		                          pendidikan_s2.penjurusan,
		                          pendidikan_s3.perguruan_tinggi,
		                          pendidikan_s3.penjurusan,
		                          jabatan_2.penugasan,
		                          jabatan_2.instansi,
		                          jabatan_1.penugasan,
		                          jabatan_1.instansi,
		                          surat_keputusan.id_grup_jabatan,
		                          surat_keputusan.tanggal_serah_terima,
		                          jenis_jabatan.nama,
		                          assesmen_direksi.nilai_asesmen_global,
		                          penghasilan.gaji_pokok,
		                          penghasilan.tantiem,
		                          penghasilan.tunjangan,
		                          cluster_bumn.nama 
		                        ORDER BY
		                          perusahaan.ID ASC,
		                        grup_jabatan.ID ASC,
		                      struktur_organ.urut ASC";
		        $data  = DB::select(DB::raw($id_sql));
            $collections = new Collection;
            foreach($data as $val){


                $collections->push([

                    'pejabat_id' => $val->id,
                    'pejabat' => $val->pejabat,
                    'wamen_bumn' => $val->wamen_bumn,
                    'klaster_bumn' => $val->klaster_bumn,
                    'kelas_bumn' => $val->kelas_bumn,
                    'bumn_id' => $val->bumn_id,
                    'bumn_induk' => $val->bumn_induk,
                    'bumn_anak_id' => $val->bumn_anak_id,
                    'bumn_anak' => $val->bumn_anak,
                    'bumn_cucu_id' => $val->bumn_cucu_id,
                    'bumn_cucu' => $val->bumn_cucu
                ]);
            }

			return response()->json([
				'status' => (bool)$collections->count(),
				'total' => (int)$collections->count(),
				'msg' => null,
				'data' => $collections
			]);
		}catch(Exception $e){
			return response()->json([
				'status' => false,
				'total' => (int)$collections->count(),
				'msg' => 'Data tidak ditemukan',
				'data' => []
			]);
		}
	}

	public function klasterbumn()
	{
		$sql = 'SELECT
							perusahaan.id,
							perusahaan.nama_lengkap,
							perusahaan.id_klaster,
							cluster_bumn.nama
						FROM
							perusahaan
							LEFT JOIN cluster_bumn ON cluster_bumn.ID = perusahaan.id_klaster 
						WHERE
							perusahaan.id_klaster is not null
						ORDER BY
						  perusahaan.id asc';

		$datas  = DB::select(DB::raw($sql));
    $collections = new Collection;

    foreach($datas as $data){
    	$collections->push([
          'id' => $data->id,
          'bumn' => $data->nama_lengkap,
          'id_klaster' => $data->id_klaster,
          'klaster_nama' => $data->nama,
      ]);
    }

    return response()->json([
			'status' => (bool)$collections->count(),
			'msg' => null,
			'data' => $collections
		]);

	}

	public function jenisjabatan()
	{
		try{

	        $data = JenisJabatan::select([
	        	'id',
	        	'id_grup_jabatan',
	        	'nama',
	        	'prosentase_gaji',
	        	'kode',
	        	'urut'

	        ])
	        ->orderBy('id', 'ASC')
	        ->get();

	        return response()->json([
	          'status' => (bool)$data->count(),
	          'msg' => null,
	          'data' => $data
	        ]);
		}catch(Exception $e){
			return response()->json([
				'status' => false,
				'msg' => 'Data tidak ditemukan',
				'data' => []
			]);
		}
	}

}
