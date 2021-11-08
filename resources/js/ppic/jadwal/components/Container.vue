<script>
import Calendar from "./Calendar.vue";
import Chart from "./Chart.vue";

export default {
  props: {
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

    chooseHeader: function () {
      if (this.$store.state.state === "perencanaan") {
        if (this.$store.state.konfirmasi == 0) return "perencanaan";
      } else if (this.$store.state.state === "persetujuan") {
        if (this.$store.state.konfirmasi === 0) return "menunggu";
        else if (this.$store.state.konfirmasi == 1) return "perubahan";
        else if (this.$store.state.konfirmasi == 2) return "perencanaan";
      } else if (this.$store.state.state === "perubahan") {
        if (this.$store.state.konfirmasi == 0) return "menunggu";
        else if (this.$store.state.konfirmasi == 1) return "perencanaan";
        else if (this.$store.state.konfirmasi == 2) return "perubahan";
      }
    },
  },

  mounted: function () {
    this.$store.dispatch("updateJadwal", this.status);
  },
};
</script>

<template>
  <div>
    <div v-if="$store.state.user.divisi_id === 24">
      <div
        v-if="chooseHeader() === 'perencanaan'"
        class="alert alert-primary"
        role="alert"
      >
        <h4 class="alert-heading">
          <i class="fas fa-check"></i> Jadwal Dapat Diubah
        </h4>
        <hr />
        <p>
          Kirim permintaan persetujuan untuk meminta persetujuan dari manager
        </p>
      </div>
      <div
        v-if="chooseHeader() === 'perubahan'"
        class="alert alert-danger"
        role="alert"
      >
        <h4 class="alert-heading">
          <i class="fas fa-times"></i> Jadwal Tidak Dapat Diubah
        </h4>
        <hr />
        <p>
          Kirim permintaan perubahan jadwal agar dapat melakukan perubahan pada
          jadwal
        </p>
      </div>
      <div
        v-if="chooseHeader() === 'menunggu'"
        class="alert alert-warning"
        role="alert"
      >
        <h4 class="alert-heading">
          <i class="far fa-hourglass"></i> Menunggu Persetujuan
        </h4>
        <hr />
        <p>Menunggu manager untuk menyetujui permintaan yang dikirim</p>
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
            <Calendar v-if="isCalendar()" :status="status" />
          </div>
          <div :class="['tab-pane fade', { 'show active': isChart() }]">
            <Chart v-if="isChart()" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>