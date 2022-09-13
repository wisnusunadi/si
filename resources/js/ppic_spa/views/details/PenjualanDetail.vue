<template>
    <div>
        <h1>Detail Penjualan</h1>
        <p>{{ detailpenjualanekatalog }}</p>
        <div class="card">
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