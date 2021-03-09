<main class="main-container">
    <slot />
</main>

<style>
    .main-container {
        max-width: 570px;
        width: min(570px, 100%);

        display: flex;
        flex-direction: column;
        align-items: center;

        background-color: #fff;

        /* background-color: rgba(51, 51, 51, 0.219); */
    }

    @media only screen and (max-width: 900px) {
        .main-container {
            width: 95%;
            margin: 2px auto;
        }
    }
</style>
