import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        jadwal: [],
    },

    mutations: {
        updateJadwal: function (state, jadwal) {
            state.jadwal = jadwal
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