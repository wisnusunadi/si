<script>
export default {
    data() {
        return {
            alasan: ''
        }
    },
    methods: {
        closeModal() {
            $('.modalAlasan').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
        simpan() {
            if (this.alasan == '') {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Alasan tidak boleh kosong!'
                })
                return
            }

            swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Data yang sudah ditolak tidak dapat diubah lagi',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire(
                        'Ditolak!',
                        'Data berhasil ditolak',
                        'success'
                    )
                    this.closeModal()
                }
            })
        }
    }
}
</script>
<template>
    <div class="modal fade modalAlasan" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Batal</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Alasan</label>
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