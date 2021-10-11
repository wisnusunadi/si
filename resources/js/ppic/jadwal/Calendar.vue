<template>
  <div>
    <h1>{{ $route.params.status }}</h1>
  </div>
</template>

<script>
import axios from "axios";

import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

export default {
  data() {
    return {
      jadwal: [],
    };
  },

  computed: {
    event: function () {
      if (this.jadwal.length == 0) return [];
      else
        this.jadwal.map((data) => ({
          id: data.id,
          title: data.detail_produk.nama,
          start: data.tanggal_mulai,
          end: data.tanggal_selesai,
          backgroundColor: data.warna,
          borderColor: data.warna,
        }));
    },
  },

  mounted() {
    axios.get("http://localhost:8000/api/ppic/schedule");
  },
};
</script>