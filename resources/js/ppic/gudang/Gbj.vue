<script>
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
// import axios from "axios";

export default {
  data: function () {
    return {
      data_stok: [],
    };
  },

  mounted() {
    let table = $("#table").DataTable({
      ajax: "/api/ppic/get-gbj-datatable",
      processing: true,
      serverSide: true,
      columns: [
        {
          data: "DT_RowIndex",
          orderable: false,
          searchable: false,
        },
        {
          data: "produk.nama",
        },
        {
          data: "stok",
        },
        {
          data: function () {
            return "<button class='btn btn-primary'><i class='fas fa-search' /></button>";
          },
        },
      ],
    });

    let vue = this;

    $("#table tbody").on("click", "button", function () {
      var data = table.row($(this).parents("tr")).data();
      vue.data_stok = data.noseri;
      $("#modal").modal("show");
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
          <th>Stok</th>
          <th>Detail</th>
        </tr>
      </thead>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Nomer Seri</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul v-for="data in data_stok" :key="data.id">
              <li>{{ data.noseri }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>