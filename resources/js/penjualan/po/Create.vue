<template>
    <div class="content">
        <div class="row">
            <div class="col-12">
                <div v-if="afterSubmit == 'error'">
                    <div
                        class="alert alert-danger alert-dismissible fade show"
                        role="alert"
                    >
                        <strong>Gagal menambahkan!</strong> Periksa kembali data
                        yang diinput
                        <button
                            type="button"
                            class="close"
                            data-dismiss="alert"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div v-else-if="afterSubmit == 'success'">
                    <div
                        class="alert alert-success alert-dismissible fade show"
                        role="alert"
                    >
                        <strong>Berhasil menambahkan data</strong>, Terima kasih
                        <button
                            type="button"
                            class="close"
                            data-dismiss="alert"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4>Info Penjualan</h4>
                        <div class="row">
                            <div class="col-4">
                                <div>
                                    <b>{{ nama_customer }}</b>
                                </div>
                                <div>
                                    <b>{{ alamat }}</b>
                                </div>
                                <div>
                                    <b>{{ telepon }}</b>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-muted">Jenis Penjualan</div>
                                <div>
                                    <b>{{ jenis_penjualan }}</b>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-muted">Tanggal</div>
                                <div>
                                    <b>{{ tanggal }}</b>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="text-muted">Status</div>
                                <div>
                                    <b>{{ status }}</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="handleSubmit">
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label
                                        for="no_po"
                                        class="col-4 col-form-label"
                                        style="text-align:right;"
                                        >No PO</label
                                    >
                                    <div class="col-5">
                                        <input
                                            type="text"
                                            class="form-control"
                                            value=""
                                            placeholder="Masukkan Nomor Purchase Order"
                                            v-model="no_po"
                                            v-bind:class="{
                                                'is-invalid': no_poer
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback"
                                            v-if="msg.no_po"
                                        >
                                            {{ msg.no_po }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for="tanggal_po"
                                        class="col-4 col-form-label"
                                        style="text-align:right;"
                                        >Tanggal PO</label
                                    >
                                    <div class="col-5">
                                        <input
                                            type="date"
                                            class="form-control"
                                            value=""
                                            placeholder="Masukkan Tanggal Purchase Order"
                                            v-model="tanggal_po"
                                            v-bind:class="{
                                                'is-invalid': tanggal_poer
                                            }"
                                        />
                                        <div
                                            class="invalid-feedback"
                                            v-if="msg.tanggal_po"
                                        >
                                            {{ msg.tanggal_po }}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for="no_do"
                                        class="col-4 col-form-label"
                                        style="text-align:right;"
                                        >No DO</label
                                    >
                                    <div class="col-5">
                                        <input
                                            type="text"
                                            class="form-control"
                                            value=""
                                            placeholder="Masukkan Nomor Purchase Order"
                                            v-model="no_do"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for="tanggal_do"
                                        class="col-4 col-form-label"
                                        style="text-align:right;"
                                        >Tanggal DO</label
                                    >
                                    <div class="col-5">
                                        <input
                                            type="date"
                                            class="form-control"
                                            value=""
                                            placeholder="Masukkan Tanggal Delivery Order"
                                            v-model="tanggal_do"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        for="keterangan"
                                        class="col-4 col-form-label"
                                        style="text-align:right;"
                                        >Keterangan</label
                                    >
                                    <div class="col-5">
                                        <textarea
                                            class="form-control"
                                            placeholder="Masukkan Keterangan"
                                            v-model="keterangan"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <router-link :to="{ name: 'show' }"
                                        ><button class="btn btn-danger">
                                            Batal
                                        </button></router-link
                                    >
                                </div>
                                <div class="col-6">
                                    <button
                                        type="submit"
                                        class="btn btn-info float-right"
                                        v-bind:class="{ disabled: btndis }"
                                    >
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
export default {
    data() {
        return {
            msg: [],
            nama_customer: "",
            alamat: "",
            telepon: "",
            status: "",
            jenis_penjualan: "",
            tanggal: "",

            no_po: "",
            tanggal_po: "",
            no_do: "",
            tanggal_do: "",
            keterangan: "",
            afterSubmit: "",
            btndis: true
        };
    },
    methods: {
        handleSubmit: function() {
            axios
                .post(`tesapi`, {
                    no_po: this.no_po,
                    tanggal_po: this.tanggal_po,
                    no_do: this.no_do,
                    tanggal_do: this.tanggal_do,
                    keterangan: this.keterangan
                })
                .then(res => {
                    this.afterSubmit = res;
                });
        },
        checkPo: function(e) {
            axios.get(`tesapi`, { no_po: this.no_po }).then(res => {
                return res;
            });
        }
    },
    watch: {
        no_po: function() {
            if (this.no_po != "") {
                if (this.checkPo >= 1) {
                    this.msg["no_po"] = "No PO sudah terisi";
                    this.no_poer = true;
                    this.btndis = true;
                } else {
                    this.msg["no_po"] = "";
                    this.no_poer = false;
                    if (this.tanggal_po != "") {
                        this.btndis = false;
                    } else {
                        this.btndis = true;
                    }
                }
            } else if (this.no_po == "") {
                this.msg["no_po"] = "No PO Wajib diisi";
                this.no_poer = true;
                this.btndis = true;
            }
        },
        tanggal_po: function() {
            if (this.tanggal_po != "") {
                this.msg["tanggal_po"] = "";
                this.tanggal_poer = false;
                if (this.no_po != "") {
                    this.btndis = false;
                } else {
                    this.btndis = true;
                }
            } else if (this.tanggal_po == "") {
                this.msg["tanggal_po"] = "Tanggal Wajib diisi";
                this.tanggal_poer = true;
                this.btndis = true;
            }
        }
    }
};
</script>
