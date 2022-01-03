<template>
  <div>
    <div class="is-flex is-justify-content-space-between">
      <h1 class="title">Data Sales Order</h1>
      <div class="dropdown is-right is-hoverable">
        <div class="dropdown-trigger">
          <button
            class="button"
            aria-haspopup="true"
            aria-controls="dropdown-menu6"
          >
            <span><i class="fas fa-filter"></i> Filter</span>
            <span class="icon is-small">
              <i class="fas fa-angle-down" aria-hidden="true"></i>
            </span>
          </button>
        </div>
        <div class="dropdown-menu" id="dropdown-menu6" role="menu">
          <div class="dropdown-content">
            <div class="dropdown-item">
              <label class="checkbox">
                <input type="checkbox" value="Ekatalog" v-model="jenis" />
                Ekatalog
              </label>
            </div>
            <div class="dropdown-item">
              <label class="checkbox">
                <input type="checkbox" value="SPA" v-model="jenis" /> SPA
              </label>
            </div>
            <div class="dropdown-item">
              <label class="checkbox">
                <input type="checkbox" value="SPB" v-model="jenis" /> SPB
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="columns is-multiline">
      <div class="column is-12">
        <table class="table is-fullwidth has-text-centered" id="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Produk</th>
              <th>Stok</th>
              <th>Pesanan</th>
              <th>Selisih stok dengan pesanan</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>

    <!-- modal -->
    <div class="modal" :class="{ 'is-active': showModal }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Detail</p>
          <button
            class="delete"
            aria-label="close"
            @click="showModal = false"
          ></button>
        </header>
        <section class="modal-card-body">
          <table class="table">
            <tbody>
              <tr>
                <td>No AKN</td>
                <td>{{ detail.no_akn }}</td>
              </tr>
              <tr>
                <td>No PO</td>
                <td>{{ detail.no_po }}</td>
              </tr>
              <tr>
                <td>Instansi</td>
                <td>{{ detail.instansi }}</td>
              </tr>
              <tr>
                <td>Alamat</td>
                <td>{{ detail.alamat }}</td>
              </tr>
            </tbody>
          </table>
        </section>
        <footer class="modal-card-foot"></footer>
      </div>
    </div>
  </div>
</template>

<script>
import $ from "jquery";
import axios from "axios";

export default {
  name: "SalesOrder",

  data() {
    return {
      data: [],
      jenis: [],
      detail: {},
      showModal: false,
      table: null,
    };
  },

  methods: {
    getDetail(id) {
      this.detail = this.format_data.find((item) => item.id == id).detail;
      this.showModal = true;
    },
  },

  async created() {
    this.$store.commit("setIsLoading", true);
    await axios.get("/api/ppic/data/so").then((response) => {
      this.data = response.data;
      console.log(this.data);
    });
    this.$store.commit("setIsLoading", false);

    this.table = $("#table").DataTable({
      serverSide: true,
      ajax: "/api/ppic/data/so",
      columns: [
        {
          data: "DT_RowIndex",
          orderable: false,
          searchable: false,
        },
        {
          data: "nama_produk",
        },
        {
          data: "gbj",
        },
        {
          data: "total",
        },
        {
          data: "penjualan",
        },
      ],
    });
  },

  computed: {
    format_data() {
      let data = Array.from(this.data, (item, index) => {
        let nama_produk = `${item.gudang_barang_jadi.produk.nama} ${item.gudang_barang_jadi.nama}`;
        let jumlah = item.detail_pesanan.jumlah;
        let no_so = item.detail_pesanan.pesanan.so;
        let tanggal_order = item.detail_pesanan.pesanan.ekatalog
          ? item.detail_pesanan.pesanan.ekatalog.tgl_buat
          : item.detail_pesanan.pesanan.tgl_po;
        let batas_kontrak = item.detail_pesanan.pesanan.ekatalog
          ? item.detail_pesanan.pesanan.ekatalog.tgl_buat
          : "";

        let customer;
        let jenis;
        let detail = {
          no_akn: "",
          no_po: "",
          instansi: "",
          alamat: "",
        };
        if (item.detail_pesanan.pesanan.ekatalog) {
          customer = item.detail_pesanan.pesanan.ekatalog.customer.nama;
          jenis = "Ekatalog";
          detail.no_akn = item.detail_pesanan.pesanan.ekatalog.no_paket;
          detail.instansi = item.detail_pesanan.pesanan.ekatalog.instansi;
          detail.alamat = item.detail_pesanan.pesanan.ekatalog.customer.alamat;
        } else if (item.detail_pesanan.pesanan.spa) {
          customer = item.detail_pesanan.pesanan.spa.customer.nama;
          jenis = "SPA";
          detail.alamat = item.detail_pesanan.pesanan.spa.customer.alamat;
        } else if (item.detail_pesanan.pesanan.spb) {
          customer = item.detail_pesanan.pesanan.spb.customer.nama;
          jenis = "SPB";
          detail.alamat = item.detail_pesanan.pesanan.spb.customer.alamat;
        }
        detail.no_po = item.detail_pesanan.pesanan.no_po;

        let status;
        if (item.detail_pesanan.pesanan.log) {
          status = item.detail_pesanan.pesanan.log.nama;
        }

        return {
          id: item.id,
          nama_produk: nama_produk,
          jumlah: jumlah,
          no_so: no_so,
          tanggal_order: tanggal_order,
          batas_kontrak: batas_kontrak,
          customer: customer,
          jenis: jenis,
          status: status,
          detail: detail,
        };
      });
      return data;
    },
  },

  watch: {
    jenis(val) {
      if (val.length === 0) {
        this.table.column(7).search("").draw();
      } else {
        let search = "(";
        for (let i = 0; i < val.length; i++) {
          search += val[i];
          if (i !== val.length - 1) search += "|";
        }
        search += ")";
        this.table.column(7).search(search, true).draw();
      }
    },
  },
};
</script>