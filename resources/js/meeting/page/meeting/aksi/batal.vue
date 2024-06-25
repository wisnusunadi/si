<script>
export default {
    props: ["meeting"],
    data() {
        return {
            alasan: "",
        };
    },
    methods: {
        closeModal() {
            $(".modalBatal").modal("hide");
            this.$nextTick(() => {
                this.$emit("closeModal");
            });
        },
        simpan() {
            swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Data yang sudah batalkan tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Batalkan!",
                cancelButtonText: "Kembali",
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$_post(
                            "/api/hr/meet/jadwal/update/batal",
                            {
                                id: this.meeting.id,
                                ket_batal: this.alasan,
                            },
                        )
                        .then((response) => {
                            swal.fire(
                                "Berhasil!",
                                "Data berhasil dibatalkan",
                                "success"
                            );
                            this.closeModal();
                            this.$emit("refresh");
                        })
                        .catch((error) => {
                            swal.fire(
                                "Gagal!",
                                "Data gagal dibatalkan",
                                "error"
                            );
                        });
                }
            });
        },
    },
};
</script>
<template>
    <div
        class="modal fade modalBatal"
        id="modelId"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Batal Meeting</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea
                        class="form-control"
                        v-model="alasan"
                        placeholder="Alasan Pembatalan"
                    ></textarea>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="closeModal"
                    >
                        Batal
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