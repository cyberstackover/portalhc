<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Talenta;
use App\CVNilai;
use App\CVInterest;
use App\CVSummary;
use App\Helpers\CVHelper;
use DB;
use Carbon\Carbon;

class NilaiPribadiController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.nilai';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV Nilai');
      $talenta = Talenta::find($id);
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'active' => "nilai",
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
                  'url' => route('cv.nilai.index', ['id_talenta' => $id]),
                  'menu' => 'Nilai'
              ]               
          ]
      ]);
    }

    public function update(Request $request, $id)
    {

      activity()->log('Menu CV Nilai - update');
      $validation = [
      ];

      $validator = Validator::make($request->all(), $validation);

      if (!$validator->fails()) {
          //$param1 = $request->except(['_token', '_method']);
          $param1['nilai'] = $request->nilai;

          $nilai = CVNilai::where('id_talenta', $id)->get()->first();
          if($nilai){            
            $status = CVNilai::where('id_talenta', $id)->update($param1);
          }else{
            $param1['id_talenta'] = $id;
            $status = CVNilai::create($param1);
          }


          $param2['interest'] = $request->ekonomi. ' ' .$request->leadership. ' ' .$request->sosial;
          //$param2['leadership'] = $request->leadership;
          //$param2['sosial'] = $request->sosial;
          $summary = CVInterest::where('id_talenta', $id)->get()->first();
          if($summary){            
            $status = CVInterest::where('id_talenta', $id)->update($param2);
          }else{
            $param2['id_talenta'] = $id;
            $status = CVInterest::create($param2);
          }

          $param_talenta['fill_nilai'] = CVHelper::nilaiFillCheck($id);
          $param_talenta['fill_interest'] = CVHelper::interestFillCheck($id);
          $param_talenta['persentase'] = CVHelper::fillPercentage($id);
          $status = Talenta::find($id)->update($param_talenta);
          if ($status) {   
            $toastType = 'success';           
            $message = 'Berhasil Mengubah Nilai, Visi dan Pribadi';
          } else {              
            $toastType = 'error';  
            $message = 'Gagal Mengubah Nilai, Visi dan Pribadi';
          }
      } else {
          $toastType = 'warning';  
          $message = 'Gagal Validasi Nilai, Visi dan Pribadi';
      }

      return redirect(route('cv.nilai.index', ['id_talenta' => $id]))->with($toastType, $message);
    }
}