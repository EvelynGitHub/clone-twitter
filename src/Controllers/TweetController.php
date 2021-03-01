<?php

namespace App\Controllers;

use App\Helpers\Helper;
use App\Helpers\HttpStatusCode;
use App\Models\Tweet;
use App\Models\User;

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
        $inputs = ["description"];

        //Verifico se os campos estão preenchidos
        if (Helper::isEmpty($inputs, $data))
            return Helper::jsonSend("Preencha a descrição!", HttpStatusCode::BAD_REQUEST);

        $user = new User();

        $authenticatedUser = $user->findByIdAndToken($this->route->inApp->data->id, $this->route->inApp->data->token);

        $tweet = new Tweet();

        $create = $tweet->createTweet($data["description"], $authenticatedUser->id);

        if ($create) return Helper::jsonSend("Tweet criado com sucesso", HttpStatusCode::OK);

        return Helper::jsonSend("Desculpe, tivemos um erro inesperado!", HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    public function getComment(int $tweetId)
    {
    }

    public function setComment(array $data)
    {
    }

    public function deleteTweet(int $userId, int $tweetId)
    {
    }
}
