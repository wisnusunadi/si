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
                    {
                        id: 1,
                        tgl_terima: '2024-02-01',
                        waktu_terima: '12:00',
                        noseri: 'TD12345',
                        tgl_uji: '2024-02-13',
                        waktu_uji: '12:00',
                        hasil: 'ok',
                    },
                    {
                        id: 2,
                        tgl_terima: '2024-02-13',
                        waktu_terima: '12:00',
                        noseri: 'TD123456',
                        tgl_uji: '2024-02-13',
                        waktu_uji: '12:00',
                        hasil: 'not_ok',
                    },
                    {
                        id: 3,
                        tgl_terima: '2024-02-01',
                        waktu_terima: '12:00',
                        noseri: 'TD123457',
                        hasil: 'belum_diuji',
                        tgl_uji: null,
                        waktu_uji: null,
                    },
                    {
                        id: 4,
                        tgl_terima: '2024-02-03',
                        waktu_terima: '12:00',
                        noseri: 'TD1234578',
                        hasil: 'belum_diuji',
                        tgl_uji: null,
                        waktu_uji: null,
                    }
                ]
            }
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