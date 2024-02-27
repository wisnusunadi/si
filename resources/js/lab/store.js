export default {
    // State
    state: {
        loading: false,
        years: new Date().getFullYear(),
    },
    // Mutations
    mutations: {
        setLoading(state, payload) {
            state.loading = payload
        },
        setYears(state, payload) {
            state.years = payload
        }
    },
    // Actions
    actions: {
        setLoading({ commit }, payload) {
            commit('setLoading', payload)
        },
        setYears({ commit }, payload) {
            commit('setYears', payload)
        }
    },
}