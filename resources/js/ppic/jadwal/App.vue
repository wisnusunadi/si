<template>
  <div>
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  props: {
    auth: String,
  },

  mounted: function () {
    this.$store.commit("updateUser", JSON.parse(this.auth));
  },
};
</script>