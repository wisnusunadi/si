<script>
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
import { unit_data, part_data } from "./data_gk_unit";

export default {
  data: function () {
    return {
      view: "",
    };
  },

  methods: {
    handleClick: function () {
      $("#modal").modal("show");
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
    this.view = "unit";
    console.log(unit_data);
    $("#table-unit").DataTable({
      pageLength: 8,
      lengthChange: false,
      ordering: false,
      info: false,
      data: unit_data,
      columns: [
        {
          data: "No",
        },
        {
          data: "Kode Unit",
        },
        {
          data: function (row) {
            let color;
            let stok = Number(row.Stok);
            if (stok < 5 && stok >= 3) color = "yellow";
            else if (stok < 10 && stok >= 5) color = "orange";
            else if (stok >= 10) color = "red";
            return `<span style="color: ${color};">${stok}</span>`;
          },
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

    $("#table-part").DataTable({
      pageLength: 8,
      lengthChange: false,
      ordering: false,
      info: false,
      data: part_data,
      columns: [
        {
          data: "No",
        },
        {
          data: "Kode Unit",
        },
        {
          data: function (row) {
            let color;
            let stok = Number(row.Stok);
            if (stok < 5 && stok >= 3) color = "yellow";
            else if (stok < 10 && stok >= 5) color = "orange";
            else if (stok >= 10) color = "red";
            return `<span style="color: ${color};">${stok}</span>`;
          },
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

    let vue = this;
    $("#table-unit tbody, #table-part tbody").on(
      "click",
      "button",
      function () {
        vue.handleClick();
      }
    );
  },
};
</script>

<template>
  <div>
    <div class="card card-primary card-outline card-outline-tabs">
      <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs">
          <li class="nav-item" @click="changeView('unit')">
            <a href="#chart-view" :class="['nav-link', { active: isUnit() }]"
              >Unit</a
            >
          </li>
          <li class="nav-item" @click="changeView('part')">
            <a href="#calendar-view" :class="['nav-link', { active: isPart() }]"
              >Part</a
            >
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content">
          <div :class="['tab-pane fade', { 'show active': isUnit() }]">
            <div class="table-responsive">
              <table
                id="table-unit"
                class="table table-hover text-center"
                width="100%"
              >
                <thead style="text-align: center; font-size: 15px">
                  <tr>
                    <th>No</th>
                    <th>Kode Unit</th>
                    <th>Stok</th>
                    <th>Detail</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
          <div :class="['tab-pane fade', { 'show active': isPart() }]">
            <div class="table-responsive">
              <div class="table-responsive">
                <table
                  id="table-part"
                  class="table table-hover text-center"
                  width="100%"
                >
                  <thead style="text-align: center; font-size: 15px">
                    <tr>
                      <th>No</th>
                      <th>Kode Part</th>
                      <th>Stok</th>
                      <th>Detail</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Detail</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table table-hover text-center">
              <thead>
                <tr>
                  <th>No Seri</th>
                  <th>Deskripsi Kerusakan</th>
                  <th>Tingkat Kerusakan</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1234</td>
                  <td>Rusak parah</td>
                  <td>s class</td>
                </tr>
                <tr>
                  <td>2345</td>
                  <td>Rusak sedang</td>
                  <td>a class</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>