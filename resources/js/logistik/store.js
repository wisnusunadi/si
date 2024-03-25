export default {
    state: {
        loading: false,
        years: new Date().getFullYear(),
    },
    mutations: {
        setYears(state, payload) {
            state.years = payload
        },
        setLoading(state, payload) {
            state.loading = payload
        },
    },
    actions: {
        setYears({ commit }, payload) {
            commit('setYears', payload)
        },
        setLoading({ commit }, payload) {
            commit('setLoading', payload)
        },
    },
}