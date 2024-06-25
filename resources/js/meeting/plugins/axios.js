import axios from "axios";

axios.defaults.headers.common["Authorization"] = `Bearer ${localStorage.getItem(
    "lokal_token"
)}`;

const _get = async (url) => {
    try {
        const { data } = await axios.get(url);
        return { success: true, data };
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const _post = async (url, payload, config = {}) => {
    try {
        const { data } = await axios.post(url, payload, config);
        return { success: true, data };
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const _put = async (url, payload, config = {}) => {
    try {
        const { data } = await axios.put(url, payload, config);
        return { success: true, data };
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const _delete = async (url) => {
    try {
        const { data } = await axios.delete(url);
        return { success: true, data };
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

// make globalProperties for Vue 2
const axiosPlugin = {
    install(Vue) {
        Vue.prototype.$_get = _get;
        Vue.prototype.$_post = _post;
        Vue.prototype.$_put = _put;
        Vue.prototype.$_delete = _delete;
    },
};

export default axiosPlugin;
