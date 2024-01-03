<script>
import axios from 'axios'
import DataTable from '../../../components/DataTable.vue'
import modalPilihan from '../perakitan/modalPilihan.vue'
export default {
    components: {
        DataTable,
        modalPilihan
    },
    props: ['detailRakit'],
    data() {
        return {
            form: {
                no_bppb: '',
                produk: '',
                jml: '',
                kedatangan: 1,
                bagian: '',
                tujuan: '',
            },
            produk: [],
            loading: false,
            headers: [
                {
                    text: 'No Seri',
                    value: 'seri',
                    align: 'text-left',
                }
            ],
            search: '',
            showModalCetak: false,
            bagian: [],
            hasilGenerate: [],
            idCetakHasilGenerate: [],
            seri: [],
            available: [],
            duplicate: [],
            isError: false,
            searchPreview: '',
            searchDuplikasi: '',
        }
    },
    methods: {
        closeModal() {
            $('.modalFleksibel').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
        keyUpperCase(e) {
            this.form.no_bppb = e.target.value.toUpperCase()
        },
        async getData() {
            try {
                const { produk } = await axios.get('/api/produk').then(res => res.data);
                const { data: bagian } = await axios.get('/api/gbj/sel-divisi', {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('lokal_token')
                    }
                })
                this.produk = produk.reduce((acc, item) => {
                    if (item.gudang_barang_jadi.length > 0) {
                        item.gudang_barang_jadi.forEach(variasi => {
                            const nama = variasi.nama == null || variasi.nama == '' ? '' : `${variasi.nama}`
                            acc.push({
                                label: `${item.nama} ${nama}`,
                                value: variasi.id
                            })
                        })
                    }
                    return acc
                }, [])

                this.bagian = bagian.map(item => {
                    return {
                        label: item.nama,
                        value: item.id
                    }
                })
            } catch (error) {
                console.log(error);
            }
        },
        async simpan() {
            // validasi
            const cekForm = Object.values(this.form).every(x => x != '' && x != null && x != undefined && x != 0)
            const cekbppb = this.form.no_bppb !== null && this.form.no_bppb !== '' && this.form.no_bppb !== undefined && this.form.no_bppb !== '-'
            if (cekForm && cekbppb) {
                // simpan
                try {
                    this.loading = true
                    const { data } = await axios.post('/api/prd/fg/non_jadwal/gen', this.form)
                    this.$swal('Berhasil', 'Data berhasil disimpan', 'success')
                    const { noseri, id } = data
                    this.hasilGenerate = noseri
                    this.idCetakHasilGenerate = id
                } catch (error) {
                    console.log(error);
                    const { message, seri, duplicate, available } = error.response.data
                    this.seri = seri
                    this.available = available
                    this.duplicate = duplicate.map(item => {
                        return {
                            seri: item,
                        }
                    })
                    if (this.seri.length > 0 || this.available > 0 || this.duplicate.length > 0) {
                        this.isError = true
                    } else {
                        this.isError = false
                    }
                    this.$swal('Gagal', message, 'error')
                } finally {
                    this.loading = false
                }
            } else {
                if (!cekbppb) {
                    this.$swal('Perhatian', 'No BPPB tidak boleh kosong', 'warning')
                } else if (!cekForm) {
                    this.$swal('Perhatian', 'Form tidak boleh kosong', 'warning')
                }
            }
        },
        async simpanSeri() {
            try {
                const kirim = {
                    seri: this.seri,
                    available: this.available,
                }
                const { data } = await axios.post('/api/prd/fg/gen/confirm', kirim)
                this.$swal('Berhasil', 'Data berhasil disimpan', 'success')
                const { noseri, id } = data
                this.hasilGenerate = noseri
                this.idCetakHasilGenerate = id
            } catch (error) {
                console.log(error);
            }
        },
        cetakSeri() {
            this.showModalCetak = true
            this.$nextTick(() => {
                $('.modalFleksibel').modal('hide')
                $('.modalPilihan').modal('show')
            })
        },
        closeModalCetak() {
            this.showModalCetak = false
            this.$nextTick(() => {
                $('.modalFleksibel').modal('show')
            })
        },
    },
    created() {
        this.getData()
    },
    watch: {
        'form.kedatangan': function (val) {
            if (val > 26) {
                this.form.kedatangan = 26
            } else if (val <= 1) {
                this.form.kedatangan = 1
            } else {
                this.form.kedatangan = val
            }
        },
    },
}
</script>
<template>
    <div>
        <modalPilihan v-if="showModalCetak" @closeModal="closeModalCetak" :data="idCetakHasilGenerate"></modalPilihan>
        <div class="modal fade modalFleksibel" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Perakitan Tanpa Jadwal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body" v-if="!isError">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="">No BPPB</label>
                                            <input type="text" v-model="form.no_bppb" class="form-control"
                                                @keyup="keyUpperCase" :disabled="hasilGenerate.length > 0">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Nama Produk</label>
                                            <v-select :options="produk" v-model="form.produk" placeholder="Pilih Produk"
                                                :disabled="hasilGenerate.length > 0"></v-select>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Kedatangan</label>
                                            <input type="number" class="form-control" v-model.number="form.kedatangan"
                                                :disabled="hasilGenerate.length > 0" @keypress="numberOnly($event)">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Jumlah Rakit</label>
                                            <input type="number" class="form-control" v-model="form.jml"
                                                @keypress="numberOnly($event)" :disabled="hasilGenerate.length > 0">
                                        </div>

                                        <div class="form-group">
                                            <label for="">Bagian (Peminta No Seri)</label>
                                            <v-select :options="bagian" v-model="form.bagian" placeholder="Bagian"
                                                :disabled="hasilGenerate.length > 0"></v-select>
                                        </div>

                                        <div class="form-group">
                                            <label for="">Tujuan (Minta No Seri)</label>
                                            <textarea class="form-control" v-model="form.tujuan" rows="3"
                                                :disabled="hasilGenerate.length > 0"></textarea>

                                        </div>
                                    </div>
                                    <div class="col" v-if="hasilGenerate.length > 0">
                                        <p class="text-bold">Hasil Generate No. Seri</p>
                                        <DataTable :headers="headers" :items="hasilGenerate" :search="search"></DataTable>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" v-else>
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="pills-preview-tab" data-toggle="pill"
                                            data-target="#pills-preview" type="button" role="tab"
                                            aria-controls="pills-preview" aria-selected="true">Preview Generate No Seri</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-duplikasi-tab" data-toggle="pill"
                                            data-target="#pills-duplikasi" type="button" role="tab"
                                            aria-controls="pills-duplikasi" aria-selected="false">Duplikasi No Seri</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-preview" role="tabpanel"
                                        aria-labelledby="pills-preview-tab">
                                        <div class="d-flex flex-row-reverse bd-highlight">
                                            <div class="p-2 bd-highlight">
                                                <input type="text" v-model="searchPreview" class="form-control"
                                                    placeholder="Cari...">
                                            </div>
                                        </div>
                                        <DataTable :headers="headers" :items="seri" :search="searchPreview" />
                                    </div>
                                    <div class="tab-pane fade" id="pills-duplikasi" role="tabpanel"
                                        aria-labelledby="pills-duplikasi-tab">
                                        <div class="d-flex flex-row-reverse bd-highlight">
                                            <div class="p-2 bd-highlight">
                                                <input type="text" v-model="searchDuplikasi" class="form-control"
                                                    placeholder="Cari...">
                                            </div>
                                        </div>
                                        <DataTable :headers="headers" :items="duplicate" :search="searchDuplikasi" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 flex-grow-1 bd-highlight">
                                <div v-if="hasilGenerate.length == 0">
                                    <button class="btn btn-success" :disabled="loading" @click="simpan" v-if="!isError">
                                        <div class="spinner-border" role="status" v-if="loading">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <span v-if="loading">Loading...</span>
                                        <span v-else>Generate</span>
                                    </button>
                                    <button class="btn btn-success" v-if="seri.length > 0"
                                        @click="simpanSeri">Simpan</button>
                                </div>

                                <button class="btn btn-success" @click="cetakSeri" v-else>Cetak Barcode</button>
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
</template>