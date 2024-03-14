export default {
    // State
    state: {
        loading: false,
        setSeri: null,
        openDetail: {},
    },
    // Mutations
    mutations: {
        setDetail(state, payload) {
            state.openDetail = payload
        },
        setLoading(state, payload) {
            state.loading = payload
        },
        setSeri(state, payload) {
            state.setSeri = payload
        },
    },
    // Actions
    actions: {
        setDetail({ commit }, payload) {
            commit('setDetail', payload)
        },
        setLoading({ commit }, payload) {
            commit('setLoading', payload)
        },
        setSeri({ commit }, payload) {
            commit('setSeri', payload)
        },
    },
}