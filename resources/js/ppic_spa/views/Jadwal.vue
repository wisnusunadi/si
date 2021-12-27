<template>
  <div>
    <h1 v-if="this.$store.state.status === 'penyusunan'" class="title">
      Perencanaan Jadwal Perakitan
    </h1>
    <h1 v-if="this.$store.state.status === 'pelaksanaan'" class="title">
      Pelaksanaan Jadwal Perakitan
    </h1>
    <div
      v-if="this.$store.state.state_ppic === 'pembuatan'"
      class="notification is-primary"
    >
      Penyusunan jadwal perakitan
    </div>
    <div
      v-else-if="this.$store.state.state_ppic === 'menunggu'"
      class="notification is-warning"
    >
      Menunggu persetujuan manajer
    </div>
    <div
      v-else-if="this.$store.state.state_ppic === 'disetujui'"
      class="notification is-success"
    >
      Jadwal telah disetujui
    </div>
    <div
      v-else-if="this.$store.state.state_ppic === 'revisi'"
      class="notification is-danger"
    >
      Jadwal harus direvisi
    </div>

    <div class="tabs is-centered">
      <ul>
        <li :class="{ 'is-active': isCalendar }" @click="isCalendar = true">
          <a>
            <span class="icon is-small"
              ><i class="fas fa-calendar" aria-hidden="true"></i
            ></span>
            <span>Kalender</span>
          </a>
        </li>
        <li :class="{ 'is-active': !isCalendar }" @click="isCalendar = false">
          <a>
            <span class="icon is-small"
              ><i class="fas fa-table" aria-hidden="true"></i
            ></span>
            <span>Tabel</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="columns is-multiline" :class="{ 'is-hidden': !isCalendar }">
      <div class="column is-9">
        <div class="box">
          <Calendar />
        </div>
      </div>
      <div class="column is-3">
        <template v-if="this.$store.state.user.divisi_id == '24'">
          <div class="buttons">
            <button
              v-if="
                this.$store.state.state_ppic === 'pembuatan' ||
                this.$store.state.state_ppic === 'revisi'
              "
              class="button is-fullwidth"
              :class="{
                'is-loading': this.$store.state.isLoading,
                'is-primary': this.$store.state.state_ppic === 'pembuatan',
                'is-danger': this.$store.state.state_ppic === 'revisi',
              }"
              @click="sendEvent('persetujuan')"
            >
              Kirim
            </button>
            <button
              v-if="this.$store.state.state_ppic === 'disetujui'"
              class="button is-success is-fullwidth"
              :class="{ 'is-loading': this.$store.state.isLoading }"
              @click="sendEvent('perubahan')"
            >
              Minta Perubahan
            </button>
          </div>
          <article class="message is-dark">
            <div class="message-header">
              <p>Pesan</p>
              <button class="delete" aria-label="delete"></button>
            </div>
            <div class="message-body">
              {{
                this.data_komentar.length > 0
                  ? this.data_komentar[0].komentar
                  : ""
              }}
              <div class="is-flex is-justify-content-flex-end">
                <button class="button is-circle" @click="detailMessage">
                  <i class="fas fa-envelope"></i>
                </button>
              </div>
            </div>
          </article>
        </template>
        <div class="box">
          <div class="table-container">
            <table class="table">
              <thead>
                <tr>
                  <th>Produk</th>
                  <th>Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in sorting_jadwal" :key="item.id">
                  <td>
                    {{ `${item.produk.produk.nama} ${item.produk.nama}` }}
                  </td>
                  <td>{{ item.jumlah }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <template v-if="!isCalendar">
      <div class="box">
        <h1 v-if="$store.state.status === 'pelaksanaan'">Pelaksanaan</h1>
        <div v-if="this.$store.state.jadwal.length == 0" class="p-3">
          Data Kosong
        </div>
        <apexchart
          v-else
          type="rangeBar"
          :options="options"
          :height="this.series[0].data.length * 25"
          :series="series"
        ></apexchart>
      </div>
      <template v-if="$store.state.status === 'pelaksanaan'">
        <div class="box">
          <h1>Rencana</h1>
          <apexchart
            type="rangeBar"
            :options="options"
            :height="this.series_rencana[0].data.length * 25"
            :series="series_rencana"
          ></apexchart>
        </div>
      </template>
    </template>

    <!-- modal -->
    <div v-if="showModal" class="modal" :class="{ 'is-active': showModal }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Komentar</p>
          <button
            class="delete"
            aria-label="close"
            @click="showModal = !showModal"
          ></button>
        </header>
        <section class="modal-card-body">
          <table class="table is-fullwidth has-text-centered">
            <thead>
              <tr>
                <th>hasil</th>
                <th>komentar</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in data_komentar" :key="item.id">
                <td>{{ item.hasil ? "disetujui" : "ditolak" }}</td>
                <td>{{ item.komentar }}</td>
              </tr>
            </tbody>
          </table>
        </section>
        <footer class="modal-card-foot"></footer>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Calendar from "../components/Calendar.vue";
import VueApexCharts from "vue-apexcharts";

export default {
  name: "Jadwal",
  components: {
    Calendar,
    apexchart: VueApexCharts,
  },

  data() {
    return {
      isCalendar: true,
      options: {
        plotOptions: {
          bar: {
            horizontal: true,
          },
        },
        xaxis: {
          type: "datetime",
        },
        // chart: {
        //   height: 1000,
        //   width: 1000,
        // },
      },

      jadwal_rencana: [],
      showModal: false,
      data_komentar: "",
    };
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);
      await axios.get("/api/ppic/data/rencana_perakitan").then((response) => {
        this.jadwal_rencana = response.data;
      });

      await axios
        .get("/api/ppic/data/komentar", {
          params: {
            status: this.$store.state.status,
          },
        })
        .then((response) => {
          this.data_komentar = response.data;
        })
        .catch((error) => {
          console.log("error to get data komentar");
          console.log(error);
        });

      this.$store.commit("setIsLoading", false);
    },

    async sendEvent(state) {
      this.$store.commit("setIsLoading", true);
      await axios
        .post("/api/ppic/update/perakitans/" + this.$store.state.status, {
          state: state,
          konfirmasi: 0,
        })
        .then((response) => {
          this.$store.commit("setJadwal", response.data);
        })
        .catch((error) => {
          console.log("error update data perakitan");
          console.log(error);
        });

      await axios
        .post("/api/ppic/create/komentar", {
          tanggal_permintaan: new Date(),
          state: this.$store.state.state,
          status: this.$store.state.status,
        })
        .catch((error) => {
          console.log("error create komentar");
          console.log(error);
        });

      this.$store.commit("setIsLoading", false);
    },

    detailMessage() {
      this.showModal = true;
    },
  },

  mounted() {
    this.loadData();
  },

  computed: {
    sorting_jadwal() {
      return this.$store.state.jadwal.sort(
        (a, b) => new Date(a.tanggal_mulai) - new Date(b.tanggal_mulai)
      );
    },
    series: function () {
      return [
        {
          data: this.$store.state.jadwal.map((event) => ({
            x: `${event.produk.produk.nama} ${event.produk.nama}`,
            y: [
              new Date(event.tanggal_mulai).getTime(),
              new Date(event.tanggal_selesai).getTime(),
            ],
          })),
        },
      ];
    },

    series_rencana: function () {
      return [
        {
          data: this.jadwal_rencana.map((data) => ({
            x: `${data.jadwal_perakitan.produk.produk.nama} ${data.jadwal_perakitan.produk.nama}`,
            y: [
              new Date(data.tanggal_mulai).getTime(),
              new Date(data.tanggal_selesai).getTime(),
            ],
          })),
        },
      ];
    },

    state: function () {
      return this.$store.state.status;
    },
  },

  watch: {
    state(newVal, oldVal) {
      axios
        .get("/api/ppic/data/komentar", {
          params: {
            status: newVal,
          },
        })
        .then((response) => {
          this.data_komentar = response.data;
        })
        .catch((error) => {
          console.log("error to get data komentar");
          console.log(error);
        });
    },
  },
};
</script>