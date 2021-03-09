<script>
    import Menu from "../components/Menu.svelte";
    import Body from "../components/Body.svelte";
    import Tweet from "../components/Tweet.svelte";

    let response = { data: [] };
</script>

<Menu />
<Body>
    <h1>Home</h1>

    {#each response.data as tweet}
        <Tweet {tweet} />
    {:else}
        <p>Nenhum tweet encontrado.</p>
    {/each}

    <a href="/#/login">Ir para Login</a>
</Body>

<style>
</style>
