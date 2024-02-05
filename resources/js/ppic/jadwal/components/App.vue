<template>
  <div>
    <div v-if="isPpicUser()"></div>
  </div>
</template>

<script>
import calendar from "./calendar/App.vue";
import chart from "./Chart.vue";

export default {
  props: {
    user: String,
    status: String,
  },

  components: {
    calendar,
    chart,
  },

  data: function () {
    view: "calendar";
  },

  methods: {
    changeView: function (view) {
      this.view = view;
    },

    isCalendarView: function () {
      return this.view === "calendar";
    },

    isChartView: function () {
      return this.view === "chart";
    },

    isPpicUser: function () {
      return this.$store.state.user === 24;
    },

    isStatusPenyusunan: function () {
      return this.$store.state.status === "penyusunan";
    },
  },

  mounted: function () {
    this.$store.commit("updateUser", JSON.parse(this.auth));
    this.$store.commit("updateStatus", this.status);
  },
};
</script>