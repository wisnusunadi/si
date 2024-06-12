import moment from "moment";

export default {
    install(Vue) {
        Vue.prototype.dateFormat = function (date) {
            return date ? moment(date).lang("id").format("DD MMMM YYYY") : "-";
        };
    },
};
import moment from "moment";

export default {
    install(Vue) {
        Vue.prototype.timeFormat = function (time) {
            return time ? moment(time, "HH:mm:ss").format("HH:mm") : "-";
        };
    },
};