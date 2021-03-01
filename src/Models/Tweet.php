<?php

namespace App\Models;

use App\SimpleCrudPhp\Crud;

class Tweet extends Crud
{

    public function createTweet(string $description, int $userId)
    {
        //$tweetToken ainda não criado no banco, mas preparado aqui para quando criar
        $crud = $this->insert(
            "tweets",
            ["description" => $description, "user_id" => $userId]
        )->execute();

        return $crud;
    }

    public function createComment(string $comment, int $tweetId, int $userId)
    {
        $crud = $this->insert(
            "comments",
            [$comment, $userId, $tweetId],
            "remark, users_id, tweets_id"
        )->execute();

        return $crud;
    }

    public function destroyTweet(int $id)
    {
        $comments = $this->delete()->from("comments")->where("tweets_id = ?", [$id])->execute();

        if ($comments) {
            $crud = $this->delete()->from("tweets")->where("id = ?", [$id])->execute();
            return $crud;
        }

        return false;
    }
}
