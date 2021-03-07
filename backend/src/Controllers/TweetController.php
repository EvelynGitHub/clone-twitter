<?php

namespace App\Controllers;

use App\Helpers\Helper;
use App\Helpers\HttpStatusCode;
use App\Models\Tweet;
use App\Models\User;
use DateTime;

class TweetController
{
    use Helper;

    private $route;

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function getMyTweets(int $start = 0, int $end = 10)
    {
        $authenticatedUser = (new User())->findByIdAndToken($this->route->inApp->data->id, $this->route->inApp->data->token);

        if ($authenticatedUser) {
            return $this->getAllTweets("my", $authenticatedUser->id, $start, $end);
        }

        return Helper::jsonSend("É preciso estar logado", HttpStatusCode::BAD_REQUEST);
    }

    public function getPersonalTweets(int $start = 0, int $end = 10)
    {
        $authenticatedUser = (new User())->findByIdAndToken($this->route->inApp->data->id, $this->route->inApp->data->token);

        if ($authenticatedUser) {
            return $this->getAllTweets("personal", $authenticatedUser->id, $start, $end);
        }

        return Helper::jsonSend("É preciso estar logar", HttpStatusCode::BAD_REQUEST);
    }

    public function getGlobalTweets(int $start = 0, int $end = 10)
    {

        return $this->getAllTweets("global", 0, $start, $end);
    }

    private function getAllTweets(string $feed = "global", int $userId = 0, int $start = 0, int $end = 10)
    {
        $tweets = (new Tweet())->findAllTweet($feed, $userId, $start, $end);

        return Helper::jsonSend("Tweets ", HttpStatusCode::ACCEPTED, null, $tweets);
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

    public function getComment(array $data)
    {
        $tweet = (new Tweet())->findAllComment($data["tweet_id"], $data["start"], $data["end"]);

        return Helper::jsonSend("Comentários", HttpStatusCode::OK, null, $tweet);
    }

    public function setComment(int $tweetId, array $data)
    {
        $inputs = ["remark"];

        //Verifico se os campos estão preenchidos
        if (Helper::isEmpty($inputs, $data))
            return Helper::jsonSend("Preencha o comentário!", HttpStatusCode::BAD_REQUEST);

        $user = new User();

        $authenticatedUser = $user->findByIdAndToken($this->route->inApp->data->id, $this->route->inApp->data->token);

        if ($authenticatedUser) {
            $tweet = new Tweet();

            $create = $tweet->createComment($data["remark"], $tweetId, $authenticatedUser->id);

            if ($create) return Helper::jsonSend("Comentário feito com sucesso", HttpStatusCode::OK);

            return Helper::jsonSend("Desculpe, tivemos um erro inesperado!", HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

        return Helper::jsonSend("É preciso estar logar para Tweetar!", HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    public function deleteTweet(int $tweetId)
    {
        $authenticatedUser = (new User())->findByIdAndToken($this->route->inApp->data->id, $this->route->inApp->data->token);

        if ($authenticatedUser) {
            $tweet = new Tweet();

            $create = $tweet->destroyTweet($tweetId);

            if ($create) return Helper::jsonSend("Tweet excluído com sucesso", HttpStatusCode::OK);

            return Helper::jsonSend("Desculpe, tivemos um erro inesperado!", HttpStatusCode::INTERNAL_SERVER_ERROR);
        }

        return Helper::jsonSend("É preciso estar logar para apagar um Tweet!", HttpStatusCode::INTERNAL_SERVER_ERROR);
    }
}
