<?php

namespace App\Console;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\Perusahaan;

class SilabaBumnSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'silaba:bumnsync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Data Bumn sync from silaba';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', env('SILABA_HOST') . 'service/bumn');
        $body = json_decode($response->getBody());
        // dump($body);
        if($body){
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $user_id = \Auth::user()->id;
            // $data = array();
            foreach ($body as $value) {
                // $data[] = [
                //       'id' => $value->id,
                //       'id_angka' => $value->id_angka,
                //       'id_huruf' => $value->id_huruf,
                //       'nama_lengkap' => $value->nama_lengkap,
                //       'nama_singkat' => $value->nama_singkat,
                //       'logo' => $value->logo,
                //       'jenis_perusahaan' => $value->jenis_perusahaan,
                //       'kepemilikan' => $value->kepemilikan,
                //       'bidang_usaha' => $value->bidang_usaha,
                //       'visi' => $value->visi,
                //       'misi' => $value->misi,
                //       'url' => $value->url,
                //       'induk' => $value->induk,
                //       'level' => $value->level,
                //       'created_at' => $now
                //     ];

                $id_silaba[] = $value->id;
                
                \DB::table('perusahaan')
                  ->updateOrInsert(
                      ['id' => $value->id],
                      [
                          'id_angka' => $value->id_angka,
                          'id_huruf' => $value->id_huruf,
                          'nama_lengkap' => $value->nama_lengkap,
                          'nama_singkat' => $value->nama_singkat,
                          'logo' => $value->logo,
                          'jenis_perusahaan' => $value->jenis_perusahaan,
                          'kepemilikan' => $value->kepemilikan,
                          'bidang_usaha' => $value->bidang_usaha,
                          'visi' => $value->visi,
                          'misi' => $value->misi,
                          'url' => $value->url,
                          'induk' => $value->induk,
                          'level' => $value->level,
                          'created_at' => $now,
                          'is_active' => true,
                          ]
                        );
                    }
                    // if(count($data) > 0){
            //     \DB::table('bumns')->delete();
            //     \DB::table('bumns')->insert($data);
            // }
            $perusahaan = \DB::table('perusahaan')->get();
            $id_perusahaan = $perusahaan->pluck('id')->all();
            // dd($id_perusahaan);
            $diff_id = array_diff($id_perusahaan, $id_silaba);
            
            // dd($diff_id);
            if(!empty($diff_id)){
                // dd($diff_id);
                $perusahaan = Perusahaan::whereIn('id', $diff_id);
                $perusahaan->update(['is_active'=>false]);
            }
            // die;
            
            $perusahaan = \DB::table('perusahaan')->get();
            \DB::statement('TRUNCATE TABLE perusahaan_relasi');
            foreach($perusahaan as $value){

                //simpan di log
                \DB::table('perusahaan_log_sync')->insert([
                    'perusahaan_id' => $value->id,
                    'id_angka' =>$value->id_angka,
                    'nama_lengkap'  =>$value->nama_lengkap,
                    'nama_singkat' =>$value->nama_singkat,
                    'logo'=> $value->logo,
                    'jenis_perusahaan' =>$value->jenis_perusahaan,
                    'kepemilikan' => $value->kepemilikan,
                    'bidang_usaha' => $value->bidang_usaha,
                    'visi' => $value->visi,
                    'misi' => $value->misi,
                    'url' => $value->url,
                    'induk' => $value->induk,
                    'level' => $value->level,
                    'created_by' => $user_id,
                    'created_at' => $now,
                    'is_active' => $value->is_active,
                ]);


                \DB::table('perusahaan_relasi')
                ->updateOrInsert(
                    ['perusahaan_id' => $value->id],
                    [
                        'perusahaan_id'=> $value->id,
                        'perusahaan_induk_id'=> $value->induk,
                        'tmt_awal' => null,
                        'tmt_akhir' => null,
                    ]
                );

            }
            
        }
        
    }
}
