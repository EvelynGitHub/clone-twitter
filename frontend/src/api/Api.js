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

export const setTweet = async (description, token) => {
    try {
        const data = new FormData();
        data.append('description', description);

        const response = await SERVICES.post(`/tweet`, data, token);
        return response;
    } catch (error) {

        return error;
    }
}

export const setComment = async (id, remark, token) => {
    try {
        const data = new FormData();
        data.append('remark', remark);

        const response = await SERVICES.post(`/comment/${id}`, data, token);
        return response;
    } catch (error) {

        return error;
    }
}

export const getComment = async (id, start, end, token) => {
    try {
        const data = new FormData();
        data.append('tweet_id', id);
        data.append('start', start);
        data.append('end', end);

        const response = await SERVICES.get(`/comment`, data, token);
        return response;
    } catch (error) {

        return error;
    }
}
