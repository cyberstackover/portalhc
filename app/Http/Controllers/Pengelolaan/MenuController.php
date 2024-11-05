<?php

namespace App\Http\Controllers\Pengelolaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Menu;
use Exception;
use Config;
use Route;
use DB;

class MenuController extends Controller
{
	protected $__route;
	//protected $__permission;

	public function __construct()
	{
		$this->__route = 'pengelolaan.menus';
		$this->middleware('permission:menu-list');
        $this->middleware('permission:menu-create', ['only' => ['create','store']]);
        $this->middleware('permission:menu-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:menu-delete', ['only' => ['delete']]);
		//$this->__permission = Config::get('permission.master-menu');
	} 

	public function index()
	{
		activity()->log('Menu Pengelolaan Menu');
		return view($this->__route.'.index',[
			'pagetitle' => 'Menu',
			'breadcrumb' => [
				[
                    'url' => '',
                    'menu' => 'User Management'
                ],
				[
					'url' => route($this->__route.'.index'),
					'menu' => 'Menu'
				]				
			]
			//'add' => $this->__permission.'-add'
		]);
	}

	public function gettreemenu()
	{
		$data = Menu::orderBy('order')->get();
		$html = '<ol class="dd-list">';
		$html .= $this->recursiveMenu($data, 0);
		$html .= '</ol>';
		return response()->json(compact('html'));
	}

	private function recursiveMenu($data, $parent_id)
	{
		$html = '';
		$result = $data->where('parent_id', (int)$parent_id)->sortBy('order');

		foreach($result as $val){
			$html .= '<li class="dd-item dd3-item" data-id="'.(int)$val->id.'">';
			$html .= '<div class="dd-handle dd3-handle"> </div>';
            $status = $val->status === true ? '<i class="flaticon2-checkmark"></i>' : '<i class="flaticon2-close-cross">';
            $html .= '<div class="dd3-content"><strong>'.$val->label.'</strong> [ Status = '.$status.' ] [ Routing = (<strong>'.$val->route_name.')</strong> ]';
            $html .= '<span class="pull-right">';
            /*if (Gate::allows($this->__permission.'-edit')) {
                $html .= '<a class="text-primary cls-button-edit" href="javascript:;" data-id="'.(int)$val->id.'" data-toggle="tooltip" data-original-title="Ubah Menu '.$val->label.'"><i class="flaticon-edit-1"></i></a>';
            }*/
            $html .= '<a class="text-primary cls-button-edit" href="javascript:;" data-id="'.(int)$val->id.'" data-toggle="tooltip" data-original-title="Ubah Menu '.$val->label.'"><i class="flaticon-edit-1"></i></a>';

            $child = $data->where('parent_id', (int)$val->id)->sortBy('order');

            if(! $child->isEmpty() ){
                /*if (Gate::allows($this->__permission.'-delete')) {
            	    $html .= '&nbsp;';
                    $html .= '<a style="color: #CCCCCC; cursor: not-allowed;" class="nounderline" href="javascript:;" data-id="'.(int)$val->id.'" data-label="'.$val->label.'" data-toggle="tooltip" data-original-title="Hapus Menu '.$val->label.'"><i class="flaticon2-trash"></i></a>';
                }*/
                $html .= '&nbsp;';
                $html .= '<a style="color: #CCCCCC; cursor: not-allowed;" class="nounderline" href="javascript:;" data-id="'.(int)$val->id.'" data-label="'.$val->label.'" data-toggle="tooltip" data-original-title="Hapus Menu '.$val->label.'"><i class="flaticon2-trash"></i></a>';
            }else{
                /*if (Gate::allows($this->__permission.'-delete')) {
            	    $html .= '&nbsp;';
                    $html .= '<a class="text-danger cls-button-delete nounderline" href="javascript:;" data-id="'.(int)$val->id.'" data-label="'.$val->label.'" data-toggle="tooltip" data-original-title="Hapus Menu '.$val->label.'"><i class="flaticon2-trash"></i></a>';
                }*/
                $html .= '&nbsp;';
                $html .= '<a class="text-danger cls-button-delete nounderline" href="javascript:;" data-id="'.(int)$val->id.'" data-label="'.$val->label.'" data-toggle="tooltip" data-original-title="Hapus Menu '.$val->label.'"><i class="flaticon2-trash"></i></a>';
            }

            $html .= '</div>';


            if((bool)$child->count()){
               $html .= '<ol class="dd-list">';
               $html .= $this->recursiveMenu($data, (int)$val->id);
               $html .= '</ol>';
            }
            $html .= '</li>';
        }
        return $html;
    }

