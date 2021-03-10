import SERVICES from "./Service";
import { writable } from 'svelte/store'

export const user = writable({ token: false })

export const getTweets = async (start, end) => {
    try {
        const response = await SERVICES.get(`/tweet/${start}/${end}`);
        return response;
    } catch (error) {
        console.error(error);
    }
}

export const login = async (login) => {
    try {
        const data = new FormData();
        data.append('password', login.password);
        data.append('email', login.email);

        const response = await SERVICES.post(`/login`, data);

        user.set({ token: response.token })

        return response;
    } catch (error) {

        return error;
    }
}

export const register = async (name, email, password, confirmpassword) => {
    try {
        const data = new FormData();
        data.append('password', password);
        data.append('email', email);
        data.append('name', name);
        data.append('confirmpassword', confirmpassword);

        const response = await SERVICES.post(`/register`, data);

        user.set({ token: response.token })
        return response;
    } catch (error) {

        return error;
    }
}

export const getTweetsMyFeed = async (start, end, token) => {
    try {
        const response = await SERVICES.get(`/tweet/my/${start}/${end}`, {}, token);
        return response;
    } catch (error) {
        console.error(error);
    }
}

export const getMyTweets = async (start, end, token) => {
    try {
        const response = await SERVICES.get(`/perfil/tweet/${start}/${end}`, {}, token);
        return response;
    } catch (error) {
        console.error(error);

        return error;
    }
}
