<script>
    import { pop, replace } from "svelte-spa-router";

    import { login, register, user } from "../api/Api";

    if ($user.token) {
        pop();
    }

    let messageLogin = "";
    let messageRegister = "";

    let loginUser = {
        email: "",
        password: "",
    };

    let registerUser = {
        name: "",
        email: "",
        password: "",
        confirmpassword: "",
    };

    const handlerLogin = async () => {
        const res = await login(loginUser);

        messageLogin = res.data.message;

        if (res.token) {
            messageLogin = res.message;
            console.clear();
            replace("/perfil");
        }
    };
    const handlerRegister = async () => {
        const res = await register(
            registerUser.name,
            registerUser.email,
            registerUser.password,
            registerUser.confirmpassword
        );
        console.log(res);
        messageRegister = res.data.message;

        if (res.token) {
            messageRegister = res.message;
            console.clear();
            replace("/perfil");
        }
    };
</script>

<main class="container">
    <div class="twitter">
        <p>Este é um clone do Twitter</p>
    </div>
    <div class="twitter-login">
        <form on:submit|preventDefault={handlerLogin} method="POST" id="login">
            <div class="row">
                <div class="col">
                    <div class="inputs">
                        <input
                            bind:value={loginUser.email}
                            type="email"
                            class="input-login"
                            placeholder="E-MAIL"
                            name="email"
                        />
                        <input
                            bind:value={loginUser.password}
                            type="password"
                            class="input-login"
                            placeholder="SENHA"
                            name="password"
                        />
                    </div>
                    <a href="/#/">Esqueceu sua senha?</a>
                </div>

                <input
                    type="submit"
                    value="ENTRAR"
                    class="btn btn-blue btn-login"
                />
            </div>

            <p class="message">{messageLogin}</p>
        </form>

        <form
            on:submit|preventDefault={handlerRegister}
            method="POST"
            id="register"
        >
            <h2>CADASTRE-SE</h2>
            <input
                bind:value={registerUser.name}
                type="text"
                class="input-login"
                placeholder="NOME"
                name="name"
            />
            <input
                bind:value={registerUser.email}
                type="email"
                class="input-login"
                placeholder="E-MAIL"
                name="email"
            />
            <input
                bind:value={registerUser.password}
                type="password"
                class="input-login"
                placeholder="SENHA"
                name="password"
            />
            <input
                bind:value={registerUser.confirmpassword}
                type="password"
                class="input-login"
                placeholder="CONFIRMAR SENHA"
                name="confirmpassword"
            />
            <input
                type="submit"
                value="CADASTRAR"
                class="btn btn-blue btn-login"
            />
            <p class="message">{messageRegister}</p>
        </form>
    </div>
</main>

<style>
    .container {
        display: flex;
        flex-direction: row;

        width: 100%;
        height: 100%;
    }

    .container .twitter {
        background-image: url("../img/twitter-logo-azul.svg");
        background-color: #039ff5;

        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: contain;

        text-align: center;
        font-size: 3rem;
        color: #fff;

        display: flex;
        justify-content: center;
        align-items: center;

        width: 50%;
        height: 100%;

        flex: 1;
    }

    .container .twitter-login {
        max-width: 50%;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0;
        overflow-y: scroll;
    }

    .twitter-login #login {
        margin-top: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .twitter-login #login .row {
        justify-content: space-around;
    }

    .twitter-login #login a {
        text-align: right;
        justify-content: flex-end;
    }
    .twitter-login .btn-login {
        border-radius: 10px;
    }

    .twitter-login #register {
        display: flex;
        flex-direction: column;
        padding: 1rem 5%;

        width: min(500px, 90%);
    }
    .row {
        display: flex;
        flex-direction: row;
    }

    .col {
        display: flex;
        flex-direction: column;
    }

    .container input {
        height: 45px;
        margin-bottom: 0.5rem;
        float: left;
        border-radius: 8px;
        border: 1px solid #ccc;
        outline: none;
    }

    @media only screen and (max-width: 1280px) {
        .row {
            flex-direction: column;
            width: 100%;
        }
    }

    @media only screen and (max-width: 1070px) {
        #login {
            width: 90%;
            padding: 1rem 5%;
        }

        .inputs {
            width: 100%;

            display: flex;
            flex-direction: column;
        }
    }

    @media only screen and (max-width: 600px) {
        .container {
            flex-direction: column-reverse;
            width: 100vw;
            justify-content: center;
            align-items: center;
            height: auto;
        }

        .container .twitter {
            width: 100%;

            flex: 1;
        }
        .container .twitter-login {
            width: 100%;
            max-width: 100%;
            padding: 0;
            margin: 0;
            flex: 1;
        }
    }
</style>
