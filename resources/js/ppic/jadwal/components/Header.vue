<script>
export default {
  methods: {
    chooseHeader: function () {
      if (this.$store.state.state === "perencanaan") {
        if (this.$store.state.konfirmasi == 0) return "perencanaan";
        else if (this.$store.state.konfirmasi == 2) return "perencanaan";
      } else if (this.$store.state.state === "persetujuan") {
        if (this.$store.state.konfirmasi === 0) return "menunggu";
        else if (this.$store.state.konfirmasi == 1) return "perubahan";
        else if (this.$store.state.konfirmasi == 2) return "perencanaan";
      } else if (this.$store.state.state === "perubahan") {
        if (this.$store.state.konfirmasi == 0) return "menunggu";
        else if (this.$store.state.konfirmasi == 1) return "perencanaan";
        else if (this.$store.state.konfirmasi == 2) return "perubahan";
      }
    },
  },
};
</script>

<template>
  <div>
    <div
      v-if="chooseHeader() === 'perencanaan'"
      class="alert alert-primary"
      role="alert"
    >
      <h4 class="alert-heading">
        <i class="fas fa-check"></i> Jadwal Dapat Diubah
      </h4>
      <hr />
      <p>Kirim permintaan persetujuan untuk meminta persetujuan dari manager</p>
    </div>
    <div
      v-if="chooseHeader() === 'perubahan'"
      class="alert alert-danger"
      role="alert"
    >
      <h4 class="alert-heading">
        <i class="fas fa-times"></i> Jadwal Tidak Dapat Diubah
      </h4>
      <hr />
      <p>
        Kirim permintaan perubahan jadwal agar dapat melakukan perubahan pada
        jadwal
      </p>
    </div>
    <div
      v-if="chooseHeader() === 'menunggu'"
      class="alert alert-warning"
      role="alert"
    >
      <h4 class="alert-heading">
        <i class="far fa-hourglass"></i> Menunggu Persetujuan
      </h4>
      <hr />
      <p>Menunggu manager untuk menyetujui permintaan yang dikirim</p>
    </div>
  </div>
</template>