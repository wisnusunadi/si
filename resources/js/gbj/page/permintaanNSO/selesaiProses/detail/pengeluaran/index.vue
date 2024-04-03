<script>
import persiapkan from './persiapkan.vue'
import penyerahan from './penyerahan.vue'
export default {
    components: {
        persiapkan,
        penyerahan,
    },
    props: ['pengeluaran'],
    data() {
        return {
            searchProduk: '',
            searchNoSeri: '',
            headersProduk: [],
            headersNoSeri: [],
            noseri: [],
            detailSelected: null,

            noSeriSelected: [],
            persiapkan: [],
            penyerahan: [],
            filterSelected: [],

            showModal: false,
            renderPaginate: [],
        }
    },
    methods: {
        clickFilterHasil(status) {
            if (this.filterSelected.includes(status)) {
                this.filterSelected = this.filterSelected.filter((n) => n !== status)
            } else {
                this.filterSelected.push(status)
            }
        },
        detailProduk(item) {
            this.detailSelected = item

            this.noSeriSelected = []
            this.persiapkan = []
            this.penyerahan = []
            this.noseri = [
                {
                    no: 1,
                    id: 1,
                    noseri: 'NS-2021080001',
                    status: 'belum_digunakan',
                    waktu_ambil: '2024-08-24 13:00:00',
                },
                {
                    no: 2,
                    id: 2,
                    noseri: 'NS-2021080002',
                    status: 'siap_diambil',
                    waktu_ambil: '2024-08-24 13:00:00',
                },
                {
                    no: 3,
                    id: 3,
                    noseri: 'NS-2021080003',
                    status: 'barang_keluar',
                    waktu_ambil: '2024-08-24 13:00:00',
                }
            ]
        },
        checkedOne(item) {
            // const index = this.pengeluaranDuplicate.findIndex(x => x.id === this.detailSelected.id)
            // this.pengeluaranDuplicate = this.pengeluaranDuplicate.map((x, i) => {
            //     if (i === index) {
            //         return {
            //             ...x,
            //             noSeriSelected: x.noSeriSelected?.find((n) => n.id === item.id) ? x.noSeriSelected?.filter((n) => n.id !== item.id) : [...x.noSeriSelected, item]
            //         }
            //     }
            //     return x
            // })

            this.noSeriSelected = this.noSeriSelected.find((n) => n.id === item.id) ? this.noSeriSelected.filter((n) => n.id !== item.id) : [...this.noSeriSelected, item]
            if (item.status == 'belum_digunakan') {
                this.persiapkan = this.persiapkan.find((n) => n.id === item.id) ? this.persiapkan.filter((n) => n.id !== item.id) : [...this.persiapkan, item]
            } else {
                this.penyerahan = this.penyerahan.find((n) => n.id === item.id) ? this.penyerahan.filter((n) => n.id !== item.id) : [...this.penyerahan, item]
            }
        },
        checkAll() {
            // const index = this.pengeluaranDuplicate.findIndex(x => x.id === this.detailSelected.id)
            // this.pengeluaranDuplicate = this.pengeluaranDuplicate.map((x, i) => {
            //     if (i === index) {
            //         return {
            //             ...x,
            //             noSeriSelected: this.noseri ? this.noseri : []
            //         }
            //     }
            //     return x
            // })

            this.noSeriSelected = this.noSeriSelected.length === this.noseri.length ? [] : this.noseri
            this.persiapkan = this.persiapkan.length === this.noseri.filter((n) => n.status == 'belum_digunakan').length ? [] : this.noseri.filter((n) => n.status == 'belum_digunakan')
            this.penyerahan = this.penyerahan.length === this.noseri.filter((n) => n.status == 'belum_digunakan').length ? [] : this.noseri.filter((n) => n.status == 'siap_diambil')
        },
        cekHeaderPengeluaran() {
            if (this.$route.params.selesai) {
                this.headersProduk = [
                    {
                        text: 'No.',
                        value: 'no'
                    },
                    {
                        text: 'Nama Produk',
                        value: 'nama',
                    },
                    {
                        text: 'Jumlah',
                        value: 'jumlah'
                    },
                    {
                        text: 'Waktu Ambil',
                        value: 'waktu_ambil'
                    },
                    {
                        text: 'Aksi',
                        value: 'aksi',
                    }
                ]

                this.headersNoSeri = [
                    {
                        text: 'No.',
                        value: 'no',
                    },
                    {
                        text: 'No. Seri',
                        value: 'noseri',
                    },
                    {
                        text: 'Waktu Diterima',
                        value: 'waktu_ambil',
                    }
                ]
            } else {
                this.headersProduk = [
                    {
                        text: 'No.',
                        value: 'no',
                    },
                    {
                        text: 'Nama Produk',
                        value: 'nama',
                        children: [

                            {
                                text: 'Penyerahan',
                                value: 'jumlahdiserahkan',
                            }
                        ]
                    },
                    {
                        text: 'Jumlah',
                        value: 'jumlah',
                    },
                    {
                        text: 'Jumlah Transfer',
                        children: [
                            {
                                text: 'Persiapkan',
                                value: 'jumlahdisiapkan',
                            },
                            {
                                text: 'Penyerahan',
                                value: 'jumlahdiserahkan',
                            }
                        ]
                    },
                    {
                        text: 'Aksi',
                        value: 'aksi',
                    }
                ]

                this.headersNoSeri = [
                    {
                        text: 'No.',
                        value: 'no',
                        sortable: false
                    },
                    {
                        text: 'No. Seri',
                        value: 'noseri',
                    },
                    {
                        text: 'Status',
                        value: 'status',
                    }
                ]
            }
        },
        statusText(status) {
            switch (status) {
                case 'siap_diambil':
                    return {
                        text: 'Siap Diambil',
                        class: 'fa fa-check text-success'
                    }
                case 'barang_keluar':
                    return {
                        text: 'Barang Keluar',
                        class: 'fas fa-paper-plane text-primary'
                    }
                default:
                    return {
                        text: 'Belum Digunakan',
                        class: 'far fa-thumbs-up text-warning'
                    }
            }
        },
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
    },
    created() {
        this.cekHeaderPengeluaran()
    },
    computed: {
        getAllStatusUnique() {
            return this.noseri.map((n) => n.status).filter((value, index, self) => self.indexOf(value) === index)
        },
        filteredDalamProses() {
            const includesSearch = (obj, search) => {
                if (obj && typeof obj === 'object') {
                    return Object.keys(obj).some(key => {
                        if (typeof obj[key] === 'object') {
                            return includesSearch(obj[key], search);
                        }
                        return String(obj[key]).toLowerCase().includes(search.toLowerCase());
                    });
                }
                return false;
            };

            return this.pengeluaran.filter(data => includesSearch(data, this.searchProduk));
        },
        filterNoseri() {
            let filter = this.noseri

            if (this.filterSelected.length > 0) {
                filter = filter.filter((n) => {
                    return this.filterSelected.includes(n.status)
                })
            }

            return filter
        }
    }
}
</script>
<template>
    <div class="row">
        <div :class="noseri.length > 0 ? 'col-7' : 'col-12'">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="searchProduk">
                        </div>
                    </div>
                    <data-table :headers="headersProduk" :items="pengeluaran" :search="searchProduk">
                        <template #item.jumlahdisiapkan="{ item }">
                            <div>
                                {{ item.jumlahdisiapkan }} Unit
                            </div>
                        </template>
                        <template #item.waktu_ambil="{ item }">
                            <div>
                                {{ dateTimeFormat(item.waktu_ambil) }}
                            </div>
                        </template>
                        <template #item.aksi="{ item }">
                            <button class="btn btn-sm btn-outline-primary" @click="detailProduk(item)">
                                <i class="fas fa-eye"></i>
                                Detail
                            </button>
                        </template>
                    </data-table>
                </div>
            </div>
        </div>
        <div class="col-5" v-if="noseri.length > 0">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight">
                            <span class="filter">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle " type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    id="filterpenjualan">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <div class="dropdown-menu" aria-labelledby="filterpenjualan" style="">
                                    <form class="px-4" style="white-space:nowrap;">
                                        <div class="dropdown-header">
                                            Status Pengujian
                                        </div>
                                        <div class="form-group" v-for="(status, idx) in getAllStatusUnique" :key="idx">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input"
                                                    :id="`dropdownStatus${idx}`" @click="clickFilterHasil(status)">
                                                <label class="form-check-label" :for="`dropdownStatus${idx}`">
                                                    {{ statusText(status).text }}
                                                </label>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </span>
                            <button class="btn btn-sm btn-warning" v-if="persiapkan.length > 0">
                                <i class="fas fa-check-square"></i>
                                Persiapkan
                            </button>
                            <button class=" btn btn-sm btn-info" v-if="penyerahan.length > 0">
                                <i class="fas fa-check-square"></i>
                                Penyerahan
                            </button>
                        </div>
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="searchNoSeri">
                        </div>
                    </div>
                    <data-table :headers="headersNoSeri" :items="filterNoseri" :search="searchNoSeri">
                        <template #header.no>
                            <input type="checkbox" v-if="!$route.params.selesai" @click="checkAll"
                                :checked="noSeriSelected ? noSeriSelected.length === noseri.length : false">
                        </template>
                        <template #item.no="{ item }">
                            <input type="checkbox" @click="checkedOne(item)"
                                :checked="noSeriSelected ? noSeriSelected.find((n) => n.id === item.id) : false"
                                v-if="!$route.params.selesai && item.status != 'barang_keluar'">
                            <div v-else></div>
                        </template>
                        <template #item.waktu_ambil="{ item }">
                            <div>
                                {{ dateTimeFormat(item.waktu_ambil) }}
                            </div>
                        </template>
                        <template #item.status="{ item }">
                            <div>
                                <i :class="statusText(item.status).class"></i>
                            </div>
                        </template>
                    </data-table>
                </div>
            </div>
        </div>
    </div>
</template>
