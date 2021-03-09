import SERVICES from "./Service";

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
        return response;
    } catch (error) {
        console.error(error);
    }
}

export const register = async (name, email, password, confirmpassword) => {
    try {
        const response = await SERVICES.post(`/login`, { name, email, password, confirmpassword });
        return response;
    } catch (error) {
        console.error(error);
    }
}