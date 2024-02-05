<script>
import axios from 'axios';
export default {
    props: ["dataSelected"],
    data() {
        return {
            metode: [],
        }
    },
    methods: {
        closeModal() {
            $(".modalDokumen").modal("hide");
            this.$emit("closeModal");
        },
        tambahMetode() {
            this.dataSelected.metode.push({
                ruang: null,
            });
        },
        async getMetode() {
            try {
                const { data } = await axios.get("/api/labs/dok");
                this.metode = data.data.map((item) => {
                    return {
                        label: item.metode,
                        id: item.id,
                    };
                });
            } catch (error) {
                console.log(error);
            }
        },
        async simpan() {
            const success = () => {
                this.$swal("Success", "Data berhasil disimpan", "success");
                this.closeModal();
                this.$emit("refresh");
            }

            const error = () => {
                this.$swal("Error", "Data gagal disimpan", "error");
            }
            if (this.dataSelected.id) {
                const { data } = await axios.put(
                    "/api/labs/ruang/" + this.dataSelected.id,
                    this.dataSelected
                ).then(() => success()).catch(() => error());
            } else {
                const { data } = await axios.post(
                    "/api/labs/ruang",
                    this.dataSelected
                ).then(() => success()).catch(() => error());
            }
        }
    },
    mounted() {
        this.getMetode();
    },
};
</script>
<template>
    <div class="modal fade modalDokumen" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Ruangan</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="" class="col-2">Ruangan</label>
                        <input type="text" class="form-control col-8" v-model="dataSelected.nama" />
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight">
                                    <h4>Metode</h4>
                                </div>
                                <div class="p-2 bd-highlight">
                                    <button class="btn btn-primary" @click="tambahMetode">
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
                                            <th>Nama Metode</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(
                                                prd, idx
                                            ) in dataSelected.metode" :key="idx">
                                            <td>{{ idx + 1 }}</td>
                                            <td>
                                                <v-select v-model="prd.metode" :options="metode" />
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-danger" @click="
                                                    dataSelected.metode.splice(
                                                        idx,
                                                        1
                                                    )
                                                    ">
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
                    <button type="button" class="btn btn-secondary" @click="closeModal">
                        Keluar
                    </button>
                    <button type="button" class="btn btn-primary" @click="simpan">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
