<template>
  <div>
    <h1 class="title">Persetujuan Perencanaan Jadwal</h1>
    <Persetujuan />
  </div>
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
        .get("/api/ppic/data/perakitan/penyusunan", {
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

  computed: {
    notif() {
      return this.$store.state.notif;
    },
  },

  watch: {
    notif() {
      if (this.$store.state.notif.user.divisi_id === 24) this.loadData();
    },
  },
};
</script>
