<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\KelasBumn;
use App\Talenta;
use App\TransactionTalentaKelas;
use App\Helpers\CVHelper;
use DB;
use Carbon\Carbon;

class AspirasiKelasController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.kelas';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV Aspirasi Kelas');
      $talenta = Talenta::find($id);
      $kelas = KelasBumn::get();
      $trans_kelas = Talenta::find($id)->transactionTalentaKelas()->get();
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'kelas' => $kelas,
          'trans_kelas' => $trans_kelas,
          'active' => "kelas",
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
                  'url' => route('cv.kelas.index', ['id_talenta' => $id]),
                  'menu' => 'Aspirasi Kelas'
              ]               
          ]
      ]);
    }

    public function update(Request $request, $id)
    {

      activity()->log('Menu CV Kelas - update');
      $validation = [
      ];
      $validator = Validator::make($request->all(), $validation);

      if($request->kelas){
        if(count($request->kelas) > 2){
            $result = [
              'flag'  => 'warning',
              'msg' => 'Maksimal 2 Pilihan',
              'title' => 'Gagal'
          ];
          return response()->json($result); 
        }
      }
      
      if (!$validator->fails()) {
        DB::beginTransaction();
        try{
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
        
          DB::commit();

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