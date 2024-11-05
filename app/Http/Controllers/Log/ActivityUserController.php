<?php


namespace App\Http\Controllers\Log;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
//use App\Unit;
use App\StatusPelaporan;
use DB;


class ActivityUserController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
         $this->__route = 'log.index';
         $this->middleware('permission:log-list');
         /*$this->middleware('permission:pengisian-create');
         $this->middleware('permission:pengisian-edit');
         $this->middleware('permission:pengisian-delete');*/
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        activity()->log('Menu Activity Log User');
        return view($this->__route,[
            'pagetitle' => 'Activity User Log',
            //'units' => Unit::orderBy('id', 'ASC')->get(),
            'breadcrumb' => [
                [
                    'url' => route('log.index'),
                    'menu' => 'Activity User Log'
                ]               
            ]
        ]);

    }

    public function datatable(Request $request)
    {
        try{

            $sql = 'SELECT
                      activity_log.id,
                      activity_log.description,
                      users.name,
                      activity_log.created_at 
                    FROM
                      activity_log
                      LEFT JOIN users ON activity_log.causer_id = users.ID
                    ORDER BY
                      activity_log.id DESC 
                    Limit 3000';

            $status  = DB::select(DB::raw($sql));

            return datatables()->of($status)
            ->rawColumns(['description','name','created_at'])
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

}