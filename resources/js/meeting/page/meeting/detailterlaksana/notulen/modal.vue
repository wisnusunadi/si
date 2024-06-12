<script>
import axios from "axios";
import VueSelect from "vue-select";
export default {
    components: {
        VueSelect,
    },
    data() {
        return {
            karyawan: [],
            formnotulen: {
                karyawan_id: "",
                uraian: "",
                kesesuaian: "",
                catatan: "",
            },
        };
    },
    methods: {
        save() {
            const form = {
                meeting_id: this.$route.params.id,
                ...this.formnotulen,
            };
            axios
                .post("/api/hr/meet/notulen", form, {
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem("lokal_token"),
                }
            })
                .then(() => {
                    this.$emit("refresh");
                    this.closeModal();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        async getDataKaryawan() {
            try {
                const { data } = await axios.get(`/api/hr/meet/jadwal/show_peserta/${this.$route.params.id}`);
                this.karyawan = data;
            } catch (error) {
                console.log(error);
            }
        },
        closeModal() {
            $(".modalNotulen").modal("hide");
            this.$nextTick(() => {
                this.$emit("closeModal");
            });
        },
    },
    mounted() {
        this.getDataKaryawan();
    },
};
</script>
<template>
    <div
        class="modal fade modalNotulen"
        id="modelId"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modelTitleId"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Hasil Notulensi</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Penanggung Jawab</label>
                        <vue-select
                            :reduce="(item) => item.id"
                            :options="karyawan"
                            label="nama"
                            v-model="formnotulen.karyawan_id"
                        />
                    </div>

                    <div class="form-group">
                        <label for="">Uraian</label>
                        <textarea
                            class="form-control"
                            v-model="formnotulen.uraian"
                        ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="closeModal"
                    >
                        Keluar
                    </button>
                    <button type="button" class="btn btn-primary" @click="save">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>