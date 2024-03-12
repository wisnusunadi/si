<script>
import moment from 'moment'
export default {
    props: ['dataGenerate'],
    data() {
        return {
            form: {
                kode: 'PRD',
                urutan: '02',
                kode_produk: this.dataGenerate.kode_produk,
                bulan: '',
                // dapatkan angka 2 digit ke belakang
                tahun: new Date().getFullYear().toString().substr(-2),
            },
            formHasilGenerate: {
                hasilGen: '',
                tgl: '',
            },
            optionKode: [
                'PRD',
                'PBK',
                'SPB',
            ]
        }
    },
    methods: {
        closeModal() {
            $('.modalGenerateBPPB').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        isFormFilled(obj) {
            for (const key in obj) {
                if (obj.hasOwnProperty(key)) {
                    let value = obj[key];
                    if (typeof value === 'string') {
                        value = value.trim()
                    }

                    if (typeof value === 'object') {
                        if (!this.isFormFilled(value)) {
                            return false
                        }
                    } else if (value === '') {
                        return false
                    }
                }
            }
            return true
        },
        simpanOut() {
            const cekForm = Object.keys(this.formHasilGenerate).every(key => this.formHasilGenerate[key] !== '')
            if (cekForm) {
                // Simpan data ke BE
                // konfirmasi simpan data
                swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang sudah disimpan tidak dapat diubah lagi",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan & keluar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        swal.fire('Berhasil', 'Data berhasil disimpan', 'success')
                        this.closeModal()
                        this.$emit('refresh')
                    }
                })
            } else {
                swal.fire('Peringatan', 'Pastikan semua form terisi', 'warning')
            }
        },
        simpanGenerate() {
            const cekForm = Object.keys(this.formHasilGenerate).every(key => this.formHasilGenerate[key] !== '')
            if (cekForm) {
                // Simpan data ke BE
                swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data yang sudah disimpan tidak dapat diubah lagi",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan & keluar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        swal.fire('Berhasil', 'Data berhasil disimpan', 'success')
                        this.closeModal()
                        this.$emit('openModalGenerate')
                    }
                })
            } else {
                swal.fire('Peringatan', 'Pastikan semua form terisi', 'warning')
            }
        }
    },
    computed: {
        monthCalc() {
            let month = []
            let monthNow = moment().month()
            // Function to convert numeric value to Roman numeral
            const toRoman = (num) => {
                const romanNumerals = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
                return romanNumerals[num - 1] || num;
            };
            for (let i = monthNow; i <= monthNow + 1; i++) {
                month.push({
                    // change value to romawi
                    value: toRoman(i),
                    label: moment().month(i - 1).lang('id').format('MMMM')
                })
            }
            return month
        },
        cekFormFilled() {
            return this.isFormFilled(this.form)
        }
    },
    watch: {
        form: {
            handler(newForm) {
                // Update formHasilGenerate based on the new form values
                if (this.cekFormFilled) {
                    this.formHasilGenerate.hasilGen = `${newForm.kode}/${newForm.urutan}-${newForm.kode_produk}/${newForm.bulan.value}/${newForm.tahun}`;
                    this.formHasilGenerate.tgl = moment().format('YYYY-MM-DD');
                } else {
                    this.formHasilGenerate.hasilGen = '';
                    this.formHasilGenerate.tgl = '';
                }
            },
            deep: true // Enable deep watching for nested properties
        }
    }
}
</script>

<template>
    <div class="modal fade modalGenerateBPPB" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Form Generate BPPB</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="">Kode</label>
                                <select class="form-control" v-model="form.kode" disabled>
                                    <option v-for="item in optionKode" :key="item">{{ item }}</option>
                                </select>
                            </div>
                            <!-- nanti di disable ngambil dari BE -->
                            <div class="form-group">
                                <label for="">No Urut</label>
                                <input type="text" class="form-control" v-model="form.urutan" disabled>
                            </div>
                            <div class="form-group">
                                <label for="">Kode Produk</label>
                                <input type="text" class="form-control" v-model="form.kode_produk" disabled>
                            </div>
                            <div class="form-group">
                                <label for="">Bulan</label>
                                <v-select v-model="form.bulan" :options="monthCalc"></v-select>
                            </div>
                            <div class="form-group">
                                <label for="">Tahun</label>
                                <input type="text" :value="new Date().getFullYear().toString()" class="form-control"
                                    disabled>
                            </div>
                        </div>
                        <div class="col" v-if="cekFormFilled">
                            <div class="form-group">
                                <label for="">No BPPB</label>
                                <div class="card">
                                    <div class="card-body">
                                        <span>{{ formHasilGenerate.hasilGen }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Tanggal BPPB</label>
                                <input type="date" class="form-control" :value="formHasilGenerate.tgl"
                                    :min="new Date().getFullYear() + '-01-01'"
                                    :max="new Date().toISOString().split('T')[0]">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <button type="button" class="btn btn-success" @click="simpanGenerate">Simpan &
                                Generate</button>
                            <button type="button" class="btn btn-primary" @click="simpanOut">Simpan & Keluar</button>
                        </div>
                        <div class="p-2 bd-highlight">
                            <button type="button" class="btn btn-secondary" @click="closeModal">Keluar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>