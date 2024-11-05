<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Talenta;
use App\Agama;
use App\Kota;
use App\StatusKawin;
use App\Provinsi;
use App\Perusahaan;
use App\SocialMedia;
use App\TransactionTalentaSocialMedia;
use App\Suku;
use App\GolonganDarah;
use App\AsalInstansiBaru;
use App\JenisAsalInstansiBaru;
use App\Toastr;
use App\Helpers\CVHelper;
use DB;
use File;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\KategoriDataTalent;
use App\KategoriJabatanTalent;
use App\KategoriNonTalent;
use App\User;

class BiodataController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.biodata';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      //test
      activity()->log('Menu CV Biodata');

      $id_users = \Auth::user()->id;
      $id_users_bumn = \Auth::user()->id_bumn;
      $users = User::where('id', $id_users)->first();
      $kategori_user = $users->kategori_user_id;

      $talenta = Talenta::find($id);
      if($talenta->id_kota){
        $kota = Kota::Where("provinsi_id", $talenta->refKota->provinsi_id)->get(); 
      }else{
        $kota = Kota::get();
      }

      if($kategori_user == 1){
        $kategori_jabatan_talent = KategoriJabatanTalent::all();
        $kategori_non_talent = KategoriNonTalent::all();
      } else {
        $kategori_jabatan_talent = KategoriJabatanTalent::where('hak_akses',true)->get();
        $kategori_non_talent = KategoriNonTalent::where('hak_akses',true)->get();
      }

      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'active' => "biodata",
          'agama' => Agama::get(),
          'status_kawin' => StatusKawin::get(),
          'social_media' => SocialMedia::get(),
          'kota' => $kota,
          'provinsi' => Provinsi::where('is_luar_negeri', false)->get(),
          'negara' => Provinsi::where('is_luar_negeri', true)->get(),
          'trans_social_media' => TransactionTalentaSocialMedia::where("id_talenta", $id)->get(),
          'golongan_darah' => GolonganDarah::get(),
          'suku' => Suku::get(),
          'jenisasalinstansis' => JenisAsalInstansiBaru::whereIn('id',[2,3,4,7,8,9,11,12,13,15,16])->get(),
          'asalinstansis' => AsalInstansiBaru::all(),
          'kategoridatas' => KategoriDataTalent::all(),
          'kategorijabatans' => $kategori_jabatan_talent,
          'kategorinons' => $kategori_non_talent,
          'kategori_user' => $kategori_user,
          'perusahaans' => Perusahaan::where('level', 0)->get(),
          'breadcrumb' => [
              [
                  'url' => '/',
                  'menu' => 'Homes'
              ],
              [
                  'url' => route('cv.board.index'),
                  'menu' => 'CV'
              ] ,
              [
                  'url' => route('cv.biodata.index', ['id_talenta' => $id]),
                  'menu' => 'Biodata'
              ]               
          ]
      ]);
    }

    public function update(Request $request, $id)
    {

      activity()->log('Menu CV Biodata - update');
      $validation = [
          'file_name' => 'mimes:jpeg,png',
          'file_ktp' => 'mimes:jpeg,png',
      ];
      $validator = Validator::make($request->all(), $validation);
      if (!$validator->fails()) {
          $param = $request->except(['_token', '_method', 'social_media_select', 'social_media_text', 'file_name', 'negara']);
          if($request->tanggal_lahir){
            $param['tanggal_lahir'] = Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d'); 
          }
          if($request->id_kota){
            $kota = Kota::where('id', $request->id_kota)->first();
            $param['tempat_lahir'] = $kota->nama;
            $param['id_kota'] = $request->id_kota; 
          }

          if($request->id_suku){
            $suku = Suku::where('id', $request->id_suku)->first();
            $param['suku'] = $suku->nama; 
          }

          if($request->id_golongan_darah){
            $gol_darah = GolonganDarah::where('id', $request->id_golongan_darah)->first();
            $param['gol_darah'] = $gol_darah->nama;
            $param['id_gol_darah'] = $request->id_golongan_darah; 
          }

          if($request->id_status_kawin){
            //$gol_darah = GolonganDarah::where('id', $request->id_golongan_darah)->first();
            $param['id_status_kawin'] = $request->id_status_kawin; 
          }

          if($request->id_agama){
            //$gol_darah = GolonganDarah::where('id', $request->id_golongan_darah)->first();
            $param['id_agama'] = $request->id_agama; 
          }

          if($request->id_provinsi){
            //$gol_darah = GolonganDarah::where('id', $request->id_golongan_darah)->first();
            $param['id_provinsi'] = $request->id_provinsi; 
          }

          if($request->id_jenis_asal_instansi){
            //$gol_darah = GolonganDarah::where('id', $request->id_golongan_darah)->first();
            $param['id_jenis_asal_instansi'] = $request->id_jenis_asal_instansi; 
          }

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

          if($request->jabatan_asal_instansi){
            //$gol_darah = GolonganDarah::where('id', $request->id_golongan_darah)->first();
            $param['jabatan_asal_instansi'] =  $request->jabatan_asal_instansi; 
          }

          if($request->kewarganegaraan){
            //$gol_darah = GolonganDarah::where('id', $request->id_golongan_darah)->first();
            $param['kewarganegaraan'] =  $request->kewarganegaraan; 
          }
          
          if($request->id_kategori_data_talent){
            //$gol_darah = GolonganDarah::where('id', $request->id_golongan_darah)->first();
            $param['id_kategori_data_talent'] = (int)$request->id_kategori_data_talent; 
          }

          if($request->id_kategori_jabatan_talent){
            //$gol_darah = GolonganDarah::where('id', $request->id_golongan_darah)->first();
            $param['id_kategori_jabatan_talent'] = (int)$request->id_kategori_jabatan_talent; 
          }

          if($request->id_kategori_non_talent){
            //$gol_darah = GolonganDarah::where('id', $request->id_golongan_darah)->first();
            $param['id_kategori_non_talent'] = (int)$request->id_kategori_non_talent; 
          }

          //is existing
          if($request->is_existing){
            //dd($request->is_existing);
            //$gol_darah = GolonganDarah::where('id', $request->id_golongan_darah)->first();
            $param['is_existing'] = $request->is_existing == 'on'? 1 : 0;
          } else {
            $param['is_existing'] = 0;
          }

          //is talenta
          if($request->is_talenta){
            //$gol_darah = GolonganDarah::where('id', $request->id_golongan_darah)->first();
            $param['is_talenta'] = $request->is_talenta == 'on'? 1 : 0;
            $param['talenta_register'] = 1;
          } else {
            $param['is_talenta'] = 0;
          }

          //Talenta::where('nik', $request->input('nik'))->count() > 0
          
                    
          DB::beginTransaction();
          try{

            // #update_sosial_media
            // if(count($request->social_media_select) == count($request->social_media_text)){
            //   #delete transaction old
            //   TransactionTalentaSocialMedia::where("id_talenta", $id)->delete();
            //   #create new transaction
            //   $create = [''];
            //   $social_media_select = $request->social_media_select;
            //   $social_media_text = $request->social_media_text;
            //   for($i = 0; $i < count($social_media_select); $i++){
            //     $create['id_talenta'] = $id;
            //     $create['id_social_media'] = $social_media_select[$i];
            //     $create['name_social_media'] = $social_media_text[$i];
            //     if($create['name_social_media'] != null && $create['id_social_media'] != null){
            //       TransactionTalentaSocialMedia::create($create); 
            //     }
            //   }
            // }            

            #update foto pegawai
            if($request->file_name){
              $talent = Talenta::find($id);
              $path = asset('img/foto_talenta');
              File::delete('img'.DIRECTORY_SEPARATOR.'foto_talenta'.DIRECTORY_SEPARATOR . $talent->foto);
              $dataUpload = $this->uploadFile($request->file('file_name'), $talent->id);
              $param['foto']  = $dataUpload->fileRaw;
            }

            #update foto ktp
            if($request->file_ktp){
              $talent = Talenta::find($id);
              $path = asset('img/ktp');
              File::delete('img'.DIRECTORY_SEPARATOR.'ktp'.DIRECTORY_SEPARATOR . $talent->file_ktp);
              $dataUpload = $this->uploadFileKtp($request->file('file_ktp'), $talent->id);
              $param['file_ktp']  = $dataUpload->fileRaw;
            }

            $param['fill_biodata'] = CVHelper::biodataFillCheck($id);
            $param['persentase'] = CVHelper::fillPercentage($id);
            $status = Talenta::find($id)->update($param);
            DB::commit();
          }catch(\Exception $e){
            DB::rollback();
            $status = false;
          }

          if ($status) {              
            $toastType = 'success';   
            $message = 'Berhasil Mengubah Biodata';
          } else {           
            $toastType = 'error';      
            $message = 'Gagal Mengubah Biodata';
          }
      } else {
          $toastType = 'warning';   
          $message = 'Gagal Validasi Biodata';
      }

      
      return redirect(route('cv.biodata.index', ['id_talenta' => $id]))->with($toastType, $message);
    }

    protected function uploadFile(UploadedFile $file, $id)
    {
        // $fileName = $file->getClientOriginalName();
        $ext = substr($file->getClientOriginalName(),strripos($file->getClientOriginalName(),'.'));
        $fileRaw  =$fileName = $id.$ext;
        $filePath = 'img'.DIRECTORY_SEPARATOR.'foto_talenta'.DIRECTORY_SEPARATOR.$fileRaw;
        $destinationPath = public_path().DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'foto_talenta'.DIRECTORY_SEPARATOR;
        $fileUpload      = $file->move($destinationPath, $fileRaw);
        $data = (object) array('fileName' => $fileName, 'fileRaw' => $fileRaw, 'filePath' => $filePath);
        return $data;
    }

    protected function uploadFileKtp(UploadedFile $file, $id)
    {
        // $fileName = $file->getClientOriginalName();
        $ext = substr($file->getClientOriginalName(),strripos($file->getClientOriginalName(),'.'));
        $fileRaw  =$fileName = $id.$ext;
        $filePath = 'img'.DIRECTORY_SEPARATOR.'ktp'.DIRECTORY_SEPARATOR.$fileRaw;
        $destinationPath = public_path().DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'ktp'.DIRECTORY_SEPARATOR;
        $fileUpload      = $file->move($destinationPath, $fileRaw);
        $data = (object) array('fileName' => $fileName, 'fileRaw' => $fileRaw, 'filePath' => $filePath);
        return $data;
    }

    public function getasalinstansi(Request $request)
    {
        $id_jenis_asal_instansi = (int)$request->id_jenis_asal_instansi;

        if($id_jenis_asal_instansi == 7 || $id_jenis_asal_instansi == 8 || $id_jenis_asal_instansi == 15 || $id_jenis_asal_instansi == 16){
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