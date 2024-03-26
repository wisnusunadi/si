<script>
export default {
    props: ['permintaan'],
    data() {
        return {
            alasan: ''
        }
    },
    methods: {
        closeModal() {
            $('.modalTolak').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
        simpan() {
            if (this.alasan) {
                console.log(this.alasan)
                this.$emit('closeAllModal')
            } else {
                this.$swal('Peringatan', 'Alasan tidak boleh kosong', 'warning')
            }
        }
    },
}
</script>
<template>
    <div class="modal fade modalTolak" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Alasan Penolakan Permintaan</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Alasan Ditolak</label>
                        <textarea class="form-control" rows="5" v-model="alasan"></textarea>
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