<script>
import axios from 'axios';
import DataTable from '../../../components/DataTable.vue';
import seriviatext from '../../../../gbj/page/PermintaanReworkGBJ/permintaan/formPermintaan/seriviatext.vue';
import modalPilihan from '../perakitan/modalPilihan.vue'
import moment from 'moment';
export default {
    components: {
        DataTable,
        seriviatext,
        modalPilihan
    },
    props: ['dataGenerate'],
    data() {
        return {
            headers: [
                {
                    text: 'No. Seri',
                    value: 'noseri',
                    align: 'text-left',
                }
            ],
            noseri: [],
            isDisabled: false,
            noseridiisi: 0,
            isError: false,
            loading: false,
            hasilGenerate: [],
            loadingNoSeri: false,
            showmodalviatext: false,
            idCetakHasilGenerate: null,
            showModalCetak: false,
            isDisableBPPB: false
        }
    },
    methods: {
        closeModal() {
            $('.inputNoSeri').modal('hide');
            this.$nextTick(() => {
                this.$emit('refresh');
                this.$emit('closeModal');
            })
        },
        addNoSeri() {
            for (let i = 0; i < this.dataGenerate.kurang; i++) {
                this.noseri.push({
                    noseri: ''
                })
            }
        },
        keyUpperCase(e) {
            this.dataGenerate.no_bppb = e.target.value.toUpperCase()
        },
        autoTab(event, idx) {
            // key upppercase
            event.target.value = event.target.value.toUpperCase();
            this.noseri.find((item) => {
                if (item.error) {
                    delete item.error;
                    delete item.message;
                }
            })

            if (!this.noseri.find((data) => data?.error)) {
                this.isError = false;
            }

            // cek noseri duplikasi
            const noseri = this.noseri.filter((item) => {
                return item.noseri !== null && item.noseri !== ''
            })

            const noseriUnique = noseri.filter((data, index) => {
                return noseri.findIndex((item) => {
                    return item.noseri === data.noseri
                }) === index
            })

            if (noseriUnique.length !== noseri.length) {
                this.noseri[idx].error = true;
                this.noseri[idx].message = 'Nomor seri tidak boleh sama';
                this.$swal({
                    title: 'Peringatan!',
                    text: 'Nomor seri tidak boleh sama',
                    icon: 'warning',
                    timer: 1000, // 1 seconds
                    showConfirmButton: false
                });
                this.loadingNoSeri = true;
                this.$nextTick(() => {
                    this.loadingNoSeri = false;
                    setTimeout(() => {
                        this.$refs.noseri[idx].focus();
                    }, 100);
                })
                return
            } else {
                this.loadingNoSeri = true;
                this.isError = false;
                delete this.noseri[idx].error;
                this.$nextTick(() => {
                    this.loadingNoSeri = false;
                    setTimeout(() => {
                        this.$refs.noseri[idx + 1].focus();
                    }, 100);
                })
            }

            if (idx < this.noseri.length - 1) {
                this.$refs.noseri[idx + 1].focus();
            } else {
                this.$refs.noseri[idx].blur();
                this.simpan();
            }
        },
        async simpan() {
            // remove space
            const bppb = this.dataGenerate.no_bppb.trim()
            const cekbppb = bppb !== null && bppb !== '' && bppb !== '-' && bppb !== '/'
            const ceknoserinull = this.noseri.filter((item) => {
                return item.noseri === null || item.noseri === ''
            })

            this.noseri.find((item) => {
                if (item.error) {
                    delete item.error;
                    delete item.message;
                }
            })

            if (!cekbppb) {
                this.$swal('Peringatan!', 'Nomor BPPB tidak boleh kosong', 'warning');
                return;
            }

            if (ceknoserinull.length === this.noseri.length) {
                this.$swal('Peringatan!', 'Nomor seri tidak boleh kosong', 'warning');
                return;
            }

            const noseri = this.noseri.filter((item) => {
                return item.noseri !== null && item.noseri !== ''
            })

            const noseriUnique = noseri.filter((data, index) => {
                return noseri.findIndex((item) => {
                    return item.noseri === data.noseri
                }) === index
            })

            if (noseriUnique.length !== noseri.length) {
                this.noseri = this.noseri.map((item) => {
                    if (this.noseri.findIndex((data) => data.noseri === item.noseri) !== this.noseri.lastIndexOf(item)) {
                        item.error = true;
                        item.message = 'Nomor seri tidak boleh sama';
                    }
                    return item;
                })
                this.$swal('Peringatan!', 'Nomor seri tidak boleh sama', 'warning');
                return
            }

            let formSending = {
                ...this.dataGenerate,
                noseri
            }

            try {
                this.isDisabled = true;
                this.loading = true;
                const { data } = await axios.post('/api/prd/fg/non_gen', formSending)
                console.log(data)
                const { produk_id, date_in } = data
                const tgl = moment(date_in).format('YYYY-MM-DD')
                const wkt_rakit = this.timeFormat(date_in)
                this.idCetakHasilGenerate = `${produk_id}&dd=${tgl} ${wkt_rakit}`
                this.$swal('Berhasil!', 'Data berhasil disimpan', 'success');
            } catch (error) {
                const { message, duplicate, available } = error.response.data

                if (duplicate) {
                    this.noseri = this.noseri.map((item) => {
                        item.noseri = item.noseri.trim()
                        const find = duplicate.find((data) => data == item.noseri)
                        if (find) {
                            return {
                                ...item,
                                error: true,
                                message
                            }
                        }
                        return item;
                    })
                }

                if (available) {
                    this.noseri = this.noseri.map((item) => {
                        item.noseri = item.noseri.trim()
                        const find = available.find((data) => data == item.noseri)
                        if (find) {
                            return {
                                ...item,
                                error: false,
                                message: 'Nomor seri bisa digunakan'
                            }
                        }
                        return item;
                    })
                }

                this.isDisabled = false;
                this.isError = true;
            } finally {
                this.loading = false;
            }
        },
        checkValidation(error) {
            if (error !== undefined) {
                if (error) {
                    return 'is-invalid'
                } else {
                    return 'is-valid'
                }
            } else {
                return ''
            }
        },
        hapusSeriDuplikasi() {
            this.noseri.find((item) => {
                if (item.error) {
                    delete item.error;
                    delete item.message;
                    item.noseri = '';
                }
            })

            this.isError = false;
        },
        showSeriText() {
            this.showmodalviatext = true;
            $('.inputNoSeri').modal('hide');
            this.$nextTick(() => {
                $('.modalChecked').modal('show');
            })
        },
        closeModalSeriviatext() {
            this.showmodalviatext = false
            this.$nextTick(() => {
                $('.inputNoSeri').modal('show');
            })
        },
        submit(noseri) {
            let noseriarray = noseri.split(/[\n, \t]/)

            // remove noseri null
            noseriarray = noseriarray.filter((item) => {
                return item !== null && item !== ''
            })

            // remove duplicate
            noseriarray = [...new Set(noseriarray)]

            if (noseriarray.length > this.dataGenerate.kurang) {
                this.$swal('Peringatan!', 'Jumlah nomor seri yang diinputkan melebihi jumlah rakit', 'warning');
                return;
            }

            for (let i = 0; i < noseriarray.length; i++) {
                for (let j = 0; j < this.noseri.length; j++) {
                    if (this.noseri[j].noseri === '') {
                        this.noseri[j].noseri = noseriarray[i].toUpperCase()
                        break;
                    }
                }
            }
        },
        cetakSeri() {
            this.showModalCetak = true;
            this.$nextTick(() => {
                $('.inputNoSeri').modal('hide');
                $('.modalPilihan').modal('show');
            })
        },
        closeModalCetak() {
            this.showModalCetak = false
            this.$nextTick(() => {
                $('.inputNoSeri').modal('show');
            })
        },
        cekBPPB() {
            const cekbppb = this.dataGenerate.no_bppb !== null && this.dataGenerate.no_bppb !== '' && this.dataGenerate.no_bppb !== '-'
            if (cekbppb) {
                this.isDisableBPPB = true
            }
        }
    },
    mounted() {
        this.cekBPPB()
        this.addNoSeri();
        this.$nextTick(() => {
            setTimeout(() => {
                this.$refs.noseri[0].focus();
            }, 200);
        })
    },
    watch: {
        noseri: {
            handler() {
                this.noseridiisi = 0;
                this.noseri.forEach((item) => {
                    if (item.noseri !== '') {
                        this.noseridiisi += 1;
                    }
                })
            },
            deep: true
        }
    }
}
</script>

