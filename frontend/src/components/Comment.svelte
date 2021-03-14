<script>
    import { createEventDispatcher } from "svelte";

    const dispatcher = createEventDispatcher();

    const nextComments = () => {
        dispatcher("nextComments");
    };

    export let comments = [{}];
</script>

{#each comments as comment}
    <div class="tweet-comment">
        <p>
            <a href="/#/perfil/{comment.comment_user_slug}">
                {comment.comment_user_name}
            </a>
        </p>
        <p>{comment.comment_remark}</p>
    </div>
{:else}
    <p class="nota">Não há outros comentários.</p>
{/each}

<style>
    a,
    p {
        margin: 0;
        padding: 0;
    }
    .nota {
        text-align: center;
        color: rgb(0, 100, 200);
        padding: 0.5rem 0;
        border-top: 1px solid #ccc;
    }

    .tweet-comment {
        margin: 1rem;
        border: 1px solid #ccc;
    }

    .tweet-comment a {
        text-decoration: none;
        color: black;
        font-size: 1rem;
        font-weight: 700;
        padding-right: 2rem;
    }

    .tweet-comment p:first-child {
        color: #999;
        font-size: 1rem;
        font-weight: 500;
        justify-content: space-between;
        border-bottom: 1px solid #ccc;
        padding: 0.5rem;
    }

    .tweet-comment p {
        color: #222;
        font-size: 1rem;
        padding: 0.5rem;
    }
</style>
