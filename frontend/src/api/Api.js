import SERVICES from "./Service";
import { writable } from 'svelte/store'

export const user = writable({ token: false })

export const getTweets = async (start, end, token) => {
    try {
        const response = await SERVICES.get(`/perfil/tweet/${start}/${end}`, {}, token);
        return response;
    } catch (error) {
        console.error(error);
    }
}

export const getAllTweets = async (start, end) => {
    try {
        const response = await SERVICES.get(`/tweet/${start}/${end}`, {});
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

export const update = async (obj, token) => {
    try {
        let form = "";

        if (obj.password == "" || obj.password == null || obj.password == undefined) {
            obj.password = "";
        }

        form = `name=${obj.name}&email=${obj.email}&bio=${obj.bio}&slug=${obj.slug}&password=${obj.password}`;

        const response = await SERVICES.put(`/perfil`, form, token);
        return response;
    } catch (error) {

        return error;
    }
}

export const deleteUser = async (token) => {
    try {
        const response = await SERVICES.delete(`/perfil`, {}, token);
        return response;
    } catch (error) {

        return error;
    }
}

export const getDataUser = async (token) => {
    try {
        const response = await SERVICES.get(`/perfil`, {}, token);
        return response;
    } catch (error) {
        console.error(error);

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

export const deleteTweet = async (id, token) => {
    try {
        const response = await SERVICES.delete(`/tweet/${id}`, {}, token);
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

export const getFollow = async (token) => {
    try {
        const response = await SERVICES.get(`/perfil/follow`, {}, token);
        return response;
    } catch (error) {

        return error;
    }
}

export const setFollow = async (id, token) => {
    try {
        const response = await SERVICES.post(`/follow/${id}`, {}, token);
        return response;
    } catch (error) {

        return error;
    }
}

export const deleteFollow = async (id, token) => {
    try {
        const response = await SERVICES.delete(`/follow/${id}`, {}, token);
        return response;
    } catch (error) {

        return error;
    }
}

export const getComments = async (id, start, end) => {
    try {
        const response = await SERVICES.get(`/comment/${id}/${start}/${end}`, {});
        return response;
    } catch (error) {

        return error;
    }
}

export const getDataUserPublic = async (name) => {
    try {
        const response = await SERVICES.get(`/perfil/${name}/data`, {});
        return response;
    } catch (error) {

        return error;
    }
}

export const getFollowUserPublic = async (name) => {
    try {
        const response = await SERVICES.get(`/perfil/${name}/follow`, {});
        return response;
    } catch (error) {

        return error;
    }
}

export const getTweetUserPublic = async (id, start = 0, end = 2) => {
    try {
        const response = await SERVICES.get(`/perfil/${id}/tweets/${start}/${end}`, {});
        return response;
    } catch (error) {

        return error;
    }
}
