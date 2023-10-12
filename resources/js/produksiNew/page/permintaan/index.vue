<script>
import Header from '../../components/header.vue'
import Table from './table.vue';
import Pagination from '../../components/pagination.vue';
import axios from 'axios';
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
            dataTable: [],
            search: '',
            renderPaginate: [],
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        async getData() {
            try {
                this.$store.dispatch('setLoading', true);
                const { data } = await axios.get('/api/prd/rw/belum_kirim');
                this.dataTable = data;
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch('setLoading', false);
            }
        }
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
    mounted() {
        this.getData();
    }
}
</script>
<template>
    <div v-if="!this.$store.state.loading">
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                        <input type="text" v-model="search" class="form-control" placeholder="Cari...">
                    </div>
                </div>
                <Table :dataTable="renderPaginate" @refresh="getData"/>
                <pagination :filteredDalamProses="filteredDalamProses"
                    @updateFilteredDalamProses="updateFilteredDalamProses" />
            </div>
        </div>
    </div>
</template>