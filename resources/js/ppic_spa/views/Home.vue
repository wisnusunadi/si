<template>
  <div>
    <h1 class="title">DashBoard</h1>
    <div class="columns is-multiline">
      <div class="column is-12">
        <template v-if="!$store.state.isLoading">
          <div class="box">
            <apexcharts
              width="100%"
              height="350"
              type="bar"
              :options="chartOptions"
              :series="series"
            ></apexcharts>
          </div>
        </template>
      </div>
      <div class="column is-12">
        <div class="box">
          <table class="table is-fullwidth has-text-centered" id="table_so">
            <thead>
              <tr>
                <th>No</th>
                <th>No SO</th>
                <th>Nomor AKN</th>
                <th>Nomor PO</th>
                <th>Tanggal Order</th>
                <th>Batas Kontrak</th>
                <th>Customer</th>
                <th>Jenis</th>
                <th>Status</th>
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
                <td>{{ d.produk.nama }}</td>
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
              <li :class="{ 'is-active': !tabs }" @click="tabs = !tabs">
                <a>
                  <span class="icon is-small"
                    ><i class="fab fa-whmcs" aria-hidden="true"></i
                  ></span>
                  <span>Sparepart</span>
                </a>
              </li>
              <li :class="{ 'is-active': tabs }" @click="tabs = !tabs">
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
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Sparepart 1</td>
                  <td>Unit 1</td>
                  <td>100 pcs</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Sparepart 1</td>
                  <td>Unit 1</td>
                  <td>100 pcs</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Sparepart 1</td>
                  <td>Unit 1</td>
                  <td>100 pcs</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Sparepart 1</td>
                  <td>Unit 1</td>
                  <td>100 pcs</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Sparepart 1</td>
                  <td>Unit 1</td>
                  <td>100 pcs</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Sparepart 1</td>
                  <td>Unit 1</td>
                  <td>100 pcs</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Sparepart 1</td>
                  <td>Unit 1</td>
                  <td>100 pcs</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Sparepart 1</td>
                  <td>Unit 1</td>
                  <td>100 pcs</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Sparepart 1</td>
                  <td>Unit 1</td>
                  <td>100 pcs</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Sparepart 1</td>
                  <td>Unit 1</td>
                  <td>100 pcs</td>
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
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Unit 1</td>
                  <td>100 Unit</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Unit 1</td>
                  <td>100 Unit</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Unit 1</td>
                  <td>100 Unit</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Unit 1</td>
                  <td>100 Unit</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Unit 1</td>
                  <td>100 Unit</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Unit 1</td>
                  <td>100 Unit</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Unit 1</td>
                  <td>100 Unit</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Unit 1</td>
                  <td>100 Unit</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Unit 1</td>
                  <td>100 Unit</td>
                </tr>
                <tr>
                  <td scope="row">Kode 1</td>
                  <td>Unit 1</td>
                  <td>100 Unit</td>
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
      // data_gk: [],
      // data_so: [],

      chartOptions: {
        subtitle: {
          text: "Data Perakitan",
          align: "center",
          margin: 10,
          offsetX: 0,
          offsetY: 0,
          floating: false,
          style: {
            fontSize: "12px",
            fontWeight: "normal",
            fontFamily: undefined,
            color: "#9699a2",
          },
        },
        chart: {
          id: "basic-bar",
          animations: {
            speed: 200,
          },
        },
        dataLabels: {
          enabled: false,
        },
        plotOptions: {
          bar: {
            distributed: true,
          },
        },
        xaxis: {
          categories: ["rencana", "pelaksanaan", "selesai"],
        },
      },
      series: [
        {
          name: "data",
          data: [30, 40, 45],
        },
      ],

      tabs: false,
    };
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);
      await axios
        .get("/api/ppic/counting/status/perakitan")
        .then((response) => {
          this.series = [
            {
              name: "data",
              data: response.data,
            },
          ];
          console.log(response.data);
        });
      await axios.get("/api/ppic/data/gbj").then((response) => {
        this.data_gbj = response.data;
      });
      $("#table_gbj").DataTable();
      this.$store.commit("setIsLoading", false);
    },
  },

  mounted() {
    this.loadData();

    let this_obj = this;
    $("#table_so").DataTable({
      ajax: {
        url: "/api/penjualan/data",
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": this_obj.$store.state.csrf_token,
        },
      },
      columns: [
        {
          data: "DT_RowIndex",
          className: "nowrap-text align-center",
          orderable: false,
          searchable: false,
        },
        {
          data: "so",
          orderable: false,
          searchable: false,
        },
        {
          data: "no_paket",
        },
        {
          data: "nopo",
        },
        {
          data: "tgl_order",
        },
        {
          data: "tgl_kontrak",
        },
        {
          data: "nama_customer",
        },
        {
          data: "jenis",
        },
        {
          data: "status",
        },
      ],
    });
    $("#table-sparepart").DataTable();
    $("#table-unit").DataTable();
  },
};
</script>