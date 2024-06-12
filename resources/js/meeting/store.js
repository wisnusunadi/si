export default {
    state: {
        loading: false,
        dataEdit: null,
    },
    mutations: {
        setLoading(state, payload) {
            state.loading = payload;
        },
        setDataEdit(state, payload) {
            state.dataEdit = payload;
        },
    },
    actions: {
        setLoading({ commit }, payload) {
            commit("setLoading", payload);
        },
        setDataEdit({ commit }, payload) {
            commit("setDataEdit", payload);
        },
    },
};
