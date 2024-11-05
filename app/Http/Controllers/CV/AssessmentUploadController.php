<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CVHelper;
use App\AssessmentUpload;
use App\Talenta;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AssessmentUploadController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.assessment_upload';;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV Hasil Assessment');
      $talenta = Talenta::find($id);
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'active' => "assessment_upload",
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
                  'url' => route('cv.assessment_upload.index', ['id_talenta' => $id]),
                  'menu' => 'Hasil Assessment'
              ]               
          ]
      ]);

    }

    public function datatable(Request $request, $id)
    {
        $data = AssessmentUpload::select('*')->where('id_talenta', $id)->orderBy("tanggal", 'asc');

        try{
            return datatables()->of($data)
            ->editColumn('file_name', function ($row){
                $file = '';

                $file .= '<a style="cursor:pointer" class="cls-urlpendukung" data-url="'.\URL::to('uploads/cv/assessment_upload/'.$row->file_name).'" data-keterangan="'.$row->tahun.'" ><i class="flaticon2-file" target="_blank"></i> '. $row->file_name .'</a>';

                return $file;
            })
            ->editColumn('tanggal', function ($row){
                return CVHelper::tglFormat($row->tanggal, 2);
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                //$button .= '<button type="button" class="btn btn-icon btn-sm  btn-light-primary mr-2 cls-button-edit first_table-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data penghargaan"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn  btn-light-danger mr-2 btn-icon cls-button-delete btn-sm first_table-delete" data-id="'.$id.'" data-tanggal="'.CVHelper::tglFormat($row->tanggal, 2).'" data-toggle="tooltip" data-original-title="Hapus data Assessment"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action', 'file_name', 'hasil'])
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
          return view($this->__route.'.form',[
                  'actionform' => 'insert',
                  'id_talenta' => $id
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
          $param = $request->except(['id', '_token', '_method', 'actionform', 'file_name']);
          $param['id_talenta'] = $id;
          $param['user'] = \Auth::user()->name;
          $param['tanggal'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tanggal)->format('Y-m-d');
            switch ($request->input('actionform')) {
              case 'insert': DB::beginTransaction();
                             try{

                                if($request->file_name){
                                  $firstSubName = 'Assessment-'.$id.'-';
                                  $dataUpload = $this->uploadFile($request->file('file_name'), $firstSubName);
                                  $param['file_name']  = $dataUpload->fileRaw;
                                }

                                $data = AssessmentUpload::create($param);

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
        $required['file_name'] = 'required';
        $required['tanggal'] = 'required';

        $message['file_name.required'] = 'File wajib Diisi';
        $message['tanggal.required'] = 'Tanggal wajib Diisi';

        return Validator::make($request->all(), $required, $message);       
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = AssessmentUpload::find((int)$request->input('id'));
            File::delete('uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR .'assessment_upload'.DIRECTORY_SEPARATOR . $data->file_name);
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

    protected function uploadFile(UploadedFile $file, $firstSubName)
    {
        $fileName = $file->getClientOriginalName();
        $ext = substr($file->getClientOriginalName(),strripos($file->getClientOriginalName(),'.'));
        $fileRaw  = $firstSubName.$fileName;
        $filePath = 'uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR.'assessment_upload'.DIRECTORY_SEPARATOR.$fileRaw;
        $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR.'assessment_upload'.DIRECTORY_SEPARATOR;
        $fileUpload      = $file->move($destinationPath, $fileRaw);
        $data = (object) array('fileName' => $fileName, 'fileRaw' => $fileRaw, 'filePath' => $filePath);
        return $data;
    }
} 