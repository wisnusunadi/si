<script>
import axios from 'axios'
export default {
    props: ['produk'],
    data() {
        return {
            loading: false,
            detailProduk: {},
            search: '',
            noSeriSelected: [],
            tanggal_kirim: new Date().toISOString().substr(0, 10),
            headers: [
                {
                    text: 'id',
                    value: 'id',
                    align: 'text-left',
                    sortable: false,
                },
                {
                    text: 'Nomor Seri',
                    value: 'noseri',
                    align: 'text-left'
                }
            ],
            form: {
                tanggal_uji: '',
                hasil_cek: '',
            },
            noseriProduk: [],
            checkAll: false,
        }
    },
    methods: {
        closeModal() {
            $('.modalUji').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        async getDetailProduk() {
            try {
                this.loading = true
                const { data: header } = await axios.get(`/api/prd/headerSeri/${this.produk.id}`)
                const { data: seri } = await axios.get(`/api/prd/detailSeri1/${this.produk.produk_id}/${this.produk.id}`)
                this.detailProduk = {
                    header,
                }
                this.noseriProduk = seri
            } catch (error) {
                console.log(error)
            } finally {
                this.loading = false
            }
        },
        checkAllSeri() {
            this.checkAll = !this.checkAll
            if (this.checkAll) {
                this.noSeriSelected = this.noseriProduk.map(item => item)
            } else {
                this.noSeriSelected = []
            }
        },
        selectNoSeri(noseri) {
            if (this.noSeriSelected.find((data) => data === noseri)) {
                this.noSeriSelected = this.noSeriSelected.filter((data) => data.id !== noseri.id)
            } else {
                this.noSeriSelected.push(noseri)
            }
        },
        simpan() {
            const cekFormNotNull = Object.values(this.form).every((data) => data !== '' && data !== null)

            if (cekFormNotNull && this.noSeriSelected.length > 0) {
                const data = {
                    ...this.form,
                    noseri: this.noSeriSelected.map((data) => data.id)
                }
                this.$emit('simpan', data)
            } else {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Form Tidak Boleh Kosong',
                })
            }
        }
    },
    created() {
        this.getDetailProduk()
    },
    watch: {
        noSeriSelected() {
            if (this.noSeriSelected.length === this.noseriProduk.length) {
                this.checkAll = true
            } else {
                this.checkAll = false
            }
        }
    }
}
</script>
<template>
    <div class="modal fade modalUji" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card" v-if="!loading">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">Info Produk</div>
                                        <div class="card-body text-center">
                                            <b>{{ detailProduk?.header.produk }}</b>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">Info Perakitan</div>
                                        <div class="card-body">
                                            <div class="margin">
                                                <div><small class="text-muted">Nomor BPPB</small></div>
                                                <div><b>{{ detailProduk?.header.bppb }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Jumlah Rakit</small></div>
                                                <div><b>{{ detailProduk?.header.jumlah }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Tanggal Mulai</small></div>
                                                <div><b>{{ detailProduk?.header.start }}</b></div>
                                            </div>
                                            <div class="margin">
                                                <div><small class="text-muted">Tanggal Selesai</small></div>
                                                <div><b>{{ detailProduk?.header.end }}</b></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Tanggal
                                                    Uji</label>
                                                <div class="col-5">
                                                    <input type="date" class="form-control  col-form-label"
                                                        v-model="form.tanggal_uji" name="tanggal_uji" id="tanggal_uji"
                                                        :max="new Date().toISOString().substr(0, 10)">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Hasil
                                                    Cek</label>
                                                <div class="col-7 col-form-label">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cek" id="yes"
                                                            v-model="form.hasil_cek" value="ok">
                                                        <label class="form-check-label" for="yes"><i
                                                                class="fas fa-check-circle text-success"></i> OK</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="cek" id="no"
                                                            v-model="form.hasil_cek" value="nok">
                                                        <label class="form-check-label" for="no"><i
                                                                class="fas fa-times-circle text-danger"></i> Tidak
                                                            OK</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex bd-highlight mb-3">
                                                <div class="mr-auto p-2 bd-highlight">
                                                    <!-- <button class="btn btn-primary" @click="showSeriText">
                                                Pilih Nomor Seri Via Text
                                            </button> -->
                                                </div>
                                                <div class="p-2 bd-highlight">
                                                    <input type="text" v-model="search" class="form-control"
                                                        placeholder="Cari Nomor Seri">
                                                </div>
                                            </div>
                                            <data-table :headers="headers" :items="noseriProduk" :search="search">
                                                <template #header.id>
                                                    <input type="checkbox" :checked="checkAll" @click="checkAllSeri">
                                                </template>

                                                <template #item.id="{ item }">
                                                    <div>
                                                        <input type="checkbox" @click="selectNoSeri(item)"
                                                            :checked="noSeriSelected && noSeriSelected.find((data) => data.id === item.id)">
                                                    </div>
                                                </template>
                                            </data-table>
                                        </div>
                                        <div class="card-footer">
                                            No Seri Yang Dipilih : {{ noSeriSelected.length }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight">
                                    <button class="btn btn-success" @click="simpan">Simpan</button>
                                </div>
                                <div class="p-2 bd-highlight">
                                    <button class="btn btn-secondary" @click="closeModal">Keluar</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="spinner-border" role="status" v-else>
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>