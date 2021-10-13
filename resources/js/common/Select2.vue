<script>
import $ from "jquery";
import "select2/dist/js/select2";

export default {
  props: [options, value],

  mounted: function () {
    let vm = this;

    $(this.$el)
      .select2({ data: this.options })
      .val(this.value)
      .trigger("change")
      .on("change", function () {
        vm.$emit("input", this.value);
      });
  },

  watch: {},
};
</script>