<script>
export default {
  props: {
    message: String,
    emit: String,
  },

  methods: {
    handleButtonYes: function () {
      this.$root.$emit(this.emit);
      console.log("done call emit");
      let element = this.$refs.modal;
      $(element).modal("hide");
      console.log("modal hide");
    },

    handleButtonNo: function () {
      let element = this.$refs.modal;
      $(element).modal("hide");
    },
  },

  mounted: function () {
    this.$root.$on("confirm_modal_show", () => {
      let element = this.$refs.modal;
      $(element).modal("show");
    });
  },
};
</script>

<template>
  <div>
    <div class="modal fade" ref="modal">
      <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
          <div class="modal-body">
            <div v-html="message"></div>
          </div>
          <div class="modal-footer d-flex justify-content-between">
            <button class="btn btn-primary" @click="handleButtonYes">
              Yes
            </button>
            <button class="btn btn-danger" @click="handleButtonNo">No</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>