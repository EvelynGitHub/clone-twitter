<script>
    import { onMount } from "svelte";
    import Menu from "../components/Menu.svelte";
    import Body from "../components/Body.svelte";
    import Tweet from "../components/Tweet.svelte";

    import {
        getTweetUserPublic,
        getDataUserPublic,
        getFollowUserPublic,
    } from "../api/Api";

    export let params = {};

    $: user = {};
    let tweets = [];
    let follows = [];
    let moreTweets = true;
    let start = 0;
    let end = 2;

    let tweetsOrFollows = true;

    const nextTweets = async () => {
        start = start + 2;

        const res = await getTweetUserPublic(user.id, start, end);

        if (res.data.length) {
            tweets = [...tweets, ...res.data];
        } else {
            moreTweets = false;
        }
    };

    const viewFollows = async () => {
        tweetsOrFollows = false;

        const followsUser = await getFollowUserPublic(params.name);

        console.log(followsUser);
        if (followsUser.error) {
        } else {
            follows = followsUser;
        }
    };

    const viewTweets = async () => {
        tweetsOrFollows = true;
    };

    const viewOtherUser = async (slug) => {
        tweetsOrFollows = true;
        follows = [];
        tweets = [];

        initPerfilUser(slug);
    };

    const initPerfilUser = async (slug) => {
        const data = await getDataUserPublic(slug);
        user = data;

        const tweetsUser = await getTweetUserPublic(user.id, start, end);

        console.log(tweetsUser);
        if (tweetsUser.error) {
        } else {
            tweets = tweetsUser.data;
        }
    };

    onMount(async () => {
        initPerfilUser(params.name);
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
                <p class="info-name">{user.name}</p>

                <p><span>E-mail: </span>{user.email}</p>
                <p><span>Entrou em: </span>{user.create_at}</p>
                <p><span>Bio: </span>{user.bio}</p>
            </div>
            <hr />
            <div class="btn-perfil">
                <input
                    on:click={viewTweets}
                    type="button"
                    value="Tweets"
                    class="btn btn-blue btn-border-blue"
                    id="view-feed"
                />
                <input
                    on:click={viewFollows}
                    type="button"
                    value="Follows"
                    class="btn btn-white btn-border-blue"
                    id="view-follows"
                />
            </div>
            <hr />
        </section>

        {#if tweetsOrFollows}
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
        {/if}
        {#if !tweetsOrFollows}
            <section id="follows">
                {#each follows as follow}
                    <div class="follow-item">
                        <p>
                            <a
                                href="/#/perfil/{follow.slug}"
                                on:click={viewOtherUser(follow.slug)}
                            >
                                {follow.name}
                            </a>
                        </p>

                        <div class="div btn ">
                            <span>Segue desde: {follow.create_at}</span>
                        </div>
                    </div>
                {:else}
                    <p class="info">Não encontrado.</p>
                {/each}
            </section>
        {/if}
    </Body>
</div>

<style>
    :global(.info) {
        width: 100%;
        text-align: center;
        padding: 1rem 0;
        background-color: #ddd;
        cursor: pointer;
    }

    .div {
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 3rem;
        border: 2px solid #039ff5;
        font-weight: 400;
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
    } /* */
</style>
