<script>
    import { setTweet, user } from "../api/Api";

    import { createEventDispatcher } from "svelte";

    const dispatch = createEventDispatcher();

    let tweet = "";
    let message = "";

    const addTweet = async () => {
        const res = await setTweet(tweet, $user.token);

        if (res.data.status >= 400) {
            message = res.data.message;
        } else {
            tweet = "";
            message = res.message;

            dispatch("afterAdd", "Aciona depois que cadastra");
        }

        setTimeout(() => (message = ""), 5000);
    };
</script>

<div class="feed-tweet">
    {#if $user.token}
        <form id="form-comment" method="post">
            <textarea name="tweet_description" rows="5" bind:value={tweet} />
            <input
                on:click|preventDefault={addTweet}
                type="submit"
                value="Publicar"
                class="btn btn-blue btn-border-blue"
            />
        </form>
        <p>{message}</p>
    {:else}
        <p><a href="/#/login">É preciso logar para publicar</a></p>
    {/if}
</div>

<style>
    p {
        margin-top: 0;
        padding-top: 0;
        text-align: center;
        color: rgba(212, 0, 255);
    }

    .feed-tweet {
        width: 90%;
        display: flex;
        flex-direction: column;
    }

    .feed-tweet form {
        /*margin: 0.5rem;  */
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

        margin: 0.5rem; /* */
    }
</style>
