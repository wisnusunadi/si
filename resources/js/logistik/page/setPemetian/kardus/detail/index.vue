<script>
import Header from '../../../../components/header.vue';
import DataTable from '../../../../components/DataTable.vue';
import Tambah from './tambah.vue';
import axios from 'axios';
import modalDetail from '../../../../../produksiNew/page/prosesSet/proses/modalDetail'
import LihatSeri from '../../../../../produksiNew/page/prosesSet/proses/modalCreate/modalSeri.vue'
export default {
    components: {
        Header,
        DataTable,
        Tambah,
        modalDetail,
        LihatSeri
    },
    data() {
        return {
            title: 'Detail Set Kardus',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/logistik/dashboard'
                },
                {
                    name: 'Set Reworks',
                    link: '/logistik/pengiriman/pemetian'
                },
                {
                    name: 'Set Kardus',
                    link: '#'
                }
            ],
            search: '',
            headers: [
                { text: 'No.', value: 'no', sortable: false },
                { text: 'No Seri', value: 'noseri', align: 'text-left' },
                { text: 'Tanggal Dibuat', value: 'tanggal_dibuat', align: 'text-left' },
                { text: 'Packer', value: 'packer', align: 'text-left' },
                { text: 'Aksi', value: 'aksi', sortable: false, align: 'text-left' },
            ],
            items: [],
            showModal: false,
            dataModalDetail: null,
            showModalDetail: false,
            dataLihatNoSeri: null,
            showModalNoSeri: false,
        }
    },
    methods: {
        async getKardus() {
            try {
                const { data } = await axios.get(`/api/prd/rw/proses/produk/${this.$route.params.id}`)
                this.items = data.item.map((item, index) => {
                    return {
                        ...item,
                        no: index + 1,
                        tanggal_dibuat: this.dateFormat(item.tgl_buat),
                    }
                })
            } catch (error) {
                console.log(error)
            }
        },
        openModalCreate() {
            this.showModal = true
            this.$nextTick(() => {
                $('.modalGenerate').modal('show')
            })
        },
        detailProdukSeri(data) {
            this.dataModalDetail = JSON.parse(JSON.stringify(data))
            this.showModalDetail = true

            this.$nextTick(() => {
                $('.modalDetailSeri').modal('show')
            })
        },
        lihatNoseri(noseri) {
            this.dataLihatNoSeri = noseri
            this.showModalNoSeri = true
            this.$nextTick(() => {
                $('.modalSeri').modal('show')
            })
        },
        cetakNoseri(noseri) {
            // window open with params
            window.open(`/produksiReworks/cetakseriReworkAllKardus?data=[${noseri}]`, '_blank');
        },
                lihatPackingList(id) {
            window.open(`/produksiReworks/viewpackinglist/${id}`, '_blank');
        },
                cetakPackingList(id) {
            window.open(`/produksiReworks/cetakpackinglist?data=[${id}]`, '_blank');
        },
    },
    created() {
        this.getKardus()
    }
}
</script>
<template>
    <div>
        <Tambah v-if="showModal" @closeModal="showModal = false" />
        <modalDetail v-if="showModalDetail" @closeModal="showModalDetail = false" :dataModalDetailSeri="dataModalDetail" />
        <LihatSeri v-if="showModalNoSeri" :hasilGenerate="dataLihatNoSeri" @closeModal="showModalNoSeri = false" />
        <Header :breadcumbs="breadcumbs" :title="title" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="openModalCreate">
                            <i class="fas fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                    </div>
                </div>
                <DataTable :headers="headers" :items="items" :search="search">
                    <template #item.aksi="{ item }">
                        <button class="btn btn-sm btn-outline-info" @click="detailProdukSeri(item)">
                            <i class="fas fa-info-circle"></i>
                            Detail No. Seri Produk
                        </button>
                        <br>
                        <button class="btn btn-sm btn-outline-info my-1" @click="lihatNoseri(item.noseri)">
                            <i class="fa fa-eye"></i> Lihat No. Seri
                        </button>
                        <button class="btn btn-sm btn btn-outline-primary my-1" @click="cetakNoseri(item.id)">
                            <i class="fas fa-print"></i>
                            Cetak No. Seri
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
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</template>