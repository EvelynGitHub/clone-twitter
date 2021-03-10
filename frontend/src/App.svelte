<script>
	import Router, { link } from "svelte-spa-router";
	import Home from "./pages/Home.svelte";
	import MyFeed from "./pages/MyFeed.svelte";
	import Perfil from "./pages/Perfil.svelte";
	import Authentication from "./pages/Authentication.svelte";
</script>

<Router
	routes={{
		"/": Home,
		"/my": MyFeed,
		"/login": Authentication,
		"/perfil": Perfil,
	}}
/>

<style>
	:global(body) {
		padding: 0;
	}
	:global(.body) {
		display: grid;
		grid-template-rows: 1fr;
		grid-template-columns: 300px 1fr;
		grid-column-gap: 1rem;
		margin: 0 auto;
		padding: 0;

		background: rgba(238, 238, 238, 0.5);
		height: 100%;
	}

	:global(.mt) {
		margin-top: 1rem;
	}

	:global(.btn) {
		height: 45px;
		padding: 0 1rem;

		border-style: none;
		border-radius: 25px;

		font-size: 1rem;
		font-weight: 800;
		text-align: center;
		outline: none;

		transition: 0.3s;
	}

	/* #message {
		margin-top: 2rem;
		color: rgba(252, 238, 213, 0.89);
		line-height: normal;
		text-align: center;
		font-weight: 800;
	} */

	:global(.btn-blue) {
		color: #fff;
		background-color: #039ff5;
		border: 2px solid #fff;
	}

	:global(.btn-blue:hover) {
		color: #039ff5;
		background-color: #fff;
	}

	:global(.btn-white) {
		color: #039ff5;
		background-color: #fff;
		border: 2px solid #039ff5;
	}

	:global(.btn-white:hover) {
		color: #fff;
		background-color: #039ff5;
	}

	:global(.btn-border-blue) {
		border: 2px solid #039ff5;
	}

	:global(.btn-border-white:hover) {
		border: 2px solid #fff;
	}

	:global(.btn-border-white) {
		border: 2px solid #039ff5;
		border-radius: 1.5rem;
		padding: 0 1rem;
		margin: 0.2rem;
		transition: 0.2s;
	}

	@media only screen and (max-width: 900px) {
		:global(.body) {
			display: grid;
			grid-template-rows: 3.5rem 1fr;
			grid-template-columns: 1fr;
		}
	}
</style>
