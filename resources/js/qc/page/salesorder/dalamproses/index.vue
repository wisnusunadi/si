<script>
import pagination from '../../../components/pagination'
import moment from 'moment'
export default {
    components: {
        pagination
    },
    props: ['dalam'],
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
        calculateDateFromNow(date) {
            // kalkulasi tanggal dari sekarang
            const tglSekarang = new Date();
            const tglKontrak = new Date(date);
            if (tglKontrak < tglSekarang) {
                return {
                    text: `Lebih ${moment(tglSekarang).diff(tglKontrak, 'days')} Hari`,
                    color: 'text-danger font-weight-bold',
                    icon: 'fas fa-exclamation-circle'
                }
            } else if (tglKontrak > tglSekarang) {
                return {
                    text: `${moment(tglKontrak).diff(tglSekarang, 'days')} Hari Lagi`,
                    color: 'text-dark',
                    icon: 'fas fa-clock'
                }
            } else {
                return {
                    text: 'Batas Kontrak Habis',
                    color: 'text-danger',
                    icon: 'fas fa-exclamation-circle'
                }
            }
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

            return this.dalam.filter(data => includesSearch(data, this.search));
        },
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
                        <i class="fas fa-filter"></i> Filter Jenis Penjualan
                    </button>
                    <form id="filter">
                        <div class="dropdown-menu" style="">
                            <div class="px-3 py-3">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="ekatalog"
                                            id="defaultCheck1" name="jenis_penj[]">
                                        <label class="form-check-label" for="defaultCheck1">
                                            E-Catalogue
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2"
                                            name="jenis_penj[]">
                                        <label class="form-check-label" for="defaultCheck2">
                                            SPA
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="spb" id="defaultCheck3"
                                            name="jenis_penj[]">
                                        <label class="form-check-label" for="defaultCheck3">
                                            SPB
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
        <table class="table text-center">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">No SO</th>
                    <th rowspan="2">No PO</th>
                    <th rowspan="2">Batas Pengujian</th>
                    <th rowspan="2">Customer</th>
                    <th rowspan="2">Keterangan</th>
                    <th colspan="2">Hasil</th>
                    <th rowspan="2">Status Transfer</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th><i class="fas fa-check text-success"></i></th>
                    <th><i class="fas fa-times text-danger"></i></th>
                </tr>
            </thead>
            <tbody v-if="renderPaginate.length > 0">
                <tr v-for="(item, idx) in renderPaginate" :key="idx">
                    <td>{{ item.no }}</td>
                    <td>{{ item.so }}</td>
                    <td>{{ item.no_po }}</td>
                    <td>
                        <div v-if="item.tgl_kontrak">
                            <div :class="calculateDateFromNow(item.tgl_kontrak).color">{{
                    dateFormat(item.tgl_kontrak) }}</div>
                            <small :class="calculateDateFromNow(item.tgl_kontrak).color">
                                <i :class="calculateDateFromNow(item.tgl_kontrak).icon"></i>
                                {{ calculateDateFromNow(item.tgl_kontrak).text }}
                            </small>
                        </div>
                        <div v-else></div>
                    </td>
                    <td>{{ item.customer }}</td>
                    <td>{{ item.ket }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-print"></i>
                            SPPB
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="100%">Data tidak ditemukan</td>
                </tr>
            </tbody>
        </table>
        <pagination :filteredDalamProses="filteredDalamProses" @updateFilteredDalamProses="updateFilteredDalamProses" />

    </div>
</template>