<template>
    <div>
        <div class="is-flex is-justify-content-space-between">
            <h1 class="title">Daftar Sales Order</h1>
        </div>
        <div class="tabs is-centered">
            <ul>
                <li :class="{ 'is-active': !tabs }" @click="tabs = false">
                    <a>
                        <span class="icon is-small"><i class="fas fa-table" aria-hidden="true"></i></span>
                        <span>Per SO</span>
                    </a>
                </li>
                <li :class="{ 'is-active': tabs }" @click="tabs = true">
                    <a>
                        <span class="icon is-small">
                            <i class="fas fa-table" aria-hidden="true"></i>
                        </span>
                        <span>Per Produk</span>
                    </a>
                </li>
            </ul>
        </div>
        <div :class="{ 'is-hidden': tabs }">
            <div class="columns is-multiline">
                <div class="column is-12">
                    <table class="table is-fullwidth has-text-centered" id="table_so">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor SO</th>
                                <th>Nomor PO</th>
                                <th>Tanggal Order</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in salesOrder" :key="'so' + item.DT_RowIndex">
                                <td v-html="item.DT_RowIndex"></td>
                                <td v-html="item.so"></td>
                                <td v-html="item.no_po"></td>
                                <td v-html="item.tgl_po"></td>
                                <td v-html="item.nama_customer"></td>
                                <td v-html="item.status_prd"></td>
                                <td>
                                    <button class="button is-light" @click="getSO(item.id, item.btnValue)">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div :class="{ 'is-hidden': !tabs }">
            <div class="columns">
                <div class="column">

                </div>
                <div class="column is-flex-shrink-1">
                    <input class="input" type="text" placeholder="Cari" v-model="search" />
                </div>
            </div>
            <div class="columns is-multiline">
                <div class="column is-12">
                    <table class="table is-fullwidth has-text-centered" id="table_produk">
                        <thead>
                            <tr>
                                <th class="has-text-centered" rowspan="2">No</th>
                                <th class="has-text-centered" rowspan="2">Nama Produk</th>
                                <th class="has-text-centered" colspan="2">Permintaan</th>
                                <th class="has-text-centered" rowspan="2">Jumlah Transfer</th>
                                <th class="has-text-centered" rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th class="has-text-centered">Total</th>
                                <th class="has-text-centered">Sisa</th>
                            </tr>
                        </thead>
                        <tbody v-if="renderPaginate.length > 0">
                            <tr v-for="(item, index) in renderPaginate" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ item.produkk }}</td>
                                <td>{{ item.permintaan }}</td>
                                <td>{{ item.sisa }}</td>
                                <td>{{ item.count_transfer }}</td>
                                <td>
                                    <button class="button is-light" @click="openModalDetail(item)">
                                        <i class="fa fa-eye"></i>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan=" 100%">Data tidak ditemukan
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <pagination :filteredDalamProses="filteredDalamProses"
                        @updateFilteredDalamProses="updateFilteredDalamProses" />
                </div>
            </div>
        </div>
        <!-- modal -->


        <div class="modal" v-if="showModalSO" :class="{ 'is-active': showModalSO }">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title"></p>
                    <button class="delete" @click="showModalSO = false"></button>
                </header>
                <section class="modal-card-body">
                    <table class="table is-fullwidth" id="detailtableSO">
                        <thead>
                            <tr>
                                <th>Paket</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Jumlah Terkirim</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in detailSO" :key="index">
                                <td v-text="item.paket"></td>
                                <td v-text="item.produk"></td>
                                <td v-text="item.jumlah"></td>
                                <td v-text="item.jumlah_kirim"></td>
                            </tr>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>

        <detailProdukSO v-if="showModal" :showModal="showModal" :detailSelected="detailSelected" @close="showModal = false" />
    </div>
</template>

<script>
import $ from "jquery";
import axios from "axios";
import pagination from "../../components/pagination.vue";
import detail from './detail.vue'
import detailProdukSO from "./detailProdukSO.vue";

/**
 * @vue-data {Array} [data=[]] - Array to store data sales order get from API (url = '/api/ppic/data/so')
 * @vue-data {Object} [detail={}] - Object to store detail sales order when detail button clicked
 * @vue-data {String} [nama_produk=""] - variable to store product name that use as header modal of detail sales order
 * @vue-data {Boolean} [showModal=false] - flag used to show or hide detail sales order modal
 *
 * @vue-event {Array} loadData - this function is used to initialized data by calling APIs
 * @vue-event {Object} getDetail - function to get product sales order detail and get product name
 */

export default {
    name: "SalesOrder",
    components: {
        pagination,
        detail,
        detailProdukSO
    },
    data() {
        return {
            showModal: false,
            showModalSO: false,
            tabs: false,
            produks: [],
            salesOrder: [],
            nama_produk: "",
            renderPaginate: [],
            search: '',
            detailSelected: {},
        };
    },

    methods: {
        async loadData() {
            this.$store.commit("setIsLoading", true);
            const body = {};
            await axios.post("/api/prd/so", body, {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
                }
            }).then((response) => {
                this.salesOrder = response.data.data;
            }).then(() => ($("#table_so").DataTable({
                pagingType: "simple_numbers_no_ellipses",
            })))

            const { data } = await axios.get('/api/v2/gbj/get_rekap_so_produk')
            this.produks = data

            // await axios.post("/api/ppic/master_pengiriman/data",body, {
            //     headers: {
            //         Authorization: 'Bearer ' + localStorage.getItem('lokal_token')
            //     }
            // }).then((response) => {
            //     this.produks = response.data.data;
            // }).then(() => ($("#table_produk").DataTable({
            //     pagingType: "simple_numbers_no_ellipses",
            //     })
            // ));
            this.$store.commit("setIsLoading", false);
        },

        async openModalDetail(item) {
            this.detailSelected = item
            this.showModal = true
        },

        async getSO(id, value) {
            this.$store.commit("setIsLoading", true);
            $("#detailtableSO").DataTable().destroy();
            try {
                await axios
                    .get("/api/ppic/data/produk_so/" + id + "/" + value)
                    .then((response) => {
                        this.detailSO = response.data.data;

                        window.requestAnimationFrame(() => {
                            $("#detailtableSO").DataTable({
                                autoWidth: false,
                                drawCallback: function (settings) {
                                    var api = this.api();
                                    var rows = api.rows({ page: "current" }).nodes();
                                    var last = null;

                                    api.column(0, { page: "current" })
                                        .data()
                                        .each(function (group, i) {
                                            if (last !== group) {
                                                var rowData = api.row(i).data();

                                                $(rows)
                                                    .eq(i)
                                                    .before(
                                                        '<tr class="is-selected"><td colspan="3">' +
                                                        group +
                                                        "</td></tr>"
                                                    );
                                                last = group;
                                            }
                                        });
                                },
                                columnDefs: [{ targets: [0], visible: false }],
                            });
                        });
                    });
            } catch (error) {
                console.log(error);
            }
            this.$store.commit("setIsLoading", false);
            this.showModalSO = true;
        },
        checkToken() {
            if (localStorage.getItem('lokal_token') == null) {
                // event.preventDefault();
                this.$swal({
                    title: 'Session Expired',
                    text: 'Silahkan login kembali',
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.value) {
                        this.logout()
                    }
                })
            }
        },

        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },

        async logout() {
            await axios.post("/logout");
            document.location.href = "/";
        },
    },

    created() {
        this.checkToken();
    },

    mounted() {
        this.loadData();
    },

    computed: {
        filteredDalamProses() {
            return this.produks.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        }
    }
};
</script>
