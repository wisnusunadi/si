<script>
import modalNoSeri from './modalNoSeri.vue'
export default {
    components: {
        modalNoSeri
    },
    props: ['items'],
    data() {
        return {
            headers: [
                {
                    text: 'Periode',
                    value: 'periode'
                },
                {
                    text: 'Tanggal Mulai',
                    value: 'tgl_mulai',
                    sortable: false
                },
                {
                    text: 'Tanggal Selesai',
                    value: 'tgl_selesai',
                    sortable: false
                },
                {
                    text: 'Jenis Perakitan',
                    value: 'jenis',
                    sortable: false
                },
                {
                    text: 'No BPPB',
                    value: 'no_bppb'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama',
                    sortable: false
                },
                {
                    text: 'Jumlah Rakit',
                    value: 'jumlah_unit'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Keterangan',
                    value: 'keterangan'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            search: '',
            tanggalAwalMulai: '',
            tanggalAkhirMulai: '',
            tanggalAwalSelesai: '',
            tanggalAkhirSelesai: '',
            filterProduk: [],
            jenisPerakitanOptions: [
                {
                    label: 'Terjadwal',
                    value: 'terjadwal'
                },
                {
                    label: 'Tidak Terjadwal',
                    value: 'tidak_terjadwal'
                }
            ],
            jenisPerakitanSelected: [],
            detailSelected: null,
            showDetail: false
        }
    },
    computed: {
        produkUnique() {
            return [...new Set(this.items.map(item => item.nama))]
        },
        filterData() {
            if (this.tanggalAwalMulai && this.tanggalAkhirMulai) {
                const startDate = new Date(this.tanggalAwalMulai)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhirMulai)
                endDate.setHours(23, 59, 59, 999)

                return this.items.filter((data) => {
                    const date = new Date(data.tanggal_mulai)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalAwalMulai) {
                const startDate = new Date(this.tanggalAwalMulai)
                startDate.setHours(0, 0, 0, 0)

                return this.items.filter((data) => {
                    const date = new Date(data.tanggal_mulai)
                    return date >= startDate
                })
            } else if (this.tanggalAkhirMulai) {
                const endDate = new Date(this.tanggalAkhirMulai)
                endDate.setHours(23, 59, 59, 999)

                return this.items.filter((data) => {
                    const date = new Date(data.tanggal_mulai)
                    return date <= endDate
                })
            }

            if (this.tanggalAwalSelesai && this.tanggalAkhirSelesai) {
                const startDate = new Date(this.tanggalAwalSelesai)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhirSelesai)
                endDate.setHours(23, 59, 59, 999)

                return this.items.filter((data) => {
                    const date = new Date(data.tanggal_selesai)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalAwalSelesai) {
                const startDate = new Date(this.tanggalAwalSelesai)
                startDate.setHours(0, 0, 0, 0)

                return this.items.filter((data) => {
                    const date = new Date(data.tanggal_selesai)
                    return date >= startDate
                })
            } else if (this.tanggalAkhirSelesai) {
                const endDate = new Date(this.tanggalAkhirSelesai)
                endDate.setHours(23, 59, 59, 999)

                return this.items.filter((data) => {
                    const date = new Date(data.tanggal_selesai)
                    return date <= endDate
                })
            }

            if (this.jenisPerakitanSelected.length > 0) {
                return this.items.filter((data) => {
                    return this.jenisPerakitanSelected.includes(data.jenis)
                })
            }

            if (this.filterProduk.length > 0) {
                return this.items.filter((data) => {
                    return this.filterProduk.includes(data.nama)
                })
            }

            return this.items
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
        detail(item) {
            this.showDetail = true
            this.detailSelected = item
            this.$nextTick(() => {
                $('.modalNoSeri').modal('show');
            });
        },
        jenisPerakitanClicked(jenis) {
            if (this.jenisPerakitanSelected.find((data) => data === jenis.value)) {
                this.jenisPerakitanSelected = this.jenisPerakitanSelected.filter((data) => data !== jenis.value)
            } else {
                this.jenisPerakitanSelected.push(jenis.value)
            }
        },
        status(status) {
            switch (status) {
                case "closeBPPBTanpaRakit":
                    return "<span class='badge badge-danger'>Close BPPB Tanpa Rakit</span>"
                case "ok":
                    return "<span class='badge badge-success'>Selesai</span>"
                case "closeBPPBDenganSisaRakit":
                    return "<span class='badge badge-warning'>Close BPPB Dengan Sisa Rakit</span>"
                default:
                    return "<span class='badge badge-secondary'>-</span>"
            }
        }
    },
}
</script>
<template>
    <div>
        <modalNoSeri v-if="showDetail" :detail="detailSelected" @close="showDetail = false" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="filterData" :search="search">
            <template #header.tgl_mulai>
                <span class="text-bold pr-2">Tanggal Mulai</span>
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
                                            <input type="date" class="form-control" v-model="tanggalAwalMulai"
                                                :max="tanggalAkhirMulai">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Akhir</label>
                                            <input type="date" class="form-control" v-model="tanggalAkhirMulai"
                                                :min="tanggalAwalMulai">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>

            <template #header.tgl_selesai>
                <span class="text-bold pr-2">Tanggal Selesai</span>
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
                                            <input type="date" class="form-control" v-model="tanggalAwalSelesai"
                                                :max="tanggalAkhirSelesai">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Akhir</label>
                                            <input type="date" class="form-control" v-model="tanggalAkhirSelesai"
                                                :min="tanggalAwalSelesai">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>

            <template #header.nama>
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

            <template #header.jenis>
                <span class="text-bold pr-2">Jenis Perakitan</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3 font-weight-normal">
                                <div class="form-check" v-for="jenis in jenisPerakitanOptions" :key="jenis.value">
                                    <input class="form-check-input" type="checkbox"
                                        @click="jenisPerakitanClicked(jenis)" :id="jenis.value">
                                    <label class="form-check-label" :for="jenis.value">
                                        {{ jenis.label }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>

            <template #item.jenis="{ item }">
                <div>
                    <span>{{ item.jenis == 'terjadwal' ? 'Terjadwal' : 'Tidak Terjadwal' }}</span>
                </div>
            </template>

            <template #item.tgl_selesai="{ item }">
                <span>{{ item.tgl_selesai }}</span> <br>
                <span v-html="selisih(item.selisih, item.tanggal_selesai)"></span>
            </template>

            <template #item.jumlah_unit="{ item }">
                <span>{{ item.jumlah_unit }}</span><br>
                <span class="badge badge-dark">{{ item.kurang_rakit }}</span>
            </template>

            <template #item.status="{ item }">
                <span v-html="status(item.status)"></span>
            </template>

            <template #item.aksi="{ item }">
                <div>
                    <button class="btn btn-sm btn-outline-secondary" @click="detail(item)">
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                </div>
            </template>
        </data-table>
    </div>
</template>