<script>
import noSeriComponents from './noseri.vue'
export default {
    components: {
        noSeriComponents
    },
    props: ['pengeluaran'],
    data() {
        return {
            searchProduk: '',
            searchNoSeri: '',
            headersProduk: [
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
            ],
            headersNoSeri: [
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
                },
                {
                    text: 'Waktu Diterima',
                    value: 'waktu_ambil',
                }
            ],
            noseri: [],
            detailSelected: null,

            noSeriSelected: [],
            filterSelected: [],

            showModal: false,
            checkAll: false
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

            this.checkAll = false
            this.noSeriSelected = []
            if (this.$route.params.selesai) {
                this.noseri = [
                    {
                        no: 1,
                        id: 2,
                        noseri: 'NS-2021080002',
                        status: 'barang_keluar',
                        waktu_ambil: '2024-08-24 13:00:00',
                    },
                    {
                        no: 2,
                        id: 3,
                        noseri: 'NS-2021080003',
                        status: 'barang_keluar',
                        waktu_ambil: '2024-08-24 13:00:00',
                    }
                ]
            } else {
                this.noseri = [
                    {
                        no: 1,
                        id: 2,
                        noseri: 'NS-2021080002',
                        status: 'siap_diambil',
                        waktu_ambil: null,
                    },
                    {
                        no: 2,
                        id: 3,
                        noseri: 'NS-2021080003',
                        status: 'barang_keluar',
                        waktu_ambil: '2024-08-24 13:00:00',
                    }
                ]
            }
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
            if (this.noSeriSelected.find((n) => n.id === item.id)) {
                this.noSeriSelected = this.noSeriSelected.filter((n) => n.id !== item.id)
            } else {
                this.noSeriSelected.push(item)
            }
        },
        checkedAll() {
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

            this.checkAll = !this.checkAll
            if (this.checkAll) {
                this.noSeriSelected = this.noseri.filter((n) => n.status != 'barang_keluar')
            } else {
                this.noSeriSelected = []
            }

            console.log('checkAll', this.checkAll)
            console.log('noseriselect', this.noSeriSelected)
            console.log('noseri', this.noseri.filter((n) => n.status != 'barang_keluar'))
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
        persiapkanBarang() {
            this.showModal = true
            this.$nextTick(() => {
                $('.modalNoSeri').modal('show');
            });
        },
        penyerahanBarang() {
            swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data yang sudah diserahkan tidak dapat diubah lagi",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire(
                        'Diterima!',
                        'Data berhasil diserahkan',
                        'success'
                    )
                }
            })
        },
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
    },
    watch: {
        noSeriSelected() {
            if (this.noSeriSelected.length == this.noseri.filter((n) => n.status != 'barang_keluar').length) {
                this.checkAll = true
            } else {
                this.checkAll = false
            }
        }
    }
}
</script>
<template>
    <div class="row">
        <noSeriComponents :detailSelected="detailSelected" v-if="showModal" @close="showModal = false" />
        <div :class="noseri.length > 0 ? 'col-7' : 'col-12'">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="searchProduk">
                        </div>
                    </div>
                    <data-table :headers="headersProduk" :items="pengeluaran" :search="searchProduk">
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
                            <span class="filter" v-if="!$route.params.selesai">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle " type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    id="filterpenjualan">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <div class="dropdown-menu" aria-labelledby="filterpenjualan" style="">
                                    <form class="px-4" style="white-space:nowrap;">
                                        <div class="dropdown-header">
                                            Status Barang
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
                            <button class="btn btn-sm btn-primary" @click="persiapkanBarang"
                                v-if="!$route.params.selesai && this.detailSelected?.jumlah != this.noseri.length && this.noSeriSelected.length == 0">
                                <i class="fa-solid fa-qrcode"></i>
                                Persiapkan
                            </button>
                            <button class=" btn btn-sm btn-info" v-if="noSeriSelected.length > 0"
                                @click="penyerahanBarang">
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
                            <input type="checkbox" v-if="!$route.params.selesai" @click="checkedAll"
                                :checked="checkAll">
                        </template>
                        <template #item.no="{ item }">
                            <input type="checkbox" @click="checkedOne(item)"
                                :checked="noSeriSelected && noSeriSelected.find((n) => n.id === item.id) ? true : false"
                                v-if="!$route.params.selesai && item.status != 'barang_keluar'">
                            <div v-else></div>
                            <span v-if="$route.params.selesai">{{ item.no }}</span>
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
