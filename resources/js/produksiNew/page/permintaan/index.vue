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
            title: 'Permintaan Perakitan Reworks',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Permintaan Perakitan Reworks',
                    link: '#'
                },
            ],
            dataTable: [
                {
                    tanggal_mulai: '2023-10-01',
                    tanggal_selesai: '2023-10-31',
                    nama_produk: 'Produk 1',
                    jumlah: 100,
                }
            ],
            search: '',
            renderPaginate: [],
        }
    },
    methods: {
        updateFilteredDalamProses(value) {
            this.renderPaginate = value;
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
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search">
            </div>
        </div>
        <pagination :filteredDalamProses="filteredDalamProses" :updateFilteredDalamProses="updateFilteredDalamProses" />
    </div>
</template>