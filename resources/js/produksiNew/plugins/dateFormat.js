import moment from 'moment'

export default {
    install(Vue) {
        Vue.prototype.formatDate = function (date) {
            return date ? moment(date).lang('id').format('DD MMMM YYYY') : '-'
        }
    }
}