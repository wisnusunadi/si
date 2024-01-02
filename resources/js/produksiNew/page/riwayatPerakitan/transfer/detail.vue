<script>
import axios from 'axios'
import DataTable from '../../../components/DataTable.vue'
export default {
    components: {
        DataTable
    },
    props: ['detailSelected'],
    data() {
        return {
            noseri: [],
            headers: [
                { text: 'No Seri', value: 'noseri', align: 'text-left' },
            ],
            loading: false,
            search: ''
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
                const { data } = await axios.post('/api/prd/detail_sisa_kirim', {
                    id: this.detailSelected.id
                })
                this.noseri = data
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
    <div class="modal fade modalDetail" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
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
                                <label for="">Nomor BPPB</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body" id="bppb1">{{ detailSelected?.no_bppb ?? "-" }}</div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #FCF9C4">
                                    <div class="card-body" id="produk1">{{ detailSelected?.produk }}</div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah</label>
                                <div class="card" style="background-color: #FFCC83">
                                    <div class="card-body" id="jml1">{{ detailSelected?.jml_rakit }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Keterangan</label>
                            <textarea class="form-control" cols="30" rows="10"
                                disabled>{{ detailSelected?.remark }}</textarea>
                        </div>
                        <div class="d-flex flex-row-reverse bd-highlight">
                            <div class="p-2 bd-highlight">
                                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
                            </div>
                        </div>
                            <DataTable :headers="headers" :items="noseri" :search="search" v-if="!loading" />
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