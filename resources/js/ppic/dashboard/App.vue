<script>
// alert("test");

export default {
  mounted: function () {
    EchoObj.private("test").listen("TestEvent", (response) => {
      this.$swal({
        icon: "success",
        text: "Message: " + response.message,
      });
      console.log(response);
    });
  },
};
</script>

<template>
  <div>test</div>
</template>