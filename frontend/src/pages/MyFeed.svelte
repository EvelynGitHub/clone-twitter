<script>
    import { onMount } from "svelte";
    import { push } from "svelte-spa-router";
    import Menu from "../components/Menu.svelte";
    import Body from "../components/Body.svelte";
    import Tweet from "../components/Tweet.svelte";

    import { getTweetsMyFeed, user } from "../api/Api";

    if (!$user.token) {
        push("/login");
    }

    let tweets = [];
    let moreTweets = true;
    let start = 0;
    let end = 2;

    const nextTweets = async () => {
        start = end;
        end = end + 2;

        const res = await getTweetsMyFeed(start, end, $user.token);
        if (res.data.length) {
            tweets = [...tweets, ...res.data];
        } else {
            moreTweets = false;
        }
    };

    onMount(async () => {
        const res = await getTweetsMyFeed(start, end, $user.token);
        if (res.data.length) {
            tweets = res.data;
        } else {
            moreTweets = false;
        }
    });
</script>

<div class="body">
    <Menu />
    <Body>
        <h1>Meu Feed Pessoal</h1>

        {#each tweets as tweet}
            <Tweet {tweet} />
        {:else}
            <p>Nenhum tweet encontrado.</p>
        {/each}

        {#if moreTweets}
            <p on:click={nextTweets}>Ver mais Tweets</p>
        {:else}
            <p>Nenhum tweet encontrado.</p>
        {/if}
    </Body>
</div>

<style>
    p {
        width: 100%;
        text-align: center;
        padding: 1rem 0;
        background-color: #ddd;
    }
</style>
