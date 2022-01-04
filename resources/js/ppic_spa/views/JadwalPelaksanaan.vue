<template>
  <div>
    <Jadwal :status="'pelaksanaan'" :jadwal_rencana="jadwal_rencana" />
  </div>
</template>

<script>
import axios from "axios";
import Jadwal from "./Jadwal.vue";

export default {
  name: "JadwalPelaksanaan",

  components: {
    Jadwal,
  },

  data() {
    return {
      jadwal_rencana: [],
    };
  },

  async created() {
    this.$store.commit("setIsLoading", true);

    await axios.get("/api/ppic/data/perakitan/pelaksanaan").then((response) => {
      this.$store.commit("setJadwal", response.data);
    });

    this.$store.commit("setStatus", "pelaksanaan");

    this.$store.commit("setIsLoading", false);
  },
};
</script>