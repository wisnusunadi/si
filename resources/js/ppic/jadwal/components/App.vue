<template>
  <div></div>
</template>

<script>
export default {
  props: {
    user: String,
    status: String,
  },

  mounted: function () {
    this.$store.commit("updateUser", JSON.parse(this.auth));
    this.$store.commit("updateStatus", this.status);
  },
};
</script>