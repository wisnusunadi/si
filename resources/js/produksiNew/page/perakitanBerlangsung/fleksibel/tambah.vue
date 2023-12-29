<script>
import axios from 'axios'
export default {
    props: ['detailRakit'],
    data() {
        return {
            form: {
                no_bppb: '',
                produk: '',
                jml: ''
            },
            produk: [],
        }
    },
    methods: {
        closeModal() {
            $('.modalFleksibel').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        async getData() {
            try {
                const { produk } = await axios.get('/api/produk').then(res => res.data);
                this.produk = produk.map(item => {
                    return {
                        label: item.nama,
                        value: item.id
                    }
                });
            } catch (error) {
                console.log(error);
            }
        },
        simpan() {
            // validasi
            const cekForm = Object.values(this.form).filter(item => item === '')
            if (cekForm.length > 0) {
                this.$swal('Perhatian', 'Form tidak boleh kosong', 'warning')
                return
            }

            // simpan
            this.$swal({
                title: 'Apakah anda yakin?',
                text: 'Data akan disimpan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal',
            }).then(async (result) => {
                if (result.value) {
                    this.$swal('Berhasil', 'Data berhasil disimpan', 'success')
                    this.closeModal()
                }
            })
        }
    },
    created() {
        this.getData()
    },
}
</script>
<template>
    <div class="modal fade modalFleksibel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Perakitan Fleksibel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">No BPPB</label>
                        <input type="text" v-model="form.no_bppb" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="">Nama Produk</label>
                        <v-select :options="produk" v-model="form.produk" placeholder="Pilih Produk"></v-select>
                    </div>

                    <div class="form-group">
                        <label for="">Jumlah Rakit</label>
                        <input type="text" class="form-control" v-model="form.jml" @keypress="numberOnly($event)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                    <button type="button" class="btn btn-secondary" @click="closeModal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</template>