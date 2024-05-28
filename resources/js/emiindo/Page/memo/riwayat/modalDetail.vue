<script>
export default {
    props: ["item"],
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
            noMemo: "",
        };
    },
    methods: {
        closeModal() {
            $(".modalDetail").modal("hide");
            this.$nextTick(() => {
                this.$emit("close");
            });
        },
        simpan() {
            if (this.noMemo == "" || this.noMemo == null) {
                swal.fire('Peringatan', 'No. Memo tidak boleh kosong', 'warning')
            } else {
                swal.fire('Berhasil', 'Data berhasil disimpan', 'success')
                this.closeModal();
            }
        },
    },
};
</script>
<template>
    <div
        class="modal fade modalDetail"
        data-backdrop="static"
        data-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
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
                            <div class="row row-cols-3">
                                <div class="col">
                                    <label for="">Nomor Permintaan</label>
                                    <div class="card nomorPermintaan">
                                        <div class="card-body">
                                            <span id="so">{{ item.no }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="">Nomor Memo</label>
                                    <div class="card nomorMemo">
                                        <div class="card-body">
                                            <span
                                                id="instansi"
                                                class="text-capitalize"
                                                >{{ item.noMemo }}</span
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="">Divisi</label>
                                    <div class="card divisiPermintaan">
                                        <div class="card-body">
                                            <span
                                                id="instansi"
                                                class="text-capitalize"
                                                >{{ item.division }}</span
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
                                    <label for="">Tanggal Permintaan</label>
                                    <div class="card tglPermintaan">
                                        <div class="card-body">
                                            <span id="po">{{
                                                item.dateRequest
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <label for="">Tanggal Kebutuhan</label>
                                    <div class="card tglKebutuhan">
                                        <div class="card-body">
                                            <span id="akn">{{
                                                item.dateNeed
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div :class="item.ket ? 'col-6' : 'col-12'">
                                    <label class="text-capitalize"
                                        >Tujuan Permintaan</label
                                    >
                                    <div class="card">
                                        <div class="card-body">
                                            <span id="instansi">{{
                                                item.tujuan
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex bd-highlight mb-3">
                                <div class="p-2 bd-highlight">
  
                                </div>
                                <div class="ml-auto p-2 bd-highlight">
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Search..."
                                        v-model="search"
                                    />
                                </div>
                            </div>
                            <data-table
                                :headers="headers"
                                :items="produk"
                                :search="search"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.nomorPermintaan {
    background-color: #f64e60;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.nomorMemo {
    background-color: #6993FF;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.divisiPermintaan {
    background-color: #1BC5BD;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.tglPermintaan {
    background-color: #3699FF;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}

.tglKebutuhan {
    background-color: #212121;
    color: #fff;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 18px;
}
</style>
