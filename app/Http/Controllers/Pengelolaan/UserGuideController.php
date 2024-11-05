<?php


namespace App\Http\Controllers\Pengelolaan;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use App\UserGuide;
use DB;
use File;
use Config;
use Carbon\Carbon;

class UserGuideController extends Controller
{
    protected $__route;
    protected $userguidefile = '';
    protected $userguidefile_url = '';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->__route = 'pengelolaan.user_guides';
         $this->middleware('permission:userguide-list');
         $this->middleware('permission:userguide-create', ['only' => ['create','store']]);
         $this->middleware('permission:userguide-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:userguide-delete', ['only' => ['delete']]);

         $this->userguidefile = Config::get('folder.userguidefile');
         $this->userguidefile_url = Config::get('folder.userguidefile_url');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Pengelolaan User Guide');
        $user_guide = UserGuide::select('*')->orderBy('created_at', 'desc')->first();
        return view($this->__route.'.index',[
            'pagetitle' => 'User Guide',
            'breadcrumb' => [
                [
                    'url' => '',
                    'menu' => 'User Management'
                ],
                [
                    'url' => route('pengelolaan.user_guides.index'),
                    'menu' => 'User Guide'
                ]               
                ],
            'current_file' => asset($this->userguidefile_url.@$user_guide->filename)
        ]);

    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(UserGuide::query())
            ->editColumn('created_at', function ($row) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('jS F Y');
            })
            ->editColumn('filename', function ($row){
                $file = '<a class="tooltips" title="Download File" href="'.asset($this->userguidefile_url.$row['filename']).'" download="'.$row->filename.'" ><span class="btn-icon-only btn btn-sm yellow-gold sbold"><i class="flaticon2-document"></i>'. $row->filename .'</a><br/>';

                return $file;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete" data-id="'.$id.'" data-user_guide="'.$row->name.'" data-toggle="tooltip" data-original-title="Hapus File '.$row->name.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['created_at','filename','action'])
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
        return view($this->__route.'.form',[
            'actionform' => 'insert'
        ]);

    }

    public function store(Request $request)
    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];   
        
        DB::beginTransaction();
        try{
            $dir = $this->userguidefile;
            if($request->hasFile('filename')){
                if ($request->file('filename')->isValid()) {
                    if (!file_exists ($dir)){
                        mkdir($dir, 0755, true);
                    }

                    $ext = strtolower(pathinfo($request->file('filename')->getClientOriginalName(), PATHINFO_EXTENSION));
                    $file = $request->file('filename')->getClientOriginalName();
                    if($request->file('filename')->move($dir, $file)){
                       $uploaded_file = true;
                       $param['filename'] = $file;
                    }
                }
            }

            
            $Userguide = UserGuide::create((array)$param);

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

            $Userguide = UserGuide::find((int)$request->input('id'));

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'userguide' => $Userguide

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Userguide::find((int)$request->input('id'));
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

}