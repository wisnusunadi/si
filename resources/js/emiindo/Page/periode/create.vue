<script>
import axios from 'axios';
export default {
    data() {
        return {
            form: {
                years: new Date().getFullYear() - 1,
                maxOpenDay: 0,
                alasan: ''
            },
            persetujuan: false,
        }
    },
    methods: {
        // fungsi untuk menyimpan data
        simpan() {
            const ceknotnull = Object.values(this.form).every(x => x !== null && x !== '');
            if (ceknotnull) {
                this.$swal({
                    title: 'Apakah anda yakin?',
                    text: "Anda akan mengajukan perubahan periode penjualan",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            await axios.post('/api/master/buka_periode/permintaan', this.form, {
                                headers: {
                                    'Authorization': 'Bearer ' + localStorage.getItem('lokal_token')
                                }
                            })
                            swal.fire({
                                title: 'Berhasil!',
                                text: 'Pengajuan periode penjualan telah dikirim',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            this.$emit('refresh');
                            this.closeModal();
                        } catch (error) {
                            console.log(error);
                            swal.fire({
                                title: 'Gagal!',
                                text: `${error.response.data.message}`,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    }
                })
            } else {
                this.$swal({
                    title: 'Gagal!',
                    text: 'Data tidak boleh kosong',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        },
        // Close modal
        closeModal() {
            $('.modalPeriode').modal('hide');
            this.$nextTick(() => {
                this.$nextTick(() => {
                    this.$emit('closeModal');
                })
            })
        }
    },
    computed: {
        // get 5 years before now
        get5YearsBeforeNow() {
            let years = [];
            let currentYear = new Date().getFullYear() - 1;
            for (let i = 0; i < 5; i++) {
                years.push(currentYear - i);
            }
            return years;
        },
        // calculate days years selected
        calculateDaysYearsSelected() {
            let years = this.years;
            let days = 0;
            if (years % 4 == 0) {
                days = 366;
            } else {
                days = 365;
            }
            return days;
        }
    },
    watch: {
        'form.maxOpenDay': function (val) {
            if (val < 0) {
                this.form.maxOpenDay = 0;
            } else if (val > this.calculateDaysYearsSelected) {
                this.form.maxOpenDay = this.calculateDaysYearsSelected;
            }
        }
    }
}
</script>
<template>
    <div class="modal fade modalPeriode" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Periode Penjualan</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body" v-if="!persetujuan">
                            <div class="form-group row">
                                <label for=""
                                    class="col-lg-5 col-md-12 col-sm-12 col-form-label text-right">Periode</label>
                                <v-select class="col-lg-3 col-md-8 col-sm-12" :options="get5YearsBeforeNow"
                                    v-model="form.years"></v-select>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-md-12 col-sm-12 col-form-label text-right">Durasi
                                    buka</label>
                                <div class="col-lg-3 col-md-8 col-sm-12">
                                    <input type="text" v-model="form.maxOpenDay" class="form-control"
                                        @keypress="numberOnly">
                                    <small><span class="text-danger">*</span>Dalam hitungan hari</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-5 col-md-12 col-sm-12 col-form-label text-right">Alasan</label>
                                <div class="col-lg-3 col-md-8 col-sm-12">
                                    <textarea cols="5" v-model="form.alasan" class="form-control "></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" v-else>
                            <div class="alert alert-success" role="alert">
                                <h4 class="alert-heading">Pengajuan periode penjualan telah dikirim</h4>
                                <hr>
                                <p>Silahkan menunggu persetujuan dari IT</p>
                            </div>
                        </div>
                        <div class="card-footer" >
                            <div class="d-flex bd-highlight mb-3">
                                <div class="p-2 bd-highlight">
                                    <button class="btn btn-primary" @click="simpan" v-if="!persetujuan">Simpan</button>
                                </div>
                                <div class="ml-auto p-2 bd-highlight">
                                    <button class="btn btn-secondary" @click="closeModal">Keluar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.alert {
    background-color: #9effb5;
    color: #464E5F;
}
</style>