<?php

namespace App\Http\Controllers\Pengelolaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\MiddlewareClient;
use DB;
use Hash;
use App\KategoriUser;

class UserPejabatController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         //$this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
         $this->__route = 'pengelolaan.pejabats';
         //$this->middleware('permission:user-list');
         /*$this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['delete']]);*/

    }

    function index(Request $request) {

    	activity()->log('Menu Pengelolaan User Pejabat');
    	return view($this->__route.'.index',[
            'pagetitle' => 'User Pejabat',
            'breadcrumb' => [
               [
                    'url' => '',
                    'menu' => 'User Pejabat Management'
                ],
               [
                    'url' => route('pengelolaan.pejabats.index'),
                    'menu' => 'User'
                ]               
            ]
        ]);
    }

    public function datatable(Request $request)
    {

    	  $id_users = \Auth::user()->id;
        $id_users_bumn = \Auth::user()->id_bumn;
        $users = User::where('id', $id_users)->first();

        try{

        	if ($users->kategori_user_id == 2) {
        		$listpejabat = User::where('is_pejabat','t')->where('id_bumn', $id_users_bumn)->get();
        	} else {
        		$listpejabat = User::where('is_pejabat','t')->get();
        	}

            return datatables()->of($listpejabat)
            ->editColumn('roles', function ($row){
                $label = '<ul class="no-margin">';
                if(!empty($row->getRoleNames())){
                    foreach ($row->getRoleNames() as $v) {
                        $label .= '<li>'.$v.'</li>';
                    }
                }
                $label .= '</ul>';
                return $label;
            })
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data user '.$row->name.'"><i class="flaticon-edit"></i></button>';

                $button .= '&nbsp;';

                $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete" data-id="'.$id.'" data-user="'.$row->name.'" data-toggle="tooltip" data-original-title="Hapus data user '.$row->name.'"><i class="flaticon-delete"></i></button>'; 

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['email','username','roles','action'])
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

    public function create()

    {

       $id_users = \Auth::user()->id;
       $id_users_bumn = \Auth::user()->id_bumn;
       $users = User::where('id', $id_users)->first();
       $kategori_user = $users->kategori_user_id;

        //$roles = Role::get();
        $kategoriuser = KategoriUser::where('kategori', 'Pejabat BUMN')->get();
        $roles = Role::where('name', 'pejabat')->get();

        //get nama pejabat diperusahaan itu
        //
        $id_users = \Auth::user()->id;
        $id_users_bumn = \Auth::user()->id_bumn;
        $users = User::where('id', $id_users)->first();

        if ($users->kategori_user_id == 2) {
        	$pejabat_sql = "SELECT
                  talenta.id,
                  talenta.nama_lengkap || ' ( ' || struktur_organ.nomenklatur_jabatan || ' )' || ' - ' || ' (' || surat_keputusan.nomor || ') ' AS nama
                FROM
                    organ_perusahaan
                    LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                    LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                    left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                    left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                    LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                WHERE
                    struktur_organ.id_perusahaan = $id_users_bumn and organ_perusahaan.aktif = 't' and grup_jabatan.id = 1
                ORDER BY
                  organ_perusahaan.id_struktur_organ asc,
                  talenta.nama_lengkap asc";
        } else {
        	$pejabat_sql = "SELECT
                  talenta.id,
                  talenta.nama_lengkap || ' ( ' || struktur_organ.nomenklatur_jabatan || ' )' || ' - ' || ' (' || surat_keputusan.nomor || ') ' AS nama
                FROM
                    organ_perusahaan
                    LEFT JOIN talenta ON talenta.ID = organ_perusahaan.id_talenta
                    LEFT JOIN struktur_organ ON struktur_organ.ID = organ_perusahaan.id_struktur_organ
                    left join jenis_jabatan on jenis_jabatan.id = struktur_organ.id_jenis_jabatan
                    left join grup_jabatan on grup_jabatan.id = jenis_jabatan.id_grup_jabatan
                    LEFT JOIN surat_keputusan ON surat_keputusan.ID = organ_perusahaan.id_surat_keputusan
                WHERE
                    organ_perusahaan.aktif = 't' and grup_jabatan.id = 1
                ORDER BY
                  organ_perusahaan.id_struktur_organ asc,
                  talenta.nama_lengkap asc";
        }
        
        $pejabats  = DB::select(DB::raw($pejabat_sql));

        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'roles' => $roles,
            'kategoriuser' => $kategoriuser,
            'pejabats' => $pejabats,
            'kategori_user' => $kategori_user,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)

    {
        $result = [
            'flag' => 'error',
            'msg' => 'Error System',
            'title' => 'Error'
        ];

        $id_users = \Auth::user()->id;
        $id_users_bumn = \Auth::user()->id_bumn;
        $users = User::where('id', $id_users)->first();      

        $validator = $this->validateform($request);

        if (!$validator->fails()) {
            $param['name'] = $request->input('name');
            $param['email'] = $request->input('email');
            $param['username'] = $request->input('username');
            $param['kategori_user_id'] = 11;
            $kategori = KategoriUser::find($param['kategori_user_id']);
            $bumn = id_users_bumn;
            $param['is_pejabat'] = true;

            if((int)$kategori->pilihan_inputan == 1){
              $param['id_bumn'] = (int)$id_users_bumn;
              $param['is_external'] = true;
            } else {
              $param['id_bumn'] = $request->input('id_bumn');
              $param['is_external'] = false;
            }

            $param['activated'] = $request->input('activated') == 'on'? 1 : 0;
            /*$param['password'] = bcrypt(hash_hmac('sha256', (new Token())->UniqueString('users', 'password', 40 ), config('app.key')));*/
            //$param['password'] = Hash::make($request->input('password'));

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{

                                  $request->username = $request->input('username');
                                  $request->email = $request->input('email');
                                  /*$param['remember_token'] = (new Token())->UniqueString('users', 'remember_token', 100 );*/
                                  $res = MiddlewareClient::addUser($request);
                                  if ($res['status']==false){
                                      throw new \Exception($res['msg'][0]);
                                  }
                                  $user = User::create((array)$param);
                                  $user->assignRole($request->input('roles'));
                                  //$user->bumns()->sync($bumn);

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
                                  
                                  $user = User::find((int)$request->input('id'));
                                  $user->update((array)$param);
                                  DB::table('model_has_roles')->where('model_id',(int)$request->input('id'))->delete();
                                  $user->assignRole($request->input('roles'));
                                  //$user->bumns()->sync($bumn);

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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request)
    {
    
        $user = User::find((int)$request->input('id'));
        $roles = Role::get();
        $userRole = $user->roles->pluck('name','name')->all();
        $kategoriuser = KategoriUser::get();
        //dd($userRole);

        return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'user' => $user,
                    'roles' => $roles,
                    'kategoriuser' => $kategoriuser,
                    'kategori' => json_encode(KategoriUser::find((int)$user->kategori_user_id)),
                    'userRole' => $userRole,
                    'bumnhidden' => $user->id_bumn? json_encode(DB::table('perusahaan')->where('id', (int)$user->id_bumn)->first()) : json_encode([])

                ]);

    }

    /**
     * [service untuk ambil tanggal awal jabat]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getnamapejabat(Request $request)
    {
        $id_talenta = $request->id_talenta;

        $organlist_sql = "SELECT
                                email,
                                nama_lengkap 
                            FROM
                                talenta
                            WHERE
                                id = $id_talenta";

        $listorgan  = DB::select(DB::raw($organlist_sql));

        //dd($listorgan[0]->tanggal_awal);

        $json = array();

        $json[] = array(
               'email' => $listorgan[0]->email,
               'nama_lengkap' => $listorgan[0]->nama_lengkap
         );

        //dd($json);

        return response()->json($json);
    }

    public function checkuser(Request $request)
    {
      try{
        /*$res = MiddlewareClient::getUserProfile($username);
        return return response()->json($this->__gm->checkuserbyparam([
          'username' => $request->input('username')
        ]));*/
        return response()->json(MiddlewareClient::getUserProfile($request->input('username')));          
      }catch(Exception $e){
        return response()->json([
          'data' => false,
          'result' => 'Data tidak ditemukan'
        ]);   
      }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $user = User::find((int)$request->input('id'));
            $response = MiddlewareClient::deleteUser($user->username);

            $user->roles()->detach();
            $user->delete();

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
        $required['name'] = 'required';
        $required['email'] = 'required';

        $message['name.required'] = 'Nama wajib diinput';
        $message['email.required'] = 'Email wajib diinput';

        return Validator::make($request->all(), $required, $message);       
    }
}
