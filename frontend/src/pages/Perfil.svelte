<script>
    import { onMount } from "svelte";
    import { push } from "svelte-spa-router";
    import Menu from "../components/Menu.svelte";
    import Body from "../components/Body.svelte";
    import Tweet from "../components/Tweet.svelte";

    import { getMyTweets, user } from "../api/Api";

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

        const res = await getMyTweets(start, end, $user.token);
        if (res.data.length) {
            tweets = [...tweets, ...res.data];
        } else {
            moreTweets = false;
        }
    };

    onMount(async () => {
        const res = await getMyTweets(start, end, $user.token);

        console.log(res);

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
        <section id="perfil">
            <div class="perfil-capa">
                <div
                    style="background-image: url('/img/fundo.jpg');"
                    class="perfil-capa-fundo"
                >
                    <h1>Dados perfil</h1>
                </div>

                <div class="perfil-capa-user">
                    <img
                        src="/img/user.png"
                        class="perfil-capa-fundo"
                        alt="Capa de fundo"
                    />
                </div>
            </div>

            <div class="perfil-info">
                <p class="info-name">Nome</p>

                <p><span>E-mail: </span>email</p>
                <p><span>Entrou em: </span>00/00</p>
                <p><span>Bio: </span>asd</p>
            </div>
            <hr />
            <div class="btn-perfil">
                <input
                    type="button"
                    value="Tweets"
                    class="btn btn-blue btn-border-blue"
                    id="view-feed"
                />
                <input
                    type="button"
                    value="Follows"
                    class="btn btn-white btn-border-blue"
                    id="view-follows"
                />
            </div>
            <hr />
        </section>

        <section id="feed">
            {#each tweets as tweet}
                <Tweet {tweet} />
            {:else}
                <p class="info">Nenhum tweet encontrado.</p>
            {/each}

            {#if moreTweets}
                <p class="info" on:click={nextTweets}>Ver mais Tweets</p>
            {:else}
                <p class="info">Nenhum tweet encontrado.</p>
            {/if}
        </section>
        <section id="follows" hidden />
    </Body>
</div>

<style>
    .info {
        width: 100%;
        text-align: center;
        padding: 1rem 0;
        background-color: #ddd;
    }

    #feed {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    #perfil {
        margin: 1rem 0;
        width: 90%;
    }

    #perfil .perfil-capa {
        position: relative;
        max-height: 50%;
        margin-bottom: 50px;
    }

    #perfil .perfil-capa .perfil-capa-fundo {
        background-position: center;
        height: 50%;
        height: 150px;
        color: #fff;
        text-align: center;

        display: flex;
        align-items: center;
    }

    #perfil .perfil-capa .perfil-capa-user {
        position: absolute;
        height: 100px;
        bottom: -50px;

        width: 100%;
        display: inline-flex;
        align-items: baseline;
        justify-content: space-between;
    }

    #perfil .perfil-capa .perfil-capa-user img {
        height: 100%;
        width: 100px;
        border-radius: 50px;
        border: 3px solid #fff;
        margin: 0 1rem;
    }

    #perfil .btn-perfil {
        margin: 1rem 0;
        display: flex;
        justify-content: space-around;
    }

    #perfil .btn-perfil .btn {
        margin: 0 1rem;
        flex: 1;
    }

    #perfil .perfil-info .info-name {
        font-size: 1.5rem;
        font-weight: bold;
        color: #000;
    }

    #perfil .perfil-info p {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        color: #000;
    }

    #perfil .perfil-info p span {
        font-size: 1.1rem;
        color: #666;
    }

    #follows {
        width: 90%;
    }
    /* 
    #follows .follow-item {
        width: 100%;

        display: flex;
        justify-content: space-between;
        align-items: center;

        margin: 1rem 0;
        padding: 1rem 0;
        border-bottom: 2px solid #cccccc;
    }

    #follows .follow-item p,
    #follows .follow-item a {
        font-size: 1.5rem;
        color: black;
        font-weight: bold;
        font-style: normal;
        text-decoration: none;
    } */
</style>
