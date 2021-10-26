<script>
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
// import axios from "axios";

export default {
  data: function () {
    return {
      jadwal: [],
      checkedData: [],
    };
  },

  mounted: function () {
    axios({
      method: "get",
      url: "/api/ppic/schedule/penyusunan",
      data: {
        proses_konfirmasi: 1,
      },
    }).then((response) => {
      this.jadwal = response.data;
    });
  },

  methods: {
    clickSetuju: function () {
      $("#modal").modal("show");
    },

    handleButtonYes: function () {
      $("#modal").modal("hide");
    },

    handleButtonNo: function () {
      $("#modal").modal("hide");
    },
  },
};
</script>

<template>
  <div>
    <table id="table" class="table table-hover styled-table table-striped">
      <thead>
        <tr>
          <th>Produk</th>
          <th>Jumlah</th>
          <th>Tanggal Mulai</th>
          <th>Tanggal Selesai</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="data in jadwal" :key="data.id">
          <td>
            <input type="checkbox" :value="data" v-model="checkedData" />
            {{ data.produk.nama }}
          </td>
          <td>{{ data.jumlah }}</td>
          <td>{{ data.tanggal_mulai }}</td>
          <td>{{ data.tanggal_selesai }}</td>
        </tr>
      </tbody>
    </table>
    <div class="btn-group btn-block">
      <button class="btn btn-success" @click="clickSetuju">Setuju</button>
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
                  <th>Produk</th>
                  <th>Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in checkedData" :key="data.id">
                  <td>{{ data.produk.nama }}</td>
                  <td>{{ data.jumlah }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button class="btn btn-primary" @click="handleButtonYes">
              Yes
            </button>
            <button class="btn btn-danger" @click="handleButtonNo">No</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>