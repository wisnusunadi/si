import Vue from 'vue'
import Vuex from 'vuex'
import mixins from '../mixins'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        user: {},
        isLoading: false,
        csrf_token: "",

        jadwal: [],

        status: '', // [penyusunan, pelaksanaan, selesai]
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

            if (jadwal.length > 0) {
                state.state = mixins.methods.change_state(jadwal[0].state)
                state.status = mixins.methods.change_status(jadwal[0].status)
            } else {
                state.status = 'penyusunan'
            }

            // set ppic state
            if (state.konfirmasi == 0 && state.state !== "perencanaan") state.state_ppic = "menunggu"
            else if (state.state === "perencanaan") state.state_ppic = "pembuatan"
            else if (state.state === "persetujuan" && state.konfirmasi == 0) state.state_ppic = "menunggu"
            else if (state.state === "persetujuan" && state.konfirmasi == 1) state.state_ppic = "disetujui"
            else if (state.state === "persetujuan" && state.konfirmasi == 2) state.state_ppic = "revisi"
        },

        setStatus(state, status) {
            state.status = status;
        },

        // deleteJadwal(state, id) {
        //     console.log("delete jadwal")
        //     let temp_id = state.jadwal.findIndex((element) => element.id == id)

        //     console.log(temp_id)
        //     // console.log(state.jadwal[temp_id])

        //     delete state.jadwal[parseInt(temp_id)];
        // }
    }
})

export default store