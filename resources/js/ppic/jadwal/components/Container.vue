<script>
import Calendar from "./Calendar.vue";
import Chart from "./Chart.vue";
import Table from "../../../manager/App.vue";

export default {
  components: {
    Calendar,
    Table,
    Chart,
  },

  methods: {
    changeView: function (view) {
      this.$store.commit("changeView", view);
      console.log(this.$store.state.view);
    },

    isCalendar: function () {
      return this.$store.state.view === "calendar";
    },

    isChart: function () {
      return this.$store.state.view === "chart";
    },
  },

  mounted: function () {
    axios
      .get("/api/ppic/schedule/" + this.$route.params.status)
      .then((response) => {
        this.$store.commit("updateJadwal", response.data);
      });
  },
};
</script>

<template>
  <div>
    <div v-if="$store.state.user.divisi_id === 24">
      <div v-if="this.$route.params.status === 'penyusunan'">
        <div
          v-if="!this.$store.state.proses_konfirmasi"
          class="alert alert-info"
          role="alert"
        >
          <h4 class="alert-heading">Penyusunan</h4>
          <hr />
          <p>
            Apabila jadwal telah selesai dibuat, kirim permintaan pada manager
          </p>
        </div>
        <div v-else class="alert alert-danger" role="alert">
          <h4 class="alert-heading">Menunggu Persetujuan</h4>
          <hr />
          <p>Menunggu manager untuk menyetujui rencana jadwal perakitan</p>
        </div>
      </div>
      <div v-if="this.$route.params.status === 'pelaksanaan'">
        <div
          v-if="this.$store.state.proses_konfirmasi"
          class="alert alert-danger"
          role="alert"
        >
          <h4 class="alert-heading">Menunggu Persetujuan</h4>
          <hr />
          <p>Menunggu manager untuk menyetujui perubahan jadwal perakitan</p>
        </div>
      </div>
    </div>

    <div class="card card-primary card-outline card-outline-tabs">
      <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs">
          <li class="nav-item" @click="changeView('calendar')">
            <a
              href="#calendar-view"
              :class="['nav-link', { active: isCalendar() }]"
              >Kalender</a
            >
          </li>
          <li class="nav-item" @click="changeView('chart')">
            <a href="#chart-view" :class="['nav-link', { active: isChart() }]"
              >Chart</a
            >
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content">
          <div :class="['tab-pane fade', { 'show active': isCalendar() }]">
            <Calendar v-if="isCalendar()" />
          </div>
          <div :class="['tab-pane fade', { 'show active': isChart() }]">
            <Chart v-if="isChart()" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>