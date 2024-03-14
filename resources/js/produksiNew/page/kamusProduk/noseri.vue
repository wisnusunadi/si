<script>
import axios from 'axios';

export default {
    props: ['detailSelected', 'years'],
    data() {
        return {
            headers: [
                {
                    text: 'No',
                    value: 'no',
                },
                {
                    text: 'Nomor Seri',
                    value: 'noseri',
                },
                {
                    text: 'No BPPB',
                    value: 'no_bppb',
                },
                {
                    text: 'Jenis Perakitan',
                    value: 'jenis',
                    sortable: false
                },
                {
                    text: 'Tanggal Rakit',
                    value: 'tanggal_rakit',
                },
                {
                    text: 'Tanggal Transfer',
                    value: 'tanggal_transfer',
                }
            ],
            items: [],
            search: '',
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
            tanggalRakitAwal: '',
            tanggalRakitAkhir: '',
            tanggaTransferAwal: '',
            tanggalTransferAkhir: '',
            loading: false,
        }
    },
    methods: {
        closeModal() {
            $('#modelId').modal('hide');
            this.$nextTick(() => {
                this.$emit('close');
            });
        },
        async getData() {
            try {
                this.loading = true
                const { data } = await axios.get(`/api/prd/kamus_prd/detail/${this.years}/${this.detailSelected.id}`)
                this.items = data.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                        tanggal_rakit: this.dateFormat(item.tgl_rakit),
                        tanggal_transfer: this.dateFormat(item.tgl_transfer)
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.loading = false
            }
        },
        jenisPerakitanClicked(jenis) {
            if (this.jenisPerakitanSelected.find((data) => data === jenis.value)) {
                this.jenisPerakitanSelected = this.jenisPerakitanSelected.filter((data) => data !== jenis.value)
            } else {
                this.jenisPerakitanSelected.push(jenis.value)
            }
        },
    },
    created() {
        this.getData()
    },
    computed: {
        filterData() {
            if (this.tanggalRakitAwal && this.tanggalRakitAkhir) {
                const startDate = new Date(this.tanggalRakitAwal)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalRakitAkhir)
                endDate.setHours(23, 59, 59, 999)

                return this.items.filter(item => {
                    const date = new Date(item.tgl_rakit)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggalRakitAwal) {
                const startDate = new Date(this.tanggalRakitAwal)
                startDate.setHours(0, 0, 0, 0)

                return this.items.filter(item => {
                    const date = new Date(item.tgl_rakit)
                    return date >= startDate
                })
            } else if (this.tanggalRakitAkhir) {
                const endDate = new Date(this.tanggalRakitAkhir)
                endDate.setHours(23, 59, 59, 999)

                return this.items.filter(item => {
                    const date = new Date(item.tgl_rakit)
                    return date <= endDate
                })
            }

            if (this.tanggaTransferAwal && this.tanggalTransferAkhir) {
                const startDate = new Date(this.tanggaTransferAwal)
                startDate.setHours(0, 0, 0, 0)

                const endDate = new Date(this.tanggalTransferAkhir)
                endDate.setHours(23, 59, 59, 999)

                return this.items.filter(item => {
                    const date = new Date(item.tgl_transfer)
                    return date >= startDate && date <= endDate
                })
            } else if (this.tanggaTransferAwal) {
                const startDate = new Date(this.tanggaTransferAwal)
                startDate.setHours(0, 0, 0, 0)

                return this.items.filter(item => {
                    const date = new Date(item.tgl_transfer)
                    return date >= startDate
                })
            } else if (this.tanggalTransferAkhir) {
                const endDate = new Date(this.tanggalTransferAkhir)
                endDate.setHours(23, 59, 59, 999)

                return this.items.filter(item => {
                    const date = new Date(item.tgl_transfer)
                    return date <= endDate
                })
            }

            if (this.jenisPerakitanSelected.length > 0) {
                return this.items.filter(item => {
                    return this.jenisPerakitanSelected.includes(item.jenis)
                })
            }

            return this.items
        }
    }
}
</script>
<template>
    <div class="modal fade" id="modelId" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Nomor Seri Produk</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                        </div>
                    </div>
                    <data-table :headers="headers" :items="filterData" :search="search" v-if="!loading">
                        <template #header.jenis>
                            <div>
                                <span class="text-bold pr-2">Jenis Perakitan</span>
                                <span class="filter">
                                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i>
                                    </a>
                                    <form id="filter_ekat">
                                        <div class="dropdown-menu">
                                            <div class="px-3 py-3 font-weight-normal">
                                                <div class="form-check" v-for="jenis in jenisPerakitanOptions"
                                                    :key="jenis.value">
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
                            </div>
                        </template>
                        <template #header.tanggal_rakit>
                            <span class="text-bold pr-2">Tanggal Rakit</span>
                            <span class="filter">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-filter"></i>
                                </a>
                                <form id="filter_ekat">
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3 font-weight-normal">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="jenis_penjualan">Tanggal Awal</label>
                                                        <input type="date" class="form-control"
                                                            v-model="tanggalRakitAwal" :max="tanggalRakitAkhir">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="jenis_penjualan">Tanggal Akhir</label>
                                                        <input type="date" class="form-control"
                                                            v-model="tanggalRakitAkhir" :min="tanggalRakitAwal">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </span>
                        </template>
                        <template #header.tanggal_transfer>
                            <span class="text-bold pr-2">Tanggal Rakit</span>
                            <span class="filter">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-filter"></i>
                                </a>
                                <form id="filter_ekat">
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3 font-weight-normal">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="jenis_penjualan">Tanggal Transfer</label>
                                                        <input type="date" class="form-control"
                                                            v-model="tanggaTransferAwal" :max="tanggalTransferAkhir">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="jenis_penjualan">Tanggal Akhir</label>
                                                        <input type="date" class="form-control"
                                                            v-model="tanggalTransferAkhir" :min="tanggaTransferAwal">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </span>
                        </template>
                        <template #item.jenis="{ item }">
                            <span>{{ item.jenis == 'terjadwal' ? 'Terjadwal' : 'Tidak Terjadwal' }}</span>
                        </template>
                    </data-table>
                    <div v-else class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>