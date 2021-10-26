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

  data: function () {
    return {
      user: JSON.parse(this.auth),
    };
  },
};
</script>