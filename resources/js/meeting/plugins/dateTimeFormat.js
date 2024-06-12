import moment from "moment";

export default {
    install(Vue) {
        Vue.prototype.dateFormat = function (date) {
            return date ? moment(date).lang("id").format("DD MMMM YYYY") : "-";
        };
    },
};
import moment from 'moment'

export default {
    install(Vue) {
        Vue.prototype.dateTimeFormat = function (dateTime) {
            return dateTime ? moment(dateTime).lang('id').format('DD MMMM YYYY HH:mm') : '-'
        }
    }
}