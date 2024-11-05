<?php

namespace App\Http\Controllers\Remunerasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Remunerasi;
use App\Perusahaan;
use App\JenisJabatan;
use App\FaktorPenghasilan;
use App\MataUang;
use App\StrukturOrgan;
use DB;
use Illuminate\Validation\Rule;

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
         $this->__route = 'remunerasi.board';
         // $this->middleware('permission:board-list');
         // $this->middleware('permission:board-create');
         // $this->middleware('permission:board-edit');
         // $this->middleware('permission:board-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Remunerasi Board');
        return view($this->__route.'.index',[
            'pagetitle' => 'Remunerasi Board',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('remunerasi.board.index'),
                    'menu' => 'Board'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        $tahun = $request->tahun;
        $remunerasi = Remunerasi::get();
        $str_query = "SELECT
          perusahaan.ID,
          perusahaan.nama_lengkap AS nama,
          remunerasi.tahun,
          SUM (
          CASE
              remunerasi.id_faktor_penghasilan 
              WHEN 1 THEN
              remunerasi.jumlah_default ELSE 0 
            END 
            ) AS gaji_pokok,
          MAX (
          CASE
              remunerasi.id_faktor_penghasilan 
              WHEN 1 THEN
              mata_uang.kode ELSE '0' 
            END 
            ) AS mata_uang_gaji_pokok,
            SUM (
            CASE
                remunerasi.id_faktor_penghasilan 
                WHEN 4 THEN
                remunerasi.jumlah_default ELSE 0 
              END 
              ) AS tantiem_board,
              MAX (
              CASE
                  remunerasi.id_faktor_penghasilan 
                  WHEN 4 THEN
                  mata_uang.kode ELSE '0' 
                END 
                ) AS mata_uang_tantiem_board,
              MAX (
              CASE
                  remunerasi.id_faktor_penghasilan 
                  WHEN 1 THEN
                  remunerasi.ID ELSE 0 
                END 
                ) AS gaji_pokok_remun_id,
                MAX (
                CASE
                    remunerasi.id_faktor_penghasilan 
                    WHEN 4 THEN
                    remunerasi.ID ELSE 0 
                  END 
                  ) AS tantiem_board_remun_id 
                FROM
                  remunerasi
                  LEFT JOIN struktur_organ ON struktur_organ.id = remunerasi.id_struktur_organ
                  LEFT JOIN jenis_jabatan ON struktur_organ.id_jenis_jabatan = jenis_jabatan.ID 
                  LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
                  LEFT JOIN faktor_penghasilan ON faktor_penghasilan.ID = remunerasi.id_faktor_penghasilan
                  LEFT JOIN mata_uang ON mata_uang.id = remunerasi.id_mata_uang
                WHERE
                  jenis_jabatan.ID = 1 
                  AND remunerasi.id_faktor_penghasilan IN ( 1, 4 ) 
                  AND remunerasi.tahun = $tahun
                  AND struktur_organ.aktif = TRUE
                GROUP BY
                perusahaan.ID,
          remunerasi.tahun";

        $remunerasi  = DB::select(DB::raw($str_query));
        try{
            return datatables()->of($remunerasi)
            ->editColumn('gaji_pokok', function ($row){
              if ($row->gaji_pokok_remun_id)
                return $row->mata_uang_gaji_pokok.' '.number_format ($row->gaji_pokok, 0, ",", "." ).' <button type="button" class="btn btn-link cls-button-edit" data-id="'.$row->gaji_pokok_remun_id.'" data-toggle="tooltip" data-original-title="Ubah data Gaji Pokok '.$row->nama.'"><i class="la la-edit" style="padding: 0px;"></i></button>';
              else
                return '<span style="padding-right: 14px;">'.$row->gaji_pokok.'</span>';
            }) 

            ->editColumn('tantiem_board', function ($row){
                if ($row->tantiem_board_remun_id)
                  return $row->mata_uang_tantiem_board.' '.number_format ($row->tantiem_board, 0, ",", "." ).' <button type="button" class="btn btn-link cls-button-edit" data-id="'.$row->tantiem_board_remun_id.'" data-toggle="tooltip" data-original-title="Ubah data Tantiem Board '.$row->nama.'"><i class="la la-edit" style="padding: 0px;"></i></button>';
                else
                  return '<span style="padding-right: 14px;">'.$row->tantiem_board.'</span>';;
            })
            ->rawColumns(['gaji_pokok','tantiem_board'])
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
        $remunerasi = Remunerasi::get();
        $perusahaans = Perusahaan::orderBy('id')->get();
        $faktor_penghasilans = FaktorPenghasilan::whereIn('id',[1,4])->get();
        $mata_uangs = MataUang::get();
       
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'perusahaans' => $perusahaans,
            'faktor_penghasilans' => $faktor_penghasilans,
            'mata_uangs' => $mata_uangs,
            'remunerasi' => $remunerasi
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
            // $param['id_perusahaan'] = $request->input('id_perusahaan');
            $param['tahun'] = $request->input('tahun');
            $param['id_faktor_penghasilan'] = 1;
            $param['jumlah'] = preg_replace('/[\p{P} ]+/', '', $request->input('jumlah'));
            $param['jumlah_default'] = $param['jumlah'];
            $param['id_mata_uang'] = $request->input('id_mata_uang');
            // untuk mata uang IDR
            if($param['id_mata_uang'] == 1)
              $param['kurs'] = 1;
            else{
              $param['kurs'] = preg_replace('/[\p{P} ]+/', '', $request->input('kurs'));
              $param['tgl_kurs'] = $request->input('tgl_kurs');
            }
            //get struktur organ
            $struktur_organ = StrukturOrgan::where('id_perusahaan', $request->input('id_perusahaan'))
                      ->where('id_jenis_jabatan', 1)
                      ->where('aktif', true)
                      ->first();
            $param['id_struktur_organ'] = $struktur_organ->id;

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $remunerasi = Remunerasi::create((array)$param);

                                  $id = $remunerasi->id;
                                  $id_perusahaan = $request->input('id_perusahaan');
                                  $tahun = $param['tahun'];
                                  $id_faktor_penghasilan = $param['id_faktor_penghasilan'];

                                  if($id_faktor_penghasilan == 1){
                                    $str_query = "DELETE FROM remunerasi WHERE tahun = $tahun AND id_faktor_penghasilan = $id_faktor_penghasilan AND id_struktur_organ IN(
                                      SELECT id 
                                      FROM struktur_organ 
                                      WHERE id_perusahaan = $id_perusahaan AND id_jenis_jabatan != 1);
                                      INSERT INTO remunerasi (
                                      tahun,
                                      id_struktur_organ,
                                      id_faktor_penghasilan,
                                      jumlah,
                                      jumlah_default,
                                      id_mata_uang,
                                      kurs,
                                      tgl_kurs,
                                      created_at,
                                      updated_at 
                                    )
                                    SELECT
                                      remunerasi.tahun,
                                      struktur_organ.ID AS id_struktur_organ,
                                      remunerasi.id_faktor_penghasilan,
                                      CASE
                                        WHEN jenis_jabatan.id_jns_jab_pengali = 1 THEN
                                        jenis_jabatan.prosentase_gaji / 100 * remunerasi.jumlah_default 
                                        WHEN pengali.id_jns_jab_pengali = 1 THEN
                                        jenis_jabatan.prosentase_gaji / 100 * pengali.prosentase_gaji / 100 * remunerasi.jumlah_default 
                                        ELSE
                                        0
                                      END AS jumlah, 
                                      CASE
                                        WHEN jenis_jabatan.id_jns_jab_pengali = 1 THEN
                                        jenis_jabatan.prosentase_gaji / 100 * remunerasi.jumlah_default 
                                        WHEN pengali.id_jns_jab_pengali = 1 THEN
                                        jenis_jabatan.prosentase_gaji / 100 * pengali.prosentase_gaji / 100 * remunerasi.jumlah_default 
                                        ELSE
                                        0
                                      END AS jumlah_default,
                                      remunerasi.id_mata_uang,
                                      remunerasi.kurs,
                                      remunerasi.tgl_kurs,
                                      remunerasi.created_at,
                                      remunerasi.updated_at 
                                    FROM
                                      struktur_organ,
                                      remunerasi,
                                      jenis_jabatan,
                                      jenis_jabatan AS pengali
                                    WHERE
                                      jenis_jabatan.ID != 1 
                                      AND remunerasi.ID = $id 
                                      AND struktur_organ.id_perusahaan = $id_perusahaan 
                                      AND struktur_organ.aktif = TRUE 
                                      AND jenis_jabatan.ID = struktur_organ.id_jenis_jabatan 
                                      AND pengali.ID = jenis_jabatan.id_jns_jab_pengali 
                                    ORDER BY
                                      jenis_jabatan.ID;
                                      INSERT INTO remunerasi (
                                        tahun,
                                        id_struktur_organ,
                                        id_faktor_penghasilan,
                                        jumlah,
                                        jumlah_default,
                                        id_mata_uang,
                                        kurs,
                                        tgl_kurs,
                                        created_at,
                                        updated_at 
                                      ) 
                                      SELECT
                                      $tahun AS tahun,
                                      struktur_organ.ID AS id_struktur_organ,
                                      faktor_penghasilan.ID AS id_faktor_penghasilan,
                                      0 AS jumlah,
                                      0 AS jumlah_default,
                                      remunerasi.id_mata_uang AS id_mata_uang,
                                      remunerasi.kurs AS kurs,
                                      remunerasi.tgl_kurs AS tgl_kurs,
                                      now( ) AS created_at,
                                      now( ) AS updated_at 
                                      FROM
                                        struktur_organ,
                                        faktor_penghasilan,
                                        remunerasi
                                      WHERE
                                        struktur_organ.id_perusahaan = $id_perusahaan
                                        AND struktur_organ.id_jenis_jabatan IS NOT NULL
                                        AND struktur_organ.aktif = TRUE
                                        AND faktor_penghasilan.id IN (2,3,4)
                                        AND remunerasi.id = $id;";

                                    $relasi  = DB::unprepared(DB::raw($str_query));
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
                                  $remunerasi = Remunerasi::find((int)$request->input('id'));
                                  $remunerasi->update((array)$param);

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
            $mata_uangs = MataUang::get();
            $remunerasi = Remunerasi::find((int)$request->input('id'));

                return view($this->__route.'.edit',[
                    'actionform' => 'update',
                    'remunerasi' => $remunerasi,
                    'mata_uangs' => $mata_uangs

                ]);
        }catch(Exception $e){}

    }

    public function update(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $validator = $this->validateUpdate($request);

        if (!$validator->fails()) {
            $param['jumlah_default'] = preg_replace('/[\p{P} ]+/', '', $request->input('jumlah_default'));
            $param['id_mata_uang'] = $request->input('id_mata_uang');
            // untuk mata uang IDR
            if($param['id_mata_uang'] == 1)
              $param['kurs'] = 1;
            else{
              $param['kurs'] = preg_replace('/[\p{P} ]+/', '', $request->input('kurs'));
              $param['tgl_kurs'] = $request->input('tgl_kurs');
            }

             DB::beginTransaction();
             try{
                $remunerasi = Remunerasi::find((int)$request->input('id'));
                $remunerasi->update((array)$param);

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
            $data = Remunerasi::find((int)$request->input('id'));
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

    protected function validateform($request)
    {
        $id_perusahaan = $request->input('id_perusahaan');
        $tahun = $request->input('tahun');
        $id_faktor_penghasilan = 1;

        $required['id_perusahaan'] = ['required',
            // Rule::unique('remunerasi')->where(function ($query) use($id_perusahaan,$tahun,$id_faktor_penghasilan) {
            //     return $query->leftJoin('struktur_organ', 'struktur_organ.id', 'remunerasi.id_struktur_organ')
            //     ->where('struktur_organ.id_perusahaan', $id_perusahaan)
            //     ->where('remunerasi.tahun', $tahun)
            //     ->where('struktur_organ.id_jenis_jabatan', 1)
            //     ->where('remunerasi.id_faktor_penghasilan', $id_faktor_penghasilan);
            // }),
            Rule::exists('struktur_organ')->where(function ($query) use($id_perusahaan){
                $query->where('id_perusahaan', $id_perusahaan)
                      ->where('id_jenis_jabatan', 1)
                      ->where('aktif', true);
            }),
            function ($attribute, $value, $fail) use($id_perusahaan,$tahun,$id_faktor_penghasilan) {
              $remunerasi = Remunerasi::leftJoin('struktur_organ', 'struktur_organ.id', 'remunerasi.id_struktur_organ')
                    ->where('struktur_organ.id_perusahaan', $id_perusahaan)
                    ->where('remunerasi.tahun', $tahun)
                    ->where('struktur_organ.id_jenis_jabatan', 1)
                    ->where('remunerasi.id_faktor_penghasilan', $id_faktor_penghasilan)
                    ->get();

                if (count($remunerasi) > 0) {
                    $fail('Data BUMN, Jenis Jabatan, Faktor Pengahasilan untuk tahun tersebut sudah diinput. Silahkan update data.');
                }
            },
          ];
        $required['tahun'] = 'required';
        // $required['id_faktor_penghasilan'] = 'required';
        $required['jumlah'] = 'required';
        $required['id_mata_uang'] = 'required';
        $required['kurs'] = Rule::requiredIf($request->input('id_mata_uang') != 1);
        $required['tgl_kurs'] = Rule::requiredIf($request->input('id_mata_uang') != 1);

        $message['id_perusahaan.required'] = 'BUMN wajib diinput';
        $message['tahun.required'] = 'Tahun wajib diinput';
        // $message['id_faktor_penghasilan.required'] = 'Faktor Penghasilan wajib diinput';
        $message['jumlah.required'] = 'Jumlah wajib diinput';
        $message['id_mata_uang.required'] = 'Mata Uang wajib diinput';
        $message['kurs.required'] = 'Kurs wajib diinput untuk mata uang selain IDR';
        $message['tgl_kurs.required'] = 'Tgl Kurs wajib diinput untuk mata uang selain IDR';
        // $message['id_perusahaan.unique'] = 'Data BUMN, Jenis Jabatan, Faktor Pengahasilan untuk tahun tersebut sudah diinput. Silahkan update data.';
        $message['id_perusahaan.exists'] = 'Data Nomenklatur Direktur Utama aktif Tidak ditemukan. Silahkan isi di menu Organ Pengurus BUMN > Komposisi Dirkomwas ';

        return Validator::make($request->all(), $required, $message);       
    }

    protected function validateUpdate($request)
    {
        $required['jumlah_default'] = 'required';
        $required['id_mata_uang'] = 'required';
        $required['kurs'] = Rule::requiredIf($request->input('id_mata_uang') != 1);
        $required['tgl_kurs'] = Rule::requiredIf($request->input('id_mata_uang') != 1);

        $message['jumlah_default.required'] = 'Jumlah wajib diinput';
        $message['id_mata_uang.required'] = 'Mata Uang wajib diinput';
        $message['kurs.required'] = 'Kurs wajib diinput untuk mata uang selain IDR';
        $message['tgl_kurs.required'] = 'Tgl Kurs wajib diinput untuk mata uang selain IDR';

        return Validator::make($request->all(), $required, $message);       
    }
}