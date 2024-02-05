export default {
    state: {
        SO: [],
        POEkat: [],
        PONonEkat: [],
        DO: [],
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
        }
    }
}