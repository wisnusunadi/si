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
            ruang: [],
            search: "",
            modal: false,
            dataSelected: {
                nama: "",
                metode: [
                    {
                        metode: null,
                    },
                ],
            },
            title: "Ruangan Pengujian Laboratorium",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Ruangan Pengujian Laboratorium",
                    link: "/master/alat",
                },
            ],
            headers: [
                {
                    text: 'No',
                    value: 'no'
                },
                {
                    text: 'Ruangan',
                    value: 'nama'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ]
        };
    },
    methods: {
        openModal() {
            this.dataSelected = {
                nama: "",
                metode: [
                    {
                        metode: null,
                    },
                ],
            };
            this.modal = true;
            this.$nextTick(() => {
                $(".modalDokumen").modal("show");
            });
        },
        async edit(id) {
            const { data } = await axios.get('/api/labs/ruang/' + id);
            this.dataSelected = data.data;
            this.modal = true;
            this.$nextTick(() => {
                $(".modalDokumen").modal("show");
            });
        },
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get(
                    "/api/labs/ruang/"
                );
                this.ruang = data.data.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
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
        <div class="card" v-if="!$store.state.loading">
            <modal v-if="modal" @closeModal="modal = false" :dataSelected="dataSelected" @refresh="getData" />
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="openModal">
                            <i class="fas fa-plus"></i> Tambah Ruang
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input type="search" v-model="search" class="form-control" placeholder="Cari..." />
                    </div>
                </div>
                <data-table :headers="headers" :items="ruang" :search="search">
                    <template #item.aksi="{ item }">
                        <div>
                            <button class="btn btn-outline-warning" @click="edit(item.id)">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </template>
                </data-table>
            </div>
        </div>
    </div>
</template>
