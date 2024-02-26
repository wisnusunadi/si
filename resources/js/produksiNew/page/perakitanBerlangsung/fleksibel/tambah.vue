<script>
import axios from 'axios'
import DataTable from '../../../components/DataTable.vue'
import modalPilihan from '../perakitan/modalPilihan.vue'
import Seriviatext from '../../../../gbj/page/PermintaanReworkGBJ/permintaan/formPermintaan/seriviatext.vue'
export default {
    components: {
        DataTable,
        modalPilihan,
        Seriviatext
    },
    props: ['detailRakit'],
    data() {
        return {
            form: {
                no_bppb: '',
                produk: '',
                jml: '',
                kedatangan: 0,
                bagian: '',
                tujuan: '',
            },
            produk: [],
            loading: false,
            headers: [
                {
                    text: 'No Seri',
                    value: 'noseri',
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
            linkExport: '',
            noseri: [],
            noseridiisi: 0,
            showmodalviatext: false,
            loadingNoSeri: false,
        }
    },
    methods: {
        closeModal() {
            $('.modalFleksibel').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
                this.$emit('refresh')
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
                                value: variasi.id,
                                isGenerate: item.generate_seri == 1 ? true : false,
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

            this.noseri.find((item) => {
                if (item.error) {
                    delete item.error;
                    delete item.message;
                }
            })

            if (this.form.produk?.isGenerate == false) {
                const ceknoserinull = this.noseri.filter((item) => {
                    return item.noseri === null || item.noseri === ''
                })

                if (ceknoserinull.length == this.noseri.length) {
                    this.$swal('Perhatian', 'No Seri tidak boleh kosong', 'warning')
                    return
                }

                const noseri = this.noseri.filter((item) => {
                    return item.noseri !== null && item.noseri !== ''
                })

                const noseriUnique = noseri.filter((data, index) => {
                    return this.noseri.findIndex((item) => {
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
                    });
                    return;
                } else {
                    this.loadingNoSeri = true;
                    this.noseri = this.noseri.map((item) => {
                        delete item.error
                        delete item.message
                        return item
                    })
                    this.$nextTick(() => {
                        this.loadingNoSeri = false;
                    });
                }
            }

            let formNoSeri = {}

            if (this.form.produk?.isGenerate) {
                formNoSeri = this.form
            } else {
                formNoSeri = {
                    ...this.form,
                    noseri: this.noseri.filter((item) => {
                        return item.noseri !== null && item.noseri !== ''
                    })
                }
            }

            if (cekForm && cekbppb) {
                // simpan
                try {
                    this.loading = true
                    const { data } = await axios.post('/api/prd/fg/non_jadwal/gen', formNoSeri)
                    this.$swal('Berhasil', 'Data berhasil disimpan', 'success')
                    const { noseri, produk_id, date_in, id } = data
                    this.hasilGenerate = noseri
                    const tgl = moment(date_in).format('YYYY-MM-DD')
                    const wkt_rakit = this.timeFormat(date_in)
                    this.idCetakHasilGenerate = `${produk_id}&dd=${tgl} ${wkt_rakit}`
                } catch (error) {
                    console.log(error);
                    const { message, seri, duplicate, available } = error.response.data
                    this.seri = seri
                    this.available = available.map(item => {
                        return {
                            noseri: item,
                        }
                    })
                    this.duplicate = duplicate.map(item => {
                        return {
                            noseri: item,
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
                    ...this.form,
                    noseri: this.available
                }
                const { data } = await axios.post('/api/prd/fg/non_jadwal/gen', kirim)
                this.$swal('Berhasil', 'Data berhasil disimpan', 'success')
                const { noseri, id, produk_id, date_in } = data
                this.hasilGenerate = noseri
                const tgl = moment(date_in).format('YYYY-MM-DD')
                const wkt_rakit = this.timeFormat(date_in)
                this.idCetakHasilGenerate = `${produk_id}&dd=${tgl} ${wkt_rakit}`
                this.linkExport = `/produksi/export_noseri_gen/${produk_id}/${tgl} ${wkt_rakit}`
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
        exportBarcode() {
            window.open(this.linkExport, '_blank')
        },
        removeSeri(index) {
            console.log(index);
            this.noseri.splice(index, 1)
        },
        showSeriText() {
            this.showmodalviatext = true
            $('.modalFleksibel').modal('hide')
            this.$nextTick(() => {
                $('.modalChecked').modal('show')
            })
            // catatan : -dimunculkan alert pas saat klik button
        },
        closeModalSeriViaText() {
            this.showmodalviatext = false
            this.$nextTick(() => {
                $('.modalFleksibel').modal('show')
            })
        },
        submit(noseri) {
            let noseriarray = noseri.split(/[\n, \t]/)
            let noseridouble = []

            // remove noseri null
            noseriarray = noseriarray.filter((item) => {
                return item !== null && item !== ''
            })

            // push noseri double ke array noseridouble
            noseriarray.forEach((item, index) => {
                if (noseriarray.indexOf(item) !== index) {
                    noseridouble.push(item)
                }
            })

            if (noseridouble.length > 0) {
                this.$swal('Peringatan!', `No. Seri ${noseridouble.join(', ')} duplikasi`, 'warning')
            }

            noseriarray = [...new Set(noseriarray)]

            for (let i = 0; i < noseriarray.length; i++) {
                this.noseri.push({
                    noseri: noseriarray[i].toUpperCase()
                })
            }

            // remove noseri null or empty
            this.noseri = this.noseri.filter((item) => {
                return item.noseri !== null && item.noseri !== ''
            })
        },
        autoTab(event, idx) {
            event.target.value = event.target.value.toUpperCase();
            this.noseri.find((item) => {
                if (item.error) {
                    delete item.error;
                    delete item.message;
                }
            })
            const noseri = this.noseri.filter((item) => {
                return item.noseri !== null && item.noseri !== ''
            })

            const noseriUnique = noseri.filter((data, index) => {
                return noseri.findIndex((item) => {
                    return item.noseri === data.noseri
                }) === index
            })

            if (noseri.length !== noseriUnique.length) {
                this.noseri[idx].error = true;
                this.noseri[idx].message = 'No. Seri tidak boleh sama';
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
                });
                return;
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
                this.simpan();
            }

        }
    },
    created() {
        this.getData()
    },
    computed: {
        cekKedatangan() {
            let idValue = [319, 149]
            return idValue.includes(this.form.produk?.value)
        }
    },
    watch: {
        'form.kedatangan': function (val) {
            if (val > 26) {
                this.form.kedatangan = 26
            } else if (val < 1) {
                this.form.kedatangan = 0
            } else {
                this.form.kedatangan = val
            }
        },
        'form.produk': function (val) {
            if (val.isGenerate) {
                this.form.jml = ''
                this.form.kedatangan = 1
            } else {
                delete this.form.jml
                delete this.form.kedatangan
            }
        },
        noseri: {
            handler() {
                this.noseridiisi = 0
                this.noseri.forEach((item) => {
                    if (item.noseri !== '') {
                        this.noseridiisi++
                    }
                })
            },
            deep: true,
        }
    },
}
</script>
<template>
    <div>
        <seriviatext v-if="showmodalviatext" @close="closeModalSeriViaText" @submit="submit"></seriviatext>
        <modalPilihan v-if="showModalCetak" @closeModal="closeModalCetak" :data="idCetakHasilGenerate"></modalPilihan>
        <div class="modal fade modalFleksibel" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Form Perakitan Tanpa Jadwal
                        </h5>
                        <button type="button" class="close" @click="closeModal">
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

                                        <div class="form-group" v-if="form.produk?.isGenerate && !cekKedatangan">
                                            <label for="exampleInputEmail1">Kedatangan</label>
                                            <input type="number" class="form-control" v-model.number="form.kedatangan"
                                                :disabled="hasilGenerate.length > 0" @keypress="numberOnly($event)">
                                        </div>

                                        <div class="form-group" v-if="form.produk?.isGenerate">
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

                                        <div class="d-flex bd-highlight" v-if="form.produk?.isGenerate == false">
                                            <div class="p-2 flex-grow-1 bd-highlight"><button class="btn btn-primary"
                                                    :disabled="hasilGenerate.length > 0" @click="showSeriText">Input
                                                    No Seri Via Text</button></div>
                                            <div class="p-2 bd-highlight">
                                                <button class="btn btn-info" @click="noseri.push({ noseri: '' })"
                                                    :disabled="hasilGenerate.length > 0">
                                                    Tambah No. Seri</button>
                                            </div>
                                        </div>
                                        <div class="scrollable" v-if="form.produk?.isGenerate == false">
                                            <table class="table" v-if="!loadingNoSeri">
                                                <thead>
                                                    <tr>
                                                        <th>No. Seri</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(seri, index) in noseri" :key="index">
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                :class="seri.error ? 'is-invalid' : ''"
                                                                v-model="seri.noseri" ref="noseri"
                                                                :disabled="hasilGenerate.length > 0"
                                                                @keyup.enter="autoTab($event, index)"
                                                                @keyup="$event.target.value = $event.target.value.toUpperCase()">
                                                            <div class="invalid-feedback" v-if="seri.error">
                                                                {{ seri.message }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-outline-danger"
                                                                :disabled="hasilGenerate.length > 0"
                                                                @click="removeSeri(index)">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
                                        <DataTable :headers="headers" :items="available" :search="searchPreview" />
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
                                        <span v-else>{{
                                            form.produk?.isGenerate ? 'Generate' : 'Simpan'
                                        }}</span>
                                    </button>
                                    <button class="btn btn-success" v-if="seri.length > 0" @click="simpanSeri">
                                        <div class="spinner-border" role="status" v-if="loading">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <span v-if="loading">Loading...</span>
                                        <span v-else>Simpan</span>
                                    </button>
                                </div>

                                <div v-else>
                                    <button class="btn btn-primary" @click="cetakSeri">Cetak Barcode</button>
                                    <!-- <button class="btn btn-success" @click="exportBarcode">Export Barcode</button> -->
                                </div>
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