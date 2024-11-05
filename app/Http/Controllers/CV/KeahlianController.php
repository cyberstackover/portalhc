<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Keahlian;
use App\Talenta;
use App\TransactionTalentaKeahlian;
use App\Helpers\CVHelper;
use DB;
use Carbon\Carbon;

class KeahlianController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.keahlian';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV Keahlian');
      $talenta = Talenta::find($id);
      $keahlian = Keahlian::get();
      $trans_keahlian = Talenta::find($id)->transactionTalentaKeahlian()->get();
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'keahlian' => $keahlian,
          'trans_keahlian' => $trans_keahlian,
          'active' => "keahlian",
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
                  'url' => route('cv.keahlian.index', ['id_talenta' => $id]),
                  'menu' => 'Keahlian'
              ]               
          ]
      ]);
    }

    public function update(Request $request, $id)
    {

      activity()->log('Menu CV Keahlian - update');
      $validation = [
      ];
      $validator = Validator::make($request->all(), $validation);
      
      if($request->keahlian){
        if(count($request->keahlian) > 5){
            $result = [
              'flag'  => 'warning',
              'msg' => 'Maksimal 5 Pilihan',
              'title' => 'Gagal'
          ];
          return response()->json($result); 
        }
      }

      if (!$validator->fails()) {
        DB::beginTransaction();
        try{
          #delete transaction old
          TransactionTalentaKeahlian::where("id_talenta", $id)->delete();

          #create new transaction
          $create = [''];
          if($request->keahlian){
            foreach($request->keahlian as $key => $data){
              $create['id_talenta'] = $id;
              $create['id_keahlian'] = $data;
              TransactionTalentaKeahlian::create($create);
            }
          }
          
          $param_talenta['fill_keahlian'] = CVHelper::keahlianFillCheck($id);
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