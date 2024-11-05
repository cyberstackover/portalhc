<?php


namespace App\Http\Controllers\Pengelolaan;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\MiddlewareClient;
use DB;
use Hash;
use App\KategoriUser;
use App\LembagaAssessment;
use App\Perusahaan;


class UserController extends Controller

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
         $this->__route = 'pengelolaan.users';
         $this->middleware('permission:user-list');
         /*$this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['delete']]);*/

    }

    public function index(Request $request)

    {

        /*$data = User::orderBy('id','DESC')->paginate(5);

        return view('users.index',compact('data'), ['breadcrum' => $breadcrum, 'breadcrum1' => $breadcrum1])

            ->with('i', ($request->input('page', 1) - 1) * 5);*/
        activity()->log('Menu Pengelolaan User');
        return view($this->__route.'.index',[
            'pagetitle' => 'User',
            'breadcrumb' => [
               [
                    'url' => '',
                    'menu' => 'User Management'
                ],
               [
                    'url' => route('pengelolaan.users.index'),
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
            if($users->kategori_user_id == 2){
              $user_list = User::where('id_bumn', $id_users_bumn)->get();
            } else {
              $user_list = User::get();
            }
            return datatables()->of($user_list)
            ->addColumn('perusahaan', function ($row){
                $id_bumn = (int)$row->id_bumn;
                if(!empty($id_bumn)){
                  $getperusahaan = Perusahaan::where('id', $id_bumn)->first();
                  $nama_perusahaan = $getperusahaan->nama_lengkap;
                } else {
                  $nama_perusahaan = 'Kementerian BUMN';
                }

                return $nama_perusahaan;
            })
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

                if(\Auth::user()->can('user-edit')){
                  $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data user '.$row->name.'"><i class="flaticon-edit"></i></button>';
                } else {
                  $button .= '&nbsp;';
                }

                $button .= '&nbsp;';

                if(\Auth::user()->can('user-edit')){
                  $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete" data-id="'.$id.'" data-user="'.$row->name.'" data-toggle="tooltip" data-original-title="Hapus data user '.$row->name.'"><i class="flaticon-delete"></i></button>';
                } else {
                  $button .= '&nbsp;';
                } 

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


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()

    {

        $roles = Role::get();
        $kategoriuser = KategoriUser::get();
        //$roles = Role::where('id', 2)->get();

        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'roles' => $roles,
            'kategoriuser' => $kategoriuser
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

        $validator = $this->validateform($request);

        if (!$validator->fails()) {
            $param['name'] = $request->input('name');
            $param['email'] = $request->input('email');
            $param['username'] = $request->input('username');
            $param['kategori_user_id'] = (int)$request->input('kategori_user_id');
            $kategori = KategoriUser::find($param['kategori_user_id']);
            $bumn = $request->input('id_bumn');

            if((int)$kategori->pilihan_inputan == 1){
              $param['id_bumn'] = (int)$request->input('id_bumn');
              $param['is_external'] = true;
            }else if((int)$kategori->pilihan_inputan == 2){
              $param['id_bumn'] = (int)$request->input('id_bumn');
              $param['asal_instansi'] = $request->input('asal_instansi');
              $param['is_external'] = true;
            }else if((int)$kategori->pilihan_inputan == 3){
              $param['is_pejabat'] = true;
              $param['id_bumn'] = (int)$request->input('id_bumn');
              $param['is_external'] = true;
            }else if((int)$kategori->pilihan_inputan == 4){
              $param['id_bumn'] = (int)$request->input('id_bumn');
              $param['id_assessment'] = $request->input('id_assessment');
              $param['is_external'] = true;
            } else {
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
                    'bumnhidden' => $user->id_bumn? json_encode(DB::table('perusahaan')->where('id', (int)$user->id_bumn)->first()) : json_encode([]),
                    'asseshidden' => $user->id_assessment? json_encode(DB::table('lembaga_assessment')->where('id', (int)$user->id_assessment)->first()) : json_encode([])

                ]);

    }


    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $id)

    {

        $this->validate($request, [

            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'roles' => 'required'

        ]);


        $input = $request->all();

        /*if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));    
        }*/


        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')

        ->with('success','User updated successfully');

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

    protected function validateform($request)
    {
        $required['name'] = 'required';
        $required['email'] = 'required';

        $message['name.required'] = 'Nama wajib diinput';
        $message['email.required'] = 'Email wajib diinput';

        $message['kategori_user_id.required'] = 'Kategori user wajib dipilih';

        return Validator::make($request->all(), $required, $message);       
    }

}