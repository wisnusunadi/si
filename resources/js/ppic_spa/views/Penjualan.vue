<template>
    <div>
        <h1 class="title">Penjualan</h1>
        <ekatalog-vue :penjualanekatalogs="penjualanekatalogs"/>
    </div>
</template>
<script>
    import axios from 'axios';
    import ekatalogVue from '../components/penjualan/ekatalog.vue';
    export default {
        components: {
            ekatalogVue
        },
        data() {
            return {
                penjualanekatalogs: []
            }
        },
        methods: {
            async getPenjualan() {
                try {
                    this.$store.commit('setIsLoading', true);
                    await axios.post('/penjualan/penjualan/ekatalog/data/semua')
                    .then(response => {
                        this.penjualanekatalogs = response.data.data;
                        this.$store.commit('setIsLoading', false);
                    })
                    .catch(error => {
                        console.log(error);
                    });
                } catch (error) {
                    console.log(error);
                }
            },
        },
        mounted() {
            this.getPenjualan();
        },
    }
</script>