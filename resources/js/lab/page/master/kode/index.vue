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
            ],
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Kode Kepemilikan',
                    value: 'kode'
                },
                {
                    text: 'Nama Kepemilikan',
                    value: 'nama'
                },
                {
                    text: 'Aksi',
                    value: 'aksi',
                }
            ]
        };
    },
    methods: {
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
        edit(item) {
            const index = this.kepemilikan.findIndex((data) => data.id === item.id);
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
                this.kepemilikan = data.data.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item
                    };
                });
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch("setLoading", false);
            }
        }
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
                <data-table :items="kepemilikan" :search="search" :headers="headers">
                    <template #item.aksi="{ item }">
                        <button class="btn btn-outline-warning" @click="edit(item)">
                            <i class="fas fa-edit"></i>
                        </button>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>
