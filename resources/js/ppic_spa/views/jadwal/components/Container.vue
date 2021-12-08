<script>
import Calendar from "./Calendar.vue";
import Chart from "./Chart.vue";

import Header from "./Header.vue";
import ProductModal from "./ProductModal";
import ConfirmModal from "./ConfirmModal.vue";

export default {
  props: {
    status: String,
  },

  components: {
    Header,
    Calendar,
    Chart,
    ProductModal,
    ConfirmModal,
  },

  data: function () {
    return {
      view: "calendar",
    };
  },

  methods: {
    changeView: function (view) {
      this.view = view;
    },

    isCalendar: function () {
      return this.view === "calendar";
    },

    isChart: function () {
      return this.view === "chart";
    },
  },

  mounted: function () {
    this.$store.dispatch("updateJadwal", this.status);

    EchoObj.private("test").listen("TestEvent", (e) => {
      console.log("pusher");
      console.log(e);
      this.$store.dispatch("updateJadwal", this.status);
      this.$root.$emit("update_comment");
    });
  },
};
</script>

<template>
  <div>
    <Header />

    <b-tabs content-class="mt-3">
      <b-tab title="Kalender" active><Calendar :status="status" /></b-tab>
      <b-tab title="Chart"><Chart /></b-tab>
    </b-tabs>

    <ProductModal
      :start_date="$store.state.start_date_str"
      :end_date="$store.state.end_date_str"
    />
    <ConfirmModal :message="$store.state.message" :emit="$store.state.emit" />
  </div>
</template>