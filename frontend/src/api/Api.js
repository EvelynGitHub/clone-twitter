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

export const login = async (email, password) => {
    try {
        const response = await SERVICES.post(`/login`, { email, password });
        user.set({ token: response.token })
        return response;
    } catch (error) {
        console.error(error);
    }
}

export const register = async (name, email, password, confirmpassword) => {
    try {
        const response = await SERVICES.post(`/register`, { name, email, password, confirmpassword });
        return response;
    } catch (error) {
        console.error(error);
    }
}