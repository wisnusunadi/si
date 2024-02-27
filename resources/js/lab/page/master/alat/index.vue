<script>
import Table from "./table.vue";
import pagination from "../../../components/pagination.vue";
import Modal from "./modal.vue";
import axios from "axios";
import Header from "../../../components/header.vue";
export default {
    components: {
        Table,
        pagination,
        Modal,
        Header,
    },
    data() {
        return {
            alat: [],
            search: "",
            renderPaginate: [],
            modal: false,
            dataSelected: null,
            title: "Master Alat Laboratorium",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Master Alat Laboratorium",
                    link: "/master/alat",
                },
            ]
        };
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        tambah() {
            this.modal = true;
            this.dataSelected = {
                kode: "",
                nama: "",
                produk: [
                    {
                        produk: null,
                    },
                ],
            };
            this.$nextTick(() => {
                $(".modalFormAlat").modal("show");
            });
        },
        async edit(id) {
            try {
                const { data } = await axios.get("/api/labs/kode/" + id);
                this.dataSelected = data.data;
                // add id on dataSelected
                this.dataSelected.id = id;
            } catch (error) {
                console.log(error);
            }
            this.modal = true;
            this.$nextTick(() => {
                $(".modalFormAlat").modal("show");
            });
        },
        async getAlat() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get("/api/labs/kode");
                this.alat = data.data;
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        },
    },
    computed: {
        filteredDalamProses() {
            return this.alat.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key])
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                });
            });
        },
    },
    created() {
        this.getAlat();
    },
};
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card" v-if="!$store.state.loading">
            <modal v-if="modal" @close="modal = false" :kode="dataSelected" @refresh="getAlat" />
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="tambah">
                            <i class="fas fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" placeholder="Cari..." v-model="search" />
                    </div>
                </div>
                <Table :dataTable="renderPaginate" @edit="edit" />
                <pagination :filteredDalamProses="filteredDalamProses"
                    @updateFilteredDalamProses="updateFilteredDalamProses" />
            </div>
        </div>
    </div>
</template>
