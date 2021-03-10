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
            <div>
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
                <br />
                <a href="/#/">Esqueceu sua senha?</a>
            </div>
            <input type="submit" value="ENTRAR" class="btn-login" />

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
            <input type="submit" value="CADASTRAR" class="btn-login" />
            <p class="message">{messageRegister}</p>
        </form>
    </div>
</main>

<style>
    .container {
        display: grid;
        grid-template-columns: 1fr 1fr;

        height: 100vh;
        /* overflow: hidden; */
    }

    .container input {
        height: 45px;
        margin-bottom: 5px;
        float: left;
        border-radius: 8px;
        border: 1px solid #ccc;
        outline: none;
        padding: 0 0.8rem;
        margin: auto 0.5rem;

        font-size: 1rem;
    }

    .container input:focus {
        border-radius: 8px;
        border: 1px solid #039ff5;
        outline: none;
    }

    .container #login .btn-login {
        margin: 0 0.5rem;
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
    }

    .container .twitter-login {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* .container .twitter-login #login {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
    } */

    .container .twitter-login div {
        text-align: right;
    }

    .container .twitter-login a {
        align-self: flex-end;
        text-decoration: none;
        color: #999;
    }

    .btn-login {
        height: 45px;
        padding: 0 1rem;

        border-style: none;
        border-radius: 23px;

        outline: none;

        transition: 0.3s;

        color: #fff;
        background-color: #039ff5;
        border: 2px solid #fff;
    }

    .btn-login:hover {
        background-color: #0284ca;
    }

    /* .container .twitter-login #register {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 10vh;
        width: 50%;
        align-self: center;
    } */

    .container .twitter-login #register h2 {
        margin-bottom: 2rem;
        margin-top: 2rem;
        color: #333;
    }

    .container .twitter-login #register input[type="text"],
    .container .twitter-login #register input[type="email"],
    .container .twitter-login #register input[type="password"] {
        margin-bottom: 1rem;
        color: #333;
        width: 100%;
    }

    .container .twitter-login #register input[type="submit"] {
        justify-content: flex-start;
        align-self: start;
        margin-left: 0;
        width: auto;
    }

    @media only screen and (max-width: 1000px) {
        .container {
            grid-template-columns: 2fr 3fr;
            overflow-x: auto;
        }

        .twitter-login #login,
        .twitter-login #login div {
            flex-direction: column;
            width: 100%;
            margin: 0.5rem;
        }

        .container .twitter-login div a {
            margin-right: 3rem;
            display: inline-block;
        }

        .container .twitter-login input {
            margin: 0.5rem auto;
        }

        .container input {
            width: 90%;
        }

        .container .twitter-login #register {
            margin: 0.5rem auto;
            width: 90%;
        }
    }

    @media only screen and (max-width: 750px) {
        .container {
            grid-template-columns: 1fr;
            grid-template-rows: 1fr 300px;
            grid-template-areas:
                "login"
                "logo ";
        }

        /**/
        .twitter-login {
            grid-area: login;
        }

        .twitter {
            grid-area: logo;
        }

        .twitter-login #login {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin: 0 auto;
        }

        .twitter-login #login div {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
            width: 100%;
        }

        .twitter-login #login div a {
            display: inline-block;
            margin-bottom: 1rem;
        }
    }
</style>
