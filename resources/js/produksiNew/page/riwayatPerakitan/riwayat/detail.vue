<script>
import axios from 'axios'
import DataTable from '../../../components/DataTable.vue'
export default {
    props: ['produk'],
    components: {
        DataTable
    },
    data() {
        return {
            riwayatRakit: [],
            headers: [
                {
                    text: 'Nomor Seri',
                    value: 'noseri',
                    align: 'text-left'
                }
            ],
            search: '',
            loading: false
        }
    },
    methods: {
        closeModal() {
            $('.modalDetail').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        async getData() {
            try {
                this.loading = true
                const { data } = await axios.get(`/api/prd/riwayat_seri_rakit/${this.produk.produk_id}/${this.produk.tgl} ${this.produk.wkt_rakit}`)
                this.riwayatRakit = data
            } catch (error) {
                console.log(error)
            } finally {
                this.loading = false
            }
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div class="modal fade modalDetail" data-backdrop="static" id="modelId" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Tanggal Perakitan</label>
                                <div class="card-group">
                                    <div class="card" style="background-color: #C8E1A7">
                                        <div class="card-body">
                                            <p class="card-text" id="d_rakit">{{ produk?.tgl_rakit }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Waktu Perakitan</label>
                                <div class="card-group">
                                    <div class="card" style="background-color: #FFECB2">
                                        <div class="card-body">
                                            <p class="card-text" id="t_rakit">{{ produk?.wkt_rakit }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Nomor BPPB</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body" id="bppb">
                                        {{ produk?.no_bppb }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #FCF9C4">
                                    <div class="card-body" id="produk">
                                        {{ produk?.produkk }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah</label>
                                <div class="card" style="background-color: #FFCC83">
                                    <div class="card-body" id="jml">
                                        {{ produk?.jml }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-row-reverse bd-highlight">
                            <div class="p-2 bd-highlight">
                                <input type="text" v-model="search" class="form-control">
                            </div>
                        </div>
                        <DataTable :headers="headers" :items="riwayatRakit" :search="search" v-if="!loading" />
                        <div class="text-center" v-else>
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>