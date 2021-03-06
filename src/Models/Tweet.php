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
            ["remark" => $comment, "user_id" => $userId, "tweet_id" => $tweetId]
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

    public function findAllTweet(int $start = 0, int $end = 10)
    {
        $data = [];

        $crud = $this->select("
                u.id as user_id,
                u.name as user_name,
                u.token as user_token,
                t.id as tweet_id,
                t.description as tweet_description,
                t.create_at as tweet_create_at
            ")
            ->from("users u
                INNER JOIN tweets t ON u.id = t.user_id")
            // ->limit($start, $end)
            ->order("t.create_at", "DESC")
            ->execute("fetchAll");

        for ($i = 0; $i < count($crud); $i++) {
            $comment = $this->findAllComment($crud[$i]["tweet_id"]);

            $crud[$i]["comments"] = $comment ?? [];

            $data[] = $crud[$i];
        }


        return $data;
    }

    public function findAllComment(int $tweetId, int $start = 0, int $end = 10)
    {
        $crud = $this->select("
                    c.id as comment_id,
                    c.remark as comment_remark,
                    u.id as comment_user_id,
                    u.name as comment_user_name
                    ")
            ->from("users u
                    INNER JOIN comments c ON u.id = c.user_id")
            ->where("c.tweet_id = ?", [$tweetId])
            // ->limit($start, $end)
            ->execute("fetchAll");

        // var_dump($crud);
        // var_dump($this->getError());

        return $crud;
    }
}
