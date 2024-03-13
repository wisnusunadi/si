<script>
export default {
    props: ['dataGenerate'],
    data() {
        return {
            keterangan: '',
        }
    },
    methods: {
        closeModal() {
            $('.closeBPPB').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        simpan() {
            const cek = this.keterangan.trim()
            if (cek === '') {
                swal.fire('Perhatian', 'Alasan Close / Pembatalan BPPB tidak boleh kosong', 'warning')
                return
            } else {
                swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Ingin Menyimpan Alasan Close / Pembatalan BPPB, Pastikan Alasan Sudah Benar",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Tidak, Batalkan!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        swal.fire('Berhasil', 'Alasan Close / Pembatalan BPPB Berhasil Disimpan', 'success')
                        this.closeModal()
                    }
                })
            }
        }
    },
}
</script>
<template>
    <div class="modal fade closeBPPB" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Close / Batal BPPB</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Alasan Close / Pembatalan BPPB</label>
                        <textarea class="form-control" cols="5" v-model="keterangan"></textarea>
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