	public function create(Request $request)
	{
		return view($this->__route.'.form',[
			'actionform' => 'insert'
		]);
	}	

	private function getparentmenu($id)
	{
		try{
			$data = Menu::find((int)$id);
			return [
				'id' => (int)$data->id,
				'text' => $data->label
			];
		}catch(Exception $e){
			return [
				'id' => 0,
				'text' => 'Sebagai Root Menu'
			];
		}
	}

	public function edit(Request $request)
	{
		try{
			$data = Menu::find((int)$request->input('id'));

			return view($this->__route.'.form',[
				'actionform' => 'update',
				'data' => $data,
				'parent' => json_encode($this->getparentmenu((int)$data->parent_id))
			]);
		}catch(Exception $e){}
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
			$param['label'] = $request->input('label');
			$param['icon'] = $request->input('icon');
			$param['parent_id'] = (int)$request->input('parent_id');
			$param['route_name'] = !empty($request->input('route_name'))?$request->input('route_name'):'';
			$param['status'] = $request->has('status') && $request->input('status') == 'on'? true : false;

            $parent = Menu::where('parent_id', (int)$request->input('parent_id'))->orderBy('order', 'desc')->first();

			switch ($request->input('actionform')) {
				case 'insert': DB::beginTransaction();
                               try{
                               	  $param['order'] = (isset($parent)) ? ($parent->order + 1) : 1;      						
                               	  Menu::create((array)$param);

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
                                    'msg' => 'Gagal tambah data',
                                    'title' => 'Gagal'
                                  ];
                               }

				break;
				
				case 'update': DB::beginTransaction();
                               try{
                               	  Menu::find((int)$request->input('id'))->update((array)$param);

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

	public function delete(Request $request)
	{
        DB::beginTransaction();
        try{
            Menu::find((int)$request->input('id'))->delete();

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

	public function submitchangestructure(Request $request)
	{
		$this->saveChangeMenu($request->input('serialized'));
		return response()->json(true);		
	}

	private function saveChangeMenu($children, $rootId = 0)
	{
		try{
			foreach($children as $key => $val){
				$parent = Menu::where('id', $rootId)->first();

				Menu::where('id', $val['id'])
				->update([
					'parent_id' => $rootId,
					'order' => $key
				]);
				if(isset($val['children'])){
					$this->saveChangeMenu($val['children'], $val['id']);
				}
			}
		}catch(Exception $e){}
  }	

	protected function validateform($request)
	{
        $required['label'] = 'required|max:256';
        /*$required['route_name'] = [
        	'required',
        	'max:256',
	        function($attribute, $value, $fail) use($request){
	        	if($value !== ''){
		        	if(!Route::has($value)){
		        		return $fail('Routing <strong>'.$value.'</strong> belum terdaftar di <mark>web.php</mark> silahkan hubungi developer.');
		        	}
	        	}
	        },        	
        ];*/

        $message['label.required'] = 'Nama menu wajib diinput';
        $message['label.max'] = 'Nama menu maksimal 256 karakter';

        $message['parent_id.required'] = 'Parent menu wajib dipilih';


        $message['route_name.required'] = 'Routing menu wajib diinput';
        $message['route_name.max'] = 'Routing menu maksimal 256 karakter';

        return Validator::make($request->all(), $required, $message); 		
	}	
}
