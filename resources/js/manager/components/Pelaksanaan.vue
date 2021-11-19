<script>
import axios from "axios";

export default {
  data: function () {
    return {
      jadwal: [],
      acc_jadwal: [],
      all_acc_jadwal: false,
      reject_jadwal: [],
      all_reject_jadwal: false,
      state: 0,
      konfirmasi: false,
      flag_setuju: false,
      komentar: "",
    };
  },

  methods: {
    selectAllSetuju: function () {
      this.reject_jadwal = [];
      this.acc_jadwal = [];
      this.all_reject_jadwal = false;
      if (!this.all_acc_jadwal) {
        for (let i = 0; i < this.jadwal.length; i++) {
          this.acc_jadwal.push(this.jadwal[i]);
        }
      }
    },

    selectAllTolak: function () {
      this.reject_jadwal = [];
      this.acc_jadwal = [];
      this.all_acc_jadwal = false;
      if (!this.all_reject_jadwal) {
        for (let i = 0; i < this.jadwal.length; i++) {
          this.reject_jadwal.push(this.jadwal[i]);
        }
      }
    },

    accCheckbox: function (event) {
      this.reject_jadwal = this.reject_jadwal.filter(
        (item) => item !== event.target._value
      );
    },

    rejectCheckbox: function (event) {
      this.acc_jadwal = this.acc_jadwal.filter(
        (item) => item !== event.target._value
      );
    },

    handleSetuju: function () {
      let element = this.$refs.modal;
      $(element).modal("show");
      this.flag_setuju = true;
    },

    handleTolak: function () {
      let element = this.$refs.modal;
      $(element).modal("show");
      this.flag_setuju = false;
    },

    handleButtonYes: function () {
      let element = this.$refs.modal;
      $(element).modal("hide");

      axios
        .post("/api/ppic/add-komentar", {
          state: this.state,
          status: 2,
          komentar: this.komentar,
        })
        .then((response) => {
          console.log("add komentar success");
          console.log(response);
          this.komentar = "";
        })
        .catch((err) => {
          console.log("error add komentar");
          console.log(err);
        });

      if (this.state === 2) {
        let state_send;
        if (this.reject_jadwal.length > 0) state_send = 1;
        else state_send = 2;

        axios
          .post("/api/ppic/update-event", {
            acc: this.acc_jadwal,
            reject: this.reject_jadwal,
            status: 2,
            state: state_send,
          })
          .then((response) => {
            console.log("update event success");
            console.log(response.data);
            this.acc_jadwal = [];
            this.reject_jadwal = [];
            this.all_acc_jadwal = false;
            this.all_reject_jadwal = false;
          })
          .catch((err) => {
            console.log("error update event");
            console.log(err);
          });
      } else if (this.state === 3) {
        let state_send, konfirmasi_send;
        if (this.flag_setuju) {
          state_send = 1;
          konfirmasi_send = 0;
        } else {
          state_send = 2;
          konfirmasi_send = 1;
        }

        axios
          .post("/api/ppic/update-event", {
            status: 2,
            state: state_send,
            konfirmasi: konfirmasi_send,
          })
          .then((response) => {
            console.log("update event success");
            console.log(response.data);
            this.acc_jadwal = [];
            this.reject_jadwal = [];
            this.all_acc_jadwal = false;
            this.all_reject_jadwal = false;
          })
          .catch((err) => {
            console.log("error update event");
            console.log(err);
          });
      }
    },

    handleButtonNo: function () {
      let element = this.$refs.modal;
      $(element).modal("hide");
    },

    updateJadwal: function () {
      axios.get("/api/ppic/schedule/pelaksanaan").then((response) => {
        this.jadwal = response.data;
        this.konfirmasi = false;
        if (this.jadwal.length > 0) {
          this.state = this.jadwal[0].state.id;
          for (let i = 0; i < this.jadwal.length; i++) {
            if (this.jadwal[i].konfirmasi !== 0) {
              this.konfirmasi = true;
              break;
            }
          }
        }
      });
    },

    showTabel: function () {
      if (this.jadwal.length === 0) return false;

      if (this.konfirmasi === false && this.state !== 1) return true;
      else return false;
    },
  },

  mounted: function () {
    this.updateJadwal();

    EchoObj.private("test").listen("TestEvent", (e) => {
      console.log("event broadcast");
      this.updateJadwal();
    });
  },
};
</script>

<template>
  <div>
    <table
      v-if="showTabel()"
      id="table"
      class="table table-hover styled-table text-center"
    >
      <thead v-if="state === 2">
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
              v-model="all_acc_jadwal"
              @click="selectAllSetuju"
            />
            Setuju
          </th>
          <th>
            <input
              type="checkbox"
              v-model="all_reject_jadwal"
              @click="selectAllTolak"
            />
            Tolak
          </th>
        </tr>
      </thead>
      <thead v-if="state === 3">
        <tr>
          <th>Produk</th>
          <th>Jumlah</th>
          <th>Tanggal Mulai</th>
          <th>Tanggal Selesai</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="data in jadwal" :key="data.id">
          <td>{{ `${data.produk.produk.tipe} ${data.produk.nama}` }}</td>
          <td>{{ data.jumlah }}</td>
          <td>{{ data.tanggal_mulai }}</td>
          <td>{{ data.tanggal_selesai }}</td>
          <td v-if="state === 2">
            <input
              type="checkbox"
              :value="data"
              v-model="acc_jadwal"
              :id="'acc-data-' + data.id"
              @click="accCheckbox"
            />
          </td>
          <td v-if="state === 2">
            <input
              type="checkbox"
              :value="data"
              v-model="reject_jadwal"
              :id="'reject-data-' + data.id"
              @click="rejectCheckbox"
            />
          </td>
        </tr>
      </tbody>
    </table>
    <div v-else class="text-center">Data kosong</div>
    <div v-if="jadwal.length > 0" class="d-flex justify-content-end">
      <div v-if="state === 2">
        <button
          v-if="acc_jadwal.length + reject_jadwal.length === jadwal.length"
          class="btn btn-success"
          @click="handleSetuju"
        >
          Kirim
        </button>
      </div>
      <div v-if="state === 3">
        <button class="btn btn-success" @click="handleSetuju">Setuju</button>
        <button class="btn btn-danger" @click="handleTolak">Tolak</button>
      </div>
    </div>

    <div class="modal fade" ref="modal">
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
              <textarea
                class="form-control"
                id="message-text"
                v-model="komentar"
              ></textarea>
            </div>
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