<script>
import moment from 'moment'
export default {
    props: ['noseri', 'header'],
    data() {
        return {
            form: {
                tgl_uji: '',
                // get time now
                waktu_uji: moment().format('HH:mm'),
                hasil: ''
            },
            dateMax: new Date().toISOString().split('T')[0],
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'No Seri',
                    value: 'noseri'
                }
            ],
            search: '',
        }
    },
    methods: {
        closeModal() {
            $('.modalIncoming').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        simpan() {
            const cekForm = Object.values(this.form).every(val => val !== '')
            if (cekForm) {
                this.$swal('Berhasil!', 'Data berhasil disimpan.', 'success')
                const produk = {
                    ...this.header,
                    ...this.form,
                    noseri: this.noseri
                }
                console.log(produk)
                this.closeModal()
            } else {
                this.$swal('Peringatan!', 'Form tidak boleh kosong.', 'warning')
            }
        }
    },
}
</script>
<template>
    <div class="modal fade modalIncoming" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Pengujian</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <div class="card">
                                <div class="card-header">Info Perakitan</div>
                                <div class="card-body">
                                    <div class="margin">
                                        <div><small class="text-muted">No BPPB</small></div>
                                        <div><b>{{ header.bppb }}</b></div>
                                    </div>
                                    <div class="margin">
                                        <div><small class="text-muted">Nama Produk</small></div>
                                        <div><b>{{ header.produk }}</b></div>
                                    </div>
                                    <div class="margin">
                                        <div><small class="text-muted">Jumlah</small></div>
                                        <div><b>{{ header.jumlah }} Unit</b></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <div class="form-group row">
                                        <label for="" class="col-5 col-form-label text-right">Tanggal Uji</label>
                                        <div class="col-5">
                                            <input type="date" class="form-control" :max="dateMax" v-model="form.tgl_uji" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-5 col-form-label text-right">Waktu Uji</label>
                                        <div class="col-5">
                                            <time-picker v-model="form.waktu_uji" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <legend for="" class="text-bold col-5 col-form-label text-right pt-0">Hasil</legend>
                                        <div class="col-5">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                    id="inlineRadio1" value="ok" v-model="form.hasil">
                                                <label class="form-check-label" for="inlineRadio1">
                                                    <i class="fa fa-check-circle text-success"></i>
                                                    Lolos
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                    id="inlineRadio2" value="nok" v-model="form.hasil">
                                                <label class="form-check-label" for="inlineRadio2">
                                                    <i class="fa fa-times-circle text-danger"></i>
                                                    Tidak Lolos
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-row-reverse bd-highlight">
                                        <div class="p-2 bd-highlight">
                                            <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                                        </div>
                                    </div>
                                    <data-table :headers="headers" :items="noseri" :search="search">
                                        <template #item.no="{ item, index }">
                                            {{ index + 1 }}
                                        </template>
                                    </data-table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                    <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
