<script>
import axios from 'axios';
export default {
    props: ["dataSelected"],
    data() {
        return {
            ruang: [],
        }
    },
    methods: {
        closeModal() {
            $(".modalDokumen").modal("hide");
            this.$emit("closeModal");
        },
        tambahRuang() {
            this.dataSelected.ruang.push({
                ruang: null,
            });
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
            try {
                if (this.dataSelected.id) {
                    const { data } = await axios.put(
                        "/api/labs/dok/" + this.dataSelected.id,
                        this.dataSelected
                    ).then(() => success()).catch(() => error());
                } else {
                    const { data } = await axios.post(
                        "/api/labs/dok",
                        this.dataSelected
                    ).then(() => success()).catch(() => error());
                }
            } catch (error) {
                error();
            }
        },
        async getRuang() {
            try {
                const { data } = await axios.get("/api/labs/ruang");
                this.ruang = data.data.map((item) => {
                    return {
                        label: item.nama,
                        id: item.id,
                    };
                });
            } catch (error) {
                console.log(error);
            }
        },
    },
    created() {
        this.getRuang();
    },
};
</script>
<template>
    <div class="modal fade modalDokumen" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Dokumen</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="" class="col-2">Metode Kerja</label>
                        <input type="text" class="form-control col-8" v-model="dataSelected.metode" />
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-2">Dokumen</label>
                        <input type="text" class="form-control col-8" v-model="dataSelected.no_dokumen" />
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex bd-highlight">
                                <div class="p-2 flex-grow-1 bd-highlight">
                                    <h4>Ruang</h4>
                                </div>
                                <div class="p-2 bd-highlight">
                                    <button class="btn btn-primary" @click="tambahRuang">
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
                                            <th>Nama Ruang</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(prd, idx) in dataSelected.ruang" :key="idx">
                                            <td>{{ idx + 1 }}</td>
                                            <td>
                                                <v-select v-model="prd.ruang" :options="ruang" />
                                            </td>
                                            <td>
                                                <button class="btn btn-outline-danger" @click="
                                                    dataSelected.ruang.splice(
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
