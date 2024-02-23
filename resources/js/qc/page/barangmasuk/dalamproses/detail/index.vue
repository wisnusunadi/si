<script>
import Header from '../../../../components/header.vue';
import cardHeader from './header.vue'
import cardProduk from './produk.vue'
export default {
    components: {
        Header,
        cardHeader,
        cardProduk
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
                },
                pengemasan: [

                ]
            }
        }
    },
    created() {
        if (!this.$route.params.isRiwayat) {
            this.detailBarangMasuk.pengemasan = [
                {
                    id: 1,
                    tgl_terima: '2024-02-01',
                    waktu_terima: '12:00',
                    noseri: 'US03223A00074',
                    tgl_uji: '2024-02-13',
                    waktu_uji: '12:00',
                    hasil: 'ok',
                    penguji: "Lorem",

                },
                {
                    id: 2,
                    tgl_terima: '2024-02-13',
                    waktu_terima: '12:00',
                    noseri: 'US03223A00004',
                    tgl_uji: '2024-02-13',
                    waktu_uji: '12:00',
                    hasil: 'not_ok',
                    penguji: "Lorem",

                },
                {
                    id: 3,
                    tgl_terima: '2024-02-01',
                    waktu_terima: '12:00',
                    noseri: 'US03225A00134',
                    hasil: 'belum',
                    tgl_uji: null,
                    waktu_uji: null,
                },
                {
                    id: 4,
                    tgl_terima: '2024-02-03',
                    waktu_terima: '12:00',
                    noseri: 'US03225A00148',
                    hasil: 'belum',
                    tgl_uji: null,
                    waktu_uji: null,
                }
            ]
        } else {
            this.detailBarangMasuk.pengemasan = [
                {
                    id: 1,
                    tgl_terima: '2024-02-01',
                    waktu_terima: '12:00',
                    noseri: 'US03223A00074',
                    tgl_uji: '2024-02-13',
                    waktu_uji: '12:00',
                    hasil: 'ok',
                    penguji: "Lorem",

                },
                {
                    id: 2,
                    tgl_terima: '2024-02-13',
                    waktu_terima: '12:00',
                    noseri: 'US03223A00004',
                    tgl_uji: '2024-02-13',
                    waktu_uji: '12:00',
                    hasil: 'not_ok',
                    penguji: "Lorem",

                }
            ]
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <cardHeader :header="detailBarangMasuk.header" />
        <cardProduk :detailBarangMasuk="detailBarangMasuk" />
    </div>
</template>