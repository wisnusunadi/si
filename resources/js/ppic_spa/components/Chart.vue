<template>
  <div>
    <template v-if="status === 'pelaksanaan' && this.rencana.length > 0">
      <div>
        <h1>Rencana</h1>
        <VueApexCharts
          type="rangeBar"
          :options="options"
          :height="this.count_length[1] * 15 + 100"
          :series="second_series"
        ></VueApexCharts>
      </div>
    </template>

    <div>
      <h1 v-if="status === 'pelaksanaan'">Pelaksanaan</h1>
      <div v-if="events.length == 0" class="p-3">Data Kosong</div>
      <VueApexCharts
        v-else
        type="rangeBar"
        :options="options"
        :height="this.count_length[0] * 15 + 100"
        :series="series"
      ></VueApexCharts>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import VueApexCharts from "vue-apexcharts";

export default {
  name: "chart-component",

  props: {
    events: {
      type: Array,
      required: true,
    },
    status: {
      type: String,
      required: true,
    },
  },

  components: {
    VueApexCharts,
  },

  data() {
    return {
      rencana: [],
      options: {
        plotOptions: {
          bar: {
            horizontal: true,
          },
        },
        xaxis: {
          type: "datetime",
        },
      },
    };
  },

  created() {
    if (this.status === "pelaksanaan") {
      axios.get("/api/ppic/data/rencana_perakitan").then((response) => {
        this.rencana = response.data;
      });
    }
  },

  computed: {
    series: function () {
      return [
        {
          data: this.events.map((event) => ({
            x: event.title,
            y: [new Date(event.start).getTime(), new Date(event.end).getTime()],
          })),
        },
      ];
    },

    second_series: function () {
      return [
        {
          data: this.rencana.map((data) => ({
            x: `${data.jadwal_perakitan.produk.produk.nama} ${data.jadwal_perakitan.produk.nama}`,
            y: [
              new Date(data.tanggal_mulai).getTime(),
              new Date(data.tanggal_selesai).getTime(),
            ],
          })),
        },
      ];
    },

    count_length() {
      let series_length = 0;
      let second_series_length = 0;

      let arr = [];
      for (let i = 0; i < this.events.length; i++) {
        if (!arr.includes(this.events[i].produk_id))
          arr.push(this.events[i].produk_id);
      }
      series_length = arr.length;

      arr = [];
      for (let i = 0; i < this.rencana.length; i++) {
        if (!arr.includes(this.rencana[i].jadwal_perakitan.produk_id))
          arr.push(this.rencana[i].jadwal_perakitan.produk_id);
      }
      second_series_length = arr.length;

      return [series_length, second_series_length];
    },
  },
};
</script>