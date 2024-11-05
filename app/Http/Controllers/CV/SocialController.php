<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CVHelper;
use App\RiwayatKesehatan;
use App\SkalaKesehatan;
use App\SocialMedia;
use App\TransactionTalentaSocialMedia;
use App\Talenta;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SocialController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.social';;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV Sosial Media');
      $talenta = Talenta::find($id);
      $jenissocials = SocialMedia::get();
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'jenissocials' => $jenissocials,
          'active' => "social",
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
                  'url' => route('cv.social.index', ['id_talenta' => $id]),
                  'menu' => 'Sosial Media'
              ]               
          ]
      ]);

    }

    public function datatable(Request $request, $id)
    {
        $data = TransactionTalentaSocialMedia::select('*')->where('id_talenta', $id)->orderBy("id_social_media", 'asc');

        try{
            return datatables()->of($data)
            /*->editColumn('file_name', function ($row){
                $file = '';
                $file .= '<a class="tooltips" title="Download LHKPN" href="'.\URL::to('uploads/cv/lhkpn/'.$row->file_name).'" download="'.$row->file_name.'" ><span class="btn-icon-only btn btn-sm yellow-gold sbold"><i class="flaticon2-document"></i>File LHKPN Tahun '. $row->tahun .'</a><br/>';

                return $file;
            })*/
            ->addColumn('jenis_social', function ($row){
                return $row->socialMedia->nama;
            })
            ->addColumn('akun_social', function ($row){
                return $row->name_social_media;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                //$button .= '<button type="button" class="btn btn-icon btn-sm  btn-light-primary mr-2 cls-button-edit first_table-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data penghargaan"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn  btn-light-danger mr-2 btn-icon cls-button-delete btn-sm first_table-delete" data-id="'.$id.'" data-periode="'.$row->tahun.'" data-toggle="tooltip" data-original-title="Hapus data sosial media"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['jenis_social','akun_social','action'])
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
          $jenissocials = SocialMedia::get();
          return view($this->__route.'.form',[
                  'actionform' => 'insert',
                  'id_talenta' => $id,
                  'jenissocials' => $jenissocials
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
          $param = $request->except(['id', '_token', '_method', 'actionform']);
          $param['id_talenta'] = $id;
          //$dataSocial = TransactionTalentaSocialMedia::where("id_talenta", $id)->Where("tahun_kesehatan", $request->tahun)->count();
          switch ($request->input('actionform')) {
              case 'insert': DB::beginTransaction();
                             try{

                                /*if($request->file_name){
                                  $firstSubName = $id.'-'.$request->tahun.'-';
                                  $dataUpload = $this->uploadFile($request->file('file_name'), $firstSubName);
                                  $param['file_name']  = $dataUpload->fileRaw;
                                }*/

                                $data = TransactionTalentaSocialMedia::create($param);
                                $param_talenta['fill_social'] = CVHelper::socialFillCheck($id);
                                $param_talenta['persentase'] = CVHelper::fillPercentage($id);
                                $status = Talenta::find($id)->update($param_talenta);

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
        //$required['file_name'] = 'required';
        // $required['tingkat'] = 'required';
        // $required['pemberi_penghargaan'] = 'required';
        $required['name_social_media'] = 'required';

        //$message['file_name.required'] = 'File wajib Diisi';
        // $message['tingkat.required'] = 'Tingkat wajib Diisi';
        // $message['pemberi_penghargaan.required'] = 'Pemberi Penghargaan wajib Diisi';
        $message['name_social_media.required'] = 'Akun Sosial wajib Diisi';

        return Validator::make($request->all(), $required, $message);       
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = TransactionTalentaSocialMedia::find((int)$request->input('id'));
            //File::delete('uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR .'spt_tahunan'.DIRECTORY_SEPARATOR . $data->file_name);
            $id = $data->id_talenta;
            $data->delete();
            $param_talenta['fill_social'] = CVHelper::socialFillCheck($id);
            $param_talenta['persentase'] = CVHelper::fillPercentage($id);
            $status = Talenta::find($id)->update($param_talenta);

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

    /*protected function uploadFile(UploadedFile $file, $firstSubName)
    {
        $fileName = $file->getClientOriginalName();
        $ext = substr($file->getClientOriginalName(),strripos($file->getClientOriginalName(),'.'));
        $fileRaw  = $firstSubName.$fileName;
        $filePath = 'uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR.'lhkpn'.DIRECTORY_SEPARATOR.$fileRaw;
        $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR.'lhkpn'.DIRECTORY_SEPARATOR;
        $fileUpload      = $file->move($destinationPath, $fileRaw);
        $data = (object) array('fileName' => $fileName, 'fileRaw' => $fileRaw, 'filePath' => $filePath);
        return $data;
    }*/
} 