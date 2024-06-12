<script>
import Header from "../../components/header.vue";
import tambahEdit from "./tambahEdit.vue";
import axios from "axios";
export default {
    components: {
        Header,
        tambahEdit,
    },
    data() {
        return {
            title: "Ruangan Meeting",
            breadcumbs: [
                {
                    name: "Home",
                    link: "/",
                },
                {
                    name: "Ruangan Meeting",
                    link: "/ruangan-meeting",
                },
            ],
            headers: [
                {
                    text: "No.",
                    value: "no",
                },
                {
                    text: "Nama Ruangan",
                    value: "nama",
                },
                {
                    text: "Aksi",
                    value: "aksi",
                },
            ],
            ruangan: [],
            search: "",
            showTambahEdit: false,
            detailSelected: {},
        };
    },
    methods: {
        hapus(item) {
            swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios
                        .post("/api/hr/meet/lokasi/delete", {
                            id: item.id,
                        })
                        .then(() => {
                            this.getData();
                            swal.fire("Berhasil", "Data berhasil dihapus", "success");
                        })
                        .catch((error) => {
                            console.log(error);
                        });
                }
            });
        },
        editRuangan(item) {
            this.detailSelected = item;
            this.showTambahEdit = true;
            this.$nextTick(() => {
                $(".modalTambahEdit").modal("show");
            });
        },
        tambahRuangan() {
            this.detailSelected = {};
            this.showTambahEdit = true;
            this.$nextTick(() => {
                $(".modalTambahEdit").modal("show");
            });
        },
        async getData() {
            try {
                this.$store.dispatch("setLoading", true);
                const { data } = await axios.get("/api/hr/meet/lokasi/show");
                this.ruangan = data.map((item, index) => {
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
        },
    },
    mounted() {
        this.getData();
    },
};
</script>
<template>
    <div>
        <tambahEdit
            v-if="showTambahEdit"
            @close="showTambahEdit = false"
            :detail="detailSelected"
            @refresh="getData"
        />
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="tambahRuangan">
                            <i class="fa fa-plus"></i> Tambah Ruangan
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Cari Ruangan"
                            v-model="search"
                        />
                    </div>
                </div>
                <data-table
                    v-if="!$store.state.loading"
                    :headers="headers"
                    :items="ruangan"
                    :search="search"
                >
                    <template #item.aksi="{ item }">
                        <button
                            class="btn btn-sm btn-outline-warning"
                            @click="editRuangan(item)"
                        >
                            <i class="fa fa-pen"></i>
                            Edit
                        </button>
                        <button
                            class="btn btn-sm btn-outline-danger"
                            @click="hapus(item)"
                        >
                            <i class="fa fa-trash"></i>
                            Hapus
                        </button>
                    </template>
                </data-table>
                <div v-else class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>