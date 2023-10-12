<script>
import Header from '../../components/header.vue'
import Table from './table.vue';
import Pagination from '../../components/pagination.vue';
export default {
    components: {
        Table,
        Header,
        Pagination,
    },
    data() {
        return {
            title: 'Set Produk Reworks',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Set Produk Reworks',
                    link: '#'
                },
            ],
            dataTable: [
                {
                    id: 1,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-10-31',
                    nama_produk: 'Produk 1',
                    status: 'menunggu',
                    jumlah_selesai: 50,
                    jumlah_belum_selesai: 50,
                },
                {
                    id: 2,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-10-31',
                    nama_produk: 'Produk 2',
                    status: 'new',
                    jumlah_selesai: 50,
                    jumlah_belum_selesai: 50,
                }
            ],
            search: '',
            renderPaginate: [],
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
    },
    computed: {
        filteredDalamProses() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
    },
}
</script>
<template>
    <div>
        <Modal
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                        <input type="text" v-model="search" class="form-control" placeholder="Cari...">
                    </div>
                </div>
                <Table :dataTable="renderPaginate" />
                <pagination :filteredDalamProses="filteredDalamProses"
                    @updateFilteredDalamProses="updateFilteredDalamProses" />
            </div>
        </div>
    </div>
</template>