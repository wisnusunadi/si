<template>
  <Jadwal />
</template>

<script>
import axios from "axios";
import Jadwal from "./Jadwal.vue";

export default {
  name: "JadwalPerencanaan",

  components: {
    Jadwal,
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);
      await axios
        .get("/api/ppic/data/perakitan/penyusunan")
        .then((response) => {
          this.$store.commit("setJadwal", response.data);
          if (response.data.length == 0)
            this.$store.commit("setStatus", "penyusunan");
        });
      this.$store.commit("setIsLoading", false);
    },
  },

  mounted() {
    this.loadData();
  },
};
</script>