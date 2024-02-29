<script>
export default {
    props: ['header'],
    data() {
        return {
            alamat: JSON.parse(JSON.stringify(this.header.alamat)),
        }
    },
    methods: {
        closeModal() {
            $('.modalAlamat').modal('hide');
            this.$nextTick(() => {
                this.$emit('close')
            })
        }
    },
}
</script>
<template>
    <div class="modal fade modalAlamat" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Alamat</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <textarea class="form-control" v-model="alamat"></textarea>
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