<script>
import pagination from "../../../components/pagination.vue";
import axios from "axios";
export default {
    props: ["kepemilikan"],
    components: {
        pagination,
    },
    methods: {
        close() {
            $(".modalFormAlat").modal("hide");
            this.$emit("close");
        },
        async simpan() {
            try {
                if (this.kepemilikan.id) {
                    await axios.put(
                        `/api/labs/kode_milik/${this.kepemilikan.id}`,
                        this.kepemilikan
                    );
                } else {
                    await axios.post(`/api/labs/kode_milik`, this.kepemilikan);
                }
                this.$swal("Berhasil!", "Data berhasil disimpan.", "success");
                this.close();
                this.$emit("refresh");
            } catch (error) {
                this.$swal("Gagal!", "Data gagal disimpan.", "error");
                return;
            }
        },
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
                    <h5 class="modal-title">Form Kepemilikan</h5>
                    <button type="button" class="close" @click="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="" class="col-2">Kode</label>
                        <input
                            type="text"
                            v-model="kepemilikan.kode"
                            class="form-control col-10"
                        />
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-2">Kepemilikan</label>
                        <input
                            type="text"
                            v-model="kepemilikan.nama"
                            class="form-control col-10"
                        />
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
