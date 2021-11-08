<template>
  <div>
    <Container :status="status" />
  </div>
</template>

<script>
import Container from "./components/Container.vue";

export default {
  components: {
    Container,
  },

  props: {
    user: String,
    status: String,
  },

  mounted: function () {
    this.$store.commit("updateUser", JSON.parse(this.user));
    this.$store.commit("updateStatus", this.status);
  },
};
</script>