<script>
import axios from 'axios';
import Pagination from '../../../components/pagination.vue';
import Table from './table.vue';
export default {
    components: {
        Pagination,
        Table,
    },
    data() {
        return {
            dataTable: [],
            search: '',
            renderPaginate: [
            ],
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        async getData() {
            try {
                this.$store.dispatch('setLoading', true);
                const { data } = await axios.get(`/api/gbj/rw/dp/seri`);
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
    <div v-if="!$store.state.loading">
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <Table :dataTable="renderPaginate" @refresh="getData"></Table>
        <pagination :filteredDalamProses="filteredDalamProses" @updateFilteredDalamProses="updateFilteredDalamProses" />
    </div>
</template>