<template>
  <div>
    <h1 class="title">Data Perakitan</h1>
    <div class="columns is-multiline">
      <div class="column is-12">
        <table class="table is-fullwidth has-text-centered" id="table">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Produk</th>
              <th>Jumlah</th>
              <th>Waktu Mulai</th>
              <th>Waktu Selesai</th>
              <th>Progres</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(d, i) in data" :key="d.id">
              <td>{{ i + 1 }}</td>
              <td>{{ d.produk.produk.nama + " " + d.produk.nama }}</td>
              <td>{{ d.jumlah }}</td>
              <td>{{ d.tanggal_mulai }}</td>
              <td>{{ d.tanggal_selesai }}</td>
              <td>
                <progress
                  class="progress"
                  :value="countVal(d.status)"
                  :class="{
                    'is-danger': d.status === 'penyusunan',
                    'is-warning': d.status === 'pelaksanaan',
                    'is-success': d.status === 'selesai',
                  }"
                  max="100"
                >
                  {{ countVal(d.status) }}%
                </progress>
                <small> {{ countVal(d.status) }}% Complete </small>
              </td>
              <td>
                <span
                  :class="{
                    'badge badge-pill': true,
                    'badge-warning': d.status === 'penyusunan',
                    'badge-info': d.status === 'pelaksanaan',
                    'badge-success': d.status === 'selesai',
                  }"
                  >{{ d.status }}</span
                >
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import $ from "jquery";
import axios from "axios";

export default {
  name: "GudangBarangJadi",

  data() {
    return {
      data: [],
    };
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);
      await axios.get("/api/ppic/data/perakitan").then((response) => {
        this.data = response.data;
      });
      this.$store.commit("setIsLoading", false);

      let table = $("#table").DataTable();
    },

    countVal(status) {
      let val;
      if (status === "penyusunan") val = 0;
      else if (status === "pelaksanaan") val = 50;
      else if (status === "selesai") val = 100;
      return val;
    },
  },

  mounted() {
    this.loadData();
  },
};
</script>