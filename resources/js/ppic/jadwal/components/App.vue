<script>
import Calendar from "./calendar/App.vue";
import Chart from "./chart/App.vue";

export default {
  props: {
    user: String,
    status: String,
  },

  components: {
    Calendar,
    Chart,
  },

  data: function () {
    return {
      view: "calendar",
    };
  },

  methods: {
    changeView: function (view) {
      this.view = view;
    },

    isCalendar: function () {
      return this.view === "calendar";
    },

    isChart: function () {
      return this.view === "chart";
    },
  },

  mounted: function () {
    this.$store.commit("updateUser", JSON.parse(this.user));
    this.$store.commit("updateStatus", this.status);
    this.$store.dispatch("updateJadwal", this.status);
  },
};
</script>

<template>
  <div>
    <div v-if="$store.state.user.divisi_id === 24">
      <div v-if="this.status === 'penyusunan'">
        <div
          v-if="!this.$store.state.proses_konfirmasi"
          class="alert alert-info"
          role="alert"
        >
          <h4 class="alert-heading"><i class="fas fa-info"></i> Penyusunan</h4>
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
      <div v-if="this.status === 'pelaksanaan'">
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
            <Calendar />
          </div>
          <div :class="['tab-pane fade', { 'show active': isChart() }]">
            <Chart />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
