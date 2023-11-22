<script>
import Header from '../../../../components/Header.vue';
import DataTable from '../../../../components/DataTable.vue';
import Generate from '../generate';
import DetailSeri from '../modalDetail';
import axios from 'axios';
export default {
    components: {
        Header,
        DataTable,
        Generate,
        DetailSeri,
    },
    data() {
        return {
            title: 'Detail Set Pemetian',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/logistik/dashboard'
                },
                {
                    name: 'Pemetian',
                    link: '/logistik/pengiriman/pemetian'
                },
                {
                    name: 'Detail Set Pemetian',
                    link: '#'
                }
            ],
            search: '',
            headers: [
                { text: 'No.', value: 'no', sortable: false },
                { text: 'No. Peti', value: 'no_peti' },
                { text: 'Tanggal Dibuat', value: 'tanggal_dibuat' },
                { text: 'Packer', value: 'packer' },
                { text: 'Aksi', value: 'action', sortable: false },
            ],
            items: [],
            showModalGenerate: false,
            showModalDetail: false,
            detailSeriSelected: {},
            filterProses: [],
            tanggalAwal: '',
            tanggalAkhir: '',
        }
    },
    methods: {
        openModalGenerate() {
            this.detailSeriSelected = {};
            this.showModalGenerate = true;
            this.$nextTick(() => {
                $('.modalGenerate').modal('show');
            });
        },
        async openModalDetail(item) {
            const { data } = await axios.get(`/api/logistik/rw/peti/detail/${item.id}`)
            this.detailSeriSelected = {
                ...item,
                seri: data.map((item, index) => {
                    return {
                        ...item,
                        produk: 'ANTROPOMETRI KIT 10',
                        no: index + 1,
                    }
                })
            }
            this.showModalDetail = true;
            this.$nextTick(() => {
                $('.modalDetailSeri').modal('show');
            });
        },
        async openEditNomorSeri(item) {
            const { data } = await axios.get(`/api/logistik/rw/peti/detail/${item.id}`)
            this.detailSeriSelected = {
                ...item,
                seri: data.map((item, index) => {
                    return {
                        ...item,
                        produk: 'ANTROPOMETRI KIT 10',
                        no: index + 1,
                    }
                })
            }
            this.showModalGenerate = true;
            this.$nextTick(() => {
                $('.modalGenerate').modal('show');
            });
        },
        async getPeti() {
            const { data } = await axios.get('/api/logistik/rw/peti/show');
            this.items = data.map((item, index) => {
                return {
                    ...item,
                    no_peti: `PETI-${item.no_urut}`,
                    tanggal_dibuat: this.dateFormat(item.tgl_buat),
                }
            });
        },
        cetakPackingList(id) {
            window.open(`/produksiReworks/cetakpeti/${id}`, '_blank');
        },
        viewPackingList(id) {
            window.open(`/produksiReworks/viewpeti/${id}`, '_blank');
        },
        clickFilterProses(filter) {
            if (this.filterProses.includes(filter)) {
                this.filterProses = this.filterProses.filter(item => item !== filter)
            } else {
                this.filterProses.push(filter)
            }
        },
        renderNo(data) {
            return data.map((item, index) => {
                return {
                    ...item,
                    no: index + 1
                }
            })
        },
        refresh() {
            this.filterProses = []
            this.tanggalAwal = ''
            this.tanggalAkhir = ''
            this.search = ''
            this.getPeti()
            this.showModalGenerate = false
        }
    },
    mounted() {
        this.getPeti();
    },
    computed: {
        filterData() {
            let filtered = this.renderNo(this.items)

            if (this.filterProses.length > 0) {
                filtered = this.renderNo(filtered.filter(data => this.filterProses.includes(data.packer)))
            }

            if (this.tanggalAwal && this.tanggalAkhir) {
                filtered = this.renderNo(filtered.filter(data => new Date(data.tgl_buat) >= new Date(this.tanggalAwal) && new Date(data.tgl_buat) <= new Date(this.tanggalAkhir)))
            } else if (this.tanggalAwal) {
                filtered = this.renderNo(filtered.filter(data => new Date(data.tgl_buat) >= new Date(this.tanggalAwal)))
            } else if (this.tanggalAkhir) {
                filtered = this.renderNo(filtered.filter(data => new Date(data.tgl_buat) <= new Date(this.tanggalAkhir)))
            }

            return filtered.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
        getAllStatusUnique() {
            const packer = this.items.map((data) => data.packer)
            return [...new Set(packer)]
        },
    },

}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <Generate v-if="showModalGenerate" @closeModal="refresh" :selectSeri="detailSeriSelected" />
        <DetailSeri v-if="showModalDetail" :dataModalDetailSeri="detailSeriSelected"
            @closeModal="showModalDetail = false" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="openModalGenerate">
                            <i class="fas fa-plus"></i>
                            Tambah
                        </button>
                        <span class="filter ml-2">
                            <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <form id="filter_ekat">
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Packer</label>
                                        </div>
                                        <div class="form-group" v-for="status in getAllStatusUnique" :key="status">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" :ref="status"
                                                    :value="status" id="status1" @click="clickFilterProses(status)" />
                                                <label class="form-check-label text-uppercase" for="status1">
                                                    {{ status }}
                                                </label>
                                            </div>
                                        </div>
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
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <DataTable :headers="headers" :items="filterData">
                    <template #item.action="{ item }">
                        <div>
                            <button class="btn btn-sm btn-outline-info" @click="openModalDetail(item)">
                                <i class="fas fa-info-circle"></i>
                                Detail No. Seri Peti
                            </button>
                            <button class="btn btn-sm btn-outline-warning" @click="openEditNomorSeri(item)">
                                <i class="fas fa-pencil"></i>
                                Edit No. Seri Peti
                            </button>
                            <br>
                            <button class="btn btn-sm btn-outline-info my-1" @click="viewPackingList(item.id)">
                                <i class="fas fa-eye"></i>
                                Lihat Packing List
                            </button>
                            <button class="btn btn-sm btn btn-outline-primary my-1" @click="cetakPackingList(item.id)">
                                <i class="fas fa-print"></i>
                                Cetak Packing List
                            </button>
                        </div>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</template>
<style>
.scrollable {
    overflow-x: scroll;
    height: 400px;
}
</style>