<template>
  <div class="modal fade" id="confirmation">
    <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content">
        <div class="modal-body">
          <div v-html="confirmationMessage"></div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <button class="btn btn-primary" @click="handleButtonYes">Yes</button>
          <button class="btn btn-danger" @click="handleButtonNo">No</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      start_date_str: "",
      end_date_str: "",
      event_ref: null,

      // modal
      produk: [],
      produkValue: "",
      quantity: 0,
      color: "#007bff",
      colors: [
        "#007bff",
        "#6c757d",
        "#28a745",
        "#dc3545",
        "#ffc107",
        "#17a2b8",
      ],
    };
  },

  computed: {
    message: function () {
      let result = {};
      let jadwal = this.$store.state.jadwal;
      for (let i = 0; i < jadwal.length; i++) {
        result[jadwal[i].id] = `
          produk: ${jadwal[i].produk.nama} <br />
          Jumlah: ${jadwal[i].jumlah}  <br />
          <br />
          Apakah Anda ingin menghapus produk ini dari jadwal?
        `;
      }

      return result;
    },
  },
};
</script>