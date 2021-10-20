<script>
import Calendar from "./Calendar.vue";
import Chart from "./Chart.vue";
import Table from "./Table.vue";

export default {
  components: {
    Calendar,
    Table,
    Chart,
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
    <Calendar v-if="this.$store.state.view === 'calendar'" />
    <Chart v-if="this.$store.state.view === 'chart'" />
    <Table v-if="this.$store.state.view === 'table'" />
  </div>
</template>