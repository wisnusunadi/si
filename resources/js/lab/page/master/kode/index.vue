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
            kepemilikan: [],
            search: "",
            renderPaginate: [],
            modal: false,
            dataSelected: null,
            title: "Kode Kepemilikan Laboratorium",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Kode Kepemilikan Laboratorium",
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
            };
            this.$nextTick(() => {
                $(".modalFormAlat").modal("show");
            });
        },
        edit(index) {
            this.modal = true;
            this.dataSelected = JSON.parse(JSON.stringify(this.kepemilikan[index]));
            this.$nextTick(() => {
                $(".modalFormAlat").modal("show");
            });
        },
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get("/api/labs/kode_milik")
                this.kepemilikan = data.data;
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        }
    },
    computed: {
        filteredDalamProses() {
            return this.kepemilikan.filter((data) => {
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
            <modal v-if="modal" @close="modal = false" :kepemilikan="dataSelected" @refresh="getData" />
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
