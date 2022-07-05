<template>
    <div class="columns">
        <div class="column is-full">
            <h1 class="title">QC Outgoing</h1>
            <div class="tabs is-toggle">
                <ul>
                    <li :class="{ 'is-active': !tabs }" @click="tabs = false">
                        <a>
                            <span class="icon is-small"
                                ><i class="fa fa-spinner" aria-hidden="true"></i
                            ></span>
                            <span>Dalam Pengujian</span>
                        </a>
                    </li>
                    <li :class="{ 'is-active': tabs }" @click="tabs = true">
                        <a>
                            <span class="icon is-small"
                                ><i class="fas fa-check" aria-hidden="true"></i
                            ></span>
                            <span>Selesai Pengujian</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="columns" :class="{ 'is-hidden': tabs }">
                <div class="column is-full">
                    <div class="table-responsive">
                        <table
                            class="table is-hoverable is-striped is-fullwidth"
                            id="dalamujitable"
                        >
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No SO</th>
                                    <th>No PO</th>
                                    <th>Batas Pengujian</th>
                                    <th>Customer</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in data_belum_uji"
                                    :key="item.id"
                                >
                                    <td v-html="item.DT_RowIndex"></td>
                                    <td v-html="item.so"></td>
                                    <td v-html="item.no_po"></td>
                                    <td v-html="item.batas_uji"></td>
                                    <td v-html="item.nama_customer"></td>
                                    <td v-html="item.ket"></td>
                                    <td v-html="item.status"></td>
                                    <td>
                                        <button
                                            class="button is-info is-small js-modal-trigger"
                                            @click="detail_so(item.id)"
                                        >
                                            <i class="fas fa-eye"></i
                                            >&nbsp;Detail
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="columns" :class="{ 'is-hidden': !tabs }">
                <div class="column is-full">
                    <div class="table-responsive">
                        <table
                            class="table is-hoverable is-striped is-fullwidth"
                            id="selesaiujitable"
                        >
                            <thead class="text-centered">
                                <tr>
                                    <th>No</th>
                                    <th>No SO</th>
                                    <th>No PO</th>
                                    <th>Customer</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in data_selesai_uji"
                                    :key="item.id"
                                >
                                    <td v-html="item.DT_RowIndex"></td>
                                    <td v-html="item.so"></td>
                                    <td v-html="item.no_po"></td>
                                    <td v-html="item.nama_customer"></td>
                                    <td v-html="item.keterangan"></td>
                                    <td>
                                        <button
                                            class="button is-info is-small js-modal-trigger"
                                            @click="detail_so(item.id)"
                                        >
                                            <i class="fas fa-eye"></i
                                            >&nbsp;Detail
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div
                id="detail_modal"
                class="modal"
                :class="{ 'is-active': detailModal }"
            >
                <div class="modal-background"></div>
                <div class="modal-card is-info">
                    <header class="modal-card-head has-background-info">
                        <p class="modal-card-title has-text-white is-size-4">
                            Sales Order
                        </p>
                        <button
                            class="delete"
                            @click="detailModal = false"
                        ></button>
                    </header>
                    <section class="modal-card-body has-background-info-light">
                        <div class="columns">
                            <div class="column is-full">
                                <div class="box">
                                    <div class="tile is-ancestor">
                                        <div class="tile is-parent">
                                            <article
                                                class="tile is-child notification is-link"
                                            >
                                                <p class="title is-spaced is-6">
                                                    Nama Customer
                                                </p>
                                                <p
                                                    class="subtitle is-6"
                                                    v-html="nama_customer"
                                                ></p>
                                            </article>
                                        </div>
                                        <div class="tile is-parent">
                                            <article
                                                class="tile is-child notification is-warning"
                                            >
                                                <p class="title is-spaced is-6">
                                                    Alamat
                                                </p>
                                                <p
                                                    class="subtitle is-6"
                                                    v-html="alamat"
                                                ></p>
                                            </article>
                                        </div>
                                    </div>
                                    <div class="tile is-ancestor">
                                        <div class="tile is-parent">
                                            <article
                                                class="tile is-child notification is-primary"
                                            >
                                                <p class="title is-spaced is-6">
                                                    No SO
                                                </p>
                                                <p
                                                    class="subtitle is-6"
                                                    v-html="no_so"
                                                ></p>
                                            </article>
                                        </div>
                                        <div class="tile is-parent">
                                            <article
                                                class="tile is-child notification is-danger"
                                            >
                                                <p class="title is-spaced is-6">
                                                    No PO
                                                </p>
                                                <p
                                                    class="subtitle is-6"
                                                    v-html="no_po"
                                                ></p>
                                            </article>
                                        </div>
                                    </div>
                                    <div class="block">
                                        <div class="table-responsive">
                                            <table
                                                class="table is-hoverable is-striped"
                                                width="100%"
                                                id="produktable"
                                            >
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">No</th>
                                                        <th rowspan="2">
                                                            Nama Produk
                                                        </th>
                                                        <th rowspan="2">
                                                            Jumlah
                                                        </th>
                                                        <th
                                                            colspan="2"
                                                            class="collapsable"
                                                        >
                                                            Hasil
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <i
                                                                class="fas fa-check has-text-success"
                                                            ></i>
                                                        </th>
                                                        <th>
                                                            <i
                                                                class="fas fa-times has-text-danger"
                                                            ></i>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        v-for="item in produk_uji"
                                                        :key="item.id"
                                                    >
                                                        <td
                                                            v-html="
                                                                item.DT_RowIndex
                                                            "
                                                        ></td>
                                                        <td
                                                            v-html="
                                                                item.nama_produk
                                                            "
                                                        ></td>
                                                        <td
                                                            v-html="item.jumlah"
                                                        ></td>
                                                        <td
                                                            v-html="
                                                                item.jumlah_ok
                                                            "
                                                        ></td>
                                                        <td
                                                            v-html="
                                                                item.jumlah_nok
                                                            "
                                                        ></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button
                            class="button is-dark float-right"
                            @click="detailModal = false"
                        >
                            Tutup
                        </button>
                    </footer>
                </div>
            </div>

            <div
                id="batal_modal"
                class="modal"
                :class="{ 'is-active': batalModal }"
            >
                <div class="modal-background"></div>
                <div class="modal-card is-info">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Sales Order Batal</p>
                        <button
                            class="delete"
                            @click="batalModal = false"
                        ></button>
                    </header>
                    <section class="modal-card-body">
                        <div class="columns">
                            <div class="column is-full">
                                <div class="box">
                                    <div class="tile is-ancestor">
                                        <div class="tile is-parent">
                                            <article
                                                class="tile is-child notification is-link"
                                            >
                                                <p class="title is-6 is-spaced">
                                                    Nama Customer
                                                </p>
                                                <p
                                                    class="subtitle is-6"
                                                    v-html="nama_customer"
                                                ></p>
                                            </article>
                                        </div>
                                        <div class="tile is-parent">
                                            <article
                                                class="tile is-child notification is-warning"
                                            >
                                                <p class="title is-6 is-spaced">
                                                    Alamat
                                                </p>
                                                <p
                                                    class="subtitle is-6"
                                                    v-html="alamat"
                                                ></p>
                                            </article>
                                        </div>
                                    </div>
                                    <div class="tile is-ancestor">
                                        <div class="tile is-parent">
                                            <article
                                                class="tile is-child notification is-primary"
                                            >
                                                <p class="title is-6 is-spaced">
                                                    No SO
                                                </p>
                                                <p
                                                    class="subtitle is-6"
                                                    v-html="no_so"
                                                ></p>
                                            </article>
                                        </div>
                                        <div class="tile is-parent">
                                            <article
                                                class="tile is-child notification is-danger"
                                            >
                                                <p class="title is-6 is-spaced">
                                                    No PO
                                                </p>
                                                <p
                                                    class="subtitle is-6"
                                                    v-html="no_po"
                                                ></p>
                                            </article>
                                        </div>
                                    </div>
                                    <div class="block">
                                        <div class="table-responsive">
                                            <table
                                                class="table is-hoverable is-striped"
                                                width="100%"
                                                id="produktable"
                                            >
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2">No</th>
                                                        <th rowspan="2">
                                                            Nama Produk
                                                        </th>
                                                        <th rowspan="2">
                                                            Jumlah
                                                        </th>
                                                        <th
                                                            colspan="2"
                                                            class="collapsable"
                                                        >
                                                            Hasil
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <i
                                                                class="fas fa-check has-text-success"
                                                            ></i>
                                                        </th>
                                                        <th>
                                                            <i
                                                                class="fas fa-times has-text-danger"
                                                            ></i>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        v-for="item in produk_uji"
                                                        :key="item.id"
                                                    >
                                                        <td
                                                            v-html="
                                                                item.DT_RowIndex
                                                            "
                                                        ></td>
                                                        <td
                                                            v-html="
                                                                item.nama_produk
                                                            "
                                                        ></td>
                                                        <td
                                                            v-html="item.jumlah"
                                                        ></td>
                                                        <td
                                                            v-html="
                                                                item.jumlah_ok
                                                            "
                                                        ></td>
                                                        <td
                                                            v-html="
                                                                item.jumlah_nok
                                                            "
                                                        ></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button
                            class="button is-dark float-right"
                            @click="batalModal = false"
                        >
                            Tutup
                        </button>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import $ from "jquery";
