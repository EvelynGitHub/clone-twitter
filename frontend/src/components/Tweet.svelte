<script>
    import Comment from "./Comment.svelte";
    import { onDestroy } from "svelte";
    import { push } from "svelte-spa-router";
    import {
        setComment,
        getComments,
        setFollow,
        deleteTweet,
        user,
    } from "../api/Api";

    export let tweet = {};
    export let myPerfil = false;

    let comment = "";
    let message = "";
    let limit = 10;
    let start = 0;
    let end = limit;
    let moreComments = true;
    let visible = "";

    const addComment = async () => {
        console.log("Comentário");

        const res = await setComment(tweet.tweet_id, comment, $user.token);

        if (res.data.message) {
            message = res.data.message;
        } else {
            message = res.message;
            comment = "";

            const comments = await getComments(tweet.tweet_id, start, end);
            tweet.comments = comments.data;
        }
    };

    const listComments = async () => {
        start = end;
        end = end + limit;

        const comments = await getComments(tweet.tweet_id, start, end);
        if (comments.data.length > 0) {
            tweet.comments = [...tweet.comments, ...comments.data];
        } else {
            moreComments = false;
        }

        console.log(comments);
    };

    const toFollowUser = async () => {
        if (!$user.token) {
            push("/login");
            return;
        }
        visible = "hidden";
        const res = await setFollow(tweet.user_id, $user.token);

        if (res.data.message) {
            message = res.data.message;
        } else {
            message = res.message;
        }
    };

    const removeTweet = async () => {
        if (!$user.token) {
            push("/login");
            return;
        }

        const res = await deleteTweet(tweet.tweet_id, $user.token);

        if (res.data.message) {
            alert(res.data.message);
        } else {
            alert(res.message);
        }
    };
</script>

<div class="feed-tweet">
    <div class="tweet-container">
        <div class="tweet-header">
            <p>
                <a href="/#/perfil/{tweet.user_slug}">{tweet.user_name}</a>
                <br />
                Criado em {tweet.tweet_create_at}
            </p>
            {#if myPerfil}
                <form
                    method="post"
                    name="follow"
                    on:submit|preventDefault={removeTweet}
                >
                    <input
                        type="submit"
                        value="Deletar"
                        class="btn btn-white"
                    />
                </form>
            {:else}
                <form
                    class={visible}
                    method="post"
                    name="follow"
                    on:submit|preventDefault={toFollowUser}
                >
                    <input type="submit" value="Seguir" class="btn btn-white" />
                </form>
            {/if}
        </div>
        <div class="tweet-body mt">
            {tweet.tweet_description}
        </div>
        {#if $user.token}
            <div class="tweet-footer">
                <form action="" method="post">
                    <textarea bind:value={comment} name="comment_description" />
                    <input
                        on:click|preventDefault={addComment}
                        type="submit"
                        value="Comentar"
                        class="btn btn-blue"
                    />
                </form>
                <p>{message}</p>
            </div>
        {/if}
    </div>
    <div class="tweet-comment-area">
        <Comment comments={tweet.comments} />

        {#if moreComments && tweet.comments.length > 0}
            <p class="nota">
                <a on:click|preventDefault={listComments} href="/#/">
                    Ver mais comentários
                </a>
            </p>
        {/if}
    </div>
</div>

<style>
    p {
        padding: 0;
        margin: 0;
    }
    .feed-tweet .hidden {
        display: none;
    }
    .nota {
        text-align: center;
        color: rgb(0, 100, 200);
        padding: 0.5rem 0;
        border-top: 1px solid #ccc;
    }
    .feed-tweet {
        margin-bottom: 2rem;
        width: 90%;
        border: 2px solid #ccc;
    }

    .feed-tweet .tweet-container {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-bottom: 1px solid #ccc;
    }

    .feed-tweet .tweet-container .tweet-header {
        border-bottom: 1px solid #ccc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem;
        color: #999;
    }

    .feed-tweet .tweet-container .tweet-header a {
        text-decoration: none;
        color: black;
        font-size: 1.3rem;
        font-weight: bold;
    }

    .feed-tweet .tweet-container .tweet-body {
        text-decoration: none;
        color: #222;
        font-size: 1rem;
        padding: 0 0.5rem 0.8rem;
        line-height: 1.5rem;

        text-align: justify;
        text-overflow: ellipsis;
        white-space: pre-line;
        /* max-height: 300px;
    overflow: hidden;*/
    }

    .feed-tweet .tweet-container .tweet-footer {
        border-top: 1px solid #ccc;
        padding: 0.5rem;
    }

    .feed-tweet form {
        /* margin: 0.5rem; */
        display: flex;
        align-items: center;
    }

    .feed-tweet form textarea {
        flex: 1;
        color: #222;
        font-size: 1rem;
        /* padding: 0.5rem; */
        line-height: 1.5rem;

        text-align: justify;
        text-overflow: ellipsis;
        white-space: pre-line;

        border-radius: 10px;
        outline: none;

        /* margin: 0.5rem; */
    }
</style>
