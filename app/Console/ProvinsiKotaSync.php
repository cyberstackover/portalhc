<?php

namespace App\Console;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Carbon\Carbon;

class ProvinsiKotaSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apisync:provinsikota';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data provinsi dan kota dari simanis api';

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

        // sync provinsi
        $response = $client->request('GET', env('SIMANIS_HOST') . 'api/provinsi');
        $body = json_decode($response->getBody());
        if($body){
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $data = array();
            foreach ($body->data as $value) {
                $data[] = [
                      'id' => $value->id,
                      'nama' => $value->nama,
                      'is_luar_negeri' => $value->is_luar_negeri,
                      'api_id' => $value->api_id,
                      'created_at' => $now
                    ];
            }
            if(count($data) > 0){
                \DB::table('provinsi')->delete();
                \DB::table('provinsi')->insert($data);
            }
        }
        
        // sync kota
        $response = $client->request('GET', env('SIMANIS_HOST') . 'api/kota');
        $body = json_decode($response->getBody());
        if($body){
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $data = array();
            foreach ($body->data as $value) {
                $data[] = [
                      'id' => $value->id,
                      'nama' => $value->nama,
                      'provinsi_id' => $value->provinsi_id,
                      'api_id' => $value->api_id,
                      'is_luar_negeri' => $value->is_luar_negeri,
                      'created_at' => $now
                    ];
            }
            if(count($data) > 0){
                \DB::table('kota')->delete();
                \DB::table('kota')->insert($data);
            }
        }
    }
}
