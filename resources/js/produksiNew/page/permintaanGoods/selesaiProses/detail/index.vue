<script>
import Header from '../../../../components/header.vue'
import headerDetail from './header.vue'
export default {
    components: { Header, headerDetail },
    data() {
        return {
            title: 'Detail Permintaan Barang',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Permintaan Barang',
                    link: '/produksi/permintaan_goods'
                },
                {
                    name: 'Detail Permintaan Barang',
                    link: '/produksi/permintaan_goods/detail'
                }
            ],
            detail: {
                headers: {
                    no_permintaan: 'NSO-2021080001',
                    no_referensi: 'SO-2021080001',
                    status: 'selesai',
                    tgl_permintaan: '2021-08-21',
                    tgl_pengambilan: '2024-08-23',
                    tgl_close: '2024-08-26',
                    tujuan_permintaaan: 'Lorem',
                    durasi: '3 hari'
                }
            }
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <headerDetail :header="detail.headers" />
    </div>
</template>