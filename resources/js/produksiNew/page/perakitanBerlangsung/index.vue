<script>
import Header from '../../components/header.vue';
import Table from './table.vue';
import pagination from '../../components/pagination.vue';
import axios from 'axios';
export default {
    components: {
        Header,
        Table,
        pagination,
    },
    data() {
        return {
            title: 'Jadwal Perakitan Berlangsung',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Jadwal Perakitan Berlangsung',
                    link: '#'
                },
            ],

            dataTable: [],
            search: '',
            renderPaginate: [],
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data } = await axios.get('/api/prd/ongoing')
                this.dataTable = data.map(item => {
                    return {
                        ...item,
                        tgl_mulai: this.dateFormat(item.tanggal_mulai),
                        tgl_selesai: this.dateFormat(item.tanggal_selesai),
                        kurang_rakit: `Kurang ${item.jumlah - item.jumlah_rakit}`,
                        jumlah_unit: `${item.jumlah} Unit`
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        },
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
    mounted() {
        this.getData()
    },
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body" v-if="!$store.state.loading">
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