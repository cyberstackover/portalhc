<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use App\SocialMedia;
use App\TransactionTalentaSocialMedia;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Helpers\CVHelper;

class ImportSocialMedia implements ToCollection   , WithHeadingRow, WithMultipleSheets 
{
    public function __construct($row,$id ){
        $this->row = $row ;
        $this->id = $id ;
        $this->suffix = $this->row->where('3','Social Media')->keys()->first();
        
        ++$this->suffix;


    }
      public function sheets(): array
    {
        return [
            1 => $this,
        ];
    }
    public function collection(Collection $row)
    {
        $arr = collect();
    

       
        foreach($row as $r) {

            if($r['akun_social_media'] == null){
                break ;
            }
            else if ($r['akun_social_media'] == null)
              continue ;
            $arr->push($r);
        }

       
       foreach ($arr as $ar) {
        $sosmed = explode(',',$ar['akun_social_media']);
        $jenissosmed = ltrim(rtrim($ar['social_media']));
        if($jenissosmed == 'Twitter'){
           $id_sosmed = 1;
        } elseif ($jenissosmed == 'Facebook') {
           $id_sosmed = 2;
        } elseif ($jenissosmed == 'Instagram') {
           $id_sosmed = 3;
        } elseif ($jenissosmed == 'Linkedin') {
           $id_sosmed = 6;
        }
        //$id_sosmed = ltrim(rtrim($ar['id']));
        TransactionTalentaSocialMedia::where('id_talenta',$this->id)->where('id_social_media',$id_sosmed)->delete();
      
       
      foreach($sosmed as $s) {
          try {
           TransactionTalentaSocialMedia::create([
              'id_talenta' => $this->id ,
              'id_social_media' =>  $id_sosmed ,
              'name_social_media' => ltrim(rtrim($s)),

           ]);
         }
            catch(\Exception $e){
              DB::rollBack();
          return redirect(route('cv.board.index'))->with('error', 'Isi Tabel Social Media Invalid');
         }
        }
      
       }
            
       $persentase = CVHelper::updatePersentase($this->id);
       return back()->with('success', 'Import Selesai');
    }







    public function headingRow(): int
    {
        return $this->suffix;
    }




}
