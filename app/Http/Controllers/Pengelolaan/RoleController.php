<?php


namespace App\Http\Controllers\Pengelolaan;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Menu;
use App\Unit;
use DB;


class RoleController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->__route = 'pengelolaan.roles';
         $this->middleware('permission:role-list');
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['delete']]);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Pengelolaan Role');
        return view($this->__route.'.index',[
            'pagetitle' => 'Role',
            'breadcrumb' => [
                [
                    'url' => '',
                    'menu' => 'User Management'
                ],
                [
                    'url' => route('pengelolaan.roles.index'),
                    'menu' => 'Role'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        try{
            return datatables()->of(Role::query())
            ->addColumn('action', function ($row){
                $id = (int)$row->id;
                $button = '<div align="center">';

                if (\Auth::user()->hasPermissionTo('role'.'-edit')) {
                  $button .= '<button type="button" class="btn btn-outline-brand btn-sm btn-icon cls-button-edit" data-id="'.$id.'" data-toggle="tooltip" data-original-title="Ubah data role '.$row->name.'"><i class="flaticon-edit"></i></button>';
                }

                $button .= '&nbsp;';

                if (\Auth::user()->hasPermissionTo('role'.'-delete')) {
                  $button .= '<button type="button" class="btn btn-outline-danger btn-sm btn-icon cls-button-delete" data-id="'.$id.'" data-role="'.$row->name.'" data-toggle="tooltip" data-original-title="Hapus data role '.$row->name.'"><i class="flaticon-delete"></i></button>';
                }

                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['guard_name','action'])
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
        $permission = Permission::get();
        //$units = Unit::get();
       
        return view($this->__route.'.form',[
            'actionform' => 'insert',
            'permission' => $permission,
            //'units' => $units
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
            $param['name'] = $request->input('name');
            $permission = $request->input('permission');
            //$unit = $request->input('unit');
            $menu = explode(',', $request->input('menu'));

            switch ($request->input('actionform')) {
                case 'insert': DB::beginTransaction();
                               try{
                                  $role = Role::create((array)$param);
                                  $role->syncPermissions($request->input('permission'));
                                  $role->menus()->sync($menu);
                                  //$role->units()->sync($unit);

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
                                  $role = Role::find((int)$request->input('id'));
                                  $role->syncPermissions($request->input('permission'));
                                  $role->menus()->sync($menu);
                                  //$role->units()->sync($unit);
                                  $role->update((array)$param);

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

        try{

            $role = Role::find((int)$request->input('id'));
            $permission = Permission::get();
            //$units = Unit::get();
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",(int)$request->input('id'))
                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                ->all();
            /*$roleUnits = DB::table("role_unit")->where("role_unit.role_id",(int)$request->input('id'))
                ->pluck('role_unit.unit_id','role_unit.unit_id')
                ->all();*/

                return view($this->__route.'.form',[
                    'actionform' => 'update',
                    'role' => $role,
                    'permission' => $permission,
                    //'units' => $units,
                    'rolePermissions' => $rolePermissions,
                    //'roleUnits' => $roleUnits

                ]);
        }catch(Exception $e){}

    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try{
            $data = Role::find((int)$request->input('id'));
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

    public function gettreemenubyrole($id=null)
    {
      try{
        $result = $this->getarrayrolebymenu((int)$id);
        return response()->json($result);
      }catch(Exception $e){
        return response()->json([]);
      }
    }

    private function getarrayrolebymenu($id)
    {
      $data = Menu::where('status',true)->orderBy('order')->get();
      $menurole = [];
      if((bool)$id){
        //jika id ada artinya ini bagian edit lakukan pengambilan data referensi
        $row = Role::find($id);
        $menurole = $row->menus()->get()->pluck('id')->toArray();
      }
      return $this->recursivemenu($data, 0, $menurole);
    }

    private function recursivemenu($data, $parent_id, $menurole)
    {
      $array = [];
        $result = $data->where('parent_id', (int)$parent_id)->sortBy('order');
        foreach ($result as $val) {
          $child = $data->where('parent_id', (int)$val->id)->sortBy('order');

          $array[] = [
            'id' => (int)$val->id,
            'text' => $val->label,
            'state' => [
              'opened' => (bool)$child->count()? true : false,
              'selected' => $val->id == 1? true : ((bool)count($menurole)? (in_array($val->id, $menurole)? true : false) : false),
              'disabled' => $val->id == 1? true : false
            ],
            'children' => (bool)$child->count()? $this->recursivemenu($data, (int)$val->id, $menurole) : []
          ];
        }
        return $array;    
    }

    protected function validateform($request)
    {
        $required['name'] = 'required';
        $required['permission'] = 'required';

        $message['name.required'] = 'Nama Role wajib diinput';
        $message['permission.required'] = 'Permission wajib dipilih';

        return Validator::make($request->all(), $required, $message);       
    }


}