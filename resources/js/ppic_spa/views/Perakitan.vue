<template>
  <div>
    <h1 class="title">Data Perakitan</h1>
    <div class="columns is-multiline">
      <div class="column is-12">
        <table class="table is-fullwidth has-text-centered" id="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Produk</th>
              <th>Jumlah</th>
              <th>Waktu Mulai</th>
              <th>Waktu Selesai</th>
              <th>Progres</th>
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
import mixins from "../mixins";

/**
 * @vue-event {Array} loadData - this function initialized datatables with server-side option
 */

export default {
  name: "Perakitan",

  methods: {
    loadData() {
      $("#table").DataTable({
        serverSide: true,
        ajax: "/api/ppic/datatables/perakitan",
        columns: [
          {
            data: "DT_RowIndex",
            orderable: false,
            searchable: false,
          },
          {
            data: "nama",
          },
          {
            data: "jumlah",
          },
          {
            data: "tanggal_mulai",
          },
          {
            data: "tanggal_selesai",
          },
          {
            data: "progres",
          },
          {
            data: function (row) {
              return mixins.change_status(row["status"]);
            },
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