import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        view: "calendar",
        jadwal: []
    },

    mutations: {
        changeView: function (state, view) {
            state.view = view
        },

        updateJadwal: function (state, jadwal) {
            state.jadwal = jadwal
        }
    }
})

export default store