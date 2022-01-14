<template>
  <div>
    <Jadwal :status="'penyusunan'" />
  </div>
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
        });

      this.$store.commit("setStatus", "perencanaan");

      this.$store.commit("setIsLoading", false);
    },
  },

  created() {
    this.loadData();
  },

  computed: {
    notif() {
      return this.$store.state.notif;
    },
  },

  watch: {
    notif() {
      if (this.$store.state.notif.user.divisi_id === 3) this.loadData();
    },
  },
};
</script>