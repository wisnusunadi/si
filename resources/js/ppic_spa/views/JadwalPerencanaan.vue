<template>
  <div>
    <h1 class="title">Perencanaan Jadwal Perakitan</h1>
    <div class="columns is-multiline">
      <div class="column is-9">
        <div class="box">
          <Calendar />
        </div>
      </div>
      <div class="column is-3">
        <div class="box">test</div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import $ from "jquery";

import Calendar from "../components/Calendar.vue";

export default {
  name: "JadwalPerakitan",
  components: {
    Calendar,
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);
      await axios.get("/api/ppic/schedule").then((response) => {
        this.$store.state.jadwal_perakitan = response.data;
      });
      this.$store.commit("setIsLoading", false);
    },
  },

  mounted() {
    this.loadData();
  },
};
</script>