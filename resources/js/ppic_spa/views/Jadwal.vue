<template>
  <div>
    <h1 class="title">Perencanaan Jadwal Perakitan</h1>
    <div
      v-if="this.$store.state.state_ppic === 'pembuatan'"
      class="notification is-primary"
    >
      Penyusunan jadwal perakitan
    </div>
    <div
      v-else-if="this.$store.state.state_ppic === 'menunggu'"
      class="notification is-warning"
    >
      Menunggu persetujuan manajer
    </div>
    <div
      v-else-if="this.$store.state.state_ppic === 'disetujui'"
      class="notification is-success"
    >
      Jadwal telah disetujui
    </div>
    <div
      v-else-if="this.$store.state.state_ppic === 'revisi'"
      class="notification is-danger"
    >
      Jadwal harus direvisi
    </div>

    <div class="tabs is-centered">
      <ul>
        <li :class="{ 'is-active': true }">
          <a>
            <span class="icon is-small"
              ><i class="fas fa-calendar" aria-hidden="true"></i
            ></span>
            <span>Kalender</span>
          </a>
        </li>
        <li :class="{ 'is-active': false }">
          <a>
            <span class="icon is-small"
              ><i class="fas fa-table" aria-hidden="true"></i
            ></span>
            <span>Tabel</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="columns is-multiline">
      <div class="column is-9">
        <div class="box">
          <Calendar />
        </div>
      </div>
      <div class="column is-3">
        <div class="buttons">
          <button
            v-if="
              this.$store.state.state_ppic === 'pembuatan' ||
              this.$store.state.state_ppic === 'revisi'
            "
            class="button is-primary"
            :class="{ 'is-loading': this.$store.state.isLoading }"
            @click="sendEvent"
          >
            Kirim
          </button>
          <button
            v-if="this.$store.state.state_ppic === 'disetujui'"
            class="button is-primary"
            :class="{ 'is-loading': this.$store.state.isLoading }"
          >
            Minta Perubahan
          </button>
        </div>
        <article class="message is-dark">
          <div class="message-header">
            <p>Pesan</p>
            <button class="delete" aria-label="delete"></button>
          </div>
          <div class="message-body">
            Keep fighting
            <div class="is-flex is-justify-content-flex-end">
              <button class="button is-circle">
                <i class="fas fa-envelope"></i>
              </button>
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Calendar from "../components/Calendar.vue";

export default {
  name: "Jadwal",
  components: {
    Calendar,
  },

  methods: {
    async sendEvent() {
      this.$store.commit("setIsLoading", true);
      await axios
        .post("/api/ppic/update-many-event/" + this.$store.state.status, {
          state: "persetujuan",
        })
        .then((response) => {
          this.$store.commit("setJadwal", response.data);
        });
      this.$store.commit("setIsLoading", false);
    },
  },
};
</script>