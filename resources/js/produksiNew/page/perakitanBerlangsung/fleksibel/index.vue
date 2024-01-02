<script>
import DataTable from '../../../components/DataTable.vue';
import tambah from './tambah.vue';
import detail from './detail.vue';
export default {
    components: {
        DataTable,
        tambah,
        detail
    },
    props: ['perakitan'],
    data() {
        return {
            headers: [
                {
                    text: 'No BPPB',
                    value: 'no_bppb'
                },
                {
                    text: 'Tanggal Rakit',
                    value: 'tgl',
                    sortable: false
                },
                {
                    text: 'Bagian',
                    value: 'bagian',
                    sortable: false
                },
                {
                    text: 'Nama Produk',
                    value: 'produk',
                    sortable: false
                },
                {
                    text: 'Jumlah Rakit',
                    value: 'jml'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            search: '',
            modalTambah: false,
            tanggalAwal: '',
            tanggalAkhir: '',
            produkFilter: [],
            detailSelected: null
        }
    },
    methods: {
        openModalTambah() {
            this.modalTambah = true
            this.$nextTick(() => {
                $('.modalFleksibel').modal('show')
            })
        },
        openDetailRakit(item) {
            this.detailSelected = item
            this.$nextTick(() => {
                $('.modalDetail').modal('show')
            })
        },
    },
    computed: {
        filterData() {
            if (this.tanggalAwal && this.tanggalAkhir) {
                const startDate = new Date(this.tanggalAwal)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhir)
                endDate.setHours(0, 0, 0, 0)

                return this.perakitan.filter(item => {
                    const date = new Date(item.tgl_rakit)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalAwal) {
                const startDate = new Date(this.tanggalAwal)
                startDate.setHours(0, 0, 0, 0)

                return this.perakitan.filter(item => {
                    const date = new Date(item.tgl_rakit)
                    return date >= startDate
                })
            } else if (this.tanggalAkhir) {
                const endDate = new Date(this.tanggalAkhir)
                endDate.setHours(0, 0, 0, 0)

                return this.perakitan.filter(item => {
                    const date = new Date(item.tgl_rakit)
                    return date <= endDate
                })
            }

            if (this.produkFilter.length > 0) {
                return this.perakitan.filter(item => {
                    return this.produkFilter.includes(item.produk)
                })
            }

            return this.perakitan
        },

        produkUnique() {
            const produk = this.perakitan.filter(item => item.produk)
            const unique = [...new Set(produk.map(item => item.produk))]
            return unique
        }
    }

}
</script>
<template>
    <div>
        <tambah v-if="modalTambah" @closeModal="modalTambah = false" />
        <detail v-if="detailSelected" :produk="detailSelected" @closeModal="detailSelected = null" />
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <button class="btn btn-primary" @click="openModalTambah">
                    <i class="fa fa-plus"></i>
                    Tambah
                </button>
            </div>
            <div class="p-2 bd-highlight"><input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <DataTable :headers="headers" :items="filterData" :search="search">
            <template #header.tgl_rakit>
                <span class="text-bold pr-2">Tanggal Rakit</span>
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
            <template #header.produk>
                <span class="text-bold pr-2">Nama Produk</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="form-group font-weight-normal">
                                  <v-select multiple :options="produkUnique" label="produk" v-model="produkFilter" />
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>

            <template #item.aksi="{ item }">
                <div>
                    <button class="btn btn-outline-secondary btn-sm" @click="openDetailRakit(item)">
                        <i class="fa fa-eye"></i>
                        Detail
                    </button>
                </div>
            </template>
        </DataTable>
    </div>
</template>