<template>
    <div>
        <seriviatext v-if="showmodalviatext" @close="closeModalSeriviatext" @submit="submit" />
        <modalPilihan v-if="showModalCetak" @closeModal="closeModalCetak" :data="idCetakHasilGenerate"></modalPilihan>
        <div class="modal fade inputNoSeri" data-backdrop="static" id="modelId" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Input Nomor Seri</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="">Nomor BPPB</label>

                                        <div class="card">
                                            <div class="card-body">
                                                <input type="text" name="no_bppb" id="no_bppb" class="form-control"
                                                    :disabled="isDisabled || isDisableBPPB"
                                                    v-model="dataGenerate.no_bppb" @keyup="keyUpperCase($event)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Nama Produk</label>
                                        <div class="card" style="background-color: #F89F81">
                                            <div class="card-body">
                                                <span id="produk">{{ dataGenerate.nama_produk }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Kategori</label>
                                        <div class="card" style="background-color: #FCF9C4">
                                            <div class="card-body">
                                                <span id="kategori">{{ dataGenerate.kategori }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <label for="">Jumlah Rakit</label>
                                        <div class="card" style="background-color: #FFCC83">
                                            <div class="card-body">
                                                <span id="jml">{{ dataGenerate.kurang_rakit }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Tanggal Mulai</label>
                                        <div class="card" style="background-color: #FFE0B4">
                                            <div class="card-body">
                                                <span id="start">{{ dataGenerate.tgl_mulai }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="">Tanggal Selesai</label>
                                        <div class="card" style="background-color: #FFECB2">
                                            <div class="card-body">
                                                <span id="end">{{ dataGenerate.tgl_selesai }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- <DataTable :headers="headers" :items="noseri">
                                <template #item.noseri="{ item }">
                                    <input type="text" ref="noseri" class="form-control" v-model="item.noseri">
                                </template>
</DataTable> -->
                                <button class="btn btn-primary" @click="showSeriText" v-if="!idCetakHasilGenerate">Input
                                    No
                                    Seri Via Text</button>
                                <div class="scrollable">
                                    <table class="table" v-if="!loadingNoSeri">
                                        <thead>
                                            <tr>
                                                <th>No. Seri</th>
                                            </tr>
                                        </thead>
                                        <tbody v-for="(item, index) in noseri" :key="index">
                                            <tr>
                                                <td>
                                                    <input type="text" ref="noseri" class="form-control"
                                                        v-model="item.noseri" :disabled="isDisabled"
                                                        :class="checkValidation(item.error)"
                                                        @keyup="$event.target.value = $event.target.value.toUpperCase()"
                                                        @keyup.enter="autoTab($event, index)">
                                                    <div class="invalid-feedback">
                                                        {{ item.message }}
                                                    </div>
                                                    <small class="text-success" v-if="!item.error">{{ item.message
                                                        }}</small>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>No Seri Yang Diisi : {{ noseridiisi }} Unit</p>

                                <div class="d-flex bd-highlight">
                                    <div class="p-2 flex-grow-1 bd-highlight">
                                        <button class="btn btn-primary" @click="simpan" :disabled="isDisabled"
                                            v-if="!idCetakHasilGenerate">
                                            <div class="spinner-border spinner-border-sm" role="status" v-if="loading">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            {{ loading ? 'Loading...' : 'Simpan' }}
                                        </button>
                                        <button class="btn btn-success" @click="cetakSeri"
                                            v-if="idCetakHasilGenerate">Cetak</button>
                                        <button class="btn btn-danger" @click="hapusSeriDuplikasi" v-if="isError">Hapus
                                            No
                                            Seri
                                            Duplikasi</button>
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <button class="btn btn-secondary" @click="closeModal">Keluar</button>
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

<style>
.scrollable {
    height: 300px;
    overflow-y: auto;
}
</style>