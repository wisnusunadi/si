<script>
import Header from '../../../../../components/header.vue';
export default {
    components: { Header },
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
                    link: '/permintaan-goods/detail'
                }
            ]
        }
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
    </div>
</template>