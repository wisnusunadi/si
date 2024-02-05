<script>
import axios from 'axios';
import seriviatext from '../../../gbj/page/PermintaanReworkGBJ/permintaan/formPermintaan/seriviatext.vue';
import modalPilihan from './modalPilihan.vue';
export default {
    components: {
        seriviatext,
        modalPilihan
    },
    data() {
        return {
            noseri: [],
            loadingNoSeri: false,
            showModal: false,
            keperluan: '',
            isDisabled: false,
            showModalPrinter: false,
            idCetakHasilGenerate: null,
            loading: false,
        }
    },
    methods: {
        closeModal() {
            $('.modalcetak').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
                this.$emit('refresh');
            });
        },
        removeSeri(index) {
            this.noseri.splice(index, 1);
        },
        autoTab(event, idx) {
            event.target.value = event.target.value.toUpperCase();
            this.noseri.find((item) => {
                if (item.error) {
                    delete item.error;
                    delete item.message;
                }
            });
            const noseri = this.noseri.filter((item) => {
                return item.noseri !== null && item.noseri !== ''
            })

            const noseriUnique = [...new Set(noseri.map((item) => item.noseri))];

            if (noseri.length !== noseriUnique.length) {
                this.noseri[idx].error = true;
                this.noseri[idx].message = 'No. Seri tidak boleh sama';
                this.$swal({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No. Seri tidak boleh sama',
                    timer: 1000,
                    showConfirmButton: false,
                });
                this.loadingNoSeri = true;
                this.$nextTick(() => {
                    this.loadingNoSeri = false;
                    setTimeout(() => {
                        this.$refs.noseri[idx].focus();
                    }, 100);
                });
                return
            } else {
                this.loadingNoSeri = true;
                delete this.noseri[idx].error;
                this.$nextTick(() => {
                    this.loadingNoSeri = false;
                    setTimeout(() => {
                        this.$refs.noseri[idx + 1].focus();
                    }, 100);
                });
            }

            if (idx < this.noseri.length - 1) {
                this.$refs.noseri[idx + 1].focus();
            } else {
                this.$refs.noseri[idx].blur();
                // this.simpan();
            }
        },
        submit(noseri) {
            let noseriarray = noseri.split(/[\n, \t]/)
            let noseridouble = []

            noseriarray.filter((item) => {
                return item !== null && item !== ''
            })

            noseriarray.forEach((item, index) => {
                if (noseriarray.indexOf(item) !== index) {
                    noseridouble.push(item)
                }
            })

            if (noseridouble.length > 0) {
                this.$swal('Peringatan!', `No. Seri ${noseridouble.join(', ')} duplikasi`, 'warning')
            }

            noseriarray = [...new Set(noseriarray)];

            for (let i = 0; i < noseriarray.length; i++) {
                if (this.noseri.length > 0) {
                    let found = false
                    for (let j = 0; j < this.noseri.length; j++) {
                        if (noseriarray[i].toUpperCase() === this.noseri[j].noseri) {
                            found = true
                        }
                    }
                    if (!found) {
                        this.noseri.push({ noseri: noseriarray[i].toUpperCase() })
                    }
                } else {
                    this.noseri.push({ noseri: noseriarray[i].toUpperCase() })
                }
            }

            this.noseri = this.noseri.filter((item) => {
                return item.noseri !== null && item.noseri !== ''
            })
        },
        showSeriText() {
            this.showModal = true;
            $('.modalcetak').modal('hide');
            this.$nextTick(() => {
                $('.modalChecked').modal('show');
            });
        },
        closeModalSeri() {
            this.showModal = false;
            this.$nextTick(() => {
                $('.modalcetak').modal('show');
            });
        },
        cetakSeri() {
            this.showModalPrinter = true;
            this.$nextTick(() => {
                $('.modalcetak').modal('hide');
                $('.modalPilihan').modal('show');
            });
        },
        closeModalCetak() {
            this.showModalPrinter = false;
            this.$nextTick(() => {
                $('.modalcetak').modal('show');
            });
        },
        async simpan() {
            const cekKeperluan = this.keperluan.trim();
            if (cekKeperluan === '') {
                this.$swal('Peringatan!', 'Keperluan tidak boleh kosong', 'warning');
                return;
            }

            this.noseri.find((item) => {
                if (item.error) {
                    delete item.error;
                    delete item.message;
                }
            });

            const ceknoserinull = this.noseri.filter((item) => {
                return item.noseri === null || item.noseri === ''
            })

            if (ceknoserinull.length == this.noseri.length) {
                this.$swal('Peringatan!', 'No. Seri tidak boleh kosong', 'warning');
                return;
            }

            const noseri = this.noseri.filter((item) => {
                return item.noseri !== null && item.noseri !== ''
            })

            const noseruUnique = [...new Set(noseri.map((item) => item.noseri))];

            if (noseruUnique.length !== noseri.length) {
                this.noseri = this.noseri.map((item) => {
                    if (this.noseri.findIndex((item2) => item2.noseri === item.noseri) !== this.noseri.lastIndexOf(item)) {
                        item.error = true;
                        item.message = 'No. Seri tidak boleh sama';
                    }
                    return item;
                })
                this.$swal({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No. Seri tidak boleh sama',
                    timer: 1000,
                    showConfirmButton: false,
                });
                this.loadingNoSeri = true;
                this.$nextTick(() => {
                    this.loadingNoSeri = false;
                });
                return
            } else {
                this.loadingNoSeri = true;
                this.noseri = this.noseri.map((item) => {
                    delete item.error;
                    delete item.message;
                    return item;
                })
                this.$nextTick(() => {
                    this.loadingNoSeri = false;
                });
            }

            const form = {
                noseri: this.noseri,
                keperluan: this.keperluan,
            }

            try {
                this.isDisabled = true;
                this.loading = true;
                const { data } = await axios.post('/api/prd/fg/non_stok/gen', form, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + localStorage.getItem('lokal_token')
                    }
                })
                const { message, created_at } = data
                const tgl = moment(created_at).format('YYYY-MM-DD HH:mm:ss')
                this.idCetakHasilGenerate = tgl
                this.$swal('Berhasil!', message, 'success');
            } catch (error) {
                const { message, duplicate } = error.response.data

                if (duplicate) {
                    this.noseri = this.noseri.map((item) => {
                        item.noseri = item.noseri.trim()
                        const find = duplicate.find((data) => data === item.noseri)
                        if (find) {
                            return {
                                ...item,
                                error: true,
                                message
                            }
                        }
                        return item
                    })
                }

                this.$swal('Peringatan!', message, 'warning');

                this.isDisabled = false;
                this.loading = false;
            } finally {
                this.loading = false;
            }
        },
    },
}
</script>
<template>
    <div>
        <seriviatext v-if="showModal = true" @submit="submit" @close="closeModalSeri"></seriviatext>
        <modalPilihan v-if="showModalPrinter" @closeModal="closeModalCetak" :data="idCetakHasilGenerate" />
        <div class="modal fade modalcetak" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Tambah Nomor Seri</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Keperluan</label>
                                    <textarea cols="5" class="form-control" v-model="keperluan"
                                        :disabled="isDisabled"></textarea>
                                </div>
                                <div class="d-flex bd-highlight mb-3" v-if="!isDisabled">
                                    <div class="p-2 bd-highlight">
                                        <button class="btn btn-primary" @click="showSeriText">Input No Seri Via
                                            Text</button>
                                    </div>
                                    <div class="ml-auto p-2 bd-highlight">
                                        <button class="btn btn-info" @click="noseri.push({ noseri: '' })">Tambah No.
                                            Seri</button>
                                    </div>
                                </div>
                                <div class="scrollable">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No. Seri</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="!loadingNoSeri">
                                            <tr v-for="(seri, index) in noseri" :key="index">
                                                <td>
                                                    <input type="text" class="form-control" v-model="seri.noseri"
                                                        ref="noseri" :class="seri.error ? 'is-invalid' : ''"
                                                        :disabled="isDisabled" @keyup.enter="autoTab($event, index)"
                                                        @keyup="$event.target.value = $event.target.value.toUpperCase()">
                                                    <div class="invalid-feedback" v-if="seri.error">
                                                        {{ seri.message }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <button class="btn btn-outline-danger" @click="removeSeri(index)"
                                                        :disabled="isDisabled">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex bd-highlight mb-3">
                                    <div class="p-2 bd-highlight">
                                        <button class="btn btn-primary" @click="simpan" v-if="!idCetakHasilGenerate">
                                            <div class="spinner-border spinner-border-sm" role="status" v-if="loading">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            {{ loading ? 'Loading...' : 'Simpan' }}
                                        </button>
                                        <button class="btn btn-success" @click="cetakSeri" v-else>Cetak</button>
                                    </div>
                                    <div class="ml-auto p-2 bd-highlight">
                                        <button class="btn btn-secondary" @click="closeModal">Batal</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
