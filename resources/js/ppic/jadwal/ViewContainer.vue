<script>
import Calendar from "./Calendar.vue";
import Table from "./Table.vue";

export default {
  components: {
    Calendar,
    Table,
  },

  created: function () {
    console.log("ViewContainer");
    console.log(this.$route);
    axios
      .get(
        "http://localhost:8000/api/ppic/schedule/" + this.$route.params.status
      )
      .then((response) => {
        this.$store.commit("updateJadwal", response.data);
      });
  },
};
</script>

<template>
  <div>
    <Calendar v-if="this.$store.state.view === 'calendar'" />
    <Table v-if="this.$store.state.view === 'table'" />
  </div>
</template>