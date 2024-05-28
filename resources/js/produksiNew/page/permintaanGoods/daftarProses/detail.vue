<script>
import edit from "./edit.vue";
export default {
    props: ["item"],
    components: { edit },
    data() {
        return {
            headers: [
                {
                    text: "No.",
                    value: "no",
                },
                {
                    text: "Nama Produk",
                    value: "nama",
                },
                {
                    text: "Jumlah",
                    value: "jumlah",
                },
            ],
            search: "",
            produk: [
                {
                    no: 1,
                    nama: "Produk 1",
                    jumlah: 1,
                },
                {
                    no: 2,
                    nama: "Produk 2",
                    jumlah: 2,
                },
            ],
            showModal: false,
        };
    },
    methods: {
        closeModal() {
            $(".modalDetail").modal("hide");
            this.$nextTick(() => {
                this.$emit("close");
            });
        },
        showModalEdit() {
            this.showModal = true;
            this.$nextTick(() => {
                $(".modalDetail").modal("hide");
                $(".modalEdit").modal("show");
            });
        },
        closeModalEdit() {
            this.showModal = false;
            this.$nextTick(() => {
                $(".modalDetail").modal("show");
            });
        },
        batalPinjam(id) {
            swal.fire({
                title: "Apakah anda yakin?",
                text: "Data yang sudah dibatalkan tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, batalkan!",
                cancelButtonText: "Keluar",
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire(
                        "Berhasil",
                        "Data berhasil dibatalkan",
                        "success"
                    );
                    // change status batal
                    this.closeModal();
                }
            });
        },
        statusEdit({ status }) {
            if (
                status === "permintaan_ditolak_atasan" ||
                status === "permintaan_gagal_diacc" ||
                status === "permintaan_ditolak_gudang"
            ) {
                return true;
            } else {
                return false;
            }
        },
    },
};
</script>
<template>
    <div>
        <edit v-if="showModal" @close="closeModalEdit" />
        <div
            class="modal fade modalDetail"
            id="modelId"
            tabindex="-1"
            role="dialog"
            aria-labelledby="modelTitleId"
            aria-hidden="true"
        >
            <div
                class="modal-dialog modal-xl modal-dialog-scrollable"
                role="document"
            >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Permintaan</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-header">
                                    <div class="row row-cols-2">
                                        <div class="col">
                                            <label for=""
                                                >Nomor Permintaan</label
                                            >
                                            <div class="card nomor-so">
                                                <div class="card-body">
                                                    <span id="so">{{
                                                        item.no_permintaan
                                                    }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for="">Jenis</label>
                                            <div class="card instansi">
                                                <div class="card-body">
                                                    <span
                                                        id="instansi"
                                                        class="text-capitalize"
                                                        >{{ item.jenis }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="row"
                                        :class="
                                            item.jenis == 'peminjaman'
                                                ? 'row-cols-3'
                                                : 'row-cols-2'
                                        "
                                    >
                                        <div class="col">
                                            <label for=""
                                                >Tanggal Permintaan</label
                                            >
                                            <div class="card nomor-po">
                                                <div class="card-body">
                                                    <span id="po">{{
                                                        dateFormat(
                                                            item.tgl_permintaan
                                                        )
                                                    }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label for=""
                                                >Tanggal Kebutuhan</label
                                            >
                                            <div class="card nomor-akn">
                                                <div class="card-body">
                                                    <span id="akn">{{
                                                        dateFormat(
                                                            item.tgl_kebutuhan
                                                        )
                                                    }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="col"
                                            v-if="item.jenis == 'peminjaman'"
                                        >
                                            <label for="">Durasi</label>
                                            <div class="card durasi">
                                                <div class="card-body">
                                                    <span id="instansi">{{
                                                        item.durasi
                                                    }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div
                                            :class="
                                                item.ket ? 'col-6' : 'col-12'
                                            "
                                        >
                                            <label class="text-capitalize"
                                                >Tujuan {{ item.jenis }}</label
                                            >
                                            <div class="card">
                                                <div class="card-body">
                                                    <span id="instansi">{{
                                                        item.tujuan_permintaan
                                                    }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col" v-if="item.ket">
                                            <label for="">Alasan Ditolak</label>
                                            <div class="card">
                                                <div class="card-body">
                                                    <span id="instansi">{{
                                                        item.ket
                                                    }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div
                                    class="d-flex flex-row-reverse bd-highlight"
                                >
                                    <div class="p-2 bd-highlight">
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="search"
                                            placeholder="Cari..."
                                        />
                                    </div>
                                </div>
                                <data-table
                                    :headers="headers"
                                    :items="produk"
                                    :search="search"
                                />
                            </div>
                            <div class="card-footer">
                                <div class="d-flex bd-highlight">
                                    <div class="p-2 flex-grow-1 bd-highlight">
                                        <button
                                            class="btn btn-warning"
                                            @click="showModalEdit"
                                            v-if="statusEdit(item)"
                                        >
                                            <i class="fas fa-pencil-alt"></i>
                                            Edit
                                        </button>
                                        <button
                                            class="btn btn-danger"
                                            @click="batalPinjam"
                                            v-if="item.status != 'batal'"
                                        >
                                            <i class="fas fa-times"></i> Batal
                                        </button>
                                    </div>
                                    <div class="p-2 bd-highlight">
                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            @click="closeModal"
                                        >
                                            Keluar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.durasi {
    background-color: #1bc5bd;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}
</style>
