<?php

namespace App\Controllers;

use App\Helpers\Helper;

class TweetController
{
    use Helper;

    private $route;

    public function __construct($route)
    {
        $this->route = $route;
    }


    public function getTweet()
    {
    }

    public function setTweet(array $data)
    {
    }

    public function getComment()
    {
    }

    public function setComment(array $data)
    {
    }

    public function deleteTweet(int $userId, int $tweetId)
    {
    }
}
