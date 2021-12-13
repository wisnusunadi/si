<template>
  <div>
    <h1 class="title">Data Gudang Karantina</h1>
    <div class="columns is-multiline">
      <div class="column is-12">
        <div class="tabs is-centered">
          <ul>
            <li
              :class="{ 'is-active': !tabs }"
              @click="changeView('sparepart')"
            >
              <a>
                <span class="icon is-small"
                  ><i class="fab fa-whmcs" aria-hidden="true"></i
                ></span>
                <span>Sparepart</span>
              </a>
            </li>
            <li :class="{ 'is-active': tabs }" @click="changeView('unit')">
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
            <tbody>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Sparepart 1</td>
                <td>Unit 1</td>
                <td>100 pcs</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Sparepart 1</td>
                <td>Unit 1</td>
                <td>100 pcs</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Sparepart 1</td>
                <td>Unit 1</td>
                <td>100 pcs</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Sparepart 1</td>
                <td>Unit 1</td>
                <td>100 pcs</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Sparepart 1</td>
                <td>Unit 1</td>
                <td>100 pcs</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Sparepart 1</td>
                <td>Unit 1</td>
                <td>100 pcs</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Sparepart 1</td>
                <td>Unit 1</td>
                <td>100 pcs</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Sparepart 1</td>
                <td>Unit 1</td>
                <td>100 pcs</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Sparepart 1</td>
                <td>Unit 1</td>
                <td>100 pcs</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Sparepart 1</td>
                <td>Unit 1</td>
                <td>100 pcs</td>
              </tr>
            </tbody>
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

            <tbody>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Unit 1</td>
                <td>100 Unit</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Unit 1</td>
                <td>100 Unit</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Unit 1</td>
                <td>100 Unit</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Unit 1</td>
                <td>100 Unit</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Unit 1</td>
                <td>100 Unit</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Unit 1</td>
                <td>100 Unit</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Unit 1</td>
                <td>100 Unit</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Unit 1</td>
                <td>100 Unit</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Unit 1</td>
                <td>100 Unit</td>
              </tr>
              <tr>
                <td scope="row">Kode 1</td>
                <td>Unit 1</td>
                <td>100 Unit</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import $ from "jquery";

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
    changeView(view) {
      if (view === "sparepart") {
        this.tabs = false;
        // if (this.table_sparepart) {
        //   this.table_sparepart.destroy();
        // }
        // // this.table_sparepart = $("#table-sparepart").DataTable();
      } else if (view === "unit") {
        this.tabs = true;
        // if (this.table_unit) {
        //   this.table_unit.destroy();
        // }
        // // this.table_unit = $("#table-unit").DataTable();
      }
    },
  },

  mounted() {
    this.table_sparepart = $("#table-sparepart").DataTable();
    this.table_unit = $("#table-unit").DataTable();
  },
};
</script>
