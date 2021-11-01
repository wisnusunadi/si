<template>
  <div class="modal fade" id="confirmation">
    <div class="modal-dialog modal-dialog-centered modal-md">
      <div class="modal-content">
        <div class="modal-body">
          <div v-html="message"></div>
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
import axios from "axios";

export default {
  props: {
    message: String,
  },

  methods: {
    handleButtonYes: function () {
      if (this.deleteJadwal) {
        axios
          .post("/api/ppic/delete-event", {
            id: this.event_ref.event._def.publicId,
          })
          .then((response) => {
            this.$store.commit("updateJadwal", response.data);
            $("#confirmation").modal("hide");
          });
      } else {
        axios
          .post("/api/ppic/update-event", {
            proses_konfirmasi: 1,
            status: this.status,
          })
          .then((response) => {
            this.$store.commit("updateJadwal", response.data);

            $("#confirmation").modal("hide");
            this.$swal({
              icon: "success",
              text: "Berhasil mengirim permintaan",
            });
          });

        axios.get("/api/ppic/test-event", {
          params: {
            message: "menunggu persetujuan",
          },
        });
      }
    },

    handleButtonNo: function () {
      $("#confirmation").modal("hide");
    },
  },
};
</script>