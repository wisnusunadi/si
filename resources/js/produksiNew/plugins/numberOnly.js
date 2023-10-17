export default {
    install(Vue) {
        Vue.prototype.numberOnly = function ($event) {
            let value = $event.target.value;
            value = value.replace(/[^0-9]/g, '');
            $event.target.value = value;
        }
    }
}