<script>
export default {
    props: ['dataBatal'],
    data() {
        return {
            alasan: ''
        }
    },
    methods: {
        simpan() {
            this.$emit('submit', this.alasan)
            this.$swal({
                title: 'Apakah anda yakin?',
                text: 'Data yang sudah dibatalkan tidak dapat dikembalikan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal('Berhasil', 'Data berhasil dibatalkan', 'success')
                    this.$emit('refresh')
                    this.$emit('closeAllModal')
                    this.closeModal()
                }
            })
        },
        closeModal() {
            $('.modalAlasanBatal').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
            })
        }
    },
}
</script>
<template>
    <div class="modal fade modalAlasanBatal" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alasan Batal</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Alasan Batal:</label>
                        <textarea class="form-control" cols="5" v-model="alasan"></textarea>
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