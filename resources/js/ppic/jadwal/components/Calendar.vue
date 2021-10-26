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
      status: this.$route.params.status,
      start_date_str: "",
      end_date_str: "",

      // modal
      produk: [],
      produkValue: "",
      quantity: 0,
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
      selectable: true,
    };
  },

  computed: {
    event: function () {
      return this.convertJadwal(this.$store.state.jadwal);
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
        selectable: this.selectable,

        events: this.convertJadwal(this.$store.state.jadwal),

        select: this.handleSelect,
        eventClick: this.handleEventClick,
      };
    },

    options: function () {
      return this.produk.map((data) => ({
        label: data.nama,
        value: data.id,
      }));
    },

    message: function () {
      let result = {};
      let jadwal = this.$store.state.jadwal;
      for (let i = 0; i < jadwal.length; i++) {
        result[jadwal[i].id] = `
          produk: ${jadwal[i].produk.nama} <br />
          Jumlah: ${jadwal[i].jumlah}  <br />
          <br />
          Apakah Anda ingin menghapus produk ini dari jadwal?
        `;
      }

      return result;
    },

    konfirmasi: function () {
      let jadwal = this.$store.state.jadwal;
      for (let i = 0; i < jadwal.length; i++) {
        if (jadwal[i].konfirmasi === 1 || jadwal[i].konfirmasi === 3) {
          return true;
        }
      }

      return false;
    },
  },

  methods: {
    convertJadwal: function (jadwal) {
      return jadwal.length == 0
        ? []
        : jadwal.map((data) => ({
            id: data.id,
            title: data.produk.nama,
            start: data.tanggal_mulai,
            end: data.tanggal_selesai,
            backgroundColor: data.warna,
            borderColor: data.warna,
          }));
    },

    handleSelect: function (selectInfo) {
      let calendarApi = selectInfo.view.calendar;
      calendarApi.unselect();

      this.start_date_str = selectInfo.startStr;
      this.end_date_str = selectInfo.endStr;

      axios.get("/api/ppic/product").then((response) => {
        this.produk = response.data;
        $("#exampleModal").modal("show");
      });
      $("#exampleModal").modal("show");
    },

    handleEventClick: function (clickEventInfo) {
      let obj = clickEventInfo.event._def;
      this.confirmationMessage = this.message[obj.publicId];
      this.deleteJadwal = true;
      this.event_ref = clickEventInfo;

      $("#confirmation").modal("show");
    },

    disableEdit() {
      this.selectable = false;
    },

    enableEdit() {
      this.selectable = true;
    },

    // modal
    handleClick(event) {
      this.color = event.target.style.backgroundColor;
    },

    handleSubmit() {
      if (!this.produkValue || Number(this.quantity) <= 0) {
        alert("input error");
        return;
      }

      axios
        .post("/api/ppic/add-event", {
          produk_id: this.produkValue,
          jumlah: this.quantity,
          tanggal_mulai: this.start_date_str,
          tanggal_selesai: this.end_date_str,
          status: this.$route.params.status,
          warna: this.color,
        })
        .then((response) => {
          this.$store.commit("updateJadwal", response.data);
          $("#exampleModal").modal("hide");
          this.produkValue = "";
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
            $("#confirmation").modal("hide");
          });
      } else {
        axios
          .post("http://localhost:8000/api/ppic/send-bppb", {
            confirmation: 1,
          })
          .then((response) => {
            this.$store.commit("updateJadwal", response.data);

            $("#confirmation").modal("hide");
            this.$swal({
              icon: "success",
              text: "Berhasil mengirim permintaan",
            });
          });
      }
    },

    handleButtonNo: function () {
      $("#confirmation").modal("hide");
    },
  },

  mounted: function () {
    let api = this.$refs.fullCalendar.getApi();
    if (this.status == "penyusunan") {
      this.headerToolbar = "";
      api.next();
    }
    if (this.status == "selesai") {
      api.prev();
    }

    if (this.status == "penyusunan") this.enableEdit();
    else this.disableEdit();
  },
};
</script>

<template>
  <div class="row">
    <div class="col-xl-8">
      <div class="card">
        <div
          :class="[
            'card-header',
            'text-center',
            { 'bg-warning': status === 'penyusunan' },
            { 'bg-info': status === 'pelaksanaan' },
            { 'bg-success': status === 'selesai' },
          ]"
        >
          {{ status.toUpperCase() }}
        </div>
        <div class="card-body">
          <full-calendar
            ref="fullCalendar"
            id="calendar"
            :options="calendarOptions"
          />
        </div>
      </div>
    </div>
    <div class="col-xl-4">
      <button
        v-if="status === 'penyusunan' && !konfirmasi"
        class="btn btn-block btn-info mb-3"
        @click="sendBppb"
      >
        Minta Persetujuan
      </button>
      <div class="card">
        <div class="card-header text-center">Daftar Produksi</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover styled-table table-striped">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in this.$store.state.jadwal" :key="item.id">
                  <td>{{ item.produk.nama }}</td>
                  <td>{{ item.jumlah }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">
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
              <v-select
                :options="options"
                :reduce="(nama) => nama.value"
                v-model="produkValue"
              />
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Stok:</label>
                <div>GBJ: -</div>
                <div>GK : -</div>
              </div>
              <div class="form-group col-md-6">
                <label>Jumlah Produk:</label>
                <input type="number" class="form-control" v-model="quantity" />
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
      <div class="modal-dialog modal-dialog-centered modal-md">
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