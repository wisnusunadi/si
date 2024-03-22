export default {
    state: {
        SO: [],
        POEkat: [],
        PONonEkat: [],
        DO: [],
        loading: false,
        years: null,
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
        },
        setYears(state, data) {
            state.years = data;
        },
    },
    actions: {
        setLoading(context, data) {
            context.commit("setLoading", data);
        },
        setYears(context, data) {
            context.commit("setYears", data);
        },
    },
};
