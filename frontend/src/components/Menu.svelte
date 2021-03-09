<script>
    let toggle = false;

    const toggleMenu = () => (toggle = !toggle);
</script>

<header>
    <nav class="tweet-menu">
        <img src="/img/twitter-logo.svg" alt="logo" />
        <a href on:click|preventDefault={toggleMenu} class="btn-toggle">
            {#if toggle}
                &times;
            {:else}
                &equiv;
            {/if}
        </a>
        <ul class={toggle ? "menu" : "hide"}>
            <li><a href="/#/">Feed Global</a></li>
            <li><a href="/#/login">Feed Pessoal</a></li>
            <li><a href="/#/">Perfil</a></li>
            <li><a href="/#/login">Sair</a></li>
        </ul>
    </nav>
</header>

<style>
    header {
        position: relative;
        background-color: #039ff5;
        border-right: 1px solid #ccc;
    }

    .btn-toggle {
        display: none;
    }

    .tweet-menu {
        width: 100%;

        position: sticky;
        top: 0;

        display: flex;
        flex-direction: column;
        align-items: center;

        line-height: 3rem;
    }

    .tweet-menu ul {
        width: 100%;
        padding-inline-start: 0;
        list-style: none;
        padding: 0 3rem;
        box-sizing: border-box;
    }

    .tweet-menu ul li {
        padding-left: 1.5rem;
        margin: 0.5rem 0;
    }

    .tweet-menu ul li a {
        display: block;
        width: 100%;
        font-weight: bold;
        font-size: 18px;
        letter-spacing: 1px;
        text-decoration: none;
        color: #fff;
    }

    .tweet-menu ul li:hover {
        background-color: #0491ddb7;
        border-radius: 1.5rem;
    }

    .tweet-menu img {
        margin: 2rem 0;
    }

    @media only screen and (max-width: 900px) {
        .hide {
            display: none;
            opacity: 0;
            transition: opacity 3s;
        }

        .menu {
            display: block;

            opacity: 1;
            transition: opacity 3s;
        }

        .tweet-menu ul {
            position: absolute;
            top: 3rem;
            background-color: #039ff5;
            transition: height 2s;
        }

        .btn-toggle {
            display: block;
            margin: 0 1rem 0;
            padding: 0;
            font-size: 4rem;
            color: #fff;
            text-decoration: none;
        }

        .tweet-menu img {
            margin: 0 0.5rem;
        }

        .tweet-menu {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    @media only screen and (max-width: 250px) {
        .tweet-menu ul {
            padding: 10px;
        }
    }
</style>
