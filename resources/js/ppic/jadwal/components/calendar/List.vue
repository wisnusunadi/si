<template>
  <div class="card">
    <div class="card-header text-center">Daftar Produksi</div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-styled">
          <thead>
            <tr>
              <th>Nama</th>
              <th>Jumlah</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in this.$store.state.jadwal"
              :key="item.id"
              :ref="'sample-ref-' + item.id"
            >
              <td>
                <i
                  v-if="$store.state.user.divisi_id === 24"
                  :class="[
                    { 'far fa-hourglass': item.proses_konfirmasi === 1 },
                    {
                      'far fa-check-circle':
                        item.proses_konfirmasi === 2 &&
                        item.konfirmasi_rencana === 1,
                    },
                    {
                      'far fa-times-circle':
                        item.proses_konfirmasi === 2 &&
                        item.konfirmasi_rencana === 0,
                    },
                  ]"
                  :style="
                    item.proses_konfirmasi === 2
                      ? { color: 'blue' }
                      : { color: 'red' }
                  "
                ></i>
                {{ item.produk.nama }}
              </td>
              <td>{{ item.jumlah }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    options: function () {
      return this.produk.map((data) => ({
        label: data.nama,
        value: data.id,
      }));
    },
  },
};
</script>