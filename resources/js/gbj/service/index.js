import axios from "axios";

const finishGoods = async (years = new Date().getFullYear()) => {
    try {
        const { data } = await axios.get(`/api/tfp/rakit?tahun=${years}`);
        return data;
    } catch (error) {
        console.error(error);
    }
}

const terimaRework = async (formKirim) => {
    try {
        await axios.post("/api/gbj/rw/terima", formKirim, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("lokal_token")}`,
            },
        });
    } catch (error) {
        console.error(error);
    }
}

const penerimaanRework = async () => {
    try {
        const { data } = await axios.get(`/api/gbj/rw/dp/seri`);
        return data;
    } catch (error) {
        console.log(error);
    }
}

const riwayatPenerimaanRework = async () => {
    try {
        const { data } = await axios.get(`/api/gbj/rw/dp/riwayat_penerimaan`);
        return data;
    } catch (error) {
        console.log(error);
    }
}

const getDataSO = async () => {
    try {
        const { data } = await axios.get("/api/tfp/data-so", {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("lokal_token")}`,
            },
        });

        return data;
    } catch (error) {
        console.error(error);
    }
}

export {
    finishGoods,
    terimaRework,
    penerimaanRework,
    riwayatPenerimaanRework,
    getDataSO,
}