<script>
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
import axios from "axios";
// import axios from "axios";

export default {
  data: function () {
    return {
      jadwal_rencana: [],
      jadwal_pelaksanaan: [],
      checked_jadwal_rencana: [],
      checked_jadwal_pelaksanaan: [],
      view: "rencana",
      param_setuju: false,
      param_rencana: false,
    };
  },

  mounted: function () {
    axios({
      method: "get",
      url: "/api/ppic/schedule/penyusunan",
      params: {
        proses_konfirmasi: 1,
      },
    }).then((response) => {
      this.jadwal_rencana = response.data;
    });

    axios({
      method: "get",
      url: "/api/ppic/schedule/pelaksanaan",
      params: {
        proses_konfirmasi: 1,
      },
    }).then((response) => {
      this.jadwal_pelaksanaan = response.data;
    });

    EchoObj.private("test").listen("TestEvent", (response) => {
      this.$swal({
        icon: "success",
        text: "Message: " + response.message,
      });

      axios({
        method: "get",
        url: "/api/ppic/schedule/penyusunan",
        params: {
          proses_konfirmasi: 1,
        },
      }).then((response) => {
        this.jadwal_rencana = response.data;
        console.log(this.jadwal_rencana);
      });
    });
  },

  methods: {
    clickSetujuRencana: function () {
      this.param_setuju = true;
      this.param_rencana = true;
      $("#modal").modal("show");
    },

    clickTolakRencana: function () {
      this.param_setuju = false;
      this.param_rencana = true;
      $("#modal").modal("show");
    },

    clickSetujuPerubahan: function () {
      this.param_setuju = true;
      this.param_rencana = false;
      $("#modal").modal("show");
    },

    clickTolakPerubahan: function () {
      this.param_setuju = false;
      this.param_rencana = false;
      $("#modal").modal("show");
    },

    handleButtonYes: function () {
      $("#modal").modal("hide");

      axios.post("/api/ppic/update-event", {
        params: {
          event: this.checked_jadwal_rencana,
        },
      });
    },

    handleButtonNo: function () {
      $("#modal").modal("hide");
    },

    isRencana: function () {
      return this.view === "rencana";
    },

    isPerubahan: function () {
      return this.view === "perubahan";
    },

    changeView: function (view) {
      this.view = view;
    },
  },
};
</script>

<template>
  <div>
    <div class="card card-primary card-outline card-outline-tabs">
      <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs">
          <li class="nav-item" @click="changeView('rencana')">
            <a href="#chart-view" :class="['nav-link', { active: isRencana() }]"
              >Rencana</a
            >
          </li>
          <li class="nav-item" @click="changeView('perubahan')">
            <a
              href="#calendar-view"
              :class="['nav-link', { active: isPerubahan() }]"
              >Perubahan</a
            >
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content">
          <div :class="['tab-pane fade', { 'show active': isRencana() }]">
            <table
              id="table"
              class="table table-hover styled-table table-striped"
            >
              <thead>
                <tr>
                  <th>Produk</th>
                  <th>Jumlah</th>
                  <th>Tanggal Mulai</th>
                  <th>Tanggal Selesai</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in jadwal_rencana" :key="data.id">
                  <td>
                    <input
                      type="checkbox"
                      :value="data"
                      v-model="checked_jadwal_rencana"
                      :id="'data-' + data.id"
                    />
                    <label :for="'data-' + data.id">{{
                      data.produk.nama
                    }}</label>
                  </td>
                  <td>{{ data.jumlah }}</td>
                  <td>{{ data.tanggal_mulai }}</td>
                  <td>{{ data.tanggal_selesai }}</td>
                </tr>
              </tbody>
            </table>
            <div
              v-if="checked_jadwal_rencana.length > 0"
              class="d-flex justify-content-around"
            >
              <button class="btn btn-success" @click="clickSetujuRencana">
                Setuju
              </button>
              <button class="btn btn-danger" @click="clickTolakRencana">
                Tolak
              </button>
            </div>
          </div>
          <div :class="['tab-pane fade', { 'show active': isPerubahan() }]">
            <table
              id="table"
              class="table table-hover styled-table table-striped"
            >
              <thead>
                <tr>
                  <th>Produk</th>
                  <th>Jumlah</th>
                  <th>Tanggal Mulai</th>
                  <th>Tanggal Selesai</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in jadwal_pelaksanaan" :key="data.id">
                  <td>
                    <input
                      type="checkbox"
                      :value="data"
                      v-model="checked_jadwal_pelaksanaan"
                      :id="'data-' + data.id"
                    />
                    <label :for="'data-' + data.id">{{
                      data.produk.nama
                    }}</label>
                  </td>
                  <td>{{ data.jumlah }}</td>
                  <td>{{ data.tanggal_mulai }}</td>
                  <td>{{ data.tanggal_selesai }}</td>
                </tr>
              </tbody>
            </table>
            <div
              class="d-flex content-justify-around"
              v-if="checked_jadwal_pelaksanaan.length > 0"
            >
              <button class="btn btn-success" @click="clickSetujuRencana">
                Setuju
              </button>
              <button class="btn btn-danger" @click="clickTolakRencana">
                Tolak
              </button>
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
            <div v-if="!param_setuju" class="form-group">
              <label for="message-text" class="col-form-label">Komentar:</label>
              <textarea class="form-control" id="message-text"></textarea>
            </div>
            <table class="table table-hover text-center">
              <thead>
                <tr>
                  <th>Produk</th>
                  <th>Jumlah</th>
                </tr>
              </thead>
              <tbody v-if="isRencana()">
                <tr v-for="data in checked_jadwal_rencana" :key="data.id">
                  <td>{{ data.produk.nama }}</td>
                  <td>{{ data.jumlah }}</td>
                </tr>
              </tbody>
              <tbody v-if="isPerubahan()">
                <tr v-for="data in checked_jadwal_pelaksanaan" :key="data.id">
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