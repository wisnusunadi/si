import axios from 'axios'
import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        konfirmasi: 0,
        status_menunggu: 0,
        status_perubahan: 0,
        jadwal: [],
        user: {},
        status: "",
    },

    mutations: {
        updateJadwal: function (state, jadwal) {
            state.jadwal = jadwal
            for (let i = 0; i < jadwal.length; i++)
                if (jadwal[i].konfirmasi === 1)
                    state.konfirmasi = jadwal[i].konfirmasi
                else {
                    state.konfirmasi = jadwal[i].konfirmasi
                    break
                }

            if (state.jadwal.length > 0) {
                state.status_menunggu = jadwal[0].status_menunggu
                state.status_perubahan = jadwal[0].status_perubahan
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