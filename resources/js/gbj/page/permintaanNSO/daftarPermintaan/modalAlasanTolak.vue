<script>
export default {
    data() {
        return {
            alasan: ''
        }
    },
    methods: {
        closeModal() {
            $('.modalTolak').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        simpan() {
            if (!this.alasan) {
                this.$swal({
                    title: 'Peringatan!',
                    text: 'Alasan tidak boleh kosong!',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                })
                return
            }

            this.$swal({
                title: 'Apakah anda yakin?',
                text: 'Data yang sudah ditolak tidak dapat diubah!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal(
                        'Ditolak!',
                        'Data berhasil ditolak.',
                        'success'
                    )
                    $('.modalDetail').modal('hide')
                    this.$nextTick(() => {
                        this.showModal = false
                    })
                }
            })
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
                    <h5 class="modal-title">Alasan Ditolak</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Keterangan</label>
                        <textarea class="form-control" v-model="alasan" rows="3"></textarea>
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