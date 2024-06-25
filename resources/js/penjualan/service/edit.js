import axios from "axios";

axios.defaults.headers.common = {
    Authorization: `Bearer ${localStorage.getItem("lokal_token")}`,
};


const getPenjualanById = async (id) => {
    try {
        const { data } = await axios.get(`/api/penj/detail/edit/${id}`);
        return data;
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

export { getPenjualanById };