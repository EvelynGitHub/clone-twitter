<script>
    import Comment from "./Comment.svelte";

    export let tweet = {};

    let comment = "";

    const addComment = () => {
        console.log("Comentário");
    };
</script>

<div class="feed-tweet">
    <div class="tweet-container">
        <div class="tweet-header">
            <p>
                <a href="/#/perfil/{tweet.user_id}">{tweet.user_name}</a>
                <br />
                Criado em {tweet.tweet_create_at}
            </p>
            <form method="post" name="follow">
                <!-- <input type="hidden" name="user_id" value={tweet.user_id} />
                <input type="hidden" name="tweet_id" value={tweet.tweet_id} /> -->
                <input type="submit" value="Seguir" class="btn btn-white" />
            </form>
        </div>
        <div class="tweet-body mt">
            {tweet.tweet_description}
        </div>
        <div class="tweet-footer">
            <form action="" method="post">
                <!-- <input
                    type="hidden"
                    name="tweet_id"
                    value="${tweet.tweet_id}"
                />
                <input
                    type="hidden"
                    name="tweet_token"
                    value={tweet.tweet_id}
                /> -->
                <textarea bind:value={comment} name="comment_description" />
                <input
                    on:click|preventDefault={addComment}
                    type="submit"
                    value="Comentar"
                    class="btn btn-blue"
                />
            </form>
        </div>
    </div>
    <div class="tweet-comment-area">
        <!-- Outro componente -->
        <!-- {#each tweet.comments as comment}
            <Comment {comment} />
        {:else}
            <p class="nota">Não há mais comentários.</p>
        {/each}
        <p><a href="/#/">Ver mais comentários</a></p> -->

        <Comment comments={tweet.comments} />
    </div>
</div>

<style>
    p {
        padding: 0;
        margin: 0;
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
