<?php

namespace App\Http\Controllers\CV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Talenta;
use App\CVSummary;
use App\Helpers\CVHelper;
use DB;
use Carbon\Carbon;

class SummaryController extends Controller
{
    protected $__route;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    function __construct()
    {
         $this->__route = 'cv.summary';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id)
    {
      activity()->log('Menu CV Summary');
      $talenta = Talenta::find($id);
      return view($this->__route.'.index',[
          'pagetitle' => 'Curicullum Vitae - '.$talenta->nama_lengkap,
          'talenta' => $talenta,
          'active' => "summary",
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
                  'url' => route('cv.summary.index', ['id_talenta' => $id]),
                  'menu' => 'Summary'
              ]               
          ]
      ]);
    }

    public function update(Request $request, $id)
    {

      activity()->log('Menu CV Summary - update');
      $validation = [
      ];

      $validator = Validator::make($request->all(), $validation);

      if (!$validator->fails()) {
          $param = $request->except(['_token', '_method']);

          $summary = CVSummary::where('id_talenta', $id)->get()->first();
          if($summary){            
            $status = CVSummary::where('id_talenta', $id)->update($param);
          }else{
            $param['id_talenta'] = $id;
            $status = CVSummary::create($param);
          }
          $param_talenta['fill_summary'] = CVHelper::summaryFillCheck($id);
          $param_talenta['persentase'] = CVHelper::fillPercentage($id);
          $status = Talenta::find($id)->update($param_talenta);
          if ($status) {   
            $toastType = 'success';           
            $message = 'Berhasil Mengubah Summary';
          } else {              
            $toastType = 'error';  
            $message = 'Gagal Mengubah Summary';
          }
      } else {
          $toastType = 'warning';  
          $message = 'Gagal Validasi Summary';
      }

      return redirect(route('cv.summary.index', ['id_talenta' => $id]))->with($toastType, $message);
    }
}