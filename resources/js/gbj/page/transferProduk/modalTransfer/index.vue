<script>
import axios from 'axios'
export default {
    props: ['data'],
    data() {
        return {
            search: '',
            produk: [],
        }
    },
    methods: {
        closeModal() {
            $('.modalTransfer').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        async getData() {
            try {
                const { data } = await axios.get(`/api/tfp/detail-so/${this.data.pesanan_id}/${this.data.button}`)
                this.produk = data
            } catch (error) {
                console.log(error)
            }
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div class="modal fade modalTransfer" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer Produk</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Nomor SO</label>
                                    <div class="card text-white" style="background-color: #717FE1;">
                                        <div class="card-body">
                                            <span id="so">{{ data.so }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Nomor AKN</label>
                                    <div class="card text-white" style="background-color: #DF7458;">
                                        <div class="card-body">
                                            <span id="akn">{{ data.ekatalog?.no_paket ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Nomor PO</label>
                                    <div class="card text-white" style="background-color: #85D296;">
                                        <div class="card-body">
                                            <span id="po">{{ data.no_po }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row-reverse bd-highlight">
                                <div class="p-2 bd-highlight">
                                    <input type="text" class="form-control" v-model="search">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Transfer</button>
                    <button class="btn btn-info">Batalkan Persiapan</button>
                    <button class="btn btn-secondary" @click="closeModal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</template>