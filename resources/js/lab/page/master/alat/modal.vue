<script>
import axios from "axios";
import pagination from "../../../components/pagination.vue";
export default {
    props: ["kode"],
    components: {
        pagination,
    },
    data() {
        return {
            dataProduk: [],
        };
    },
    methods: {
        close() {
            $(".modalFormAlat").modal("hide");
            this.$emit("close");
        },
        simpan() {
            // validasi
            if (this.kode.kode == "" || this.kode.kode == null) {
                this.$swal("Kode tidak boleh kosong", "", "error");
                return;
            }

            if (this.kode.nama == "" || this.kode.nama == null) {
                this.$swal("Nama tidak boleh kosong", "", "error");
                return;
            }

            if (this.kode.produk.length == 0) {
                this.$swal("Produk tidak boleh kosong", "", "error");
                return;
            }

            // check every produk not null
            for (let i = 0; i < this.kode.produk.length; i++) {
                if (
                    this.kode.produk[i].produk == null ||
                    this.kode.produk[i].produk == ""
                ) {
                    this.$swal("Produk tidak boleh kosong", "", "error");
                    return;
                }
            }

            // check every produk not duplicate
            for (let i = 0; i < this.kode.produk.length; i++) {
                for (let j = 0; j < this.kode.produk.length; j++) {
                    if (i != j) {
                        if (
                            this.kode.produk[i].produk ==
                            this.kode.produk[j].produk
                        ) {
                            this.$swal(
                                "Produk tidak boleh duplikat",
                                "",
                                "error"
                            );
                            return;
                        }
                    }
                }
            }

            this.$swal({
                title: "Apakah anda yakin?",
                text: "Data yang sudah disimpan tidak dapat diubah kembali!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Terima!",
            }).then((result) => {
                if (result.value) {
                    const error = () => {
                        this.$swal("Data gagal disimpan", "", "error");
                    }

                    try {
                        const success = () => {
                            this.$swal("Data berhasil disimpan", "", "success");
                            this.close();
                            this.$emit("refresh");
                        }

                        if (this.kode?.id) {
                            const { data } = axios.put(
                                "/api/labs/kode/" + this.kode.id,
                                this.kode
                            )
                            .then(success).catch(error)
                        } else {
                            const { data } = axios.post(
                                "/api/labs/kode",
                                this.kode
                            ).then(success).catch(error)
                        }
                    } catch (error) {
                        error();
                    }
                }
            });
        },
        async showProduk() {
            const { produk } = await axios
                .get("/api/produk")
                .then((res) => res.data);
            this.dataProduk = produk.map((data) => {
                return {
                    id: data.id,
                    label: data.nama,
                };
            });
        },
        tambahProduk() {
            this.kode.produk.push({
                produk: null,
            });
        },
    },
    created() {
        this.showProduk();
    },
};
</script>
<template>
    <div
        class="modal fade modalFormAlat"
        id="exampleModal" data-backdrop="static"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modelTitleId"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Alat</h5>
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="" class="col-2">Kode</label>
                        <input
                            type="text"
                            v-model="kode.kode"
                            class="form-control col-10"
                        />
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2">Nama Alat</label>
                        <input
                            type="text"
                            v-model="kode.nama"
                            class="form-control col-10"
                        />
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight">
                                    <h4>Produk</h4>
                                </div>
                                <div class="p-2 bd-highlight">
                                    <button
                                        class="btn btn-primary"
                                        @click="tambahProduk"
                                    >
                                        <i class="fas fa-plus"></i>
                                        Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(prd, idx) in kode.produk"
                                            :key="idx"
                                        >
                                            <td>{{ idx + 1 }}</td>
                                            <td>
                                                <v-select
                                                    v-model="prd.produk"
                                                    :options="dataProduk"
                                                />
                                            </td>
                                            <td>
                                                <button
                                                    class="btn btn-outline-danger"
                                                    @click="
                                                        kode.produk.splice(
                                                            idx,
                                                            1
                                                        )
                                                    "
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="close"
                    >
                        Keluar
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="simpan"
                    >
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.table-responsive {
    max-height: 300px;
}
</style>
