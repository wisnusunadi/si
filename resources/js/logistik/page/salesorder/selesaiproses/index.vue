<script>
import pagination from '../../../components/pagination.vue';
export default {
    props: ['selesai'],
    components: {
        pagination
    },
    data() {
        return {
            search: '',
            renderPaginate: [],
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        dateFormatString(date) {
            return date ? moment(date).format('DD-MM-Y') : '-';
        }
    },
    computed: {
        filteredDalamProses() {
            const includesSearch = (obj, search) => {
                if (obj && typeof obj === 'object') {
                    return Object.keys(obj).some(key => {
                        if (typeof obj[key] === 'object') {
                            return includesSearch(obj[key], search);
                        }
                        return String(obj[key]).toLowerCase().includes(search.toLowerCase());
                    });
                }
                return false;
            };

            return this.selesai.filter(data => includesSearch(data, this.search));
        },
    }
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>
        <table class="table text-center">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">No SO</th>
                    <th rowspan="2">No PO</th>
                    <th colspan="2">Pengiriman</th>
                    <th rowspan="2">Customer</th>
                    <th rowspan="2">Keterangan</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Awal</th>
                    <th>Akhir</th>
                </tr>
            </thead>
            <tbody v-if="renderPaginate.length > 0">
                <tr v-for="(item, idx) in renderPaginate" :key="idx">
                    <td>{{ item.no }}</td>
                    <td>{{ item.so }}</td>
                    <td>{{ item.no_po }}</td>
                    <td>{{ dateFormatString(item.tgl_kirim_min) }}</td>
                    <td>{{ dateFormatString(item.tgl_kirim_max) }}</td>
                    <td>{{ item.tujuan_kirim }}</td>
                    <td>{{ item.ket }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                    </td>
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