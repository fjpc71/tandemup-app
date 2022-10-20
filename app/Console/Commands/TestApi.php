<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class TestApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testea Api de MonkeyLearn';

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
     * @return int
     */
    public function handle()
    {
        $curl = curl_init();

        $tweet = "RT @Stefmisano: In attesa del nuovo appuntamento con #farinecaputo, rivediamo questa preparazione fatta con #jessyselassie e Davide Civitie…";

        curl_setopt_array($curl,
            array(
            CURLOPT_URL =>Config::get('services.monkeylearn.url'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "data": ["'.$tweet.'"]
            }',
            CURLOPT_HTTPHEADER => array(
            'Authorization: Token '.Config::get('services.monkeylearn.key'),
            'Content-Type: application/json'
            ),
            ));
        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);

        print_r($data[0]['classifications'][0]['tag_name']);

        return 0;
    }
}
