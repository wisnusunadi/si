<script>
import Header from '../../components/header.vue';
import pagination from '../../components/pagination.vue';
import Table from './table.vue';
import axios from 'axios';
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
            dataTable: [],
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
                const { data } = await axios.get('/api/gbj/rw/belum_kirim');
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
    },
}
</script>
<template>
    <div v-if="!$store.state.loading">
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                        <input type="text" v-model="search" class="form-control" placeholder="Cari...">
                    </div>
                </div>
                <Table :dataTable="renderPaginate" @refresh="getData" />
                <pagination :filteredDalamProses="filteredDalamProses"
                    @updateFilteredDalamProses="updateFilteredDalamProses" />
            </div>
        </div>
    </div>
</template>