import axios from "axios";

axios.defaults.headers.common = {
    Authorization: `Bearer ${localStorage.getItem("lokal_token")}`,
};

const getCustomer = async () => {
    try {
        const { data } = await axios.get("/api/customer/select");
        return data;
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const noUrutCheck = async (no_urut, id = 0) => {
    try {
        const { data } = await axios.post(
            `/api/penjualan/check_no_urut/${id}/${no_urut}`
        );
        return data;
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const getInstansi = async (id = null, tahun = new Date().getFullYear()) => {
    try {
        const { data } = await axios.get(
            `/api/customer/get_instansi/${id}/${tahun}`
        );
        return data;
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const getSatuanKerja = async () => {
    try {
        const { data } = await axios.get("/api/ekatalog/all_satuan");
        return data;
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const getAlamat = async () => {
    try {
        const { data } = await axios.get("/api/penjualan/check_alamat");
        return data;
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const getProvinsi = async () => {
    try {
        const { data } = await axios.get("/api/provinsi/select");
        return data;
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const getDeskripsi = async () => {
    try {
        const { data } = await axios.get("/api/ekatalog/all_deskripsi");
        return data;
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const getEkspedisiAll = async () => {
    try {
        const { data } = await axios.get("/api/logistik/ekspedisi/all");
        return data;
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const getJasa = async () => {
    try {
        const { data } = await axios.post("/api/gk/sel-m-jasa");
        return data;
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const getPart = async () => {
    try {
        const { data } = await axios.post("/api/gk/sel-m-spare");
        return data;
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const getProduk = async (jenis) => {
    try {
        const { data } = await axios.get(
            `/api/penjualan_produk/select_param_new/${jenis}`
        );
        return data;
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const storePenjualan = async (formData) => {
    try {
        const { data } = await axios.post("/api/penj/action", formData);
       return { success: true, data}
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
};

const storeEditPenjualan = async (formData) => {
    try {
        const { data } = await axios.post("/api/penj/action/edit", formData);
        return { success: true, message: data };
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
}

const getYears = async () => {
    try {
        const { data } = await axios.get('/api/penjualan/getYearsPeriode')
        return {success: true, data: data}
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
}

const getVariasi = async (id) => {
    try {
        const { data } = await axios.get(`/api/penjualan_produk/select/${id}`);
        return {success: true, data}
    } catch (error) {
        return { success: false, message: error.response.data.message };
    }
}

export {
    getCustomer,
    noUrutCheck,
    getInstansi,
    getSatuanKerja,
    getAlamat,
    getProvinsi,
    getDeskripsi,
    getEkspedisiAll,
    getJasa,
    getPart,
    getProduk,
    storePenjualan,
    storeEditPenjualan,
    getYears,
    getVariasi,
};
