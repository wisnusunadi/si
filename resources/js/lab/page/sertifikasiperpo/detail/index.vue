<script>
import Header from '../../../components/header.vue';
import HeaderDetail from './header.vue';
import Produk from './produk.vue';
import axios from 'axios';
export default {
    components: {
        Header,
        HeaderDetail,
        Produk
    },
    data() {
        return {
            title: 'DETAIL SERTIFIKAT PER NOMOR ORDER',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/'
                },
                {
                    name: 'Sertifikat',
                    link: this.$route.params.history,
                },
                {
                    name: 'Detail Sertifikat PerNoOrder',
                    link: '/lab/sertifikasiperpo/detail'
                }
            ],
            dataTable: [],
            header: null,
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data } = await axios.get(`/api/labs/data/detail/${this.$route.params.id}`)
                this.header = data.data.header
                this.dataTable = data.data.produk
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        }
    },
    created() {
        this.getData();
    }
}
</script>
<template>
    <div v-if="!$store.state.loading">
        <Header :title="title" :breadcumbs="breadcumbs" />
        <HeaderDetail :header="header"/>
        <Produk :data-table="dataTable"/>
    </div>
</template>