<script>
import axios from "axios";
import seriviatext from "./noseri.vue";
import riwayat from "./riwayat.vue";
import editSJ from "./editSJ.vue";
export default {
    components: {
        seriviatext,
        riwayat,
        editSJ,
    },
    props: ["detail"],
    data() {
        return {
            pesanan: {},
            searchProduk: "",
            searchPartJasa: "",
            form: {
                nama_pic: "",
                telp_pic: "",
                jenis_sj: "SPA-",
                no_invoice: "",
                tgl_kirim: "",
                pengiriman_surat_jalan: "ekspedisi",
                ekspedisi_terusan: "",
                pilihan_pengiriman: "penjualan",
                perusahaan_pengiriman: "",
                alamat_pengiriman: "",
                kemasan: "nonpeti",
                dimensi: "",
                keterangan_pengiriman: "",
            },
            ekspedisi: [],
            headersProduk: [
                {
                    text: "id",
                    value: "id",
                    sortable: false,
                },
                {
                    text: "Nama Produk",
                    value: "nama",
                },
                {
                    text: "Jumlah",
                    value: "jumlah",
                },
                {
                    text: "Jumlah No Seri Diinput",
                    value: "noseri",
                },
                {
                    text: "Aksi",
                    value: "aksi",
                },
            ],
            headersPartJasa: [
                {
                    text: "id",
                    value: "id",
                    sortable: false,
                },
                {
                    text: "Nama",
                    value: "nama",
                },
                {
                    text: "Jumlah",
                    value: "jumlah",
                },
            ],
            produk: [],
            partJasa: [],
            checkAllProduct: false,
            checkedProductSelected: [],
            checkAllPart: false,
            checkedPartSelected: [],
            showModalSeri: false,
            showModalEditSJ: false,
            detailSelected: {},
            riwayatCetak: [],
            loading: false,
        };
    },
    methods: {
        closeModal() {
            $(".modalCetak").modal("hide");
            this.$nextTick(() => {
                this.$emit("close");
            });
        },
        async getPesanan() {
            try {
                this.loading = true;
                const { data: ekspedisi } = await axios.get(
                    "/api/logistik/ekspedisi/all"
                );
                this.ekspedisi = ekspedisi.map((item) => {
                    return {
                        id: item.id,
                        label: item.nama,
                    };
                });
                const { data: pesanan } = await axios.get(
                    `/api/logistik/so/data/detail/item/${this.detail.id}`
                );
                const { data: riwayatcetakData } = await axios
                    .get(`/api/logistik/so/sj_draft/${this.detail.id}`)
                    .then((res) => res.data);
                this.riwayatCetak = riwayatcetakData.map((item, index) => {
                    return {
                        no: index + 1,
                        ...item,
                    };
                });
                this.pesanan = pesanan;
            } catch (error) {
                console.log(error);
            } finally {
                this.loading = false;
            }
        },
        checkedAllProduct() {
            this.checkAllProduct = !this.checkAllProduct;
            if (this.checkAllProduct) {
                this.checkedProductSelected = this.produk;
            } else {
                this.checkedProductSelected = [];
            }
        },
        checkedProduct(item) {
            const index = this.checkedProductSelected.findIndex(
                (data) => data.id === item.id
            );
            if (index === -1) {
                this.checkedProductSelected.push(item);
            } else {
                this.checkedProductSelected.splice(index, 1);
            }
        },
        checkedAllPart() {
            this.checkAllPart = !this.checkAllPart;
            if (this.checkAllPart) {
                this.checkedPartSelected = this.partJasa;
            } else {
                this.checkedPartSelected = [];
            }
        },
        checkedPart(item) {
            const index = this.checkedPartSelected.findIndex(
                (data) => data.id === item.id
            );
            if (index === -1) {
                this.checkedPartSelected.push(item);
            } else {
                this.checkedPartSelected.splice(index, 1);
            }
        },
        showNoSeri(item) {
            this.detailSelected = item;
            this.showModalSeri = true;
            this.$nextTick(() => {
                $(".modalCetak").modal("hide");
                $(".modalChecked").modal("show");
            });
        },
        closeNoSeri() {
            this.showModalSeri = false;
            this.$nextTick(() => {
                $(".modalCetak").modal("show");
            });
        },
        submit(noseri) {
            let noseriarray = noseri.split(/[\n, \t]/);

            // remove empty string
            noseriarray = noseriarray.filter((data) => data !== "");

            // remove duplicate
            noseriarray = [...new Set(noseriarray)];

            if (noseriarray.length > this.detailSelected.jumlah) {
                this.$swal(
                    "Error",
                    "Jumlah No Seri yang diinputkan melebihi jumlah produk",
                    "error"
                );
                return;
            } else {
                this.detailSelected.noseri_selected = noseriarray;
                this.detailSelected.jumlah_noseri = noseriarray.length;

                this.searchProduk = "&";
                this.$nextTick(() => {
                    this.searchProduk = "";
                });
            }
        },
        cetak() {
            // cek form apakah ada yang kosong
            let ObjectCannotEmpty = ["no_invoice", "keterangan_pengiriman"];

            let cekFormisNotEmpty = ObjectCannotEmpty.every((item) => {
                return this.form[item];
            });
            if (!cekFormisNotEmpty) {
                this.$swal("Error", "Form tidak boleh kosong", "error");
                return;
            }

            // let produk = []
            // // jika noseri sudah diinputkan maka push ke produk
            // this.produk.forEach(item => {
            //     if (item.noseri) {
            //         produk.push(item)
            //     }
            // })

            // // validasi jika noseri belum diinputkan
            // if (produk.length === 0 && this.produk.length > 0) {
            //     this.$swal('Error', 'No Seri belum diinputkan', 'error')
            //     return
            // }

            // cek produk yang dipilih
            if (this.produk.length > 0) {
                if (this.checkedProductSelected.length === 0) {
                    this.$swal("Error", "Produk belum dipilih", "error");
                    return;
                }
            }

            // cek part/jasa yang dipilih
            if (this.partJasa.length > 0) {
                if (this.checkedPartSelected.length === 0) {
                    this.$swal("Error", "Part / Jasa belum dipilih", "error");
                    return;
                }
            }

            const dataform = {
                pesanan_id: this.pesanan.header.pesanan_id,
                so: this.pesanan.header.so,
                no_po: this.pesanan.header.no_po,
                tgl_po: this.pesanan.header.tgl_po,
                nama_customer: this.pesanan.header.customer.nama,
                alamat_customer: this.pesanan.header.customer.alamat,
                provinsi_id: this.pesanan.header.provinsi.id,
                ...this.form,
            };

            let formData = {
                dataform,
            };

            // jika produk dipilih maka push ke formData
            if (this.checkedProductSelected.length > 0) {
                formData.produk = this.checkedProductSelected;
            }

            // jika part/jasa dipilih maka push ke formData
            if (this.checkedPartSelected.length > 0) {
                formData.part = this.checkedPartSelected;
            }

            axios
                .post("/api/logistik/so/create_draft", formData)
                .then((res) => {
                    this.$swal(
                        "Success",
                        "Draft Surat Jalan berhasil dibuat",
                        "success"
                    );
                    this.$nextTick(() => {
                        window.open(
                            `/logistik/pengiriman/prints/${res.data.id}`,
                            "_blank"
                        );
                    }).catch((err) => {
                        this.$swal(
                            "Error",
                            "Draft Surat Jalan gagal dibuat",
                            "error"
                        );
                    });
                });
        },
        async editCetak(item) {
            try {
                const { data } = await axios.get(
                    `/api/logistik/so/sj_draft/edit/${item.id}`
                );
                this.detailSelected = data;
                this.showModalEditSJ = true;
                this.$nextTick(() => {
                    $(".modalCetak").modal("hide");
                    $(".modalEditSJ").modal("show");
                });
            } catch (error) {
                console.log(error);
            }
        },
        closeEditSJ() {
            this.showModalEditSJ = false;
            this.$nextTick(() => {
                $(".modalCetak").modal("show");
            });
        },
    },
    created() {
        this.getPesanan();
    },
    watch: {
        pesanan: {
            handler() {
                this.form.perusahaan_pengiriman =
                    this.pesanan?.header?.perusahaan_pengiriman;
                this.form.alamat_pengiriman =
                    this.pesanan?.header?.alamat_pengiriman;
                if (this.pesanan?.header?.ekspedisi) {
                    this.$set(this.form, "pengiriman_surat_jalan", "ekspedisi");
                    this.$set(this.form, "ekspedisi", {
                        id: this.pesanan?.header?.ekspedisi.id,
                        label: this.pesanan?.header?.ekspedisi.nama,
                    });
                } else {
                    this.$set(
                        this.form,
                        "pengiriman_surat_jalan",
                        "nonekspedisi"
                    );
                    this.$delete(this.form, "ekspedisi");
                }
                this.produk = this.pesanan.item.produk.map((item) => {
                    return {
                        ...item,
                        jumlah_noseri: item.noseri?.length ?? 0,
                        noseri_selected: [],
                    };
                });
                this.partJasa = this.pesanan.item.part;
            },
            deep: true,
        },
        checkedProductSelected: {
            handler() {
                if (this.checkedProductSelected.length === this.produk.length) {
                    this.checkAllProduct = true;
                } else {
                    this.checkAllProduct = false;
                }
            },
            deep: true,
        },
        checkedPartSelected: {
            handler() {
                if (this.checkedPartSelected.length === this.partJasa.length) {
                    this.checkAllPart = true;
                } else {
                    this.checkAllPart = false;
                }
            },
            deep: true,
        },
        "form.pengiriman_surat_jalan": {
            handler(val) {
                if (val == "ekspedisi") {
                    this.$delete(this.form, "nama_pengirim");
                    if (this.pesanan?.header?.ekspedisi) {
                        this.$set(this.form, "ekspedisi", {
                            id: this.pesanan?.header?.ekspedisi?.id,
                            label: this.pesanan?.header?.ekspedisi?.nama,
                        });
                    } else {
                        this.$set(this.form, "ekspedisi", "");
                    }
                } else {
                    this.$delete(this.form, "ekspedisi");
                    this.$set(this.form, "nama_pengirim", "");
                }
            },
            deep: true,
        },
        "form.keterangan_pengiriman": {
            // change to uppercase
            handler(val) {
                this.form.keterangan_pengiriman = val.toUpperCase();
            },
        },
    },
};
</script>
<template>
    <div>
        <seriviatext
            v-if="showModalSeri"
            :noseriInput="detailSelected?.noseri_selected"
            @close="closeNoSeri"
            @submit="submit"
        />
        <editSJ
            v-if="showModalEditSJ"
            :form="detailSelected"
            @close="closeEditSJ"
            @refresh="getPesanan"
        />
        <div
            class="modal fade modalCetak"
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
                        <h5 class="modal-title">Cetak Surat Jalan</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" v-if="!loading">
                        <ul
                            class="nav nav-pills mb-3"
                            id="pills-tab"
                            role="tablist"
                        >
                            <li class="nav-item" role="presentation">
                                <a
                                    class="nav-link active"
                                    id="pills-cetak-tab"
                                    data-toggle="pill"
                                    data-target="#pills-cetak"
                                    tcetakype="button"
                                    role="tab"
                                    aria-controls="pills-cetak"
                                    aria-selected="true"
                                    >Cetak Surat Jalan Baru</a
                                >
                            </li>
                            <li class="nav-item" role="presentation">
                                <a
                                    class="nav-link"
                                    id="pills-riwayat-tab"
                                    data-toggle="pill"
                                    data-target="#pills-riwayat"
                                    type="button"
                                    role="tab"
                                    aria-controls="pills-riwayat"
                                    aria-selected="false"
                                    >Riwayat Draft Surat Jalan</a
                                >
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div
                                class="tab-pane fade show active"
                                id="pills-cetak"
                                role="tabpanel"
                                aria-labelledby="pills-cetak-tab"
                            >
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Data PIC</h5>
                                        <div class="form-group row">
                                            <label
                                                class="col-form-label col-lg-5 col-md-12 text-right"
                                                for="no_invoice"
                                                >Nama PIC</label
                                            >
                                            <div class="col-lg-6 col-md-12">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="form.nama_pic"
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-form-label col-lg-5 col-md-12 text-right"
                                                for="no_invoice"
                                                >Nomor Telepon PIC</label
                                            >
                                            <div class="col-lg-6 col-md-12">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    v-model="form.telp_pic"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h5>Data Pengiriman</h5>
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label
                                                    class="col-form-label col-lg-5 col-md-12 text-right"
                                                    for="no_invoice"
                                                    >No Surat Jalan
                                                </label>
                                                <div class="col-lg-6 col-md-12">
                                                    <div
                                                        class="input-group mb-3 sj_baru"
                                                        id="sj_baru"
                                                        name="sj_baru"
                                                    >
                                                        <div
                                                            class="input-group-prepend"
                                                        >
                                                            <select
                                                                class="form-control jenis_sj"
                                                                v-model="
                                                                    form.jenis_sj
                                                                "
                                                                id="jenis_sj"
                                                            >
                                                                <option
                                                                    value="SPA-"
                                                                >
                                                                    SPA-
                                                                </option>
                                                                <option
                                                                    value="B."
                                                                >
                                                                    B.
                                                                </option>
                                                                <option
                                                                    value="NBT"
                                                                >
                                                                    NBT
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <input
                                                            type="text"
                                                            class="form-control col-form-label"
                                                            v-model="
                                                                form.no_invoice
                                                            "
                                                            id="no_invoice"
                                                        />
                                                        <div
                                                            class="invalid-feedback"
                                                            id="msgnoinvoice"
                                                        ></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    class="col-form-label col-lg-5 col-md-12 text-right"
                                                    for="tgl_kirim"
                                                    >Tanggal Pengiriman</label
                                                >
                                                <div class="col-lg-4 col-md-6">
                                                    <input
                                                        type="date"
                                                        class="form-control col-form-label"
                                                        v-model="form.tgl_kirim"
                                                        id="tgl_kirim"
                                                    />
                                                    <div
                                                        class="invalid-feedback"
                                                        id="msgtgl_kirim"
                                                    ></div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    for=""
                                                    class="col-form-label col-lg-5 col-md-12 text-right"
                                                    >Pengiriman</label
                                                >
                                                <div
                                                    class="col-lg-5 col-md-12 col-form-label"
                                                >
                                                    <div
                                                        class="form-check form-check-inline"
                                                    >
                                                        <input
                                                            class="form-check-input"
                                                            type="radio"
                                                            v-model="
                                                                form.pengiriman_surat_jalan
                                                            "
                                                            id="pengiriman1"
                                                            value="ekspedisi"
                                                        />
                                                        <label
                                                            class="form-check-label"
                                                            for="pengiriman1"
                                                            >Ekspedisi</label
                                                        >
                                                    </div>
                                                    <div
                                                        class="form-check form-check-inline"
                                                    >
                                                        <input
                                                            class="form-check-input"
                                                            type="radio"
                                                            v-model="
                                                                form.pengiriman_surat_jalan
                                                            "
                                                            id="pengiriman2"
                                                            value="nonekspedisi"
                                                        />
                                                        <label
                                                            class="form-check-label"
                                                            for="pengiriman2"
                                                            >Non
                                                            Ekspedisi</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="form-group row"
                                                v-if="
                                                    form.pengiriman_surat_jalan ==
                                                    'ekspedisi'
                                                "
                                                id="ekspedisi"
                                            >
                                                <label
                                                    class="col-form-label col-lg-5 col-md-12 text-right"
                                                    for="ekspedisi_id"
                                                    >Jasa Pengiriman</label
                                                >
                                                <div class="col-lg-7 col-md-12">
                                                    <v-select
                                                        placeholder="Pilih Ekspedisi"
                                                        :options="ekspedisi"
                                                        v-model="form.ekspedisi"
                                                    ></v-select>
                                                </div>
                                            </div>
                                            <div
                                                class="form-group row"
                                                id="nonekspedisi"
                                                v-else
                                            >
                                                <label
                                                    class="col-form-label col-lg-5 col-md-12 text-right"
                                                    for="nama_pengirim"
                                                    >Nama Pengirim</label
                                                >
                                                <div class="col-lg-7 col-md-12">
                                                    <textarea
                                                        type="text"
                                                        class="form-control col-form-label"
                                                        v-model="
                                                            form.nama_pengirim
                                                        "
                                                        id="nama_pengirim"
                                                    ></textarea>
                                                    <div
                                                        class="invalid-feedback"
                                                        id="msgnama_pengirim"
                                                    ></div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    for="dimensi"
                                                    class="col-form-label col-lg-5 col-md-12 text-right"
                                                    >Ekspedisi Terusan</label
                                                >
                                                <div class="col-lg-7 col-md-12">
                                                    <textarea
                                                        type="text"
                                                        class="form-control col-form-label"
                                                        v-model="
                                                            form.ekspedisi_terusan
                                                        "
                                                        id="dimensi"
                                                    ></textarea>
                                                    <div
                                                        class="invalid-feedback"
                                                        id="msgnama_pengirim"
                                                    ></div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    for=""
                                                    class="col-lg-5 col-md-12 col-form-label text-right"
                                                    >Alamat Pengiriman</label
                                                >
                                                <div
                                                    class="col-lg-6 col-md-12 col-form-label"
                                                >
                                                    <div
                                                        class="form-check form-check-inline"
                                                    >
                                                        <input
                                                            type="radio"
                                                            class="form-check-input"
                                                            v-model="
                                                                form.pilihan_pengiriman
                                                            "
                                                            id="pilihan_pengiriman0"
                                                            value="penjualan"
                                                        />
                                                        <label
                                                            for="pengiriman0"
                                                            class="form-check-label"
                                                            >Sama dengan
                                                            Penjualan</label
                                                        >
                                                    </div>
                                                    <div
                                                        class="form-check form-check-inline"
                                                    >
                                                        <input
                                                            type="radio"
                                                            class="form-check-input"
                                                            v-model="
                                                                form.pilihan_pengiriman
                                                            "
                                                            id="pilihan_pengiriman1"
                                                            value="lainnya"
                                                        />
                                                        <label
                                                            for="pengiriman1"
                                                            class="form-check-label"
                                                            >Ubah Alamat</label
                                                        >
                                                    </div>
                                                    <input
                                                        type="text"
                                                        v-model="
                                                            form.perusahaan_pengiriman
                                                        "
                                                        id="perusahaan_pengiriman"
                                                        class="form-control col-form-label"
                                                        :readonly="
                                                            form.pilihan_pengiriman ==
                                                            'penjualan'
                                                        "
                                                    />
                                                    <input
                                                        type="text"
                                                        class="form-control col-form-label mt-2"
                                                        :readonly="
                                                            form.pilihan_pengiriman ==
                                                            'penjualan'
                                                        "
                                                        v-model="
                                                            form.alamat_pengiriman
                                                        "
                                                        id="alamat_pengiriman"
                                                    />
                                                    <div
                                                        class="invalid-feedback"
                                                        id="msg_alamat_pengiriman"
                                                    ></div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    for=""
                                                    class="col-lg-5 col-md-12 col-form-label text-right"
                                                    >Kemasan</label
                                                >
                                                <div
                                                    class="col-lg-6 col-md-12 col-form-label"
                                                >
                                                    <div
                                                        class="form-check form-check-inline"
                                                    >
                                                        <input
                                                            type="radio"
                                                            class="form-check-input"
                                                            v-model="
                                                                form.kemasan
                                                            "
                                                            id="kemasan0"
                                                            value="peti"
                                                        />
                                                        <label
                                                            for="kemasan0"
                                                            class="form-check-label"
                                                            >PETI</label
                                                        >
                                                    </div>
                                                    <div
                                                        class="form-check form-check-inline"
                                                    >
                                                        <input
                                                            type="radio"
                                                            class="form-check-input"
                                                            v-model="
                                                                form.kemasan
                                                            "
                                                            id="kemasan1"
                                                            value="nonpeti"
                                                        />
                                                        <label
                                                            for="kemasan1"
                                                            class="form-check-label"
                                                            >NON PETI</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    for="dimensi"
                                                    class="col-form-label col-lg-5 col-md-12 text-right"
                                                    >Dimensi</label
                                                >
                                                <div class="col-lg-7 col-md-12">
                                                    <textarea
                                                        type="text"
                                                        class="form-control col-form-label"
                                                        v-model="form.dimensi"
                                                        id="dimensi"
                                                    ></textarea>
                                                    <div
                                                        class="invalid-feedback"
                                                        id="msgnama_pengirim"
                                                    ></div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label
                                                    class="col-form-label col-lg-5 col-md-12 text-right"
                                                    for="nama_pengirim"
                                                    >Keterangan
                                                    Pengiriman</label
                                                >
                                                <div class="col-lg-7 col-md-12">
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        v-model="
                                                            form.keterangan_pengiriman
                                                        "
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <ul
                                            class="nav nav-pills mb-3"
                                            id="pills-tab"
                                            role="tablist"
                                        >
                                            <li
                                                class="nav-item"
                                                role="presentation"
                                            >
                                                <a
                                                    class="nav-link active"
                                                    id="pills-produk-tab"
                                                    data-toggle="pill"
                                                    data-target="#pills-produk"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="pills-produk"
                                                    aria-selected="true"
                                                    >Produk</a
                                                >
                                            </li>
                                            <li
                                                class="nav-item"
                                                role="presentation"
                                            >
                                                <a
                                                    class="nav-link"
                                                    id="pills-jasa-tab"
                                                    data-toggle="pill"
                                                    data-target="#pills-jasa"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="pills-jasa"
                                                    aria-selected="false"
                                                    >Part / Jasa</a
                                                >
                                            </li>
                                        </ul>
                                        <div
                                            class="tab-content"
                                            id="pills-tabContent"
                                        >
                                            <div
                                                class="tab-pane fade show active"
                                                id="pills-produk"
                                                role="tabpanel"
                                                aria-labelledby="pills-produk-tab"
                                            >
                                                <div
                                                    class="d-flex flex-row-reverse bd-highlight"
                                                >
                                                    <div
                                                        class="p-2 bd-highlight"
                                                    >
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            placeholder="Cari..."
                                                            v-model="
                                                                searchProduk
                                                            "
                                                        />
                                                    </div>
                                                </div>
                                                <data-table
                                                    :headers="headersProduk"
                                                    :items="produk"
                                                    :search="searchProduk"
                                                >
                                                    <template #header.id>
                                                        <input
                                                            type="checkbox"
                                                            :checked="
                                                                checkAllProduct
                                                            "
                                                            @click="
                                                                checkedAllProduct
                                                            "
                                                        />
                                                    </template>
                                                    <template
                                                        #item.id="{ item }"
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            @click="
                                                                checkedProduct(
                                                                    item
                                                                )
                                                            "
                                                            :checked="
                                                                checkedProductSelected &&
                                                                checkedProductSelected.find(
                                                                    (data) =>
                                                                        data.id ===
                                                                        item.id
                                                                )
                                                            "
                                                        />
                                                    </template>
                                                    <template
                                                        #item.noseri="{ item }"
                                                    >
                                                        {{
                                                            item.noseri_selected
                                                                ?.length ?? 0
                                                        }}
                                                    </template>
                                                    <template
                                                        #item.aksi="{ item }"
                                                    >
                                                        <button
                                                            class="btn btn-sm btn-outline-primary"
                                                            @click="
                                                                showNoSeri(item)
                                                            "
                                                        >
                                                            No Seri
                                                        </button>
                                                    </template>
                                                </data-table>
                                            </div>
                                            <div
                                                class="tab-pane fade"
                                                id="pills-jasa"
                                                role="tabpanel"
                                                aria-labelledby="pills-jasa-tab"
                                            >
                                                <div
                                                    class="d-flex flex-row-reverse bd-highlight"
                                                >
                                                    <div
                                                        class="p-2 bd-highlight"
                                                    >
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            placeholder="Cari..."
                                                            v-model="
                                                                searchPartJasa
                                                            "
                                                        />
                                                    </div>
                                                </div>
                                                <data-table
                                                    :headers="headersPartJasa"
                                                    :items="partJasa"
                                                    :search="searchPartJasa"
                                                >
                                                    <template #header.id>
                                                        <input
                                                            type="checkbox"
                                                            @click="
                                                                checkedAllPart
                                                            "
                                                            :checked="
                                                                checkAllPart
                                                            "
                                                        />
                                                    </template>
                                                    <template
                                                        #item.id="{ item }"
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            @click="
                                                                checkedPart(
                                                                    item
                                                                )
                                                            "
                                                            :checked="
                                                                checkedPartSelected &&
                                                                checkedPartSelected.find(
                                                                    (data) =>
                                                                        data.id ===
                                                                        item.id
                                                                )
                                                            "
                                                        />
                                                    </template>
                                                </data-table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="tab-pane fade"
                                id="pills-riwayat"
                                role="tabpanel"
                                aria-labelledby="pills-riwayat-tab"
                            >
                                <riwayat
                                    :items="riwayatCetak"
                                    @edit="editCetak"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" v-else>
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
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
                            @click="cetak"
                        >
                            <i class="fas fa-print"></i>
                            Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
