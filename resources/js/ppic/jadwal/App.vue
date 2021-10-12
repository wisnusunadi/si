<template>
  <div>
    <div class="btn-group mb-3">
      <button class="btn btn-primary" @click="changeView('calendar')">
        Kalender
      </button>
      <button class="btn btn-info" @click="changeView('table')">Tabel</button>
      <!-- <button type="button" data-view="list" class="btn btn-warning">Daftar</button> -->
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