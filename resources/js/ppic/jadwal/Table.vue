<script>
export default {
  computed: {
    series: function () {
      return [
        {
          data: this.$store.state.jadwal.map((event) => ({
            x: event.detail_produk.nama,
            y: [
              new Date(event.tanggal_mulai).getTime(),
              new Date(event.tanggal_selesai).getTime(),
            ],
          })),
        },
      ];
    },
  },

  data: function () {
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
};
</script>

<template>
  <div>
    <div class="card">
      <div v-if="this.$store.state.jadwal.length == 0" class="p-3">
        Data Kosong
      </div>
      <apexchart
        v-else
        type="rangeBar"
        height="200"
        :options="options"
        :series="series"
      ></apexchart>
    </div>
  </div>
</template>