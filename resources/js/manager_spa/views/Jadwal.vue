<template>
  <div>
    <h1 v-if="this.$store.state.status === 'penyusunan'" class="title">
      Perencanaan Jadwal Perakitan
    </h1>
    <h1 v-if="this.$store.state.status === 'pelaksanaan'" class="title">
      Pelaksanaan Jadwal Perakitan
    </h1>

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
        <article class="message is-dark">
          <div class="message-header">
            <p>Pesan</p>
            <button class="delete" aria-label="delete"></button>
          </div>
          <div class="message-body">
            Keep fighting
            <div class="is-flex is-justify-content-flex-end">
              <button class="button is-circle">
                <i class="fas fa-envelope"></i>
              </button>
            </div>
          </div>
        </article>
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
    <div class="box" :class="{ 'is-hidden': isCalendar }">
      <div v-if="this.$store.state.jadwal.length == 0" class="p-3">
        Data Kosong
      </div>
      <apexchart
        v-else
        type="rangeBar"
        height="200"
        :options="options"
        :series="series"
      ></apexchart>
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
      },
    };
  },

  methods: {
    async sendEvent(state) {
      this.$store.commit("setIsLoading", true);
      await axios
        .post("/api/ppic/update-many-event/" + this.$store.state.status, {
          state: state,
          konfirmasi: 0,
        })
        .then((response) => {
          this.$store.commit("setJadwal", response.data);
          console.log(response.data);
        });
      this.$store.commit("setIsLoading", false);
    },
  },

  computed: {
    sorting_jadwal() {
      console.log("change sorting");
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
  },
};
</script>