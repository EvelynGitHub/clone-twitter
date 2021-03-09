<script>
	import Router, { link } from "svelte-spa-router";
	import Home from "./pages/Home.svelte";
	import Authentication from "./pages/Authentication.svelte";
</script>

<Router
	routes={{
		"/": Home,
		"/login": Authentication,
	}}
/>

<style>
	:global(body) {
		display: grid;
		grid-template-rows: 1fr;
		grid-template-columns: 300px 1fr;
		grid-column-gap: 1rem;
		margin: 0 auto;
		padding: 0;
	}

	@media only screen and (max-width: 900px) {
		:global(body) {
			display: grid;
			grid-template-rows: 3.5rem 1fr;
			grid-template-columns: 1fr;
		}
	}
</style>
