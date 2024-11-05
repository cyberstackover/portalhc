<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CVHelper;
use App\CVPajak;
use App\Talenta;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PajakController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.pajak';;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV Pajak');
      $talenta = Talenta::find($id);
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'active' => "pajak",
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
                  'url' => route('cv.pajak.index', ['id_talenta' => $id]),
                  'menu' => 'SPT Tahunan'
              ]               
          ]
      ]);

    }

    public function datatable(Request $request, $id)
    {
        $data = CVPajak::select('*')->where('id_talenta', $id)->orderBy("tahun", 'asc');

        try{
            return datatables()->of($data)
            ->editColumn('file_name', function ($row){
                $file = '';
                //$file .= '<a class="tooltips" title="Download ST Upload" href="'.\URL::to('uploads/cv/spt_tahunan/'.$row->file_name).'" download="'.$row->file_name.'" target="_blank"><span class="btn-icon-only btn btn-sm yellow-gold sbold"><i class="flaticon2-document"></i>File SPT Tahun '. $row->tahun .'</a><br/>';

                $file .= '<a style="cursor:pointer" class="cls-urlpendukung" data-url="'.\URL::to('uploads/cv/spt_tahunan/'.$row->file_name).'" data-keterangan="'.$row->tahun.'" target="_blank"><i class="flaticon2-file" ></i> File SPT Tahun '. $row->tahun .'</a>';

                return $file;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                //$button .= '<button type="button" class="btn btn-icon btn-sm  btn-light-primary mr-2 cls-button-edit first_table-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data penghargaan"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn  btn-light-danger mr-2 btn-icon cls-button-delete btn-sm first_table-delete" data-id="'.$id.'" data-periode="'.$row->tahun.'" data-toggle="tooltip" data-original-title="Hapus data penghargaan"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action', 'file_name', 'user'])
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
          $dataPajak = CVPajak::where("id_talenta", $id)->Where("tahun", $request->tahun)->count();
          if($dataPajak > 0){
            $result = [
                'flag'  => 'warning',
                'msg' => "Data Pajak Pada Tahun ".$request->tahun." Sudah Tersedia",
                'title' => 'Gagal proses data'
            ];
          }else{
            switch ($request->input('actionform')) {
              case 'insert': DB::beginTransaction();
                             try{

                                if($request->file_name){
                                  $firstSubName = $id.'-'.$request->tahun.'-';
                                  $dataUpload = $this->uploadFile($request->file('file_name'), $firstSubName);
                                  $param['file_name']  = $dataUpload->fileRaw;
                                }

                                $data = CVPajak::create($param);
                                $param_talenta['fill_pajak'] = CVHelper::pajakFillCheck($id);
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
        // $required['tingkat'] = 'required';
        // $required['pemberi_penghargaan'] = 'required';
        $required['tahun'] = 'required';

        $message['file_name.required'] = 'File wajib Diisi';
        // $message['tingkat.required'] = 'Tingkat wajib Diisi';
        // $message['pemberi_penghargaan.required'] = 'Pemberi Penghargaan wajib Diisi';
        $message['tahun.required'] = 'Tahun wajib Diisi';

        return Validator::make($request->all(), $required, $message);       
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = CVPajak::find((int)$request->input('id'));
            File::delete('uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR .'spt_tahunan'.DIRECTORY_SEPARATOR . $data->file_name);
            $id = $data->id_talenta;
            $data->delete();
            $param_talenta['fill_pajak'] = CVHelper::pajakFillCheck($id);
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

    protected function uploadFile(UploadedFile $file, $firstSubName)
    {
        $fileName = $file->getClientOriginalName();
        $ext = substr($file->getClientOriginalName(),strripos($file->getClientOriginalName(),'.'));
        $fileRaw  = $firstSubName.$fileName;
        $filePath = 'uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR.'spt_tahunan'.DIRECTORY_SEPARATOR.$fileRaw;
        $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR.'spt_tahunan'.DIRECTORY_SEPARATOR;
        $fileUpload      = $file->move($destinationPath, $fileRaw);
        $data = (object) array('fileName' => $fileName, 'fileRaw' => $fileRaw, 'filePath' => $filePath);
        return $data;
    }
} 