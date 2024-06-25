import axiosPlugin from "./axios";
import dateformat from "./dateFormat";
import numberOnly from "./numberOnly";
import dateTimeFormat from "./dateTimeFormat";
import timeFormat from "./timeFormat";

export default {
    install(Vue) {
        Vue.use(axiosPlugin);
        Vue.use(dateformat);
        Vue.use(numberOnly);
        Vue.use(dateTimeFormat);
        Vue.use(timeFormat);
    },
};

