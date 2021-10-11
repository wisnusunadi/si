import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        view: "calendar"
    },

    mutations: {
        changeView: function(state, view){
            state.view = view
        }
    }
})

export default store