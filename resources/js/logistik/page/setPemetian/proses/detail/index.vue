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
            // items: [
            //     {
            //         id: 1,
            //         no_peti: 'PETI-1',
            //         tanggal_dibuat: '31 Desember 2020',
            //         packer: 'Packer 1',
            //         seri: [
            //             {
            //                 produk: 'ANTROPOMETRI KIT 10',
            //                 noseri: 'TD08217A4235'
            //             },
            //             {
            //                 produk: 'ANTROPOMETRI KIT 10',
            //                 noseri: 'TD08217A4235'
            //             },
            //             {
            //                 produk: 'ANTROPOMETRI KIT 10',
            //                 noseri: 'TD08217A4235'
            //             }
            //         ]
            //     }
            // ],
            items: [],
            showModalGenerate: false,
            showModalDetail: false,
            detailSeriSelected: {},
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
    },
    mounted() {
        this.getPeti();
    }

}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <Generate v-if="showModalGenerate" @closeModal="showModalGenerate = false" :selectSeri="detailSeriSelected" />
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
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <DataTable :headers="headers" :items="items" :search="search">
                    <template #item.no="{ item, index }">
                        <div>
                            {{ index + 1 }}
                        </div>
                    </template>
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