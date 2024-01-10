import moment from 'moment'

export default {
    install(Vue) {
        Vue.prototype.timeFormat = function (date) {
            return date ? moment(date).lang('id').format('HH:mm') : '-'
        }
    }
}