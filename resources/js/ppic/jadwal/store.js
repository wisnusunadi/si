import Vue from 'vue'
import Vuex from 'vuex'

import axios from 'axios'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        jadwal: [],
        user: {},
        status: "",
        state: "",
        konfirmasi: 0
    },

    mutations: {

        updateJadwal: function (state, jadwal) {
            state.jadwal = jadwal
            for (let i = 0; i < jadwal.length; i++)
                if (jadwal[i].konfirmasi !== -1) {
                    if (jadwal[i].konfirmasi === 0) {
                        state.konfirmasi = 0
                        break
                    }

                    if (jadwal[i].konfirmasi === 1)
                        state.konfirmasi = 1
                    else if (jadwal[i].konfirmasi === 2) {
                        state.konfirmasi = 2
                        break
                    }
                }

            if (jadwal.length > 0) state.state = jadwal[0].state.nama
        },

        updateUser: function (state, user) {
            state.user = user
        },

        updateStatus: function (state, status) {
            state.status = status
        }
    },

    actions: {
        updateJadwal: function (context, status) {
            axios
                .get("/api/ppic/schedule/" + status)
                .then((response) => {
                    context.commit("updateJadwal", response.data);
                });
        }
    }
})

export default store