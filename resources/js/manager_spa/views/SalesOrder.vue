<template>
  <div>
    <h1 class="title">Data Sales Order</h1>
    <div class="columns is-multiline">
      <div class="column is-12">
        <table class="table is-fullwidth has-text-centered" id="table">
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
    };
  },

  methods: {
    async loadData() {
      // this.$store.commit("setIsLoading", true);
      // await axios.get("/api/ppic/so/data").then((response) => {
      //   this.data = response.data;
      //   console.log(this.data);
      // });
      // this.$store.commit("setIsLoading", false);

      let this_obj = this;
      let table = $("#table").DataTable({
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
    },
  },

  mounted() {
    this.loadData();
  },
};
</script>