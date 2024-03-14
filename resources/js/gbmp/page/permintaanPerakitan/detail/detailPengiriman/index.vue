<script>
import Header from '../../../../components/header.vue';
import headerDetail from './header.vue';
import detail from './detail.vue';
export default {
    components: {
        Header,
        headerDetail,
        detail,
    },
    data() {
        return {
            title: 'Detail Transfer Produk',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Permintaan Perakitan',
                    link: '/gbmp/perakitan'
                },
                {
                    name: 'Detail Permintaan Perakitan',
                    link: `/gbmp/perakitan/${this.$route.params.route}`
                },
                {
                    name: 'Detail Transfer Produk',
                    link: '/permintaan-perakitan/detail/transfer'
                }
            ],
            detailProduk: {
                header: {
                    no_permintaan: '20210621001',
                    no_bppb: '20210621001',
                    nama: 'Produk 1',
                    sudah_tf: 5,
                },
                produk: [
                    {
                        jumlah: 2,
                        kedatangan: 2,
                        tgl_tf: '2021-06-21',
                        operator: 'Operator 1',
                        transfer: 'Transfer 1',
                    },
                    {
                        jumlah: 2,
                        kedatangan: 2,
                        tgl_tf: '2021-06-21',
                        operator: 'Operator 1',
                        transfer: 'Transfer 1',
                    },
                    {
                        jumlah: 2,
                        kedatangan: 2,
                        tgl_tf: '2021-06-21',
                        operator: 'Operator 1',
                        transfer: 'Transfer 2',
                    },
                ]
            }
        }
    },
    computed: {
        produks() {
            let produk = []
            this.detailProduk.produk.forEach(item => {
                let existingEntryIndex = produk.findIndex(entry => entry.transfer === item.transfer)

                if (existingEntryIndex === -1) {
                    produk.push({
                        detail: [{
                            no: produk.length + 1,
                            jumlah: item.jumlah,
                            kedatangan: item.kedatangan,
                            tgl_tf: item.tgl_tf,
                            tanggal_transfer: this.dateFormat(item.tgl_tf),
                            operator: item.operator,
                        }],
                        transfer: item.transfer,
                    })
                } else {
                    produk[existingEntryIndex].detail.push({
                        no: produk[existingEntryIndex].detail.length + 1,
                        jumlah: item.jumlah,
                        kedatangan: item.kedatangan,
                        tgl_tf: item.tgl_tf,
                        tanggal_transfer: this.dateFormat(item.tgl_tf),
                        operator: item.operator,
                    })
                }
            })

            return produk
        }
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <headerDetail :header="detailProduk.header" />
        <div class="card">
            <div class="card-body">
                <div v-for="(produk, key) in produks" :key="key">
                    <detail :produk="produk" />
                </div>
            </div>
        </div>
    </div>
</template>