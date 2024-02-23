<script>
import Table from "./table.vue";
import pagination from "../../../components/pagination.vue";
import modal from "./modal.vue";
import axios from "axios";
import Header from "../../../components/header.vue";
export default {
    components: {
        Table,
        pagination,
        modal,
        Header,
    },
    data() {
        return {
            dokumen: [],
            search: "",
            renderPaginate: [],
            modal: false,
            dataSelected: {
                metode: "",
                no_dokumen: "",
                ruang: [
                    {
                        ruang: null,
                    },
                ],
            },
            title: "Dokumen",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Dokumen",
                    link: "/master/alat",
                },
            ]
        };
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        openModal() {
            this.dataSelected = {
                metode: "",
                no_dokumen: "",
                ruang: [
                    {
                        ruang: null,
                    },
                ],
            };
            this.modal = true;
            this.$nextTick(() => {
                $(".modalDokumen").modal("show");
            });
        },
        async edit(id) {
            try {
                const { data } = await axios.get(
                    `/api/labs/dok/${id}`
                );
                this.dataSelected = data.data;
                // add id on dataSelected
                this.dataSelected.id = id;
                this.modal = true;
                this.$nextTick(() => {
                    $(".modalDokumen").modal("show");
                });
            } catch (error) {
                console.log(error);
            }
        },
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get(
                    "/api/labs/dok"
                );
                this.dokumen = data.data;
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        },
    },
    computed: {
        filteredDalamProses() {
            return this.dokumen.filter((item) => {
                return Object.keys(item).some((key) => {
                    return String(item[key])
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
        <div class="card" v-if="!$store.state.loading">
            <modal v-if="modal" @closeModal="modal = false" :dataSelected="dataSelected" @refresh="getData" />
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="openModal">
                            <i class="fas fa-plus"></i> Tambah Dokumen
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="search" v-model="search" class="form-control" placeholder="Cari Dokumen" />
                    </div>
                </div>
                <Table :dataTable="renderPaginate" @edit="edit" />
                <pagination :filteredDalamProses="filteredDalamProses"
                    @updateFilteredDalamProses="updateFilteredDalamProses" />
            </div>
        </div>
    </div>
</template>
