<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Traits\ApiTrait;
use Inertia\Inertia;

class TweetController extends Controller
{
    use ApiTrait;

    //Renderiza la pantalla inicial.
    public function home()
    {
        $tweets = Tweet::paginate(20);
        $allTweets = Tweet::all();
        $countTweetsByDate = Tweet::select(\DB::raw('DATE(publish_date) as date'), \DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return Inertia::render("Homes/Home", ['tweets' => $tweets, 'allTweets' => $allTweets, 'dateTweets' => $countTweetsByDate]);
    }

    /* Obtiene los últimos tweets a través de la API */
    public function fetch()
    {

        //get tweets
        $tweets = $this->getTweets();

        //print_r($tweets['statuses']);

        foreach ($tweets['statuses'] as $key => $tweet) {
            
            //Obtiene los hashtags para la nube de etiquetas.
            $hashtags = [];

            foreach ($tweet['entities']['hashtags'] as $hashtag) {
                if(isset($hashtag['text'])){
                    $hashtags[] = $hashtag['text'];
                }
                
            }

            //Conecta con Api de MonkeyLearn para etiquetar el tweet.
            $sentiment = $this->getSentiments($tweet['text']);

            //Guarda en BBDD.
            Tweet::updateOrCreate(
                ['tweet_id' =>  $tweet['id']],
                [
                    'text' => $tweet['text'],
                    'hashtags' => $hashtags,
                    'sentiment' => (isset($sentiment[0]))?$sentiment[0]['classifications'][0]['tag_name']: null,
                    'publish_date' => date("Y-m-d H:i:s", strtotime($tweet['created_at'])),
                ],
            );
        }

        return $this->home();

    }
}
