<script>
import DataTable from '../../../components/DataTable.vue';
import detail from './detail.vue';
export default {
    props: ['riwayatRakit'],
    components: {
        DataTable,
        detail
    },
    data() {
        return {
            headers: [
                { text: 'Tanggal Perakitan', value: 'tgl_rakit', sortable: false },
                { text: 'Waktu Perakitan', value: 'wkt_rakit' },
                { text: 'Nomor BPPB', value: 'no_bppb' },
                { text: 'Produk', value: 'produkk', sortable: false },
                { text: 'Jumlah', value: 'jml' },
                { text: 'Aksi', value: 'aksi' }
            ],
            search: '',
            tanggalAwal: '',
            tanggalAkhir: '',
            filterProduk: [],
            produkSelected: null,
            modalDetail: false
        }
    },
    computed: {
        filterData() {
            if (this.tanggalAwal && this.tanggalAkhir) {
                const startDate = new Date(this.tanggalAwal)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhir)
                endDate.setHours(23, 59, 59, 999)

                return this.riwayatRakit.filter(item => {
                    const date = new Date(item.date_in)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalAwal) {
                const startDate = new Date(this.tanggalAwal)
                startDate.setHours(0, 0, 0, 0)

                return this.riwayatRakit.filter(item => {
                    const date = new Date(item.date_in)
                    return date >= startDate
                })
            } else if (this.tanggalAkhir) {
                const endDate = new Date(this.tanggalAkhir)
                endDate.setHours(23, 59, 59, 999)

                return this.riwayatRakit.filter(item => {
                    const date = new Date(item.date_in)
                    return date <= endDate
                })
            }

            if (this.filterProduk.length > 0) {
                return this.riwayatRakit.filter(item => {
                    return this.filterProduk.includes(item.produkk)
                })
            }

            return this.riwayatRakit
        },
        produkUnique() {
            // remove produk is empty or null
            const produk = this.riwayatRakit.filter(item => item.produkk)
            // get unique produk
            const unique = [...new Set(produk.map(item => item.produkk))]
            // return produk unique
            return unique;
        }
    },
    methods: {
        detailProdukSelected(item) {
            this.produkSelected = item
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
        <detail :produk="produkSelected" v-if="modalDetail" @closeModal="modalDetail = false" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight"><input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <DataTable :headers="headers" :items="filterData" :search="search">
            <template #header.tgl_rakit>
                <span class="text-bold pr-2">Tanggal Perakitan</span>
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
                                            <input type="date" class="form-control" v-model="tanggalAwal"
                                                :max="tanggalAkhir">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Akhir</label>
                                            <input type="date" class="form-control" v-model="tanggalAkhir"
                                                :min="tanggalAwal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>

            <template #header.produkk>
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

            <template #item.aksi="{ item }">
                <div>
                    <button class="btn btn-sm btn-outline-secondary" @click="detailProdukSelected(item)">
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                </div>
            </template>
        </DataTable>
    </div>
</template>