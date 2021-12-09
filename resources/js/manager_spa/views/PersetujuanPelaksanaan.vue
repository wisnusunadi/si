<template>
  <Persetujuan />
</template>

<script>
import axios from "axios";
import Persetujuan from "./Persetujuan.vue";

export default {
  components: {
    Persetujuan,
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);
      await axios
        .get("/api/ppic/perakitan/data/pelaksanaan", {
          params: {
            konfirmasi: "0",
          },
        })
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
