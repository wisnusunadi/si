<script>
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
// import axios from "axios";

export default {
  data: function () {
    return {
      jadwal_rencana: [],
      jadwal_pelaksanaan: [],

      acc_checked_jadwal_rencana: [],
      all_acc_rencana: false,
      reject_checked_jadwal_rencana: [],
      all_reject_rencana: false,

      checked_jadwal_pelaksanaan: [],
      view: "rencana",
      param_setuju: false,
      param_rencana: false,

      picked: null,
    };
  },

  mounted: function () {
    axios({
      method: "get",
      url: "/api/ppic/schedule/penyusunan",
      params: {
        state: "persetujuan",
        konfirmasi: 0,
      },
    }).then((response) => {
      this.jadwal_rencana = response.data;
    });

    axios({
      method: "get",
      url: "/api/ppic/schedule/pelaksanaan",
      params: {
        state: "perubahan",
        konfirmasi: 0,
      },
    }).then((response) => {
      this.jadwal_pelaksanaan = response.data;
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

    selectAllSetuju: function () {
      this.reject_checked_jadwal_rencana = [];
      this.acc_checked_jadwal_rencana = [];
      if (!this.all_acc_rencana) {
        for (let i = 0; i < this.jadwal_rencana.length; i++) {
          this.acc_checked_jadwal_rencana.push(this.jadwal_rencana[i]);
        }
      }
    },

    selectAllTolak: function () {
      this.reject_checked_jadwal_rencana = [];
      this.acc_checked_jadwal_rencana = [];
      if (!this.all_reject_rencana) {
        for (let i = 0; i < this.jadwal_rencana.length; i++) {
          this.reject_checked_jadwal_rencana.push(this.jadwal_rencana[i]);
        }
      }
    },

    accCheckbox: function (event) {
      console.log(event);
      this.reject_checked_jadwal_rencana =
        this.reject_checked_jadwal_rencana.filter(
          (item) => item !== event.target._value
        );
    },

    rejectCheckbox: function (event) {
      this.acc_checked_jadwal_rencana = this.acc_checked_jadwal_rencana.filter(
        (item) => item !== event.target._value
      );
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
              class="table table-hover styled-table text-center"
            >
              <thead>
                <tr>
                  <th rowspan="2">Produk</th>
                  <th rowspan="2">Jumlah</th>
                  <th rowspan="2">Tanggal Mulai</th>
                  <th rowspan="2">Tanggal Selesai</th>
                  <th colspan="2">Aksi</th>
                </tr>
                <tr>
                  <th>
                    <input
                      type="checkbox"
                      v-model="all_acc_rencana"
                      @click="selectAllSetuju"
                    />
                    Setuju
                  </th>
                  <th>
                    <input
                      type="checkbox"
                      v-model="all_reject_rencana"
                      @click="selectAllTolak"
                    />
                    Tolak
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in jadwal_rencana" :key="data.id">
                  <td>{{ data.produk.nama }}</td>
                  <td>{{ data.jumlah }}</td>
                  <td>{{ data.tanggal_mulai }}</td>
                  <td>{{ data.tanggal_selesai }}</td>
                  <td>
                    <input
                      type="checkbox"
                      :value="data"
                      v-model="acc_checked_jadwal_rencana"
                      :id="'acc-data-' + data.id"
                      @click="accCheckbox"
                    />
                  </td>
                  <td>
                    <input
                      type="checkbox"
                      :value="data"
                      v-model="reject_checked_jadwal_rencana"
                      :id="'reject-data-' + data.id"
                      @click="rejectCheckbox"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
            <div
              v-if="
                acc_checked_jadwal_rencana.length +
                  reject_checked_jadwal_rencana.length ===
                jadwal_rencana.length
              "
              class="btn-group btn-block"
            >
              <button class="btn btn-success" @click="clickSetujuRencana">
                Kirim
              </button>
              <!-- <button class="btn btn-danger" @click="clickTolakRencana">
                Tolak
              </button> -->
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
                  <td>{{ data.produk.nama }}</td>
                  <td>{{ data.jumlah }}</td>
                  <td>{{ data.tanggal_mulai }}</td>
                  <td>{{ data.tanggal_selesai }}</td>
                </tr>
              </tbody>
            </table>
            <div
              v-if="jadwal_pelaksanaan.length > 0"
              class="btn-group btn-block"
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
            <h4>Komentar</h4>
            <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <textarea class="form-control" id="message-text"></textarea>
            </div>
            <!-- <table class="table table-hover text-center">
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
            </table> -->
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