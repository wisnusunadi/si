<script>
import $ from "jquery";
import "datatables.net-bs4/js/dataTables.bootstrap4.min";
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";

export default {
  mounted() {
    $("#table").DataTable({
      ajax: "http://localhost:8000/api/ppic/bppb/" + this.$route.params.status,
      columns: [
        {
          data: "DT_RowIndex",
          orderable: false,
          searchable: false,
        },
        {
          data: "detail_produk.nama",
        },
        {
          data: "no_bppb",
        },
        {
          data: "tanggal_bppb",
        },
        {
          data: "jumlah",
        },
      ],
    });
  },
};
</script>

<template>
  <div>
    <table id="table" class="table table-hover styled-table table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>No Bppb</th>
          <th>Tanggal</th>
          <th>Jumlah</th>
        </tr>
      </thead>
    </table>
  </div>
</template>