<script>
// import library
import axios from "axios";

// import Calendar from "./Calendar.vue";
// import List from "./List.vue";
// import ProdukModal from "./ProdukModal.vue";
import KonfirmasiModal from "./KonfirmasiModal.vue";

export default {
  components: {
    // Calendar,
    // List,
    // ProdukModal,
    KonfirmasiModal,
  },

  data: function () {
    return {
      confirmationMessage: "",
    };
  },

  methods: {
    showKonfirmasiModal: function (message) {
      this.confirmationMessage = message;

      $("#confirmation").modal("show");
    },

    // select button action to show
    showActionButton() {
      if (this.$store.state.jadwal.length > 0) {
        if (this.$store.state.status_menunggu === 1)
          return "menunggu_persetujuan";
        else if (this.$store.state.konfirmasi === 0)
          return "permintaan_persetujuan";
        else if (this.$store.state.status_perubahan === 0)
          return "permintaan_perubahan";
      }
    },
  },
};
</script>

<template>
  <div>
    <div class="row">
      <div class="col-xl-8">
        <div class="card">
          <div class="card-header text-center">
            {{
              $store.state.status.charAt(0).toUpperCase() +
              $store.state.status.slice(1)
            }}
          </div>
          <div class="card-body">
            <!-- <Calendar /> -->
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div v-if="$store.state.user.divisi_id === 24">
          <button
            v-if="showActionButton() === 'permintaan_persetujuan'"
            class="btn btn-block btn-primary mb-3"
            style="padding: 0.75rem 1.25rem"
            @click="sendToManager"
          >
            Permintaan Persetujuan
          </button>
          <button
            v-if="showActionButton() === 'permintaan_perubahan'"
            class="btn btn-block btn-info mb-3"
            @click="sendToManager"
          >
            Permintaan Perubahan Jadwal
          </button>
        </div>
      </div>
    </div>

    <KonfirmasiModal :message="{ confirmationMessage }" />
  </div>
</template>