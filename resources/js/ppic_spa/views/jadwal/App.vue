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

    EchoObj.private("test").listen("TestEvent", (e) => {
      this.$bvToast.toast(`Pesan Baru`, {
        title: `Test Event}`,
        toaster: "b-toaster-top-full",
        solid: true,
        appendToast: append,
      });
    });
  },

  methods: {
    makeToast(append = false) {
      this.toastCount++;
      this.$bvToast.toast(`This is toast number ${this.toastCount}`, {
        title: "BootstrapVue Toast",
        autoHideDelay: 5000,
        appendToast: append,
      });
    },
    toast(toaster, append = false) {
      this.counter++;
      this.$bvToast.toast(`Toast ${this.counter} body content`, {
        title: `Toaster ${toaster}`,
        toaster: toaster,
        solid: true,
        appendToast: append,
      });
    },
  },
};
</script>