<script>
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";

export default {
  data: function () {
    return {
      filterData: [],
    };
  },

  methods: {
    handleClick: function () {
      $("#modal").modal("show");
    },

    filter: function (status) {
      if (this.filterData.length === 0) return true;

      for (let i = 0; i < this.filterData.length; i++) {
        if (status === this.filterData[i]) return true;
      }
      return false;
    },
  },

  mounted: function () {
    $("#perakitan-table").DataTable({
      ajax: "/api/ppic/schedule/datatables",
      processing: true,
      serverSide: true,
      pageLength: 8,
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
          data: function (row) {
            return `${row.produk.produk.tipe} ${row.produk.nama}`;
          },
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
          data: function (row) {
            let val;
            if (row.status.id == 1) val = 0;
            else if (row.status.id == 2) val = 50;
            else if (row.status.id == 3) val = 100;

            return `<div class="progress progress-sm">
                    <div
                      class="progress-bar bg-green"
                      role="progressbar"
                      aria-valuenow="${val}"
                      aria-valuemin="0"
                      aria-valuemax="100"
                      style="width: ${val}%"
                    ></div>
                  </div>
                  <small> ${val}% Complete </small>`;
          },
        },
        {
          data: function (row) {
            if (row.status.id === 1)
              return `<span class="badge badge-pill badge-warning">Perencanaan</span>`;
            if (row.status.id === 2)
              return `<span class="badge badge-pill badge-info">Pelaksanaan</span>`;
            if (row.status.id === 3)
              return `<span class="badge badge-pill badge-success">Selesai</span>`;
          },
        },
      ],
    });
  },
};
</script>

<template>
  <div>
    <div>
      <div class="dropdown show">
        <button
          class="btn btn-outline-primary dropdown-toggle mb-3"
          data-toggle="dropdown"
        >
          Filter
        </button>

        <div class="dropdown-menu p-3">
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value="perencanaan"
              id="defaultCheck1"
              v-model="filterData"
            />
            <label class="form-check-label" for="defaultCheck1">
              Perencanaan
            </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value="pelaksanaan"
              id="defaultCheck2"
              v-model="filterData"
            />
            <label class="form-check-label" for="defaultCheck2">
              Pelaksanaan
            </label>
          </div>
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              value="selesai"
              id="defaultCheck3"
              v-model="filterData"
            />
            <label class="form-check-label" for="defaultCheck3">
              Selesai
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table
            id="perakitan-table"
            class="table table-hover text-center"
            width="100%"
          >
            <thead style="text-align: center; font-size: 15px">
              <tr align="center">
                <th rowspan="2">No</th>
                <th rowspan="2">Nama Produk</th>
                <th rowspan="2">Jumlah</th>
                <th colspan="2">Waktu</th>
                <th rowspan="2">Progres</th>
                <th rowspan="2">Status</th>
              </tr>
              <tr>
                <th>Mulai</th>
                <th>Selesai</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>