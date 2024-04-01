<script>
export default {
    props: ['produk'],
    data() {
        return {
            catatan: ''
        }
    },
    methods: {
        closeModal() {
            $('.modalCatatan').modal('hide')
            this.$nextTick(() => {
                this.$emit('close')
            })
        },
        simpan() {
            if (this.catatan == '') {
                swal.fire({
                    title: 'Peringatan',
                    text: 'Catatan tidak boleh kosong',
                    icon: 'warning'
                });
                return;
            }

            swal.fire({
                title: 'Simpan',
                text: 'Apakah anda yakin ingin menyimpan data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = {
                        produk: this.produk,
                        catatan: this.catatan
                    }
                    console.log(form)
                    swal.fire(
                        'Berhasil',
                        'Data berhasil disimpan',
                        'success'
                    )
                    this.$emit('closeAll')
                    this.closeModal()
                }
            });
        }
    },
}
</script>
<template>
    <div class="modal fade modalCatatan" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Catatan Permintaan</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Catatan</label>
                        <textarea v-model="catatan" cols="5" class="form-control"></textarea>
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