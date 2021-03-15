<script>
    import { onMount } from "svelte";
    import { push } from "svelte-spa-router";
    import Menu from "../components/Menu.svelte";
    import Body from "../components/Body.svelte";
    import Tweet from "../components/Tweet.svelte";

    import {
        getFollow,
        getDataUser,
        user,
        getTweets,
        update,
        deleteUser,
        deleteFollow,
    } from "../api/Api";

    if (!$user.token) {
        push("/login");
    }

    $: userData = {};
    let userUpdate = { name: "", bio: "", slug: "", email: "", password: "" };
    let tweets = [];
    let follows = [];
    let moreTweets = true;
    let start = 0;
    let end = 2;
    $: edit = false;

    let tweetsOrFollows = true;

    const nextTweets = async () => {
        start = start + 2;

        const res = await getTweets(start, end, $user.token);

        if (res.data.length) {
            tweets = [...tweets, ...res.data];
        } else {
            moreTweets = false;
        }
    };

    const viewFollows = async () => {
        tweetsOrFollows = false;

        const followsUser = await getFollow($user.token);

        console.log(followsUser);
        if (followsUser.data) {
            alert(followsUser.data.message);
        } else {
            follows = followsUser;
        }
    };

    const removeFollow = async (idFollow) => {
        tweetsOrFollows = true;

        console.log("ID:", idFollow);

        const followsUser = await deleteFollow(idFollow, $user.token);

        console.log(followsUser);
        if (followsUser.data.message) {
            alert(followsUser.data.message);
        } else {
            alert(followsUser.message);
        }
    };

    const viewTweets = async () => {
        tweetsOrFollows = true;
    };

    const viewOtherUser = async () => {
        tweetsOrFollows = true;
        follows = [];
        tweets = [];

        initPerfilUser();
    };

    const initPerfilUser = async () => {
        const data = await getDataUser($user.token);
        userData = data;

        const tweetsUser = await getTweets(start, end, $user.token);

        if (tweetsUser.error) {
        } else {
            tweets = tweetsUser.data;
        }
    };

    const btnEditar = () => {
        console.log("Editar");
        userUpdate = userData;
        edit = true;
    };

    const btnCancelar = () => {
        console.log("Cancelar");
        userUpdate = { name: "", bio: "", slug: "", email: "", password: "" };
        edit = false;
    };

    const btnSalvar = async () => {
        console.log("Salvar");

        const res = await update(userUpdate, $user.token);

        console.log(res);

        if (res.data.message) {
            alert(res.data.message);
        } else {
            alert(res.message);
        }

        btnCancelar();
    };

    const btnDeletar = async () => {
        console.log("Deletar");
        let conf = confirm("Deseja realmente EXCLUIR sua conta?");

        if (conf) {
            const res = await deleteUser($user.token);

            console.log(res);

            user.set({ token: false });

            alert("Até a próxima vez.");

            push("/login");
        }
    };

    onMount(async () => {
        initPerfilUser();
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

            {#if edit}
                <div class="perfil-info edit">
                    <form on:submit|preventDefault={btnSalvar}>
                        <label for="">Nome:</label>
                        <input
                            type="text"
                            bind:value={userUpdate.name}
                            placeholder="Seu nome"
                        />

                        <label for="">Email:</label>
                        <input
                            type="text"
                            bind:value={userUpdate.email}
                            placeholder="Seu email"
                        />
                        <label for="">Nome de usuário:</label>
                        <input
                            type="text"
                            bind:value={userUpdate.slug}
                            placeholder="Seu nome de usuário"
                        />
                        <label for="">Bio:</label>
                        <input
                            type="text"
                            bind:value={userUpdate.bio}
                            placeholder="Breve biografia"
                        />
                        <label for="">Senha:</label>
                        <input
                            type="text"
                            bind:value={userUpdate.password}
                            placeholder="Nova senha?"
                        />
                        <br />
                        <button type="submit" class="btn btn-white"
                            >Salvar</button
                        >
                        <button on:click={btnCancelar} class="btn btn-blue"
                            >Cancelar</button
                        >
                    </form>
                </div>
            {:else}
                <div class="perfil-info">
                    <p class="info-name">
                        {userData.name}
                        <button class="btn-edit" on:click={btnEditar}>
                            Editar
                        </button>
                        <button class="btn-delete" on:click={btnDeletar}>
                            Excluir Conta
                        </button>
                    </p>

                    <p><span>E-mail: </span>{userData.email}</p>
                    <p><span>Entrou em: </span>{userData.create_at}</p>
                    <p><span>Bio: </span>{userData.bio}</p>
                </div>
            {/if}

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
                    <Tweet {tweet} myPerfil={true} />
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
                        <div>
                            <p>
                                <a
                                    href="/#/perfil/{follow.slug}"
                                    on:click={viewOtherUser}
                                >
                                    {follow.name}
                                </a>
                            </p>
                            <span>Segue desde: {follow.create_at}</span>
                        </div>

                        <button
                            on:click={removeFollow(follow.user_id)}
                            class="div btn btn-white">Deixar de Seguir</button
                        >
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
        margin: 0.5rem 0;
    } /* */

    .edit {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .edit form {
        display: flex;
        flex-direction: column;
        max-width: 450px;
        width: 100%;
        margin: 1rem auto;
    }
    .edit input {
        max-width: 450px;
        width: 100%;
        border-radius: 5px;
        margin-bottom: 0.5rem;
    }

    .btn-edit {
        border: 2px solid #039ff5;
        border-radius: 10px;
        font-size: small;
        color: #037cbd;
        max-height: 1.5rem;
        margin: 0.5rem 1rem;
    }
    .btn-delete {
        border: 2px solid red;
        border-radius: 10px;
        font-size: small;
        color: #b30303;
        max-height: 1.5rem;
        margin: 0.5rem 1rem;
    }
</style>
