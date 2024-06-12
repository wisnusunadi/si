import axios from "axios";

// default config axios
axios.defaults.headers.authorization = `Bearer ${localStorage.getItem("lokal_token")}`;

const _get = async (url) => {
    try {
        const { data } = await axios.get(url);
        return data;
    } catch (error) {
        console.error(error);
    }
}

const _post = async (url, formKirim) => {
    try {
        await axios.post(url, formKirim);
    } catch (error) {
        console.error(error);
    }
}

const _put = async (url, formKirim) => {
    try {
        await axios.put(url, formKirim);
    } catch (error) {
        console.error(error);
    }
}

const _delete = async (url) => {
    try {
        await axios.delete(url);
    } catch (error) {
        console.error(error);
    }
}

export default {
    install(Vue) {
        Vue.prototype.$get = _get;
        Vue.prototype.$post = _post;
        Vue.prototype.$put = _put;
        Vue.prototype.$delete = _delete;
    }
}