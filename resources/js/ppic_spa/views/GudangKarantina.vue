<template>
  <div>
    <h1 class="title">Data Gudang Karantina</h1>
    <div class="columns is-multiline">
      <div class="column is-12">
        <div class="tabs is-centered">
          <ul>
            <li :class="{ 'is-active': !tabs }" @click="tabs = false">
              <a>
                <span class="icon is-small"
                  ><i class="fab fa-whmcs" aria-hidden="true"></i
                ></span>
                <span>Sparepart</span>
              </a>
            </li>
            <li :class="{ 'is-active': tabs }" @click="tabs = true">
              <a>
                <span class="icon is-small"
                  ><i class="fas fa-tools" aria-hidden="true"></i
                ></span>
                <span>Unit</span>
              </a>
            </li>
          </ul>
        </div>
        <!-- sparepart -->
        <div :class="{ 'is-hidden': tabs }">
          <table
            class="table is-fullwidth has-text-centered"
            id="table-sparepart"
          >
            <thead>
              <tr>
                <th>Kode Sparepart</th>
                <th>Nama</th>
                <th>Unit</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

        <!-- unit -->
        <div :class="{ 'is-hidden': !tabs }">
          <table class="table is-fullwidth has-text-centered" id="table-unit">
            <thead>
              <tr>
                <th>Kode Unit</th>
                <th>Nama</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import $ from "jquery";
import axios from "axios";

export default {
  name: "GudangKarantina",

  data() {
    return {
      tabs: false,
      table_sparepart: null,
      table_unit: null,
    };
  },

  methods: {
    async loadData() {
      this.$store.commit("setIsLoading", true);
      await axios.get("/api/ppic/data/gk");
    },
  },

  mounted() {
    this.table_sparepart = $("#table-sparepart").DataTable();
    this.table_unit = $("#table-unit").DataTable();
  },
};
</script>
