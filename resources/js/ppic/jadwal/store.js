import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        view: "calendar",
        konfirmasi: false,
        proses_konfirmasi: false,
        jadwal: []
    },

    mutations: {
        changeView: function (state, view) {
            state.view = view
        },

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
        }
    }
})

export default store