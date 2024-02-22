<script>
import Header from '../../../../components/header.vue';
import cardHeader from './header.vue'
export default {
    components: {
        Header,
        cardHeader,
    },
    data() {
        return {
            title: 'Detail Barang Masuk',
            breadcumbs: [
                {
                    name: 'Dashboard',
                    link: '/',
                },
                {
                    name: 'Barang Masuk',
                    link: '/qc/incoming/show',
                },
                {
                    name: 'Detail Barang Masuk',
                    link: '#',
                },
            ],
            detailBarangMasuk: {
                header: {
                    id: 1,
                    bppb: 'BPPB-001',
                    produk: 'Produk 1',
                    jumlah: 500,
                    terisi: 80,
                    lolos: 40,
                    persentase_lolos: 50,
                    tidak_lolos: 20,
                    persentase_tidak_lolos: 25,
                }
            }
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <cardHeader :header="detailBarangMasuk.header" />
    </div>
</template>