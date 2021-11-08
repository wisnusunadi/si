<script>
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
import { data } from "./data_so";

export default {
  data: function () {
    return {
      show: false,
    };
  },

  methods: {
    handleClick: function () {
      $("#modal").modal("show");
    },

    showCard: function (show) {
      this.show = show;
    },

    isPart: function () {
      return this.view === "part";
    },

    isUnit: function () {
      return this.view === "unit";
    },

    changeView: function (view) {
      this.view = view;
    },
  },

  mounted: function () {
    $("#table-so").DataTable({
      pageLength: 5,
      lengthChange: false,
      ordering: false,
      info: false,
      data: data,
      columns: [
        {
          data: "No",
        },
        {
          data: "No SO",
        },
        {
          data: "Jenis",
        },
        {
          data: "Batas Kontrak",
        },
        {
          data: "status",
        },
        {
          data: function () {
            return `<button class="btn btn-outline-primary btn-sm">
                      <i class="fas fa-search"></i>
                    </button>`;
          },
        },
      ],
    });

    $("#table-detail").DataTable({
      pageLength: 3,
      lengthChange: false,
      ordering: false,
      info: false,
    });

    let vue = this;
    $("#table-so tbody").on("click", "button", function () {
      vue.showCard(true);
    });
  },
};
</script>

<template>
  <div>
    <div v-if="show" class="card card-info">
      <div class="card-header">
        <div class="card-title">Detail SO</div>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" @click="showCard(false)">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>

      <div class="card-body">
        <!-- info row -->
        <div class="row invoice-info">
          <div class="col-sm-6 invoice-col">
            Distributor: test <br />
            Instansi: test <br />
            No SO: 1234
          </div>
        </div>
        <div class="row">
          <div class="col-12 table-responsive">
            <table id="table-detail" class="table table-hover">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Produk</th>
                  <th>Jumlah</th>
                  <th>Detail</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Produk 1</td>
                  <td>10</td>
                  <td>
                    <button
                      class="btn btn-outline-primary btn-sm"
                      @click="handleClick"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Produk 2</td>
                  <td>10</td>
                  <td>
                    <button
                      class="btn btn-outline-primary btn-sm"
                      @click="handleClick"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Produk 3</td>
                  <td>10</td>
                  <td>
                    <button
                      class="btn btn-outline-primary btn-sm"
                      @click="handleClick"
                    >
                      <i class="fas fa-search"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="card card-primary">
      <div class="card-header">
        <h4>Daftar SO</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table
            id="table-so"
            class="table table-hover text-center"
            width="100%"
          >
            <thead style="text-align: center; font-size: 15px">
              <tr>
                <th>No</th>
                <th>No SO</th>
                <th>Jenis</th>
                <th>Batas Kontrak</th>
                <th>Status</th>
                <th>Detail</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-hover text-center">
              <thead>
                <tr>
                  <th>No Seri</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1234</td>
                </tr>
                <tr>
                  <td>2345</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>