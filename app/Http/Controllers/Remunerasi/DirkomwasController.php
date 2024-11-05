<?php

namespace App\Http\Controllers\Remunerasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Remunerasi;
use App\JenisJabatan;
use App\GrupJabatan;
use App\Perusahaan;
use App\FaktorPenghasilan;
use App\MataUang;
use App\StrukturOrgan;
use DB;

class DirkomwasController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'remunerasi.dirkomwas';
         // $this->middleware('permission:dirkomwas-list');
         // $this->middleware('permission:dirkomwas-create');
         // $this->middleware('permission:dirkomwas-edit');
         // $this->middleware('permission:dirkomwas-delete');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Remunerasi Dirkomwas');
        $perusahaans = Perusahaan::orderBy('id')->get();
        return view($this->__route.'.index',[
            'pagetitle' => 'Remunerasi Dirkomwas',
            'perusahaans' => $perusahaans,
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('remunerasi.dirkomwas.index'),
                    'menu' => 'Dirkomwas'
                ]               
            ]
        ]);

    }

    public function detail(Request $request)
    {
      activity()->log('Menu Remunerasi Dirkomwas');
        $tahun = $request->tahun;
        $perusahaan = $request->perusahaan;
        $str_query = "SELECT
          jenis_jabatan.id,
          struktur_organ.nomenklatur_jabatan,
          jenis_jabatan.kode,
          jenis_jabatan.parent_id,
          jenis_jabatan.prosentase_gaji,
          pengali.nama as pengali,
          SUM (
            CASE
                remunerasi.id_faktor_penghasilan 
                WHEN 1 THEN
                remunerasi.jumlah ELSE 0 
              END 
              ) AS gaji_pokok,
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
                  WHEN 1 THEN
                  mata_uang.kode ELSE'0' 
                END 
                ) AS mata_uang_gaji_pokok,
                SUM (
                CASE
                    remunerasi.id_faktor_penghasilan 
                    WHEN 2 THEN
                    remunerasi.jumlah ELSE 0 
                  END 
                  ) AS thp,
                  MAX (
              CASE
                  remunerasi.id_faktor_penghasilan 
                  WHEN 2 THEN
                  remunerasi.ID ELSE 0 
                END 
                ) AS thp_remun_id,
                  MAX (
                  CASE
                      remunerasi.id_faktor_penghasilan 
                      WHEN 2 THEN
                      mata_uang.kode ELSE'0' 
                    END 
                    ) AS mata_uang_thp,
                    SUM (
                    CASE
                        remunerasi.id_faktor_penghasilan 
                        WHEN 3 THEN
                        remunerasi.jumlah ELSE 0 
                      END 
                      ) AS tantiem,
                      MAX (
                    CASE
                        remunerasi.id_faktor_penghasilan 
                        WHEN 3 THEN
                        remunerasi.ID ELSE 0 
                      END 
                      ) AS tantiem_remun_id,
                      MAX (
                      CASE
                          remunerasi.id_faktor_penghasilan 
                          WHEN 3 THEN
                          mata_uang.kode ELSE'0' 
                        END 
                        ) AS mata_uang_tantiem 
                      FROM
                        remunerasi
                        LEFT JOIN struktur_organ ON struktur_organ.ID = remunerasi.id_struktur_organ
                        LEFT JOIN jenis_jabatan ON struktur_organ.id_jenis_jabatan = jenis_jabatan.
                        ID LEFT JOIN perusahaan ON perusahaan.ID = struktur_organ.id_perusahaan
                        LEFT JOIN faktor_penghasilan ON faktor_penghasilan.ID = remunerasi.id_faktor_penghasilan
                        LEFT JOIN mata_uang ON mata_uang.ID = remunerasi.id_mata_uang 
                        LEFT JOIN jenis_jabatan as pengali ON pengali.id = jenis_jabatan.id_jns_jab_pengali
                      WHERE
                        remunerasi.tahun = $tahun 
                        AND perusahaan.ID = $perusahaan 
                        AND struktur_organ.aktif = TRUE
                    GROUP BY
            struktur_organ.ID, jenis_jabatan.kode, jenis_jabatan.prosentase_gaji, jenis_jabatan.parent_id, pengali.nama, jenis_jabatan.id
            ORDER BY jenis_jabatan.kode";

        $remunerasis  = DB::select(DB::raw($str_query));
        return view($this->__route.'.detail',[
            'remunerasis' => $remunerasis,
            'pagetitle' => 'Remunerasi Dirkomwas',
            'breadcrumb' => [
                [
                    'url' => '/',
                    'menu' => 'Homes'
                ],
                [
                    'url' => route('remunerasi.dirkomwas.index'),
                    'menu' => 'Dirkomwas'
                ]               
            ]
        ]);
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
        $faktor_penghasilans = FaktorPenghasilan::whereIn('id',[2,3])->get();
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
            $param['id_faktor_penghasilan'] = $request->input('id_faktor_penghasilan');
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
                                  // $remunerasi = Remunerasi::create((array)$param);

                                  // $id = $remunerasi->id;
                                  // $id_perusahaan = $request->input('id_perusahaan');
                                  // $tahun = $param['tahun'];
                                  // $id_faktor_penghasilan = $param['id_faktor_penghasilan'];

                                  // if($id_faktor_penghasilan == 1){
                                  //   $str_query = "DELETE FROM remunerasi WHERE tahun = $tahun AND id_faktor_penghasilan = $id_faktor_penghasilan AND id_struktur_organ IN(
                                  //     SELECT id 
                                  //     FROM struktur_organ 
                                  //     WHERE id_perusahaan = $id_perusahaan AND id_jenis_jabatan != 1);
                                  //     INSERT INTO remunerasi (tahun, id_struktur_organ, id_faktor_penghasilan, jumlah, jumlah_default, id_mata_uang, kurs, tgl_kurs, created_at, updated_at)
                                  //     SELECT
                                  //       remunerasi.tahun,
                                  //       struktur_organ.ID as id_struktur_organ,
                                  //       remunerasi.id_faktor_penghasilan,
                                  //       jenis_jabatan.prosentase_gaji * remunerasi.jumlah_default as jumlah,
                                  //       jenis_jabatan.prosentase_gaji * remunerasi.jumlah_default as jumlah_default,
                                  //       remunerasi.id_mata_uang,
                                  //       remunerasi.kurs,
                                  //       remunerasi.tgl_kurs,
                                  //       remunerasi.created_at,
                                  //       remunerasi.updated_at
                                  //     FROM
                                  //       struktur_organ,
                                  //       remunerasi,
                                  //       jenis_jabatan
                                  //     WHERE
                                  //       jenis_jabatan.ID != 1
                                  //       AND 
                                  //       remunerasi.id = $id
                                  //       AND 
                                  //       struktur_organ.id_perusahaan = $id_perusahaan
                                  //       AND
                                  //       struktur_organ.aktif = TRUE
                                  //       AND
                                  //       jenis_jabatan.ID = struktur_organ.id_jenis_jabatan
                                  //     ORDER BY
                                  //     jenis_jabatan.id;";

                                  //   $relasi  = DB::unprepared(DB::raw($str_query));
                                  // }
                                  

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
            $param['jumlah'] = preg_replace('/[\p{P} ]+/', '', $request->input('jumlah'));
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
            $data = JenisJabatan::find((int)$request->input('id'));
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
        $required['id_grup_jabatan'] = 'required';
        $required['nama'] = 'required';

        $message['nama.required'] = 'Nama Dirkomwas wajib diinput';
        $message['id_grup_jabatan.required'] = 'Jenis Grup Jabatan wajib diinput';

        return Validator::make($request->all(), $required, $message);       
    }

    protected function validateUpdate($request)
    {
        $required['jumlah'] = 'required';
        $required['id_mata_uang'] = 'required';
        $required['kurs'] = Rule::requiredIf($request->input('id_mata_uang') != 1);
        $required['tgl_kurs'] = Rule::requiredIf($request->input('id_mata_uang') != 1);

        $message['jumlah.required'] = 'Jumlah wajib diinput';
        $message['id_mata_uang.required'] = 'Mata Uang wajib diinput';
        $message['kurs.required'] = 'Kurs wajib diinput untuk mata uang selain IDR';
        $message['tgl_kurs.required'] = 'Tgl Kurs wajib diinput untuk mata uang selain IDR';

        return Validator::make($request->all(), $required, $message);       
    }
}