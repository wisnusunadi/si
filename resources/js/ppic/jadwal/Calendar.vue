<script>
// import library
import axios from "axios";

// import component
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

export default {
  components: {
    FullCalendar,
  },

  data: function () {
    return {
      start_date_str: "",
      end_date_str: "",
      status: this.$route.params.status,

      // modal
      produk: [],
      produkValue: "",
      versi: [],
      versiValue: "",
      quantity: 0,
      maxQuantity: 0,
      quantityError: false,
      color: "#6c757d",
      colors: [
        "#007bff",
        "#6c757d",
        "#28a745",
        "#dc3545",
        "#ffc107",
        "#17a2b8",
      ],

      confirmationMessage: "",
      deleteJadwal: false,

      event_ref: null,
    };
  },

  computed: {
    event: function () {
      if (this.$store.state.jadwal.length == 0) return [];
      else
        return this.$store.state.jadwal.map((data) => ({
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
          this.$store.state.jadwal.length == 0
            ? []
            : this.$store.state.jadwal.map((data) => ({
                id: data.id,
                title: data.detail_produk.nama,
                start: data.tanggal_mulai,
                end: data.tanggal_selesai,
                backgroundColor: data.warna,
                borderColor: data.warna,
                jumlah: data.jumlah,
              })),

        select: this.handleSelect,
        eventClick: this.handleEventClick,
      };
    },

    options: function () {
      this.produk.map((item) => ({
        text: item.nama,
        value: item.id,
      }));
    },

    message: function () {
      let result = {};
      let jadwal = this.$store.state.jadwal;
      for (let i = 0; i < jadwal.length; i++) {
        result[jadwal[i].id] = `
          produk: ${jadwal[i].detail_produk.nama} <br />
          Jumlah: ${jadwal[i].jumlah_produksi} <br />
          <br />
          Apakah Anda ingin menghapus produk ini dari jadwal?
        `;
      }

      return result;
    },

    konfirmasi: function () {
      let jadwal = this.$store.state.jadwal;
      if (this.status === "penyusunan") {
        if (jadwal.length > 0 && jadwal[0].konfirmasi == 1) return true;
        return false;
      }
      return true;
    },
  },

  mounted: function () {
    let api = this.$refs.fullCalendar.getApi();
    if (this.status == "penyusunan") {
      api.next();
    }
    if (this.status == "selesai") {
      api.prev();
    }

    if (!this.konfirmasi) this.enableEdit();
    else this.disableEdit();
  },

  methods: {
    handleSelect: function (selectInfo) {
      let calendarApi = selectInfo.view.calendar;
      calendarApi.unselect();

      this.start_date_str = selectInfo.startStr;
      this.end_date_str = selectInfo.endStr;

      axios.get("http://localhost:8000/api/ppic/product").then((response) => {
        this.produk = response.data;
        $("#exampleModal").modal("show");
      });
    },

    handleEventClick: function (clickEventInfo) {
      let obj = clickEventInfo.event._def;
      this.confirmationMessage = this.message[obj.publicId];
      this.deleteJadwal = true;
      this.event_ref = clickEventInfo;

      $("#confirmation").modal("show");
    },

    disableEdit() {
      this.calendarOptions.selectable = false;
    },

    enableEdit() {
      this.calendarOptions.selectable = true;
    },

    // modal
    handleClick(event) {
      this.color = event.target.style.backgroundColor;
    },

    changeProduk() {
      axios.get("/api/ppic/version/" + this.produkValue).then((response) => {
        this.versi = response.data.produk_bill_of_material;
        this.versiValue = "";

        this.quantity = 0;
        this.maxQuantity = 0;
      });
    },

    changeVersi() {
      axios
        .get("/api/ppic/max-quantity/" + this.versiValue)
        .then((response) => {
          this.maxQuantity = response.data;
        });
    },

    handleSubmit() {
      if (!this.produkValue || !this.versiValue || !this.quantity) {
        alert("form tidak lengkap");
        return;
      }

      axios
        .post("/api/ppic/add-event", {
          detail_produk_id: this.produkValue,
          produk_bill_of_material_id: this.versiValue,
          jumlah_produksi: this.quantity,
          tanggal_mulai: this.start_date_str,
          tanggal_selesai: this.end_date_str,
          status: this.$route.params.status,
          warna: this.color,
        })
        .then((response) => {
          this.$store.commit("updateJadwal", response.data);
          $("#exampleModal").modal("hide");
          this.produkValue = "";
          this.versiValue = "";
          this.quantity = 0;
        });
    },

    sendBppb: function () {
      this.confirmationMessage = `Apakah Anda yakin ingin mengirim permintaan BPPB?`;
      this.deleteJadwal = false;

      $("#confirmation").modal("show");
    },

    handleButtonYes: function () {
      if (this.deleteJadwal) {
        axios
          .post("http://localhost:8000/api/ppic/delete-event", {
            id: this.event_ref.event._def.publicId,
          })
          .then((response) => {
            this.$store.commit("updateJadwal", response.data);
          });
      } else {
        axios
          .post("http://localhost:8000/api/ppic/send-bppb", {
            confirmation: 1,
          })
          .then((response) => {
            console.log(response.data);
          });
      }

      $("#confirmation").modal("hide");
    },

    handleButtonNo: function () {
      $("#confirmation").modal("hide");
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
              <tr v-for="item in this.$store.state.jadwal" :key="item.id">
                <td>{{ item.detail_produk.nama }}</td>
                <td>{{ item.jumlah_produksi }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <button
        v-if="event.length > 0 && !konfirmasi"
        class="btn btn-block btn-info"
        @click="sendBppb"
      >
        BPPB
      </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Produk Modal</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div style="margin-bottom: 20px">
              <label>Pilih Warna:</label>
              <button
                v-for="col in colors"
                :key="col"
                v-on:click="handleClick"
                class="btn"
                :style="{
                  padding: '20px',
                  margin: '8px',
                  backgroundColor: col,
                  borderColor: col,
                }"
              ></button>
            </div>
            <div class="form-group">
              <label>Produk:</label>
              <select
                class="form-control"
                v-on:change="changeProduk"
                v-model="produkValue"
              >
                <option v-for="item in produk" :key="item.id" :value="item.id">
                  {{ item.nama }}
                </option>
              </select>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Versi BOM:</label>
                <select
                  class="form-control"
                  v-on:change="changeVersi"
                  v-model="versiValue"
                >
                  <option v-for="item in versi" :key="item.id" :value="item.id">
                    {{ item.versi }}
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
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn"
              :style="{ backgroundColor: color, borderColor: color }"
              @click="handleSubmit"
            >
              Save
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="confirmation">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
          <div class="modal-body">
            <div v-html="confirmationMessage"></div>
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