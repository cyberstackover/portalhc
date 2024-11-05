<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\KelasBumn;
use App\ClusterBumn;
use App\Keahlian;
use App\Talenta;
use App\TransactionTalentaKeahlian;
use App\TransactionTalentaCluster;
use App\TransactionTalentaKelas;
use App\Helpers\CVHelper;
use DB;
use Carbon\Carbon;

class AspirasiController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.aspirasi';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV Aspirasi');
      $talenta = Talenta::find($id);
      $kelas = KelasBumn::Orderby('id','ASC')->get();
      $trans_kelas = Talenta::find($id)->transactionTalentaKelas()->get();
      $cluster = ClusterBumn::Orderby('id','ASC')->get();
      $trans_cluster = Talenta::find($id)->transactionTalentaCluster()->get();
      $keahlian = Keahlian::whereIn('id',array(1,20,21,22,23,24,25,26,27,28,29,30,31,32))->Orderby('id','ASC')->get();
      $trans_keahlian = Talenta::find($id)->transactionTalentaKeahlian()->get();
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'kelas' => $kelas,
          'trans_kelas' => $trans_kelas,
          'cluster' => $cluster,
          'trans_cluster' => $trans_cluster,
          'keahlian' => $keahlian,
          'trans_keahlian' => $trans_keahlian,
          'active' => "aspirasi",
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
                  'url' => route('cv.aspirasi.index', ['id_talenta' => $id]),
                  'menu' => 'Aspirasi Kelas'
              ]               
          ]
      ]);
    }

    public function update(Request $request, $id)
    {

      activity()->log('Menu CV Aspirasi - update');
      $validation = [
      ];
      $validator = Validator::make($request->all(), $validation);

      if (!$validator->fails()) {
        try{
          DB::commit();
          #delete transaction old
          TransactionTalentaKelas::where("id_talenta", $id)->delete();

          #create new transaction
          $create = [''];
          if($request->kelas){
            foreach($request->kelas as $key => $data){
              $create['id_talenta'] = $id;
              $create['id_kelas'] = $data;
              TransactionTalentaKelas::create($create);
            }
          }
          
          $param_talenta['fill_kelas'] = CVHelper::kelasFillCheck($id);
          $param_talenta['persentase'] = CVHelper::fillPercentage($id);
          $status = Talenta::find($id)->update($param_talenta);

          $result = [
              'flag'  => 'success',
              'msg' => 'Sukses Ubah data',
              'title' => 'Sukses'
          ];
          //$toastType = 'success';           
          //$message = 'Berhasil Mengubah Interest';
          // return redirect(route('cv.keahlian.index', ['id_talenta' => $id]))->with($toastType, $message);
        }catch(\Exception $e){dd($e);
          DB::rollback();
          $result = [
              'flag'  => 'warning',
              'msg' => 'Gagal Ubah data',
              'title' => 'Gagal'
          ];
          // $toastType = 'error';   
          // $message = 'Gagal Mengubah Keahlian';
          // return redirect(route('cv.keahlian.index', ['id_talenta' => $id]))->with($toastType, $message);
       }

      } else {
          // $toastType = 'warning';  
          // $message = 'Gagal Validasi Keahlian';
          $result = [
              'flag'  => 'warning',
              'msg' => 'Gagal Validasi data',
              'title' => 'Gagal'
          ];
      }
      return response()->json($result); 
      // return redirect(route('cv.keahlian.index', ['id_talenta' => $id]))->with($toastType, $message);
    }
}