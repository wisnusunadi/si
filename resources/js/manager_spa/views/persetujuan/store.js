import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        jadwal: [],
        status: 0,
        state: 0,
        konfirmasi: false
    },

    mutations: {
        updateJadwal: function (state, jadwal) {
            state.jadwal = jadwal
            if (jadwal.length > 0) {
                state.state = jadwal[0].state.nama

                for (let i = 0; i < jadwal.length; i++) {
                    if (jadwal[i].konfirmasi !== 0) state.konfirmasi = true
                }
            }
        },

        updateStatus: function (state, status) {
            state.status = status
        }
    },

    actions: {
        updateJadwal: function (context, status) {
            axios.get("/api/ppic/schedule/" + status).then((response) => {
                context.commit('updateJadwal', response.data)
            })
            context.commit("updateStatus", status)
        }
    }
})

export default store