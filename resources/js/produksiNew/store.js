export default {
    // State
    state: {
        loading: false,
        setSeri: 0,
    },
    // Mutations
    mutations: {
        setLoading(state, payload) {
            state.loading = payload
        },
        setSeri(state, payload) {
            state.setSeri = payload
        },
    },
    // Actions
    actions: {
        setLoading({ commit }, payload) {
            commit('setLoading', payload)
        },
        setSeri({ commit }, payload) {
            commit('setSeri', payload)
        },
    },
}