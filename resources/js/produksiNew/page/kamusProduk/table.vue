<script>
import pagination from '../../components/pagination.vue';
export default {
    components: {
        pagination,
    },
    props: ['dataTable', 'search'],
    data() {
        return {
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
        }
    },
}
</script>
<template>
    <div>
        <table class="table text-center">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Kode Produk</th>
                    <th rowspan="2">Nama Produk</th>
                    <th colspan="2">Jumlah No Seri Dirakit</th>
                    <th rowspan="2">Total No Seri Dirakit</th>
                </tr>
                <tr>
                    <th>No. Seri Terjadwal</th>
                    <th>No. Seri Tanpa Jadwal</th>
                </tr>
            </thead>
            <tbody v-if="renderPaginate.length > 0">
                <tr v-for="(item, index) in renderPaginate" :key="index">
                    <td>{{ item.no }}</td>
                    <td>{{ item.kode }}</td>
                    <td>{{ item.nama }}</td>
                    <td>{{ item.terjadwal }}</td>
                    <td>{{ item.tdk_terjadwal }}</td>
                    <td>{{ item.total }}</td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="100%" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
        <pagination :filteredDalamProses="filteredDalamProses" @updateFilteredDalamProses="updateFilteredDalamProses" />
    </div>
</template>