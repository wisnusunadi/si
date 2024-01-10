<script>
import DataTable from '../../../components/DataTable.vue';
import detail from './detail.vue';
export default {
    components: {
        DataTable,
        detail
    },
    props: ['transferSisa'],
    data() {
        return {
            headers: [
                {
                    text: 'Tanggal Masuk',
                    value: 'tgl_mulai',
                    sortable: false,
                },
                {
                    text: 'Tanggal Keluar',
                    value: 'tgl_selesai',
                    sortable: false,
                },
                {
                    text: 'Nomor BPPB',
                    value: 'no_bppb',
                },
                {
                    text: 'Nama Produk',
                    value: 'produk',
                    sortable: false,
                },
                {
                    text: 'Jumlah Rakit',
                    value: 'jml_rakit',
                },
                {
                    text: 'Jumlah Sisa',
                    value: 'jml_sisa',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ],
            search: '',
            tanggalAwalMasuk: '',
            tanggalAkhirMasuk: '',
            tanggalAwalKeluar: '',
            tanggalAkhirKeluar: '',
            filterProduk: [],
            modalDetail: false,
            detailSelected: null
        }
    },
    computed: {
        filterData() {
            if (this.tanggalAwalMasuk && this.tanggalAkhirMasuk) {
                const startDate = new Date(this.tanggalAwalMasuk)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhirMasuk)
                endDate.setHours(23, 59, 59, 999)

                return this.transferSisa.filter(item => {
                    const date = new Date(item.tanggal_mulai)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalAwalMasuk) {
                const startDate = new Date(this.tanggalAwalMasuk)
                startDate.setHours(0, 0, 0, 0)

                return this.transferSisa.filter(item => {
                    const date = new Date(item.tanggal_mulai)
                    return date >= startDate
                })
            } else if (this.tanggalAkhirMasuk) {
                const endDate = new Date(this.tanggalAkhirMasuk)
                endDate.setHours(23, 59, 59, 999)

                return this.transferSisa.filter(item => {
                    const date = new Date(item.tanggal_mulai)
                    return date <= endDate
                })
            }

            if (this.tanggalAwalKeluar && this.tanggalAkhirKeluar) {
                const startDate = new Date(this.tanggalAwalKeluar)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhirKeluar)
                endDate.setHours(23, 59, 59, 999)

                return this.transferSisa.filter(item => {
                    const date = new Date(item.tanggal_selesai)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalAwalKeluar) {
                const startDate = new Date(this.tanggalAwalKeluar)
                startDate.setHours(0, 0, 0, 0)

                return this.transferSisa.filter(item => {
                    const date = new Date(item.tanggal_selesai)
                    return date >= startDate
                })
            } else if (this.tanggalAkhirKeluar) {
                const endDate = new Date(this.tanggalAkhirKeluar)
                endDate.setHours(23, 59, 59, 999)

                return this.transferSisa.filter(item => {
                    const date = new Date(item.tanggal_selesai)
                    return date <= endDate
                })
            }

            if (this.filterProduk.length > 0) {
                return this.transferSisa.filter(item => {
                    return this.filterProduk.includes(item.produk)
                })
            }

            return this.transferSisa
        },
        produkUnique() {
            // remove produk is empty or null
            const produk = this.transferSisa.filter(item => item.produk)
            // get unique produk
            const unique = [...new Set(produk.map(item => item.produk))]
            // return produk unique
            return unique;
        }
    },
    methods: {
        openDetail(item) {
            this.detailSelected = item
            this.modalDetail = true
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        }
    },
}
</script>
<template>
    <div>
        <detail :detailSelected="detailSelected" v-if="modalDetail" @closeModal="modalDetail = false" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight"><input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <DataTable :headers="headers" :items="filterData" :search="search">
            <template #header.tgl_mulai>
                <span class="text-bold pr-2">Tanggal Masuk</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Awal</label>
                                            <input type="date" class="form-control" v-model="tanggalAwalMasuk"
                                                :max="tanggalAkhirMasuk">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Akhir</label>
                                            <input type="date" class="form-control" v-model="tanggalAkhirMasuk"
                                                :min="tanggalAwalMasuk">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>

            <template #header.tgl_selesai>
                <span class="text-bold pr-2">Tanggal Keluar</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Awal</label>
                                            <input type="date" class="form-control" v-model="tanggalAwalKeluar"
                                                :max="tanggalAkhirKeluar">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Akhir</label>
                                            <input type="date" class="form-control" v-model="tanggalAkhirKeluar"
                                                :min="tanggalAwalKeluar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>

            <template #header.produk>
                <span class="text-bold pr-2">Nama Produk</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3 font-weight-normal">
                                <v-select multiple :options="produkUnique" v-model="filterProduk"
                                    placeholder="Pilih Produk" />
                            </div>
                        </div>
                    </form>
                </span>
            </template>

            <template #item.jml_sisa="{ item }">
                <span class="badge badge-success">Sisa Kirim : {{ item.jml_sisa.sisa_kirim }} Unit</span>
                <br><span class="badge badge-warning">Sisa Rakit : {{ item.jml_sisa.sisa_rakit }} Unit</span>
            </template>

            <template #item.aksi="{ item }">
                <div>
                    <button class="btn btn-outline-secondary btn-sm" @click="openDetail(item)">
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                </div>
            </template>
        </DataTable>
    </div>
</template>