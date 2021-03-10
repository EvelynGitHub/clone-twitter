<script>
    import { push } from "svelte-spa-router";
    import Menu from "../components/Menu.svelte";
    import Body from "../components/Body.svelte";
    import Tweet from "../components/Tweet.svelte";

    import { user } from "../api/Api";

    if (!$user.token) {
        push("/login");
    }
    let json = `{
            "message": "Tweets ",
            "status": 202,
            "token": null,
            "data": [
                {
                    "user_id": 2,
                    "user_name": "Rodrigo",
                    "user_token": "90fda61c1449c30483daf34b9ded64a5635006c0cd422d175522c650bf97485b87737b496587ae4766a460c5824b205af62a",
                    "tweet_id": 3,
                    "tweet_description": "Meu segundo Tweet: 'A vida é bela, você que complica'",
                    "tweet_create_at": "2021-03-06",
                    "comments": []
                },
                {
                    "user_id": 1,
                    "user_name": "Evelyn",
                    "user_token": "1e10849d5658944e02ba149903a4b883ebc811bc237c6e5b420ab30afe775abcbdb88cb9892bbc810d4b9b812efa2425ed0c",
                    "tweet_id": 4,
                    "tweet_description": "Meu primeiro Tweet: 'Muito prazer, sou a Evelyn'",
                    "tweet_create_at": "2021-03-06",
                    "comments": []
                },
                {
                    "user_id": 4,
                    "user_name": "Francisco",
                    "user_token": "aedf081c789474fc078b1b21b8e67ed9db3717f4bc6456c80f2194780bea13e41ae57c94220c5418d1334528699424e4de46",
                    "tweet_id": 5,
                    "tweet_description": "Vou jogar para Mundo ",
                    "tweet_create_at": "2021-03-06",
                    "comments": []
                },
                {
                    "user_id": 2,
                    "user_name": "Rodrigo",
                    "user_token": "90fda61c1449c30483daf34b9ded64a5635006c0cd422d175522c650bf97485b87737b496587ae4766a460c5824b205af62a",
                    "tweet_id": 1,
                    "tweet_description": "Meu primeiro Tweet",
                    "tweet_create_at": "2021-03-01",
                    "comments": [
                        {
                        "comment_id": 3,
                        "comment_remark": "nesse é o primeiro comentário.",
                        "comment_user_id": 2,
                        "comment_user_name": "Rodrigo"
                        }
                    ]
                }
            ]
        }`;

    let response = JSON.parse(json);

    console.log(response);
</script>

<div class="body">
    <Menu />
    <Body>
        <h1>Perfil</h1>

        {#each response.data as tweet}
            <Tweet {tweet} />
        {:else}
            <p>Nenhum Tweet Encontrado</p>
        {/each}

        {#if response.data.length}
            <p><a href="/#/">Ver mais Tweets</a></p>
        {/if}
    </Body>
</div>

<style>
    p,
    a {
        width: 100%;
        text-align: center;
        padding: 1rem 0;
        background-color: #ddd;
    }
</style>
