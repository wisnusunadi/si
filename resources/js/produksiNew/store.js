export default {
    // State
    state: {
        loading: false,
    },
    // Mutations
    mutations: {
        setLoading(state, payload) {
            state.loading = payload
        },
    },
    // Actions
    actions: {
        setLoading({ commit }, payload) {
            commit('setLoading', payload)
        },
    },
}