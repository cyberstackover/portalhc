<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CVHelper;
use App\RiwayatLhkpn;
use App\Talenta;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LhkpnController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         ini_set( 'max_execution_time', 0);
         $this->__route = 'cv.lhkpn';;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV LHKPN');
      $talenta = Talenta::find($id);
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'active' => "lhkpn",
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
                  'url' => route('cv.lhkpn.index', ['id_talenta' => $id]),
                  'menu' => 'LHKPN'
              ]               
          ]
      ]);

    }

    public function datatable(Request $request, $id)
    {
        $data = RiwayatLhkpn::select('*')->where('id_talenta', $id)->orderBy("tahun", 'asc');

        try{
            return datatables()->of($data)
            ->editColumn('file_name', function ($row){
                $file = '';
                /*$file .= '<a class="tooltips" title="Download LHKPN" href="'.\URL::to('uploads/cv/lhkpn/'.$row->file_name).'" download="'.$row->file_name.'" ><span class="btn-icon-only btn btn-sm yellow-gold sbold"><i class="flaticon2-document"></i>File LHKPN Tahun '. $row->tahun .'</a><br/>';*/

                $file .= '<a style="cursor:pointer" class="cls-urlpendukung" data-url="'.\URL::to('uploads/cv/lhkpn/'.$row->file_name).'" data-keterangan="'.$row->tahun.'" target="_blank"><i class="flaticon2-file" ></i> File LHKPN Tahun '. $row->tahun .'</a>';

                return $file;
            })
            ->editColumn('jml_kekayaan_rp', function($row) {

                return number_format($row->jml_kekayaan_rp,0,',',',');

            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                //$button .= '<button type="button" class="btn btn-icon btn-sm  btn-light-primary mr-2 cls-button-edit first_table-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data penghargaan"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn  btn-light-danger mr-2 btn-icon cls-button-delete btn-sm first_table-delete" data-id="'.$id.'" data-periode="'.$row->tahun.'" data-toggle="tooltip" data-original-title="Hapus data lhkpn"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action', 'file_name', 'tgl_pelaporan', 'user'])
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
          $param['tgl_pelaporan'] = \Carbon\Carbon::createFromFormat('d/m/Y', $request->tgl_pelaporan)->format('Y-m-d');
          $param['user'] = \Auth::user()->name;
          
          switch ($request->input('actionform')) {
              case 'insert': DB::beginTransaction();
                             try{

                                if($request->file_name){
                                  $firstSubName = $id.'-'.$request->tahun.'-';
                                  $dataUpload = $this->uploadFile($request->file('file_name'), $firstSubName);
                                  $param['file_name']  = $dataUpload->fileRaw;
                                  $param['jml_kekayaan_rp'] = str_replace(',', '', $request->input('jml_kekayaan_rp'));
                                }

                                $data = RiwayatLhkpn::create($param);
                                $param_talenta['fill_lhkpn'] = CVHelper::lhkpnFillCheck($id);
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
        $required['file_name'] = 'required|mimes:pdf';
        // $required['tingkat'] = 'required';
        // $required['pemberi_penghargaan'] = 'required';
        $required['tgl_pelaporan'] = 'required';

        $message['file_name.required'] = 'File wajib Diisi';
        $message['file_name.mimes'] = 'Berkas Hanya Boleh Bertipe PDF!!!';
        // $message['tingkat.required'] = 'Tingkat wajib Diisi';
        // $message['pemberi_penghargaan.required'] = 'Pemberi Penghargaan wajib Diisi';
        $message['tgl_pelaporan.required'] = 'Tanggal Pelaporan wajib Diisi';

        return Validator::make($request->all(), $required, $message);       
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = RiwayatLhkpn::find((int)$request->input('id'));
            File::delete('uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR .'spt_tahunan'.DIRECTORY_SEPARATOR . $data->file_name);
            $id = $data->id_talenta;
            $data->delete();
            $param_talenta['fill_lhkpn'] = CVHelper::lhkpnFillCheck($id);
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
        $filePath = 'uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR.'lhkpn'.DIRECTORY_SEPARATOR.$fileRaw;
        $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'cv'.DIRECTORY_SEPARATOR.'lhkpn'.DIRECTORY_SEPARATOR;
        $fileUpload      = $file->move($destinationPath, $fileRaw);
        $data = (object) array('fileName' => $fileName, 'fileRaw' => $fileRaw, 'filePath' => $filePath);
        return $data;
    }
} 