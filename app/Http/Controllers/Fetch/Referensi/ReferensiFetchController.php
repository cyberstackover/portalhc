<?php

namespace App\Http\Controllers\Fetch\Referensi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Agama;
use App\Kota;
use App\Provinsi;
use App\Universitas;
use App\Talenta;
use App\DynamicFilter;
use App\DynamicOperator;
use App\DynamicTabelSumber;
use App\DynamicStandarValue;
use App\Perusahaan;
use DB;


class ReferensiFetchController extends Controller
{
    public function GetAllAgama(Request $request) 
    {
        $return = [];
        $data = Agama::get();
        foreach($data as $id => $text){
            $return[] = ['id' => $id, 'text' => $text ];
        }
        return response()->json(['results' => $return]);
    }

    public function getKota(Request $request)
    {
    	$id_provinsi = $request->id_provinsi;
        $return = [];
        $data = Kota::where('provinsi_id', $id_provinsi)->get();

        foreach($data as $item){
            $return[] = ['id' => $item->id, 'nama' => $item->nama];
        }
        return response()->json($return);
    }

    public function getAllProvinsi(Request $request)
    {
        $return = [];
        $data = Provinsi::where('is_luar_negeri', false)->get();
        foreach($data as $item){
            $return[] = ['id' => $item->id, 'nama' => $item->nama];
        }
        return response()->json($return);
    }
    
    public function getTalenta(Request $request)
    {
        $return = [];
        $data = Talenta::orderBy('nama_lengkap')->get();
        foreach($data as $item){
            $return[] = ['id' => $item->id, 'nama' => $item->nama_lengkap];
        }
        return response()->json($return);
    }
    
    public function getDataUniversitas(Request $request)
    {
        $return = [];
        $data = Universitas::where('id', $request->id_universitas)->first();
        if(@$data->refNegara->is_luar_negeri == false){
            $return['negara'] = 'INDONESIA';
            $return['id_negara'] = 'INDONESIA';
            $return['provinsi'] = (@$data->refNegara->nama ? @$data->refNegara->nama:'' );
            $return['id_provinsi'] = (@$data->refNegara->id ? @$data->refNegara->id:'' );
        }else{
            $return['negara'] = (@$data->refNegara->nama ? @$data->refNegara->nama: '' );
            $return['id_negara'] = (@$data->refNegara->id ? @$data->refNegara->id:'' );
            $return['provinsi'] = '';
            $return['id_provinsi'] = '';
        }
        $return['kota'] = (@$data->refKota->nama ? @$data->refKota->nama:'' );
        $return['id_kota'] = (@$data->refKota->id ? @$data->refKota->id:'' );

        return response()->json($return);
    }
    
    public function getDynamicSearch(Request $request)
    {
        $return = [];
        $data = DynamicFilter::OrderBy('id', 'asc')->get();

        foreach($data as $item){
            $return[] = ['id' => $item->id, 'nama' => $item->menu];
        }
        return response()->json($return);
    }

    public function getDynamicSearchMenu(Request $request)
    {
        $return = [];
        $data = DynamicFilter::Select(DB::raw('distinct menu as menu'))
                                ->where('aktif', true)
                                ->get();

        foreach($data as $item){
            $return[] = ['id' => $item->menu, 'nama' => $item->menu];
        }
        return response()->json($return);
    }

    public function getDynamicSearchSubMenu(Request $request)
    {
        $return = [];
        $data = DynamicFilter::Where('aktif', 't')->OrderBy('id', 'asc')->get();

        foreach($data as $item){
            $return[] = ['id' => $item->id, 'nama' => $item->submenu];
        }
        return response()->json($return);
    }

    public function getDynamicSearchById(Request $request)
    {
        $id = $request->id;
        $data = DynamicFilter::where('id', $id)->first();
        $return['id']       = $data->id;
        $return['tipe']     = $data->tipe;
        $return['nilai']    = $data->nilai;
        $return['submenu']  = $data->submenu;
        $return['is_number']= $data->is_number;
        $return['select_nilai'] = '';
        $return['standar_value'] = '';

        if($data->dynamic_tabel_sumber_id){
            $dynamic_tabel_sumber = DynamicTabelSumber::where('id', $data->dynamic_tabel_sumber_id)->first();
            $return['alias'] = $dynamic_tabel_sumber->alias;
            
            if($data->dynamic_standar_value_id){
                $dynamic_standar_value = DynamicStandarValue::where('id', $data->dynamic_standar_value_id)->pluck('opsi')->first();
                $return['standar_value'] = $dynamic_standar_value;
            }else if($data->tipe == 'select' || $data->tipe == 'multi select' ){
                $select_nilai = DB::select(DB::raw('select *, '.$dynamic_tabel_sumber->field.' as nama from '.$dynamic_tabel_sumber->tabel));
                $return['select_nilai'] = $select_nilai;
            }
        }

        
        return response()->json($return);
    }

    public function getDynamicSearchOperator(Request $request)
    {
        $return = [];
        $is_number = $request->is_number;
        $data = DynamicOperator::WhereRaw("(is_sorting = false OR is_sorting is null)")->whereRaw("(is_number = false OR is_number is null)")->get();

        if ($is_number == 'true') {
            $data = DynamicOperator::WhereRaw("(is_sorting = false OR is_sorting is null)")->get();
        }

        foreach($data as $item){
            $return[] = ['id' => $item->operator, 'nama' => $item->nama];
        }
        return response()->json($return);
    }
    
    public function getDynamicSearchBumn(Request $request)
    {
        $return = [];
        $id_kelas = $request->id_kelas;
        $id_cluster = $request->id_cluster;
        $data = Perusahaan::select('id', 'nama_lengkap');
        if($id_kelas) {
            $data = $data->where('kelas', $id_kelas);
        }
        if($id_cluster) {
            $data = $data->where('id_klaster', $id_cluster);
        }
        $data = $data->get();

        foreach($data as $item){
            $return[] = ['id' => $item->id, 'nama' => $item->nama_lengkap];
        }
        return response()->json($return);
    }
}
