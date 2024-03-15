<script>
import modalGenerate from './modalGenerate.vue';
import modalPilihan from '../riwayat/modalPilihan.vue';
import modalGenerateBPPB from './modalGenerateBPPB.vue';
import inputNoSeri from './inputNoSeri.vue';
import DataTable from '../../../components/DataTable.vue';
import closeBPPB from './closeBPPB.vue';
import moment from 'moment';
export default {
    props: ['dataTable', 'openDataAfterGenerate'],
    components: {
        modalGenerate,
        modalPilihan,
        inputNoSeri,
        DataTable,
        modalGenerateBPPB,
        closeBPPB
    },
    data() {
        return {
            showModal: false,
            detailData: {},
            search: '',
            moment: moment,
            headers: [
                {
                    text: 'Periode',
                    value: 'periode'
                },
                {
                    text: 'Tanggal Mulai',
                    value: 'tgl_mulai',
                },
                {
                    text: 'Tanggal Selesai',
                    value: 'tgl_selesai',
                },
                {
                    text: 'No BPPB',
                    value: 'no_bppb'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama_produk',
                },
                {
                    text: 'Jumlah Rakit',
                    value: 'jumlah_unit'
                },
                {
                    text: 'Aksi Produk',
                    sortable: false,
                    value: 'aksi'
                },
            ],
            tanggalAwalMulai: '',
            tanggalAkhirMulai: '',
            tanggalAwalSelesai: '',
            tanggalAkhirSelesai: '',
            filterProduk: [],
            showModalNoSeri: false,
            showModalBPPB: false,
            showModalCloseBPPB: false,
            periode: '',
        }
    },
    methods: {
        selisih(selisih, tanggal_selesai) {
            if (tanggal_selesai) {
                if (selisih > 0) {
                    return `<span class="badge badge-danger">Lebih ${selisih} hari</span>`
                } else if (selisih < 0) {
                    return `<span class="badge badge-warning">Kurang ${selisih * -1} hari</span>`
                } else {
                    return `<span class="badge badge-success">Tepat Waktu</span>`
                }
            }
        },
        detail(data) {
            this.detailData = JSON.parse(JSON.stringify(data))
            this.showModal = true
            this.$nextTick(() => {
                $('.modalGenerate').modal('show')
            })
        },
        detailInputNoSeri(data) {
            this.detailData = JSON.parse(JSON.stringify(data))
            this.showModalNoSeri = true
            this.$nextTick(() => {
                $('.inputNoSeri').modal('show')
            })
        },
        openModal() {
            this.$emit('refreshData')
            this.$store.dispatch('setDetail', this.detailData)
        },
        generateBPPB(data) {
            this.detailData = JSON.parse(JSON.stringify(data))
            this.showModalBPPB = true
            this.$nextTick(() => {
                $('.modalGenerateBPPB').modal('show')
            })
        },
        refresh() {
            this.$emit('refresh')
        },
        openCloseBPPB(item) {
            this.showModalCloseBPPB = true
            this.detailData = item
            this.$nextTick(() => {
                $('.closeBPPB').modal('show')
            })
        }
    },
    watch: {
        openDataAfterGenerate(val) {
            if (val) {
                // get index data
                this.detailData = this.dataTable.find(item => item.id == this.$store.state.openDetail.id)
                if (this.detailData.generate_seri == 1) {
                    this.showModal = true
                    this.$nextTick(() => {
                        $('.modalGenerate').modal('show')
                    })
                } else {
                    this.showModalNoSeri = true
                    this.$nextTick(() => {
                        $('.inputNoSeri').modal('show')
                    })
                }
            }
        }
    },
    computed: {
        filterData() {
            if (this.tanggalAwalMulai && this.tanggalAkhirMulai) {
                const startDate = new Date(this.tanggalAwalMulai)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhirMulai)
                endDate.setHours(23, 59, 59, 999)

                return this.dataTable.filter((data) => {
                    const date = new Date(data.tgl_mulai)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalAwalMulai) {
                const startDate = new Date(this.tanggalAwalMulai)
                startDate.setHours(0, 0, 0, 0)

                return this.dataTable.filter((data) => {
                    const date = new Date(data.tgl_mulai)
                    return date >= startDate
                })
            } else if (this.tanggalAwalSelesai) {
                const endDate = new Date(this.tanggalAwalSelesai)
                endDate.setHours(23, 59, 59, 999)

                return this.dataTable.filter((data) => {
                    const date = new Date(data.tgl_mulai)
                    return date <= endDate
                })
            }

            if (this.tanggalAwalSelesai && this.tanggalAkhirSelesai) {
                const startDate = new Date(this.tanggalAwalSelesai)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhirSelesai)
                endDate.setHours(23, 59, 59, 999)

                return this.dataTable.filter((data) => {
                    const date = new Date(data.tgl_selesai)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalAwalSelesai) {
                const startDate = new Date(this.tanggalAwalSelesai)
                startDate.setHours(0, 0, 0, 0)

                return this.dataTable.filter((data) => {
                    const date = new Date(data.tgl_selesai)
                    return date >= startDate
                })
            } else if (this.tanggalAkhirSelesai) {
                const endDate = new Date(this.tanggalAkhirSelesai)
                endDate.setHours(23, 59, 59, 999)

                return this.dataTable.filter((data) => {
                    const date = new Date(data.tgl_selesai)
                    return date <= endDate
                })
            }

            if (this.filterProduk.length > 0) {
                return this.dataTable.filter((data) => {
                    return this.filterProduk.includes(data.nama_produk)
                })
            }

            if (this.periode != '') {
                return this.dataTable.filter((data) => {
                    return this.periode.includes(data.periode)
                })
            }

            return this.dataTable
        },
        produkUnique() {
            return [...new Set(this.dataTable.map(item => item.nama_produk))]
        },
        monthOptions() {
            let month = []
            for (let i = 1; i <= 12; i++) {
                month.push(moment().month(i - 1).lang('id').format('MMMM'))
            }
            return month
        }
    }
}
</script>

<template>
    <div v-if="!$store.state.loading">
        <modalGenerate v-if="showModal" :dataGenerate="detailData" @closeModal="showModal = false" @refresh="refresh" />
        <inputNoSeri v-if="showModalNoSeri" :dataGenerate="detailData" @closeModal="showModalNoSeri = false"
            @refresh="refresh" />
        <modalGenerateBPPB v-if="showModalBPPB" :dataGenerate="detailData" @closeModal="showModalBPPB = false"
            @refresh="refresh" @openModalGenerate="openModal" />
        <closeBPPB v-if="showModalCloseBPPB" :dataGenerate="detailData" @closeModal="showModalCloseBPPB = false"
            @refresh="refresh" />
        <div class="row">
            <div class="col-8">
                <v-select :options="monthOptions" v-model="periode" placeholder="Periode"></v-select>
                <v-select multiple :options="produkUnique" v-model="filterProduk" placeholder="Pilih Produk" />
                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
            <div class="col-4">
                <span class="filter">
                    <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>

                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Awal Mulai</label>
                                            <input type="date" class="form-control" v-model="tanggalAwalMulai"
                                                :max="tanggalAkhirMulai">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Akhir Mulai</label>
                                            <input type="date" class="form-control" v-model="tanggalAkhirMulai"
                                                :min="tanggalAwalMulai">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Awal Selesai</label>
                                            <input type="date" class="form-control" v-model="tanggalAwalSelesai"
                                                :max="tanggalAkhirSelesai">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Akhir Selesai</label>
                                            <input type="date" class="form-control" v-model="tanggalAkhirSelesai"
                                                :min="tanggalAwalSelesai">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </div>
        </div>
        <DataTable :headers="headers" :items="filterData" :search="search">
            <template #item.tgl_selesai="{ item }">
                <span>{{ item.tgl_selesai }}</span> <br>
                <span v-html="selisih(item.selisih, item.tanggal_selesai)"></span>
            </template>

            <template #item.jumlah_unit="{ item }">
                <span>{{ item.jumlah_unit }}</span><br>
                <span class="badge badge-dark">{{ item.kurang_rakit }}</span>
            </template>

            <template #item.aksi="{ item }">
                <button class="btn btn-sm btn-outline-success" @click="generateBPPB(item)" v-if="item.no_bppb == '-'">
                    <i class="fa fa-barcode"></i>
                    Generate BPPB
                </button>

                <button class="btn btn-sm btn-outline-primary" @click="detail(item)"
                    v-if="item.generate_seri == 1 && item.no_bppb != '-'">
                    <i class="fa fa-barcode"></i>
                    Generate Nomor Seri
                </button>

                <button class="btn btn-sm btn-outline-primary" @click="detailInputNoSeri(item)"
                    v-if="item.generate_seri == 0 && item.no_bppb != '-'">
                    <!-- icon tambah nomor seri -->
                    <i class="fa fa-barcode"></i>
                    Tambah Nomor Seri
                </button>
                <button class="btn btn-sm btn-outline-danger" @click="openCloseBPPB(item)">
                    <i class="fas fa-ban"></i>
                    Close / Batal BPPB
                </button>
            </template>
        </DataTable>
    </div>
</template>