export default {
    install(Vue) {
        Vue.prototype.rupiahFormat = function (number) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
            }).format(number);
        };
    },
};
