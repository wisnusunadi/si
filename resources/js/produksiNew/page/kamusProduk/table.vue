<script>
import pagination from '../../components/pagination.vue';
import noseri from './noseri.vue';
export default {
    components: {
        pagination,
        noseri  
    },
    props: ['dataTable', 'search', 'years'],
    data() {
        return {
            renderPaginate: [],
            showModal: false,
            detailSelected: {}
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        openModalNoSeri(item) {
            this.showModal = true;
            this.detailSelected = item;
            this.$nextTick(() => {
                $('#modelId').modal('show');
            });
        }
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
        <noseri v-if="showModal" :detailSelected="detailSelected" :years="years" @close="showModal = false" />
        <table class="table text-center">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Kode Produk</th>
                    <th rowspan="2">Nama Produk</th>
                    <th colspan="2">Jumlah No Seri Dirakit</th>
                    <th rowspan="2">Total No Seri Dirakit</th>
                    <th rowspan="2">Aksi</th>
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
                    <td><button class="btn btn-sm btn-outline-primary" @click="openModalNoSeri(item)">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button></td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="100%" class="text-center">Tidak ada data</td>
                </tr>
            </tbody>
            </table>
            <pagination :filteredDalamProses="filteredDalamProses"
                @updateFilteredDalamProses="updateFilteredDalamProses" />
    </div>
</template>