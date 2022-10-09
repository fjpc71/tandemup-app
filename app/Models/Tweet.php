<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $fillable = ["text", "publish_date", "tweet_id", "hashtags"];
    public $timestamps = false;
    use HasFactory;

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    protected $casts = [
        'hashtags' => 'array'
    ];
}
