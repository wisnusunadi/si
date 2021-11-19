<script>
import Rencana from "./Rencana.vue";
import Pelaksanaan from "./Pelaksanaan.vue";

export default {
  components: {
    Rencana,
    Pelaksanaan,
  },
};
</script>

<template>
  <div>
    <b-card body-class="pt-0">
      <b-tabs align="center" card>
        <b-tab title="Rencana" active><Rencana /></b-tab>
        <b-tab title="Pelaksanaan"><Pelaksanaan /></b-tab>
      </b-tabs>
    </b-card>
  </div>
</template>