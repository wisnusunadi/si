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
        },
        filterByYear(year) {
            this.$store.dispatch('setYears', year);
            this.$emit('refresh');
        },
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
        years5BeforeNow() {
            const years = [];
            for (let i = 0; i < 5; i++) {
                years.push(moment().subtract(i, 'years').format('YYYY'));
            }
            return years;
        }
    }
}
</script>
<template>
    <div>
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <span class="filter">
                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-filter"></i> Filter Tahun
                    </button>
                    <form id="filterSelesaiProses">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="form-group">
                                    <label for="jenis_penjualan">Database</label>
                                </div>
                                <div class="form-group">
                                    <div class="form-check" v-for="year in years5BeforeNow" :key="year">
                                        <input class="form-check-input" type="radio" :value="year"
                                            @click="filterByYear(year)" :checked="$store.state.years == year"
                                            :id="'defaultCheck' + year" />
                                        <label class="form-check-label" :for="'defaultCheck' + year">
                                            {{ year }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari...">
            </div>
        </div>


        <div v-if="!$store.state.loading">
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
            <pagination :filteredDalamProses="filteredDalamProses"
                @updateFilteredDalamProses="updateFilteredDalamProses" />
        </div>
        <div v-else class="text-center">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>