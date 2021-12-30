import Vue from 'vue'
import Vuex from 'vuex'
import mixins from '../mixins'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        // global
        user: {},
        isLoading: false,
        csrf_token: "",

        // jadwal perakitan
        jadwal: [],
        status: 'penyusunan', // [penyusunan, pelaksanaan, selesai]
        state: 'perencanaan', // [perencanaan, persetujuan, perubahan]
        konfirmasi: 0, // [0 => inisial, 1 => setuju, 2 => tolak]
        state_ppic: "pembuatan", // [pembuatan, revisi, disetujui, menunggu]
    },

    mutations: {
        setIsLoading(state, status) {
            state.isLoading = status
        },

        setJadwal(state, jadwal) {
            state.jadwal = jadwal;

            // set konfirmasi
            let flag = false;
            for (let i = 0; i < jadwal.length; i++) {
                if (!flag) {
                    if (jadwal[i].konfirmasi !== 1) {
                        if (jadwal[i].konfirmasi === 0) {
                            state.konfirmasi = 0
                            flag = true
                        }

                        if (jadwal[i].konfirmasi === 2) {
                            state.konfirmasi = 2
                            flag = true
                        }
                    } else {
                        state.konfirmasi = 1
                    }
                }
            }

            // set status and state
            if (jadwal.length > 0) {
                state.state = mixins.change_state(jadwal[0].state)
                state.status = mixins.change_status(jadwal[0].status)
            }

            // set ppic state
            if (state.konfirmasi == 0 && state.state !== "perencanaan") state.state_ppic = "menunggu"
            else if (state.state === "perencanaan") state.state_ppic = "pembuatan"
            else if (state.state === "persetujuan" && state.konfirmasi == 0) state.state_ppic = "menunggu"
            else if (state.state === "persetujuan" && state.konfirmasi == 1) state.state_ppic = "disetujui"
            else if (state.state === "persetujuan" && state.konfirmasi == 2) state.state_ppic = "revisi"
        },
    }
})

export default store