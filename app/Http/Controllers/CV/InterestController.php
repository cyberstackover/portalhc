<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Talenta;
use App\CVInterest;
use App\CVSummary;
use App\Helpers\CVHelper;
use DB;
use Carbon\Carbon;

class InterestController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.interest';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV Interest');
      $talenta = Talenta::find($id);
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'active' => "interest",
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
                  'url' => route('cv.interest.index', ['id_talenta' => $id]),
                  'menu' => 'Interest'
              ]               
          ]
      ]);
    }

    public function update(Request $request, $id)
    {

      activity()->log('Menu CV Interest - update');
      $validation = [
      ];

      $validator = Validator::make($request->all(), $validation);

      if (!$validator->fails()) {
          $param = $request->except(['_token', '_method']);

          $summary = CVInterest::where('id_talenta', $id)->get()->first();
          if($summary){            
            $status = CVInterest::where('id_talenta', $id)->update($param);
          }else{
            $param['id_talenta'] = $id;
            $status = CVInterest::create($param);
          }
          
          $param_talenta['fill_interest'] = CVHelper::interestFillCheck($id);
          $param_talenta['persentase'] = CVHelper::fillPercentage($id);
          $status = Talenta::find($id)->update($param_talenta);
          if ($status) {   
            $toastType = 'success';           
            $message = 'Berhasil Mengubah Interest';
          } else {              
            $toastType = 'error';  
            $message = 'Gagal Mengubah Interest';
          }
      } else {
          $toastType = 'warning';  
          $message = 'Gagal Validasi Interest';
      }

      return redirect(route('cv.keahlian.index', ['id_talenta' => $id]))->with($toastType, $message);
    }
}