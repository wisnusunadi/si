<script>
import axios from "axios";
export default {
    props: ["detail"],
    data() {
        return {
            nama_ruangan: "",
            judul: "Tambah Ruangan",
        };
    },
    methods: {
        closeModal() {
            $(".modalTambahEdit").modal("hide");
            this.$nextTick(() => {
                this.$emit("close");
            });
        },
        simpan() {
            if (this.nama_ruangan === "") {
                swal.fire(
                    "Peringatan",
                    "Nama ruangan tidak boleh kosong",
                    "warning"
                );
                return;
            }
            if (this.detail && Object.keys(this.detail).length > 0) {
                this.editRuangan();
            } else {
                this.tambahRuangan();
            }
        },
        async editRuangan() {
            try {
                let form = {
                    id: this.detail.id,
                    nama: this.nama_ruangan,
                };
                await axios.post(`/api/hr/meet/lokasi/update`, form);
                this.$emit("refresh");
                swal.fire("Berhasil", "Data berhasil disimpan", "success");
                this.closeModal();
            } catch (error) {
                console.log(error);
                swal.fire("Gagal", `${error.response.data.message}`, "error");
            }
        },
        async tambahRuangan() {
            try {
                let form = {
                    nama: this.nama_ruangan,
                };
                await axios.post(`/api/hr/meet/lokasi/store`, form);
                this.$emit("refresh");
                swal.fire("Berhasil", "Data berhasil disimpan", "success");
                this.closeModal();
            } catch (error) {
                console.log(error);
                swal.fire("Gagal", `${error.response.data.message}`, "error");
            }
        },
    },
    watch: {
        detail: {
            handler() {
                // object is not empty
                if (this.detail && Object.keys(this.detail).length > 0) {
                    this.nama_ruangan = this.detail.nama;
                    this.judul = "Edit Ruangan";
                } else {
                    this.nama_ruangan = "";
                    this.judul = "Tambah Ruangan";
                }
            },
            immediate: true,
        },
    },
};
</script>
<template>
    <div
        class="modal fade modalTambahEdit"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ judul }}</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nama Ruangan</label>
                        <input
                            type="text"
                            class="form-control"
                            id="exampleInputEmail1"
                            aria-describedby="emailHelp"
                            v-model="nama_ruangan"
                        />
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