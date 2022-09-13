<template>
    <div>
        <h1>Detail Penjualan</h1>
        <p>{{ detailpenjualanekatalog }}</p>
        <div class="tabs is-centered">
            <ul>
                <li :class="{'is-active': !tabs}" @click="tabs = false">
                    <a><span>Informasi</span></a>
                </li>
                <li :class="{'is-active': tabs}" @click="tabs = true">
                    <a><span>Produk</span></a>
                </li>
            </ul>
        </div>
        <div class="card" v-if="pesanan != null" :class="{'is-hidden':tabs}">
            <div class="card-header">
                <div class="card-header-title">Informasi</div>
            </div>
            <div class="card-content">
                <div class="content">
                    <div class="columns">
                        <div class="column">
                            <p>No SO</p>
                            <p class="has-text-weight-bold">{{ pesanan.so }}</p>
                        </div>
                        <div class="column">
                            <p>Tanggal Buat</p>
                            <p class="has-text-weight-bold">{{ detailpenjualanekatalog.data.tgl_buat }}</p>
                        </div>
                        <div class="column">
                            <p>No PO</p>
                            <p class="has-text-weight-bold">{{ pesanan.no_po }}</p>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <p>No AKN</p>
                            <p class="has-text-weight-bold">{{ pesanan.so }}</p>
                        </div>
                        <div class="column">
                            <p>Tanggal Edit</p>
                            <p class="has-text-weight-bold">{{ detailpenjualanekatalog.data.tgl_edit }}</p>
                        </div>
                        <div class="column">
                            <p>Tanggal PO</p>
                            <p class="has-text-weight-bold">{{ pesanan.tgl_po }}</p>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <p>No Urut</p>
                            <p class="has-text-weight-bold">{{ detailpenjualanekatalog.data.no_urut }}</p>
                        </div>
                        <div class="column">
                            <p>Tanggal Delivery</p>
                            <span v-html="detailpenjualanekatalog.tgl_kontrak"></span>
                        </div>
                        <div class="column">
                            <p>Status</p>
                            <progress class="progress is-success" :value="detailpenjualanekatalog.status" max="100">{{detailpenjualanekatalog.status}}%</progress>
                            <span><b>{{detailpenjualanekatalog.status}}%</b> Selesai</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-content">
                <div class="notification is-primary">
                <span class="has-text-weight-bold">Detail :</span> {{ detailpenjualanekatalog.data.deskripsi }}
                </div>
            </div>
        </div>
        
        <div class="card" :class="{'is-hidden':!tabs}">
            <div class="card-header">
                <div class="card-header-title">Produk</div>
            </div>
            <div class="card-content">
                <div class="content">
                    <div class="columns is-gapless is-multiline is-mobile">
                        <div class="column is-one-quarter">
                            test
                        </div>
                        <div class="column is-half">
                            test
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios';
    export default {
        data() {
            return {
                detailpenjualanekatalog: null,
                tabs: false
            }
        },
        methods: {
            async getPenjualan() {
                try {
                    let id = this.$route.params.id;
                    let jenis = this.$route.params.jenis;
                    this.$store.commit('setIsLoading', true);
                    switch (jenis) {
                        case 'ekatalog':
                        await axios.get('/penjualan/penjualan/detail/ekatalog_ppic/'+id)
                            .then(response => {
                                this.detailpenjualanekatalog = response.data;
                                this.$store.commit('setIsLoading', false);
                            })
                            break;
                    
                        default:
                            break;
                    }
                } catch (error) {
                    console.log(error);
                }
            },
        },
        computed: {
            pesanan() {
                if(this.detailpenjualanekatalog != null) {
                    return this.detailpenjualanekatalog.data.pesanan;
                }
                return null;
            }
        },
        mounted() {
            this.getPenjualan();
        },
    }
</script>