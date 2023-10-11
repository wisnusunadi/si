<script>
import Header from '../../components/header.vue';
import pagination from '../../components/pagination.vue';
import Table from './table.vue';
export default {
    components: {
        Header,
        pagination,
        Table,
    },
    data() {
        return {
            title: 'Permintaan Rework',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/gbj/dashboard'
                },
                {
                    name: 'Permintaan Rework',
                    link: '/permintaan-rework'
                }
            ],
            search: '',
            dataTable: [
                {
                    id: 1,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-10-31',
                    nama_produk: 'Produk 1',
                    jumlah: 100,
                },
                {
                    id: 2,
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-10-31',
                    nama_produk: 'Produk 2',
                    jumlah: 100,
                }
            ],
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