<template>
  <div>
    <h1 v-if="this.status === 'penyusunan'" class="title">
      Perencanaan Jadwal Perakitan
    </h1>
    <h1 v-if="this.status === 'pelaksanaan'" class="title">
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
        <li
          :class="{ 'is-active': view === 'calendar' }"
          @click="view = 'calendar'"
        >
          <a>
            <span class="icon is-small"
              ><i class="fas fa-calendar" aria-hidden="true"></i
            ></span>
            <span>Kalender</span>
          </a>
        </li>
        <li :class="{ 'is-active': view === 'chart' }" @click="view = 'chart'">
          <a>
            <span class="icon is-small"
              ><i class="fas fa-chart-bar" aria-hidden="true"></i
            ></span>
            <span>Chart</span>
          </a>
        </li>
        <li :class="{ 'is-active': view === 'table' }" @click="view = 'table'">
          <a>
            <span class="icon is-small"
              ><i class="fas fa-table" aria-hidden="true"></i
            ></span>
            <span>Tabel</span>
          </a>
        </li>
      </ul>
    </div>
    <template v-if="view === 'calendar'">
      <div class="columns">
        <div
          :class="[
            'column',
            this.$store.state.user.divisi_id == '24' ? 'is-9' : 'is-12',
          ]"
        >
          <div class="box">
            <Calendar :events="events" :status="status" />
          </div>
        </div>
        <template v-if="this.$store.state.user.divisi_id == '24'">
          <div class="column is-3">
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
          </div>
        </template>
      </div>
      <div class="columns">
        <div class="column is-12">
          <div class="box">
            <div class="table-container">
              <table class="table is-fullwidth">
                <thead>
                  <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in sorting_jadwal" :key="item.id">
                    <td>
                      {{ `${item.produk.produk.nama} ${item.produk.nama}` }}
                    </td>
                    <td>{{ item.jumlah }}</td>
                    <td>{{ item.tanggal_mulai }}</td>
                    <td>{{ item.tanggal_selesai }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-if="view === 'chart'"> 
      <template
        v-if="
          $store.state.status === 'pelaksanaan' &&
          this.series_rencana.length > 0
        "
      >
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
    </template>

    <template v-if="view === 'table'">
      <div class="columns">
        <div class="column is-12">
          <div class="box">
            <Table :events="events" :status="status" />
          </div>
        </div>
      </div>
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
import Table from "../components/Table.vue";
import VueApexCharts from "vue-apexcharts";

import mixins from "../mixins";

export default {
  name: "Jadwal",

  props: {
    status: {
      type: String,
      required: true,
    },

    jadwal_rencana: {
      type: Array,
    },
  },

  components: {
    Calendar,
    apexchart: VueApexCharts,
    Table,
  },

  data() {
    return {
      view: "table",

      options: {
        plotOptions: {
          bar: {
            horizontal: true,
          },
        },
        xaxis: {
          type: "datetime",
        },
      },

      showModal: false,
      data_komentar: [],
    };
  },

  async created() {
    this.$store.commit("setIsLoading", true);

    await axios
      .get("/api/ppic/data/komentar", {
        params: {
          status: this.status,
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

  methods: {
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
      if (this.jadwal_rencana != undefined) {
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
      }
      return [];
    },

    events() {
      let data = mixins.convertJadwal(this.$store.state.jadwal);
      return data;
    },
  },
};
</script>