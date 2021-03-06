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
        $comments = $this->delete()->from("comments")->where("tweet_id = ?", [$id])->execute();

        if ($comments) {
            $crud = $this->delete()->from("tweets")->where("id = ?", [$id])->execute();
            return $crud;
        }

        return false;
    }

    public function findAllTweet(string $feed, int $id = 0, int $start = 0, int $end = 10)
    {
        $data = [];

        $crud = $this->select("DISTINCT
                u.id as user_id,
                u.name as user_name,
                u.slug as user_slug,
                u.token as user_token,
                t.id as tweet_id,
                t.description as tweet_description,
                t.create_at as tweet_create_at
            ");

        if ($feed == "global") {
            $crud = $crud
                ->from("users u
                INNER JOIN tweets t ON u.id = t.user_id");
        } else if ($feed == "personal") {
            $crud = $crud
                ->from("users u 
                INNER JOIN tweets t ON  u.id = t.user_id
                LEFT JOIN follows f ON u.id = f.user_id")
                ->where("f.user_id_followers = ? OR u.id = ?", [$id, $id]);
        } else if ($feed == "my") {
            $crud = $crud
                ->from("users u 
                INNER JOIN tweets t ON  u.id = t.user_id")
                ->where("u.id = ?", [$id]);
        }

        $crud = $crud->order("t.create_at", "DESC")
            ->limit($end, $start)
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
                    u.name as comment_user_name,
                    u.slug as comment_user_slug
                    ")
            ->from("users u
                    INNER JOIN comments c ON u.id = c.user_id")
            ->where("c.tweet_id = ?", [$tweetId])
            ->order("c.create_at", "ASC")
            ->limit($end, $start)
            ->execute("fetchAll");

        return $crud;
    }
}
