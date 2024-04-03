<script>
import pagination from '../../../../../emiindo/components/pagination.vue'
export default {
    props: ['peminjaman'],
    components: {
        pagination
    },
    data() {
        return {
            renderPaginate: [],
            search: '',
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
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

            return this.peminjaman.filter(data => includesSearch(data, this.search));
        },
    }
}
</script>
<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-row-reverse bd-highlight">
                <div class="p-2 bd-highlight">
                    <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Tgl Peminjaman</th>
                        <th>Tgl Pengambilan</th>
                    </tr>
                </thead>
                <tbody v-if="renderPaginate.length > 0">
                    <template v-for="(item, idx) in renderPaginate">
                        <tr>
                            <td>
                                <button class="btn btn-sm"
                                    :class="item.expanded ? 'btn-outline-danger' : 'btn-outline-primary'"
                                    @click="item.expanded = !item.expanded">
                                    <i class="fa" :class="item.expanded ? 'fa-minus' : 'fa-plus'"></i>
                                </button>
                            </td>
                            <td>{{ item.nama }}</td>
                            <td>{{ item.jumlah }}</td>
                            <td>{{ dateFormat(item.tgl_peminjaman) }}</td>
                            <td>{{ dateFormat(item.tgl_pengambilan) }}</td>
                        </tr>
                        <tr v-if="item.expanded">
                            <td colspan="100%">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor Seri</th>
                                            <th>Tanggal Pengembalian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(noseri, idx2) in item.noseri">
                                            <td>{{ idx2 + 1 }}</td>
                                            <td>{{ noseri.noseri }}</td>
                                            <td>{{ dateFormat(noseri.tgl_pengembalian) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </template>
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
    </div>
</template>