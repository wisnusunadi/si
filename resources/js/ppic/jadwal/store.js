import axios from 'axios'
import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        konfirmasi: false,
        proses_konfirmasi: false,
        jadwal: [],
        user: {},
        status: "",
    },

    mutations: {
        updateJadwal: function (state, jadwal) {
            state.jadwal = jadwal
            for (let i = 0; i < jadwal.length; i++)
                if (jadwal[i].konfirmasi === 1)
                    state.konfirmasi = true
                else {
                    state.konfirmasi = false
                    break
                }

            for (let i = 0; i < jadwal.length; i++)
                if (jadwal[i].proses_konfirmasi === 1)
                    state.proses_konfirmasi = true
                else {
                    state.proses_konfirmasi = false
                    break
                }
        },

        updateUser: function (state, user) {
            state.user = user
        },

        updateStatus: function (state, status) {
            state.status = status
        },
    },

    actions: {
        updateJadwal: function (context, status) {
            axios.get("/api/ppic/schedule/" + status).then((response) => {
                context.commit('updateJadwal', response.data)
            })
        }
    }
})

export default store