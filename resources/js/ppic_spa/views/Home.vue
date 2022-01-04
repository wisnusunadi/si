<template>
  <div>
    <h1 class="title">DashBoard</h1>
    <div class="columns is-multiline">
      <div class="column is-6">
        <article class="message is-primary">
          <div class="message-header">
            <p>Permintaan</p>
          </div>
          <div class="message-body has-text-centered">
            <p class="subtitle is-1">{{ jumlah_permintaan }}</p>
          </div>
        </article>
      </div>
      <div class="column is-6">
        <article class="message is-warning">
          <div class="message-header">
            <p>Proses</p>
          </div>
          <div class="message-body has-text-centered">
            <p class="subtitle is-1">{{ jumlah_proses }}</p>
          </div>
        </article>
      </div>
      <div class="column is-12">
        <div class="box">
          <table class="table is-fullwidth has-text-centered" id="table_so">
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
      <div class="column is-6">
        <div class="box">
          <table class="table is-fullwidth has-text-centered" id="table_gbj">
            <thead>
              <tr>
                <th>No</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Stok</th>
                <th>Kelompok</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(d, index) in data_gbj" :key="d.id">
                <td>{{ index + 1 }}</td>
                <td>{{ d.produk.product.kode }}</td>
                <td>{{ d.produk.nama + " " + d.nama }}</td>
                <td>{{ d.stok }}</td>
                <td>{{ d.produk.kelompok_produk.nama }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="column is-6">
        <div class="box">
          <div class="tabs is-centered">
            <ul>
              <li :class="{ 'is-active': !tabs }" @click="tabs = false">
                <a>
                  <span class="icon is-small"
                    ><i class="fab fa-whmcs" aria-hidden="true"></i
                  ></span>
                  <span>Sparepart</span>
                </a>
              </li>
              <li :class="{ 'is-active': tabs }" @click="tabs = true">
                <a>
                  <span class="icon is-small"
                    ><i class="fas fa-tools" aria-hidden="true"></i
                  ></span>
                  <span>Unit</span>
                </a>
              </li>
            </ul>
          </div>
          <!-- sparepart -->
          <div :class="{ 'is-hidden': tabs }">
            <table
              class="table is-fullwidth has-text-centered"
              id="table-sparepart"
            >
              <thead>
                <tr>
                  <th>Kode Sparepart</th>
                  <th>Nama</th>
                  <th>Unit</th>
                  <th>Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in data_sparepart" :key="item.id">
                  <td>{{ item.kode }}</td>
                  <td>{{ item.nama }}</td>
                  <td>{{ item.unit }}</td>
                  <td>{{ item.jml }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- unit -->
          <div :class="{ 'is-hidden': !tabs }">
            <table class="table is-fullwidth has-text-centered" id="table-unit">
              <thead>
                <tr>
                  <th>Kode Unit</th>
                  <th>Nama</th>
                  <th>Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in data_unit" :key="item.id">
                  <td>{{ item.kode }}</td>
                  <td>{{ item.nama }}</td>
                  <td>{{ item.jml }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueApexCharts from "vue-apexcharts";
import $ from "jquery";
import axios from "axios";

export default {
  name: "Home",

  components: {
    apexcharts: VueApexCharts,
  },

  data() {
    return {
      data_gbj: [],
      data_so: [],
      data_unit: [],
      data_sparepart: [],

      jumlah_permintaan: 0,
      jumlah_proses: 0,

      tabs: false,
    };
  },

  async created() {
    this.$store.commit("setIsLoading", true);

    await axios.get("/api/ppic/data/so").then((response) => {
      this.data_so = response.data;
    });

    await axios.get("/api/ppic/data/gbj").then((response) => {
      this.data_gbj = response.data;
    });
    $("#table_gbj").DataTable();

    await axios.get("/api/ppic/data/gk/sparepart").then((response) => {
      this.data_sparepart = response.data;
    });
    $("#table-sparepart").DataTable();

    await axios.get("/api/ppic/data/gk/unit").then((response) => {
      this.data_unit = response.data;
    });
    $("#table-unit").DataTable();

    await axios.get("/api/ppic/counting/komentar").then((response) => {
      this.jumlah_permintaan = response.data[0];
      this.jumlah_proses = response.data[1];
    });

    $("#table_so").DataTable({
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

    this.$store.commit("setIsLoading", false);
  },

  computed: {
    format_data() {
      let data = Array.from(this.data_so, (item, index) => {
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
};
</script>