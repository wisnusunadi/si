<script>
import Header from '../../components/header.vue'
export default {
    components: {
        Header,
    },
    data() {
        return {
            title: 'Permintaan Perakitan',
            breadcumbs: [
                {
                    name: 'Dashboard',
                    link: '/dashboard',
                },
                {
                    name: 'Permintaan Perakitan',
                    link: '/permintaan-perakitan',
                },
            ],

            // table
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'No Permintaan',
                    value: 'no_permintaan'
                },
                {
                    text: 'No BPPB',
                    value: 'no_bppb'
                },
                {
                    text: 'Tanggal Permintaan',
                    value: 'tanggal_permintaan',
                    sortable: false,
                },
                {
                    text: 'Tanggal Akhir Persiapan',
                    value: 'tanggal_akhir_persiapan',
                    sortable: false,
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ],
            search: '',
            permintaanPerakitan: [
                {
                    no_permintaan: '20210621001',
                    no_bppb: '20210621001',
                    tgl_permintaan: '2023-09-23',
                    tanggal_permintaan: '23 September 2023',
                    tgl_akhir: '2023-10-23',
                    tanggal_akhir_persiapan: '23 Oktober 2023',
                },
                {
                    no_permintaan: '20210621002',
                    no_bppb: '20210621002',
                    tgl_permintaan: '2023-09-30',
                    tanggal_permintaan: '30 September 2023',
                    tgl_akhir: '2023-11-23',
                    tanggal_akhir_persiapan: '23 November 2023',
                }
            ],
            tanggalAwalPermintaan: '',
            tanggalAkhirPermintaan: '',
            tanggalAwalPersiapan: '',
            tanggalAkhirPersiapan: '',
        }
    },
    methods: {
        renderNo(data) {
            return data.map((item, index) => {
                return {
                    ...item,
                    no: index + 1
                }
            })
        }
    },
    computed: {
        filterData() {
            let filtered = this.permintaanPerakitan

            if (this.tanggalAwalPermintaan && this.tanggalAkhirPermintaan) {
                const startDate = new Date(this.tanggalAwalPermintaan)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhirPermintaan)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter((item) => {
                    const date = new Date(item.tgl_permintaan)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalAwalPermintaan) {
                const startDate = new Date(this.tanggalAwalPermintaan)
                startDate.setHours(0, 0, 0, 0)

                filtered = filtered.filter((item) => {
                    const date = new Date(item.tgl_permintaan)
                    return date >= startDate
                })
            } else if (this.tanggalAkhirPermintaan) {
                const endDate = new Date(this.tanggalAkhirPermintaan)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter((item) => {
                    const date = new Date(item.tgl_permintaan)
                    return date <= endDate
                })
            }

            if (this.tanggalAwalPersiapan && this.tanggalAkhirPersiapan) {
                const startDate = new Date(this.tanggalAwalPersiapan)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalAkhirPersiapan)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter((item) => {
                    const date = new Date(item.tgl_akhir)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalAwalPersiapan) {
                const startDate = new Date(this.tanggalAwalPersiapan)
                startDate.setHours(0, 0, 0, 0)

                filtered = filtered.filter((item) => {
                    const date = new Date(item.tgl_akhir)
                    return date >= startDate
                })
            } else if (this.tanggalAkhirPersiapan) {
                const endDate = new Date(this.tanggalAkhirPersiapan)
                endDate.setHours(23, 59, 59, 999)

                filtered = filtered.filter((item) => {
                    const date = new Date(item.tgl_akhir)
                    return date <= endDate
                })
            }

            return this.renderNo(filtered)

        }
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <data-table :headers="headers" :items="filterData" :search="search">
                    <template #header.tanggal_permintaan>
                        <span class="text-bold pr-2">Tanggal Permintaan</span>
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
                                                    <label for="">Tanggal Awal</label>
                                                    <input type="date" class="form-control" v-model="tanggalAwalPermintaan">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="">Tanggal Akhir</label>
                                                    <input type="date" class="form-control"
                                                        v-model="tanggalAkhirPermintaan">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>
                    <template #header.tanggal_akhir_persiapan>
                        <span class="text-bold pr-2">Tanggal Akhir Persiapan</span>
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
                                                    <label for="">Tanggal Awal</label>
                                                    <input type="date" class="form-control" v-model="tanggalAwalPersiapan">
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-group">
                                                    <label for="">Tanggal Akhir</label>
                                                    <input type="date" class="form-control" v-model="tanggalAkhirPersiapan">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </span>
                    </template>
                    <template #item.aksi="{ item }">
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>