import axios from "axios";
export default {
    name: "Outgoing",
    data() {
        return {
            nama_customer: "",
            alamat: "",
            no_po: "",
            no_so: "",
            tanggal_batal: "",

            data_belum_uji: [],
            data_selesai_uji: [],
            produk_uji: [],

            batalModal: false,
            detailModal: false,
            detailSo: false,
            tabs: false,
        };
    },

    methods: {
        async loadData() {
            await axios
                .post("/api/manager/qc/belum_uji")
                .then((response) => {
                    this.data_belum_uji = response.data.data;
                })
                .then(() =>
                    $("#dalamujitable").DataTable({
                        pagingType: "simple_numbers_no_ellipses",
                    })
                );

            await axios
                .post("/api/manager/qc/selesai_uji")
                .then((response) => {
                    this.data_selesai_uji = response.data.data;
                })
                .then(() =>
                    $("#selesaiujitable").DataTable({
                        pagingType: "simple_numbers_no_ellipses",
                    })
                );
        },

        async detail_so(id) {
            $("#produktable").DataTable().destroy();
            await axios.get("/api/manager/pesanan/" + id).then((response) => {
                console.log(response.data);
                if (response.data.ekatalog != null) {
                    this.nama_customer = response.data.ekatalog.satuan;
                    this.alamat = response.data.ekatalog.alamat;
                } else if (response.data.spa != null) {
                    this.nama_customer = response.data.spa.customer.nama;
                    this.alamat = response.data.spa.customer.alamat;
                } else if (response.data.spb != null) {
                    this.nama_customer = response.data.spb.customer.nama;
                    this.alamat = response.data.spb.customer.alamat;
                }
                this.no_so = response.data.so;
                this.no_po = response.data.no_po;
            });
            await axios
                .post("/api/qc/so/detail/" + id)
                .then((response) => {
                    this.produk_uji = response.data.data;
                })
                .then(() =>
                    $("#produktable").DataTable({
                        pagingType: "simple_numbers_no_ellipses",
                    })
                );
            this.detailModal = true;
        },
    },

    mounted() {
        this.loadData();
    },
};
</script>
