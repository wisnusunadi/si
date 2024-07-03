export default {
    methods: {
        // format tanggal
        tgl_format(tgl) {
            return moment(tgl).format('DD MMMM YYYY');
        },
        // format rupiah
        formatRupiah(e) {
            if(isNaN(e)){
                return 0;
            }else{
                return e.toString().split('').reverse().join('').match(/\d{1,3}/g).join('.').split('').reverse().join('');
            }
        },
        // penghitungan subtotal
        subtotal(qty, price, ongkir) {
            return (parseInt(qty) * parseInt(price)) + parseInt(ongkir);
        },
        // penghitungan subtotal PO
        subtotalPO(qty, price, discount) {
            return (parseInt(qty) * parseInt(price) * ((100 - parseInt(discount)) / 100));
        },
        // penghitungan total PO
        totalPO(type, data) {
            let total = 0;
            if (type == 'ekat') {
                data.purchaseorderdetail.forEach(item => {
                    total += this.subtotalPO(item.qty, item.price, item.discount);
                });
            } else {
                data.purchaseorderdetail.forEach(item => {
                    total += this.subtotalPO(item.qty, item.price, item.discount);
                });
            }
            return total;
        },
        // penghitungan total SO
        totalHargaSOEkat(total){
            let totalHarga = 0;
            total.forEach(item => {
                totalHarga += this.subtotal(item.qty, item.price, item.shippingcharge);
            });
            return totalHarga;
        },
        // penghitungan total PO
        totalHargaPO(total){
            let ttl = 0;
            total.forEach(item => {
                ttl += this.subtotalPO(item.qty, item.price, item.discount);
            });;
            return ttl;
        },
        // penghitungan total
        total(detail) {
            let total = 0;
            detail.forEach(item => {
                total += this.subtotal(item.qty, item.price, item.shippingcharge);
            });
            return total;
        },
        // penghitungan total
        isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();;
            } else {
                return true;
            }
        },
    },
}