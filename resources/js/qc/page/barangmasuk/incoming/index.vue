<script>
import modalUji from './modalUji.vue'
export default {
    components: {
        modalUji
    },
    props: ['data'],
    data() {
        return {
            search: '',
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
                    value: 'no_bppb'
                },
                {
                    text: 'Produk',
                    value: 'nama_produk',
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah',
                },
                {
                    text: 'Progress',
                    value: 'progress',
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                },
            ],
            tanggalMasukAwal: '',
            tanggalMasukAkhir: '',
            tanggalKeluarAwal: '',
            tanggalKeluarAkhir: '',
            showModal: false,
            detail: {},
        }
    },
    methods: {
        clickDetail(detail) {
            this.detail = detail
            this.showModal = true
            this.$nextTick(() => {
                $('.modalUji').modal('show')
            })
        }
    },
    computed: {
        filterData() {
            let filtered = this.data

            if (this.tanggalMasukAwal && this.tanggalMasukAkhir) {
                const startDate = new Date(this.tanggalMasukAwal)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalMasukAkhir)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tgl_mulai)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalMasukAwal) {
                const startDate = new Date(this.tanggalMasukAwal)
                startDate.setHours(0, 0, 0, 0)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tgl_mulai)
                    return date >= startDate
                })
            } else if (this.tanggalMasukAkhir) {
                const endDate = new Date(this.tanggalMasukAkhir)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tgl_mulai)
                    return date <= endDate
                })
            }

            if (this.tanggalMasukAwal && this.tanggalMasukAkhir) {
                const startDate = new Date(this.tanggalMasukAwal)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalMasukAkhir)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tgl_selesai)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalMasukAwal) {
                const startDate = new Date(this.tanggalMasukAwal)
                startDate.setHours(0, 0, 0, 0)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tgl_selesai)
                    return date >= startDate
                })
            } else if (this.tanggalMasukAkhir) {
                const endDate = new Date(this.tanggalMasukAkhir)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter(item => {
                    const date = new Date(item.tgl_selesai)
                    return date <= endDate
                })
            }

            return filtered;
        }
    }
}
</script>
<template>
    <div>
        <modalUji :produk="detail" v-if="showModal" @closeModal="showModal = false" />
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight"><input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="filterData" :search="search">
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
                                            <input type="date" class="form-control" v-model="tanggalMasukAwal"
                                                :max="tanggalMasukAkhir">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Akhir</label>
                                            <input type="date" class="form-control" v-model="tanggalMasukAkhir"
                                                :min="tanggalMasukAwal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>
            <template #header.tgl_selesai>
                <div>
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
                                                        <input type="date" class="form-control" v-model="tanggalKeluarAwal"
                                                            :max="tanggalKeluarAkhir">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="jenis_penjualan">Tanggal Akhir</label>
                                                        <input type="date" class="form-control" v-model="tanggalKeluarAkhir"
                                                            :min="tanggalKeluarAwal">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </span>
                </div>
            </template>
            <template #item.jumlah="{ item }">
                <div>
                    {{ item.jumlah }}
                    <br><span class="badge badge-dark">
                        Terisi: {{ item.jml_all }} Unit
                    </span>
                </div>
            </template>
            <template #item.progress="{ item }">
                <div>
                    <span class="badge badge-dark">Rakit: {{ item.jml_rakit }} Unit {{ item.perc_rakit ??
                        0.00 }}%</span> <br>
                        <span class="badge badge-info">Terkirim: {{ data.jml_kirim }} Unit {{ data.perc_kirim ??
                            0.00 }}%</span> <br>
                        <!-- baru -->
                    <span class="badge badge-success">Lolos: 10 Unit 10%</span> <br>
                    <span class="badge badge-danger">Tidak Lolos: 10 Unit 10%</span>
                </div>
            </template>
            <template #item.aksi="{item}">
                <div v-if="item.jml_rakit != 0">
                    <button class="btn btn-outline-info btn-sm" @click="clickDetail(item)">
                        <i class="fas fa-flask"></i>
                        Pengujian
                    </button>
                </div>
            </template>
        </data-table>
    </div>
</template>