<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Models\Tweet;

use Inertia\Inertia;

class TweetController extends Controller
{
    //Renderiza la pantalla inicial.
    public function home()
    {
        $tweets = Tweet::paginate(20);

        return Inertia::render("Homes/Home", ['tweets' => $tweets]);
    }

    public function fetch()
    {

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
        
        $tweets = json_decode($response, true);

        //print_r($tweets['statuses']);

        foreach ($tweets['statuses'] as $key => $tweet) {
            
            //Obtiene los hashtags para la nube de etiquetas.
            $hashtags = [];

            foreach ($tweet['entities']['hashtags'] as $hashtag) {
                if(isset($hashtag['text'])){
                    $hashtags[] = $hashtag['text'];
                }
                
            }

            //Guarda en BBDD.
            Tweet::updateOrCreate(
                ['tweet_id' =>  $tweet['id']],
                [
                    'text' => $tweet['text'],
                    'hashtags' => $hashtags,
                    'publish_date' => date("Y-m-d H:i:s", strtotime($tweet['created_at'])),
                ],
            );
        }

        return $this->home();

    }
}
