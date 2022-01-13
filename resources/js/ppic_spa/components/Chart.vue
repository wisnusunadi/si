<template>
  <div>
    <div>
      <div v-if="events.length == 0" class="p-3">Data Kosong</div>
      <VueApexCharts
        v-else
        type="rangeBar"
        :options="options"
        :height="this.count_length * 15 + 100"
        :series="series"
      ></VueApexCharts>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import VueApexCharts from "vue-apexcharts";

/**
 * @vue-prop {Array} events - array of schedule data
 * @vue-prop {String} status - status string
 *
 * @vue-data {Object} options - options for setting chart
 *
 * @vue-computed {Array} series - array of data for chart computed from events array
 * @vue-computed {Number} count_length - count number of different product in events
 */

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

    count_length() {
      let series_length = 0;

      let arr = [];
      for (let i = 0; i < this.events.length; i++) {
        if (!arr.includes(this.events[i].produk_id))
          arr.push(this.events[i].produk_id);
      }
      series_length = arr.length;

      return series_length;
    },
  },
};
</script>