<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

use App\Models\Lang;

trait ApiTrait {


    public function getTweets() {

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => Config::get('services.twitter.url'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.Config::get('services.twitter.key'),
            'Cookie: guest_id=v1%3A166508467021231199'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return json_decode($response, true);

    }

    public function getSentiments($tweet)
    {
        $curl = curl_init();

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

            return json_decode($response, true);
    }

}