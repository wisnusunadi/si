<script>
export default {
    props: ['detail'],
    methods: {
        closeModal() {
            $('.modalProduk').modal('hide');
            this.$nextTick(() => {
                this.$emit('close')
            })
        }
    },
}
</script>
<template>
    <div class="modal fade modalProduk" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Batal Produk</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm"><label for="">Nomor SO</label>
                                    <div class="card text-white" style="background-color: rgb(113, 127, 225);">
                                        <div class="card-body"><span id="so">{{ detail.so ?? '-' }}</span></div>
                                    </div>
                                </div>
                                <div class="col-sm"><label for="">Nomor PO</label>
                                    <div class="card text-white" style="background-color: rgb(223, 116, 88);">
                                        <div class="card-body"><span id="akn">{{ detail.po ?? '-' }}</span></div>
                                    </div>
                                </div>
                                <div class="col-sm"><label for="">Customer</label>
                                    <div class="card text-white" style="background-color: rgb(133, 210, 150);">
                                        <div class="card-body"><span id="po">{{ detail.customer ?? '-' }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">Title</h4>
                            <p class="card-text">Text</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>