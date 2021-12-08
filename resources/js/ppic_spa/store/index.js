import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        user: {},
        isLoading: false,
        csrf_token: "",
        jadwal_perakitan: [],
    },

    mutations: {
        setIsLoading(state, status) {
            state.isLoading = status
        }
    }
})

export default store