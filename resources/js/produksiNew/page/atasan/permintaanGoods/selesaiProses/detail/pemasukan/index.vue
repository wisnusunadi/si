<script>
export default {
    data() {
        return {
            searchProduk: '',
            headersProduk: [
                {
                    text: 'No.',
                    value: 'no',
                },
                {
                    text: 'Nama Produk',
                    value: 'nama'
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
                    value: 'aksi'
                }
            ],
            itemsProduk: [
                {
                    no: 1,
                    nama: 'Produk 1',
                    jumlah: 2,
                    waktu_ambil: null
                }
            ],
            detailSelected: null,

            searchNoSeri: '',
            headersNoSeri: [
                // {
                //     text: 'id',
                //     value: 'id',
                //     sortable: false
                // },
                {
                    text: 'No. Seri',
                    value: 'noseri'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Waktu Diterima',
                    value: 'waktu_diterima'
                }
            ],
            itemsNoSeri: [
                {
                    no: 1,
                    id: 1,
                    noseri: 'NS-2021080001',
                    status: 'siap_diambil',
                    waktu_diterima: '03 Agustus 2024 13:00'
                },
                {
                    no: 2,
                    id: 2,
                    noseri: 'NS-2021080002',
                    status: 'barang_keluar',
                    waktu_diterima: '03 Agustus 2024 13:00'
                }
            ],
            checkAll: false,
            noSeriSelected: [],
            filterSelected: []
        }
    },
    methods: {
        detail(item) {
            this.detailSelected = item
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
        checkedAll() {
            this.checkAll = !this.checkAll
            if (this.checkAll) {
                this.noSeriSelected = this.itemsNoSeri.filter((n) => n.status !== 'barang_keluar')
            } else {
                this.noSeriSelected = []
            }
        },
        checkedOne(item) {
            if (this.noSeriSelected.find((n) => n.id === item.id)) {
                this.noSeriSelected = this.noSeriSelected.filter((n) => n.id !== item.id)
            } else {
                this.noSeriSelected.push(item)
            }
        },
        clickFilterHasil(status) {
            if (this.filterSelected.includes(status)) {
                this.filterSelected = this.filterSelected.filter((n) => n !== status)
            } else {
                this.filterSelected.push(status)
            }
        },
        penerimaanBarang() {
            swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Data yang sudah diterima tidak dapat diubah lagi',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Terima',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire(
                        'Diterima!',
                        'Data berhasil diterima',
                        'success'
                    )
                }
            })
        },
    },
    computed: {
        getAllStatusUnique() {
            return [...new Set(this.itemsNoSeri.map((item) => item.status))]
        },
        filterNoSeri() {
            let filter = this.itemsNoSeri

            if (this.filterSelected.length > 0) {
                filter = filter.filter((item) => this.filterSelected.includes(item.status))
            }

            return filter
        }
    },
    watch: {
        noSeriSelected() {
            if (this.noSeriSelected.length === this.itemsNoSeri.filter((n) => n.status !== 'barang_keluar').length) {
                this.checkAll = true
            } else {
                this.checkAll = false
            }
        }
    },
    created() {
        if (this.$route.params.selesai) {
            this.itemsProduk = [
                {
                    no: 1,
                    nama: 'Produk 1',
                    jumlah: 2,
                    waktu_ambil: '03 Agustus 2024 13:00'
                }
            ],
            this.itemsNoSeri = [
                {
                    no: 1,
                    id: 1,
                    noseri: 'NS-2021080001',
                    status: 'barang_keluar',
                    waktu_diterima: '03 Agustus 2024 13:00'
                },
                {
                    no: 2,
                    id: 2,
                    noseri: 'NS-2021080002',
                    status: 'barang_keluar',
                    waktu_diterima: '03 Agustus 2024 13:00'
                }
            ]
        }
    }
}
</script>
<template>
    <div class="row">
        <div :class="detailSelected ? 'col-7' : 'col-12'">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="searchProduk" placeholder="Cari...">
                        </div>
                    </div>
                    <data-table :headers="headersProduk" :items="itemsProduk" :search="searchProduk">
                        <template #item.aksi="{ item }">
                            <button class="btn btn-sm btn-outline-primary" @click="detail(item)">
                                <i class="fas fa-eye"></i>
                                Detail
                            </button>
                        </template>
                    </data-table>
                </div>
            </div>
        </div>
        <div class="col-5" v-if="detailSelected">
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
                            <button class="btn btn-sm btn-info"
                                v-if="noSeriSelected.length > 0" @click="penerimaanBarang">
                                <i class="fas fa-check-square"></i>
                                Penerimaan Barang
                            </button>
                        </div>
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="searchNoSeri" placeholder="Cari...">
                        </div>
                    </div>
                    <data-table :headers="headersNoSeri" :items="filterNoSeri" :search="searchNoSeri">
                        <template #header.id>
                            <input type="checkbox" @click="checkedAll" :checked="checkAll" v-if="!$route.params.selesai">
                            <div v-else></div>
                        </template>
                        <template #item.id="{ item }">
                            <input type="checkbox" @click="checkedOne(item)" v-if="item.status != 'barang_keluar'"
                                :checked="noSeriSelected && noSeriSelected.find((n) => n.id === item.id) ? true : false">
                            <div v-else></div>
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