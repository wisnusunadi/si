<template>
  <div>
    <button class="button is-light" @click="test">
      <i class="fas fa-search"></i>
    </button>
  </div>
</template>

<script>
export default {
  name: "button-component",
  methods: {
    test() {
      alert("test");
    },
  },
};
</script>