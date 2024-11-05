<?php

namespace App\Console;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Carbon\Carbon;

class LembagaSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apisync:lembaga';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data lembaga dari simanis api';

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

        $response = $client->request('GET', env('SIMANIS_HOST') . 'api/lembaga');
        $body = json_decode($response->getBody());
        if($body){
            $now = Carbon::now()->format('Y-m-d H:i:s');
            $data = array();
            foreach ($body->data as $value) {
                $data[] = [
                      'id' => $value->id,
                      'nama' => $value->nama,
                      'created_at' => $now
                    ];
            }
            if(count($data) > 0){
                \DB::table('kementerian_lain')->delete();
                \DB::table('kementerian_lain')->insert($data);
            }
        }
    }
}
