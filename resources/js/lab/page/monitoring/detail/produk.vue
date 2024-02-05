<script>
import Pagination from "../../../components/pagination.vue";
import noseri from "./noseri.vue";
import axios from "axios";
export default {
    components: {
        Pagination,
        noseri,
    },
    props: ["dataTable"],
    data() {
        return {
            search: "",
            renderPaginate: [],
            no_seri_get: [],
        };
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        async detail(id) {
            const { data } = await axios.get(`/api/qc/monitoring/seri/${id}`).then(res => res.data)
            this.no_seri_get = data.map((item) => {
                return {
                    ...item,
                    tgl_masuk: this.formatDate(item.tgl_masuk),
                };
            });
        },
    },
    computed: {
        filteredDalamProses() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key])
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                });
            });
        },
    },
};
</script>
<template>
    <div class="row">
        <div :class="no_seri_get.length > 0 ? 'col-md-8' : 'col-md-12'">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <div class="p-2 bd-highlight">
                            <input type="text" class="form-control" v-model="search" placeholder="Cari...">
                        </div>
                    </div>
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama Produk</th>
                                <th rowspan="2">Jumlah</th>
                                <th colspan="2">Hasil</th>
                                <th rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th>
                                    <i class="fas fa-check"></i>
                                </th>
                                <th>
                                    <i class="fas fa-times"></i>
                                </th>
                            </tr>
                        </thead>
                        <tbody v-if="renderPaginate.length > 0">
                            <tr v-for="(data, index) in renderPaginate" :key="index">
                                <td>
                                    {{ index + 1 }}
                                </td>
                                <td>{{ data.tipe }}</td>
                                <td>{{ data.jumlah }}</td>
                                <td>{{ data.jumlah_ok }}</td>
                                <td>{{ data.jumlah_nok }}</td>
                                <td>
                                    <button @click="detail(data.id)" class="btn btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="100%" class="text-center">
                                    Data tidak ditemukan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination :filteredDalamProses="filteredDalamProses"
                        @updateFilteredDalamProses="updateFilteredDalamProses" />
                </div>
            </div>
        </div>
        <div class="col-md-4" v-if="no_seri_get.length > 0">
            <noseri :dataTable="no_seri_get" />
        </div>
    </div>
</template>
