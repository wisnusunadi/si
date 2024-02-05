<script>
import pagination from "../../components/pagination.vue";
import Header from "../../components/header.vue";
import produk from "./produk.vue";
import loading from "../../components/loading.vue";
import axios from 'axios';
export default {
    components: {
        pagination,
        produk,
        Header,
        loading,
    },
    data() {
        return {
            // header
            title: "Penggantian Unit Gagal Uji",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Penggantian Unit",
                    link: "/gbj/changeunit",
                },
            ],
            search: "",
            renderPaginate: [],
            dataTable: [],
            modal: false,
            selectedSO: null,
        };
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        transfer(data) {
            this.selectedSO = data;
            this.modal = true;
            this.$nextTick(() => {
                $(".modalProduk").modal("show");
            });
        },
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get("/api/gbj/ganti_unit");
                this.dataTable = data;
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        }
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
    created() {
        this.getData();
    },
};
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="spinner-border" role="status" v-if="$store.state.loading">
                <span class="sr-only">Loading...</span>
            </div>
            <div class="card-body" v-else>
                <produk v-if="modal" @close="modal = false" :headerSO="selectedSO" @refresh="getData" />
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="search" />
                        </div>
                    </div>
                </div>
                <table class="table">
                    <thead class="text-center">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">No SO</th>
                            <th rowspan="2">No PO</th>
                            <th rowspan="2">Customer</th>
                            <th colspan="2">Hasil</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Pengujian</th>
                            <th>Kalibrasi</th>
                        </tr>
                    </thead>
                    <tbody v-if="renderPaginate.length > 0">
                        <tr v-for="(data, index) in renderPaginate" :key="index">
                            <td class="text-center">{{ index + 1 }}</td>
                            <td class="text-center">{{ data.so }}</td>
                            <td class="text-center">{{ data.no_po }}</td>
                            <td class="text-center">{{ data.customer }}</td>
                            <td class="text-center"><i class="fas fa-times text-danger" v-if="data.jumlah_pengujian > 0"></i>
                            </td>
                            <td class="text-center"><i class="fas fa-times text-danger" v-if="data.jumlah_kalibrasi > 0"></i>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-outline-primary btn-sm" @click="transfer(data)">
                                    Transfer
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="5" class="text-center">
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
</template>
