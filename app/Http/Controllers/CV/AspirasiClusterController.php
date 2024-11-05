<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\ClusterBumn;
use App\Talenta;
use App\TransactionTalentaCluster;
use App\Helpers\CVHelper;
use DB;
use Carbon\Carbon;

class AspirasiClusterController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.cluster';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV Cluster Kelas');
      $talenta = Talenta::find($id);
      $cluster = ClusterBumn::get();
      $trans_cluster = Talenta::find($id)->transactionTalentaCluster()->get();
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'cluster' => $cluster,
          'trans_cluster' => $trans_cluster,
          'active' => "cluster",
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
                  'url' => route('cv.cluster.index', ['id_talenta' => $id]),
                  'menu' => 'Aspirasi Cluster'
              ]               
          ]
      ]);
    }

    public function update(Request $request, $id)
    {

      activity()->log('Menu CV Cluster - update');
      $validation = [
      ];
      $validator = Validator::make($request->all(), $validation);

      if($request->cluster){
        if(count($request->cluster) > 3){
          $result = [
            'flag'  => 'warning',
            'msg' => 'Maksimal 3 Pilihan',
            'title' => 'Gagal'
          ];
          return response()->json($result); 
        }
      }
      if (!$validator->fails()) {
        DB::beginTransaction();
        try{
          #delete transaction old
          TransactionTalentaCluster::where("id_talenta", $id)->delete();

          #create new transaction
          $create = [''];
          if($request->cluster){
            foreach($request->cluster as $key => $data){
              $create['id_talenta'] = $id;
              $create['id_cluster'] = $data;
              TransactionTalentaCluster::create($create);
            }
          }
          
          $param_talenta['fill_cluster'] = CVHelper::clusterFillCheck($id);
          $param_talenta['persentase'] = CVHelper::fillPercentage($id);
          $status = Talenta::find($id)->update($param_talenta);

          $result = [
              'flag'  => 'success',
              'msg' => 'Sukses Ubah data',
              'title' => 'Sukses'
          ];
          DB::commit();
        }catch(\Exception $e){
          DB::rollback();
          $result = [
              'flag'  => 'warning',
              'msg' => 'Gagal Ubah data',
              'title' => 'Gagal'
          ];
       }

      } else {
          $result = [
              'flag'  => 'warning',
              'msg' => 'Gagal Validasi data',
              'title' => 'Gagal'
          ];
      }
      return response()->json($result); 
    }
}