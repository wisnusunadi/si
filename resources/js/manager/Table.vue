<script>
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
// import axios from "axios";

export default {
  data: function () {
    return {
      jadwal: this.$store.state.jadwal,
    };
  },
};
</script>

<template>
  <div>
    <table id="table" class="table table-hover styled-table table-striped">
      <thead>
        <tr>
          <th>Produk</th>
          <th>Jumlah</th>
          <th>Tanggal Mulai</th>
          <th>Tanggal Selesai</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="data in jadwal" :key="data.id">
          <td>
            <input type="checkbox" />
            {{ data.produk.nama }}
          </td>
          <td>{{ data.jumlah }}</td>
          <td>{{ data.tanggal_mulai }}</td>
          <td>{{ data.tanggal_selesai }}</td>
        </tr>
      </tbody>
    </table>
    <div class="btn-group btn-block">
      <button class="btn btn-success">Yes</button>
      <button class="btn btn-danger">No</button>
    </div>
  </div>
</template>