<script>
export default {
    props: ["dataCetak"],
    data() {
        return {
            hal: 1,
        };
    },
    methods: {
        closeModal() {
            $('.modalPage').modal('hide');
            this.$emit('closeModal');
        },
        numberOnly(e) {
            const charCode = (e.which) ? e.which : e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                e.preventDefault();
            }
        },
        async simpan() {
            // open window href new tab
            const jenis = this.dataCetak.jenis;
            const id = this.dataCetak.id;
            const ttd = this.dataCetak.ttd;

            switch (jenis) {
                case 'po':
                    window.open(`/api/labs/cetak/po/${id}/${ttd}/${this.hal}`, '_blank');
                    break;
                case 'produk':
                    window.open(`/api/labs/cetak/produk/${id}/${ttd}/${this.hal}`, '_blank');
                    break;
                case 'seri':
                    window.open(`/api/labs/cetak/seri/${id}/${ttd}/${this.hal}`, '_blank');
                    break;
                default:
                    break;
            }
        }
    },
}
</script>
<template>
    <div class="modal fade modalPage" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Batas Halaman</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                      <label class="col-4">Batas Halaman</label>
                      <input type="text" class="form-control col-8" v-on:keypress="numberOnly" v-model="hal" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>