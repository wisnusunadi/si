<script>
export default {
    data() {
        return {
            alasan: null
        }
    },
    methods: {
        closeModal() {
            $('.modalAlasan').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        simpan() {
            if (!this.alasan) {
                swal.fire('Peringatan', 'Alasan tidak boleh kosong', 'warning')
            } else {
                swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang sudah disimpan tidak dapat diubah!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        swal.fire(
                            'Berhasil!',
                            'Data berhasil disimpan.',
                            'success'
                        )
                        this.closeModal()
                    }
                })
            }
        }
    },
}
</script>
<template>
    <div class="modal fade modalAlasan" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Tidak Disetujui</h5>
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