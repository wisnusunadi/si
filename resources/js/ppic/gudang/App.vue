<script>
import $ from "jquery";
import "datatables.net-bs4/js/dataTables.bootstrap4.min";
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";

export default {
  mounted() {
    $("#table").DataTable({
      ajax: "http://localhost:8000/api/ppic/part",
      columns: [
        {
          data: "DT_RowIndex",
          orderable: false,
          searchable: false,
        },
        {
          data: "kode",
        },
        {
          data: "nama",
        },
        {
          data: "jumlah",
        },
        {
          data: "layout",
        },
      ],
    });
  },
};
</script>

<template>
  <div>
    <div v-if="this.$route.path === '/ppic/gudang/gbmp'">
      <table id="table" class="table table-hover styled-table table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Jumlah</th>
            <th>Layout</th>
          </tr>
        </thead>
      </table>
    </div>
    <div v-else>
      <p>Data not available</p>
    </div>
  </div>
</template>