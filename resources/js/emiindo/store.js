export default {
    state: {
        SO: [],
        POEkat: [],
        PONonEkat: [],
        DO: [],
        loading: false
    },
    mutations: {
        setDataSO(state, data) {
            state.SO = data;
        },
        setDataPOEkat(state, data) {
            state.POEkat = data;
        },
        setDataPONonEkat(state, data) {
            state.PONonEkat = data;
        },
        setDataDO(state, data) {
            state.DO = data;
        },
        setLoading(state, data) {
            state.loading = data;
        }
    },
    actions: {
        setLoading(context, data) {
            context.commit('setLoading', data);
        },
    }
}