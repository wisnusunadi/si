<script>
import axios from "axios";

import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

export default {
  components: {
    FullCalendar,
  },

  data: function () {
    return {
      jadwal: [],
      start_date_str: "",
      end_date_str: "",
      status: this.$route.params.status,

      // modal
      produks: null,
      produkSelect: "",
      bomSelect: "",
      quantity: 0,

      produkBom: [],
      maxQuantity: 0,
      color: "#6c757d",
      colors: [
        "#007bff",
        "#6c757d",
        "#28a745",
        "#dc3545",
        "#ffc107",
        "#17a2b8",
      ],

      quantityError: false,
    };
  },

  computed: {
    event: function () {
      if (this.jadwal.length == 0) return [];
      else
        return this.jadwal.map((data) => ({
          id: data.id,
          title: data.detail_produk.nama,
          start: data.tanggal_mulai,
          end: data.tanggal_selesai,
          backgroundColor: data.warna,
          borderColor: data.warna,
        }));
    },
    calendarOptions: function () {
      return {
        plugins: [dayGridPlugin, interactionPlugin],

        locale: "id",
        headerToolbar: {
          end: "",
        },
        weekends: false,
        showNonCurrentDates: false,
        editable: true,
        selectable: true,

        events:
          this.jadwal.length == 0
            ? []
            : this.jadwal.map((data) => ({
                id: data.id,
                title: data.detail_produk.nama,
                start: data.tanggal_mulai,
                end: data.tanggal_selesai,
                backgroundColor: data.warna,
                borderColor: data.warna,
              })),

        select: this.handleSelect,
        eventClick: this.handleEventClick,
      };
    },
  },

  created: function () {
    axios
      .get(
        "http://localhost:8000/api/ppic/schedule/" + this.$route.params.status
      )
      .then((response) => {
        this.jadwal = response.data;
      });
  },

  mounted: function () {
    let api = this.$refs.fullCalendar.getApi();
    if (this.status == "penyusunan") {
      // if (this.konfirmasi != 0) this.disableEdit();
      // else this.enableEdit();
      api.next();
    }
    if (this.status == "pelaksanaan") {
      // this.disableEdit();
    }
    if (this.status == "selesai") {
      // this.disableEdit();
      api.prev();
    }
  },

  methods: {
    handleSelect: function (selectInfo) {
      let calendarApi = selectInfo.view.calendar;
      calendarApi.unselect();

      this.start_date_str = selectInfo.startStr;
      this.end_date_str = selectInfo.endStr;

      console.log(this.start_date_str, this.end_date_str);
      $("#exampleModal").modal("show");
    },

    // modal
    handleClick(event) {
      console.log(event.target.style["bakground-color"]);
      // this.color = event.originalTarget.className;
    },

    changeProduct() {
      // console.log("/api/ppic/version-bom-product/" + this.produkSelect);
      axios
        .get("/api/ppic/version-bom-product/" + this.produkSelect)
        .then((response) => {
          this.produkBom = response.data.produk_bill_of_material;
          this.maxQuantity = 0;
          this.bomSelect = "";
          this.quantity = 0;
        });
    },

    changeBom() {
      axios
        .get("/api/ppic/get-max-quantity/" + this.bomSelect)
        .then((response) => {
          this.maxQuantity = response.data;
        });
    },

    handleSubmit() {
      if (!this.produkSelect || !this.bomSelect || !this.quantity) {
        alert("form tidak lengkap");
        return;
      }

      axios
        .post("/api/ppic/add-event", {
          detail_produk_id: this.produkSelect,
          produk_bill_of_material_id: this.bomSelect,
          jumlah_produksi: this.quantity,
          tanggal_mulai: dateFormat(this.startDate, "yyyy-mm-dd"),
          tanggal_selesai: dateFormat(this.endDate, "yyyy-mm-dd"),
          status: this.status,
          warna: this.color,
        })
        .then((response) => {
          let data = response.data;
          let data_last = data[data.length - 1];
          this.$emit("hide-product-modal", data, data_last);
        });
    },
  },

  watch: {
    quantity: function (val) {
      if (val > this.maxQuantity) this.quantityError = true;
      else this.quantityError = false;
    },
  },
};
</script>

<template>
  <div class="row">
    <div class="col-md-9">
      <div class="card">
        <full-calendar
          ref="fullCalendar"
          id="calendar"
          :options="calendarOptions"
        />
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-header">
          <h5 class="text-center">Daftar Produksi</h5>
        </div>
        <div class="card-body" style="max-height: 500px">
          <table class="table table-hover styled-table table-striped">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in jadwal" :key="item.id">
                <td>{{ item.detail_produk.nama }}</td>
                <td>{{ item.jumlah_produksi }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Produk Modal</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div style="margin-bottom: 20px">
              <label>Pilih Warna:</label>
              <button
                v-for="col in colors"
                :key="col"
                v-on:click="handleClick"
                :style="{
                  padding: '20px',
                  margin: '8px',
                  backgroundColor: col,
                  borderColor: col,
                }"
              ></button>
            </div>
            <!-- <div class="form-group">
              <label>Produk:</label>
              <select
                class="form-control"
                data-placeholder="Pilih produk"
                v-on:change="changeProduct"
                v-model="produkSelect"
              >
                <option
                  v-for="produk in produks"
                  :key="produk.id"
                  :value="produk.id"
                >
                  {{ produk.nama }}
                </option>
              </select>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Versi BOM:</label>
                <select
                  class="form-control"
                  data-placeholder="Pilih versi BOM"
                  v-on:change="changeBom"
                  v-model="bomSelect"
                >
                  <option
                    v-for="bom in produkBom"
                    :key="bom.id"
                    :value="bom.id"
                  >
                    {{ bom.versi }}
                  </option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label>Jumlah Produk:</label>
                <input
                  type="number"
                  class="form-control"
                  v-model="quantity"
                  :max="maxQuantity"
                />
                <small
                  :class="[
                    'form-text',
                    quantityError ? 'text-danger' : 'text-muted',
                  ]"
                  >max: {{ maxQuantity }}</small
                >
              </div>
              <b-button
                class="mt-3"
                block
                :style="{ backgroundColor: color, borderColor: color }"
                v-on:click="handleSubmit"
                >Submit</b-button
              >
            </div> -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>