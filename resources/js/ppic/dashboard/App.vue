<template>
  <div>
    <div class="card p-3">
      <apexcharts
        width="100%"
        height="350"
        type="bar"
        :options="chartOptions"
        :series="series"
      ></apexcharts>
    </div>
    <div class="card"></div>
  </div>
</template>

<script>
import VueApexCharts from "vue-apexcharts";

export default {
  name: "Chart",
  components: {
    apexcharts: VueApexCharts,
  },
  data: function () {
    return {
      chartOptions: {
        subtitle: {
          text: "Data Perakitan",
          align: "center",
          margin: 10,
          offsetX: 0,
          offsetY: 0,
          floating: false,
          style: {
            fontSize: "12px",
            fontWeight: "normal",
            fontFamily: undefined,
            color: "#9699a2",
          },
        },
        chart: {
          id: "basic-bar",
          animations: {
            speed: 200,
          },
        },
        dataLabels: {
          enabled: false,
        },
        plotOptions: {
          bar: {
            distributed: true,
          },
        },
        xaxis: {
          categories: ["rencana", "pelaksanaan", "selesai"],
        },
      },
      series: [
        {
          name: "data",
          data: [30, 40, 45],
        },
      ],
    };
  },
  methods: {
    updateTheme(e) {
      this.chartOptions = {
        theme: {
          palette: e.target.value,
        },
      };
    },
  },
};
</script>
