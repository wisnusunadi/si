<template>
    <div>
        <h1 class="title">Penjualan</h1>
        <div class="tabs is-centered">
            <ul>
                <li :class="{'is-active': tabs == 'ekatalog'}" @click="category('ekatalog')">
                    <a><span>E-Katalog</span></a>
                </li>
                <li :class="{'is-active': tabs == 'spa'}" @click="category('spa')">
                    <a><span>SPA</span></a>
                </li>
                <li :class="{'is-active': tabs == 'spb'}" @click="category('spb')">
                    <a><span>SPB</span></a>
                </li>
                <li :class="{'is-active': tabs == 'penjualan'}" @click="category('penjualan')">
                    <a><span>Penjualan</span></a>
                </li>
            </ul>
        </div>
        <ekatalog-vue :penjualanekatalogs="penjualanekatalogs" v-show="tabs == 'ekatalog'" />
        <spa-vue :penjualanspas="penjualanspas" v-show="tabs == 'spa'" />
        <spb-vue :penjualanspbs="penjualanspbs" v-show="tabs == 'spb'" />
    </div>
</template>
<script>
    import axios from 'axios';
    import ekatalogVue from '../components/penjualan/ekatalog.vue';
    import spaVue from '../components/penjualan/spa.vue';
    import spbVue from '../components/penjualan/spb.vue';
    export default {
        components: {
            ekatalogVue,
            spaVue,
            spbVue,
        },
        data() {
            return {
                penjualanekatalogs: [],
                penjualanspas: [],
                penjualanspbs: [],
                tabs: 'ekatalog'
            }
        },
        methods: {
            category(tabs) {
                switch (tabs) {
                    case 'ekatalog':
                        this.tabs = 'ekatalog'
                        this.getPenjualan('ekatalog')
                        break;
                    case 'spa':
                        this.tabs = 'spa'
                        this.getPenjualan('spa')
                        break;
                    case 'spb':
                        this.tabs = 'spb'
                        this.getPenjualan('spb')
                        break;
                    case 'penjualan':
                        this.tabs = 'penjualan'
                        this.getPenjualan('penjualan')
                        break;
                    default:
                        break;
                }
            },
            async getPenjualan(category) {
                switch (category) {
                    case 'ekatalog':
                        if (this.penjualanekatalogs.length == 0) {
                            try {
                                this.$store.commit('setIsLoading', true);
                                await axios.post('/penjualan/penjualan/ekatalog/data/semua')
                                    .then(response => {
                                        this.penjualanekatalogs = response.data.data;
                                    })
                                    .catch(error => {
                                        console.log(error);
                                    });
                                this.$store.commit('setIsLoading', false);
                            } catch (error) {
                                console.log(error);
                            }
                        }
                        break;
                    case 'spa':
                        if (this.penjualanspas.length == 0) {
                            try {
                                this.$store.commit('setIsLoading', true);
                                await axios.post('/penjualan/penjualan/spa/data/semua')
                                    .then(response => {
                                        this.penjualanspas = response.data.data;
                                    })
                                    .catch(error => {
                                        console.log(error);
                                    });
                                this.$store.commit('setIsLoading', false);
                            } catch (error) {
                                console.log(error);
                            }
                        }
                        break;

                    case 'spb':
                        if(this.penjualanspbs.length == 0){
                            try {
                                this.$store.commit('setIsLoading', true);
                                await axios.post('/penjualan/penjualan/spb/data/semua')
                                    .then(response => {
                                        this.penjualanspbs = response.data.data;
                                    })
                                    .catch(error => {
                                        console.log(error);
                                    });
                                this.$store.commit('setIsLoading', false);
                            } catch (error) {
                                console.log(error);
                            }
                        }
                    default:
                        break;
                }
            },
            
        },
        mounted() {
            this.getPenjualan('ekatalog')
        },
    }

</script>
