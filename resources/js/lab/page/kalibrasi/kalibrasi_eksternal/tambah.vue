<script>
import axios from 'axios'
export default {
    data() {
        return {
            alat: [],
            produk: [],
            form: {
                nama_alat: '',
                tipe_alat: '',
                nama_pemilik: '',
                nama_pemilik_sertifikat: '',
                alamat: '',
                nama_customer: '',
                nomor_referensi: '',
                merk: ''
            },
            headers: [
                {
                    text: 'Nama Produk',
                    value: 'nama_produk'
                },
                {
                    text: 'Nomor Seri',
                    value: 'noseri'
                }
            ],
            items: [
                {
                    nama_produk: '123',
                    noseri: '123'
                }
            ]
        }
    },
    methods: {
        closeModal() {
            $('#modelId').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        async getData() {
            try {
                const { data: alat } = await axios.get('/api/labs/kode').then(res => res.data)
                const { produk } = await axios.get('/api/produk').then(res => res.data)
                this.alat = alat
                this.produk = produk
            } catch (error) {
                console.log(error)
            }
        },
        simpan() {
            const cekNotNull = Object.values(this.form).every((val) => val !== '' && val !== null && val !== undefined)
            if (cekNotNull) {
                this.$emit('simpan', this.form)
                this.closeModal()
            } else {
                this.$swal('Error', 'Form tidak boleh kosong', 'error')
            }
        }
    },
    created() {
        this.getData()
    }
}
</script>
<template>
    <div class="modal fade" id="modelId" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Kalibrasi Eksternal</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Nama Alat</label>
                                <v-select :options="alat" :reduce="alat => alat.id" label="nama"
                                    v-model="form.nama_alat"></v-select>
                            </div>
                            <div class="form-group">
                                <label for="">Tipe Alat</label>
                                <v-select :options="produk" :reduce="produk => produk.id" label="nama"></v-select>
                            </div>
                            <div class="form-group">
                                <label for="">Nama Pemilik</label>
                                <input type="text" class="form-control" v-model="form.nama_pemilik">
                            </div>
                            <div class="form-group">
                                <label for="">Nama Pemilik Sertifikat</label>
                                <input type="text" class="form-control" v-model="form.nama_pemilik_sertifikat">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="">Alamat</label>
                                <input type="text" class="form-control" v-model="form.alamat">
                            </div>
                            <div class="form-group">
                                <label for="">Nama Customer</label>
                                <input type="text" class="form-control" v-model="form.nama_customer">
                            </div>
                            <div class="form-group">
                                <label for="">Nomor Referensi</label>
                                <input type="text" class="form-control" v-model="form.nomor_referensi">
                            </div>
                            <div class="form-group">
                                <label for="">Merk</label>
                                <input type="text" class="form-control" v-model="form.merk">
                            </div>
                        </div>
                    </div>
                    <h5>Nomor Seri</h5>
                    <data-table :headers="headers" :items="items">
                        <template #item.nama_produk="{ item }">
                            <div>
                                <v-select v-model="item.nama_produk" placeholder="Pilih Produk"></v-select>
                            </div>
                        </template>
                        <template #item.noseri="{ item }">
                            <div>
                                <v-select v-model="item.noseri" placeholder="Pilih Nomor Seri"></v-select>
                            </div>
                        </template>
                    </data-table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>