<script>
import axios from "axios";
export default {
    props: ["items", "batal"],
    data() {
        return {
            loading: false,
            alasan: "",
        };
    },
    methods: {
        // Function to save the cancellation reason
        simpan() {
            if (this.alasan == "") {
                this.$swal(
                    "Peringatan",
                    "Alasan batal tidak boleh kosong",
                    "warning"
                );
                return;
            }
            this.$swal({
                title: "Apakah anda yakin?",
                text: "Data yang sudah dibatalkan tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Batalkan",
                cancelButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = {
                        item: this.items.filter((item) => item.jumlah > 0),
                        ket: this.alasan,
                        pesanan_id: this.batal.pesanan_id,
                    };
                    this.loading = true;
                    axios
                        .post("/api/penjualan/batal_po/kirim", form)
                        .then((res) => {
                            this.$swal(
                                "Berhasil",
                                "Data berhasil dibatalkan",
                                "success"
                            );
                            this.$emit("refresh");
                            this.$emit("closeAllModal");
                            this.closeModal();
                        })
                        .catch((err) => {
                            this.$swal(
                                "Gagal",
                                "Data gagal dibatalkan",
                                "error"
                            );
                        })
                        .finally((this.loading = false));
                }
            });
        },
        // Close modal
        closeModal() {
            $(".modalAlasanBatal").modal("hide");
            this.$nextTick(() => {
                this.$emit("close");
            });
        },
    },
};
</script>
<template>
    <div
        class="modal fade modalAlasanBatal"
        id="staticBackdrop"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div
            class="modal-dialog modal-lg modal-dialog-scrollable"
            role="document"
        >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alasan Batal</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Alasan Batal:</label>
                        <textarea
                            class="form-control"
                            cols="5"
                            v-model="alasan"
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
                    <button
                        type="button"
                        class="btn btn-primary"
                        :disabled="loading"
                        @click="simpan"
                    >
                        {{ loading ? "Menyimpan..." : "Simpan" }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
