<script>
    import { onMount } from "svelte";

    import Menu from "../components/Menu.svelte";
    import Body from "../components/Body.svelte";
    import Tweet from "../components/Tweet.svelte";
    import AddTweet from "../components/AddTweet.svelte";
    import { getTweets } from "../api/Api";

    let tweets = [];
    let moreTweets = true;
    let limit = 2;
    let start = 0;
    let end = limit;

    const nextTweets = async () => {
        start = end;
        end = end + limit;

        const res = await getTweets(start, end);
        if (res.data.length) {
            tweets = [...tweets, ...res.data];
        } else {
            moreTweets = false;
        }
    };

    const addTweet = async (e) => {
        console.log(e.detail);

        start = 0;
        end = limit;

        const res = await getTweets(start, end);
        if (res.data.length) {
            tweets = res.data;
        } else {
            moreTweets = false;
        }
    };

    onMount(async () => {
        const res = await getTweets(start, end);
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
        <h1>Home</h1>

        <AddTweet on:afterAdd={addTweet} />

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
</style>
