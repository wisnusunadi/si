<template>
  <div>
    <div class="btn-group mb-3">
      <button class="btn btn-primary" @click="changeView('calendar')">
        Kalender
      </button>
      <button class="btn btn-info" @click="changeView('table')">Tabel</button>
    </div>
    <div
      v-if="this.$store.state.konfirmasi"
      class="alert alert-warning"
      role="alert"
    >
      <i class="fas fa-bell"></i> Menunggu persetujuan dari manager
    </div>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  methods: {
    changeView: function (view) {
      this.$store.commit("changeView", view);
    },
  },
};
</script>