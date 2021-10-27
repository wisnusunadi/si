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
      searching: false,
      lengthChange: false,
      ordering: false,
      info: false,
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
            return "<button class='btn btn-outline-primary btn-sm'><i class='fas fa-search' /></button>";
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
    <div class="card">
      <div class="card-body">
        <table
          id="table"
          class="table table-hover styled-table table-striped text-center"
        >
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Stok</th>
              <th>Detail</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Nomer Seri</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-hover text-center">
              <tbody>
                <tr v-for="data in data_stok" :key="data.id">
                  <td>{{ data.noseri }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>