<?php

namespace App\Http\Controllers\Pengelolaan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralModel;
use App\KategoriUser;
use Exception;
use App\LembagaAssessment;

class GeneralController extends Controller
{
	protected $gm;

	public function __construct()
	{
		$this->gm = new GeneralModel();
	}

	public function fetchparentmenu(Request $request)
	{
		try{
			$search = $request->has('q')? $request->input('q') : null;

			$item = $this->gm->getparentmenu($search)
			        ->map(function($item, $key){
			        	return [
			        		'id' => $item->id,
			        		'text' => $item->label
			        	];
			        })
			        ->toArray();

            array_unshift($item, ['id' => 0, 'text' => 'Sebagai Root Menu']);        

			return response()->json(compact('item'));			        
		}catch(Exception $e){
            $item[] = [
                'id' => 0,
                'text' => '[ - Pilih Parent Menu - ]'
            ];
            return response()->json(compact('item'));			
		}			
	}

	public function fetchparentunit(Request $request)
	{
		try{
			$search = $request->has('q')? $request->input('q') : null;

			$item = $this->gm->getparentunit($search)
			        ->map(function($item, $key){
			        	return [
			        		'id' => $item->id,
			        		'text' => $item->nama
			        	];
			        })
			        ->toArray();

            array_unshift($item, ['id' => 0, 'text' => 'Sebagai Root Menu']);        

			return response()->json(compact('item'));			        
		}catch(Exception $e){
            $item[] = [
                'id' => 0,
                'text' => '[ - Pilih Parent Menu - ]'
            ];
            return response()->json(compact('item'));			
		}			
	}

	public function fetchkategoriuser(Request $request)
	{
		try{
			$search = $request->has('q')? $request->input('q') : null;

			$item = $this->gm->getkategoriuser($search)
			        ->map(function($item, $key){
			        	return [
			        		'id' => $item->id,
			        		'text' => $item->kategori,
			        		'ad' => (int)$item->is_ad,
			        		'inputan' => (int)$item->pilihan_inputan
			        	];
			        })
			        ->toArray();    

			return response()->json(compact('item'));			        
		}catch(Exception $e){
            $item[] = [
                'id' => 0,
                'text' => '[ - Pilih Kategori User - ]'
            ];
            return response()->json(compact('item'));			
		}		
	}

	public function fetchassessment(Request $request)
	{
		try{
			$search = $request->has('q')? $request->input('q') : null;

			$item = $this->gm->getlembagaassessment($search)
			        ->map(function($item, $key){
			        	return [
			        		'id' => $item->id,
			        		'text' => $item->nama
			        	];
			        })
			        ->toArray();    

			return response()->json(compact('item'));			        
		}catch(Exception $e){
            $item[] = [
                'id' => 0,
                'text' => '[ - Pilih Lembaga Assessment - ]'
            ];
            return response()->json(compact('item'));			
		}
	}

	public function fetchbumnactive(Request $request)
	{
		try{
			$search = $request->has('q')? $request->input('q') : null;

			$item = $this->gm->getbumnactive($search)
			        ->map(function($item, $key){
			        	return [
			        		'id' => $item->perusahaan_id,
			        		'text' => $item->nama_lengkap
			        	];
			        })
			        ->toArray();    

			return response()->json(compact('item'));			        
		}catch(Exception $e){
            $item[] = [
                'id' => 0,
                'text' => '[ - Pilih BUMN - ]'
            ];
            return response()->json(compact('item'));			
		}		
	}
}
