<script>
import Calendar from "./Calendar.vue";
import Chart from "./Chart.vue";
import Table from "../../../manager/Table.vue";

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

  created: function () {
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
    <div
      v-if="this.$store.state.konfirmasi"
      class="alert alert-warning"
      role="alert"
    >
      <i class="fas fa-bell"></i> Menunggu persetujuan dari manager
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