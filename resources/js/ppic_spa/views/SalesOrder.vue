<template>
  <div>
    <div class="is-flex is-justify-content-space-between">
      <h1 class="title">Daftar Sales Order</h1>
    </div>
    <div class="tabs is-centered">
      <ul>
        <li :class="{ 'is-active': view === 'sales_order'}" @click="loadData">
        <a>
          <span class="icon is-small"><i class="fas fa-table" aria-hidden="true"></i></span>
          <span>Per SO</span>
        </a>
        </li>
        <li :class="{ 'is-active': view === 'per_produk'}" @click="perProduk">
          <a>
            <span class="icon is-small">
              <i class="fas fa-table" aria-hidden="true"></i>
            </span>
              <span>Per Produk</span>
          </a>
        </li>
      </ul>
    </div>
    <template v-if="view == 'sales_order'">
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
            <tr v-for="item in salesOrder" :key="'so'+item.DT_RowIndex">
              <td v-html="item.DT_RowIndex"></td>
              <td v-html="item.so"></td>
              <td v-html="item.no_po"></td>
              <td v-html="item.tgl_po"></td>
              <td v-html="item.nama_customer "></td>
              <td v-html="item.status_prd"></td>
              <td>
                <button
                  class="button is-light"
                  @click="getSO(item.id, item.btnValue)"
                >
                  <i class="fas fa-search"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    </template>
    <template v-if="view == 'per_produk'">
      <div class="columns is-multiline">
      <div class="column is-12">
        <table class="table is-fullwidth has-text-centered" id="table_produk">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Produk</th>
              <th>Stok</th>
              <th>Pesanan</th>
              <th>Selisih stok dengan pesanan</th>
              <th>Jumlah Terkirim</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in data" :key="item.id">
              <td>{{ item.DT_RowIndex }}</td>
              <td v-html="item.nama_produk"></td>
              <td>{{ item.stok }}</td>
              <td>{{ item.total }}</td>
              <td v-html="item.penjualan">
              </td>
              <td v-text="item.jumlah_kirim"></td>
              <td>
                <button
                  class="button is-light"
                  @click="getDetail(item.id, item.nama_produk)"
                >
                  <i class="fas fa-search"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    </template>
    <!-- modal -->
    <div class="modal" :class="{ 'is-active': showModal }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title" v-html="nama_produk"></p>
          <button
            class="delete"
            aria-label="close"
            @click="showModal = false"
          ></button>
        </header>
        <section class="modal-card-body">
          <table class="table is-fullwidth" id="detailtable">
            <thead>
              <tr>
                <th>SO</th>
                <th>PO</th>
                <th>AKN</th>
                <th>Tanggal order</th>
                <th>Tanggal pengiriman</th>
                <th>Jumlah</th>
                <th>Pelanggan</th>
                <th>Jenis</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in detail" :key="item.id">
                <td v-html="item.so"></td>
                <td>{{ item.po }}</td>
                <td>{{ item.akn }}</td>
                <td v-html="item.tgl_order"></td>
                <td v-html="item.tgl_delivery"></td>
                <td v-html="item.jumlah"></td>
                <td>{{ item.customer }}</td>
                <td>{{ item.jenis }}</td>
                <td>{{ item.status }}</td>
              </tr>
            </tbody>
          </table>
        </section>
        <footer class="modal-card-foot"></footer>
      </div>
    </div>

<div class="modal" :class="{ 'is-active': showModalSO }">
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
              <tr v-for="item in detailSO" :key="'detailSO'+item.id">
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
  </div>
</template>

<script>
import $ from "jquery";
import axios from "axios";

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

  data() {
    return {
      data: [],
      salesOrder: [],
      detail: {},
      nama_produk: "",
      detailSO: {},

      showModal: false,
      showModalSO: false,
      view: "sales_order"
    };  
  },

  methods: {
    loadData() {
      this.$store.commit("setIsLoading", true);
        axios.post("/api/prd/so").then((response) => {
          this.salesOrder = response.data.data;
        }).then(() => ($("#table_so").DataTable()))
        .then(() => (this.$store.commit("setIsLoading", false)));
        this.view = "sales_order";
    },


    async getDetail(id, nama) {
      this.$store.commit("setIsLoading", true);
      await axios.get("/api/ppic/data/so/detail/" + id).then((response) => {
        this.detail = response.data.data;
      });
      $("#detailtable").DataTable();
      this.$store.commit("setIsLoading", false);

      this.nama_produk = nama;

      this.showModal = true;
    },

  async getSO(id, value){
      this.$store.commit("setIsLoading", true);
      $("#detailtableSO").DataTable().destroy();
      try {
        await axios.get("/api/ppic/data/produk_so/" + id + "/" + value).then((response) => {
        this.detailSO = response.data.data;
      });
      } catch (error) {
        console.log(error);
      }
      $("#detailtableSO").DataTable({
            autoWidth: false,
          "drawCallback": function (settings) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(0, {page:'current'} ).data().each( function ( group, i ) {

                if (last !== group) {
                    var rowData = api.row(i).data();

                    $(rows).eq(i).before(
                    '<tr class="is-selected"><td colspan="3">' + group + '</td></tr>'
                );
                    last = group;
                }
            });
          },
           "columnDefs":[
                {"targets": [0], "visible": false},
            ],
      });
      this.$store.commit("setIsLoading", false);
      this.showModalSO = true;
      
    },
    perProduk(){
        this.$store.commit("setIsLoading", true);
        $("#table_produk").DataTable().destroy();
        axios.get("/api/ppic/data/so").then((response) => {
        this.data = response.data.data;
      }).catch((error) => {
        console.log(error);
      })
      .then(() => (this.$store.commit("setIsLoading", false)))
      .then(() => ($("#table_produk").DataTable())) ;
      this.view = "per_produk";
    }
  },

  mounted() {
    this.loadData();
  },
};
</script>
