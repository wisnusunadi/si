<script>
// import library
import axios from "axios";

// import component
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
  components: {
    FullCalendar,
    "v-select": vSelect,
  },

  props: {
    status: String,
  },

  data: function () {
    return {
      start_date_str: "",
      end_date_str: "",
      event_ref: null,
      calendarApi: null,

      // modal
      produk: [],
      produkValue: "",
      quantity: 0,
      color: "#007bff",
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
      // editable: true,
      calendarHeight: 0,

      comment: [],
      state: "",
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
        selectable: this.editable(
          this.$store.state.state,
          this.$store.state.user
        ),

        events: this.convertJadwal(this.$store.state.jadwal),

        select: this.handleSelect,
        eventClick: this.editable(
          this.$store.state.state,
          this.$store.state.user
        )
          ? this.handleEventClick
          : null,
        eventMouseEnter: this.handleEventMouseEnter,
        eventMouseLeave: this.handleEventMouseLeave,
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

    proses_konfirmasi: function () {
      let jadwal = this.$store.state.jadwal;
      if (jadwal.length === 0) return -1;
      return jadwal[0].proses_konfirmasi;
    },

    konfirmasi_rencana: function () {
      console.log("konfirmasi rencana");
      let jadwal = this.$store.state.jadwal;
      if (jadwal.length === 0) return -1;
      for (let i = 0; i < jadwal.length; i++) {
        if (jadwal[i].konfirmasi_rencana === 0) {
          console.log(jadwal[i]);
          return 0;
        }
      }
      console.log("true");
      return 1;
    },

    konfirmasi_perubahan: function () {
      let jadwal = this.$store.state.jadwal;
      if (jadwal.length === 0) return -1;
      for (let i = 0; i < jadwal.length; i++) {
        if (jadwal[i].konfirmasi_perubahan === 0) return 0;
      }
      return 1;
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

    editable: function (state, user) {
      if (user.divisi_id !== 24) return false;

      if (state === "perencanaan") return true;
      else return false;
    },

    handleSelect: function (selectInfo) {
      if (this.editable) {
        let calendarApi = selectInfo.view.calendar;
        calendarApi.unselect();
        this.start_date_str = selectInfo.startStr;
        this.end_date_str = selectInfo.endStr;

        axios.get("/api/ppic/product").then((response) => {
          this.produk = response.data;
          $("#exampleModal").modal("show");
        });
        $("#exampleModal").modal("show");
      }
    },

    handleEventClick: function (clickEventInfo) {
      if (this.editable) {
        let obj = clickEventInfo.event._def;
        this.confirmationMessage = this.message[obj.publicId];
        this.deleteJadwal = true;
        this.event_ref = clickEventInfo;

        $("#confirmation").modal("show");
      }
    },

    handleEventMouseEnter: function (eventInfo) {
      let $ref = this.$refs["sample-ref-" + eventInfo.event._def.publicId][0];

      $ref.style.backgroundColor = "yellow";
      eventInfo.event.setProp("borderColor", "yellow");
    },

    handleEventMouseLeave: function (eventInfo) {
      let $ref = this.$refs["sample-ref-" + eventInfo.event._def.publicId][0];

      $ref.style.backgroundColor = "";
      eventInfo.event.setProp("borderColor", eventInfo.event.backgroundColor);
    },

    // disableEdit: function () {
    //   this.editable = false;
    // },

    // enableEdit: function () {
    //   this.editable = true;
    // },

    // modal
    handleClick: function (event) {
      this.color = event.target.style.backgroundColor;
    },

    handleSubmit: function () {
      if (!this.produkValue | (Number(this.quantity) <= 0)) {
        alert("input error");
        return;
      }

      axios
        .post("/api/ppic/add-event", {
          produk_id: this.produkValue,
          jumlah: this.quantity,
          tanggal_mulai: this.start_date_str,
          tanggal_selesai: this.end_date_str,
          status: this.$store.state.status,
          warna: this.color,
        })
        .then((response) => {
          this.$store.commit("updateJadwal", response.data);
          $("#exampleModal").modal("hide");
          this.produkValue = "";
          this.quantity = 0;
        });
    },

    sendToManager: function (state) {
      this.confirmationMessage = `Apakah Anda yakin ingin mengirim permintaan?`;
      this.deleteJadwal = false;
      this.state = state;

      $("#confirmation").modal("show");
    },

    handleButtonYes: function () {
      if (this.deleteJadwal) {
        axios
          .post("/api/ppic/delete-event", {
            id: this.event_ref.event._def.publicId,
            status: this.status,
          })
          .then((response) => {
            // alert(response.data);
            console.log(response.data);
            this.$store.commit("updateJadwal", response.data);
            $("#confirmation").modal("hide");
          });
      } else {
        axios
          .post("/api/ppic/update-event", {
            state: this.state,
            status: this.status,
          })
          .then((response) => {
            this.$store.commit("updateJadwal", response.data);

            $("#confirmation").modal("hide");
            this.$swal({
              icon: "success",
              text: "Berhasil mengirim permintaan",
            });
          });

        axios.get("/api/ppic/test-event", {
          params: {
            message: "menunggu persetujuan",
          },
        });
      }
    },

    handleButtonNo: function () {
      $("#confirmation").modal("hide");
    },

    showButtonAction: function () {
      if (this.$store.state.state === "perencanaan") {
        if (this.$store.state.konfirmasi == 0) return "perencanaan";
      } else if (this.$store.state.state === "persetujuan") {
        if (this.$store.state.konfirmasi == 1) return "perubahan";
        else if (this.$store.state.konfirmasi == 2) return "perencanaan";
      } else if (this.$store.state.state === "perubahan") {
        if (this.$store.state.konfirmasi == 1) return "perencanan";
        else if (this.$store.state.konfirmasi == 2) return "perubahan";
      }
    },

    // table
    tableItemHover: function (id) {
      // alert(id);
      // console.log(this.$refs.fullCalendar.getApi());
    },

    // common
    getCalendarHeight: function () {
      // console.log(this.$refs.calendar_card);
      // console.log(this.$refs.calendar_card.clientHeight);
      this.calendarHeight = this.$refs.calendar_card.clientHeight;
    },

    windowResize: function () {},
  },

  created: function () {
    window.addEventListener("resize", this.windowResize);
  },

  mounted: function () {
    this.getCalendarHeight();
    this.calendarApi = this.$refs.fullCalendar.getApi();

    if (this.status == "penyusunan") {
      this.headerToolbar = "";
      this.calendarApi.next();
      // if (this.$store.state.proses_konfirmasi) this.disableEdit();
      // else this.enableEdit();
    }

    if (this.status === "pelaksanaan") {
      // this.disableEdit();
    }

    if (this.$store.state.user.divisi_id === 3) {
      // this.disableEdit();
    }

    // axios.get("/api/get-comment").then((response) => {
    //   this.comment = response.data;
    // });
  },

  updated: function () {
    if (this.status === "penyusunan") {
      // if (this.$store.state.proses_konfirmasi) this.disableEdit();
      // else this.enableEdit();
    }
  },

  destroyed: function () {
    window.removeEventListener("resize", this.windowResize);
  },
};
</script>

<template>
  <div class="row">
    <div class="col-xl-8">
      <div class="card" ref="calendar_card">
        <div :class="['card-header', 'text-center']">
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
      <div v-if="$store.state.user.divisi_id === 24">
        <button
          v-if="showButtonAction() === 'perencanaan'"
          class="btn btn-block btn-info mb-3"
          @click="sendToManager('persetujuan')"
        >
          Permintaan Persetujuan
        </button>
        <button
          v-if="showButtonAction() === 'perubahan'"
          class="btn btn-block btn-warning mb-3"
          @click="sendToManager('perubahan')"
        >
          Permintaan Perubahan Jadwal
        </button>
      </div>

      <div class="card">
        <div class="card-header text-center">Daftar Produksi</div>
        <div
          class="card-body table-responsive p-0"
          :style="{ height: (this.calendarHeight * 5) / 12 + 'px' }"
        >
          <table class="table table-hover table-head-fixed">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="item in this.$store.state.jadwal"
                :key="item.id"
                :ref="'sample-ref-' + item.id"
              >
                <td>
                  <i
                    v-if="$store.state.user.divisi_id === 24"
                    :class="[
                      {
                        'far fa-check-circle':
                          item.proses_konfirmasi === 2 &&
                          item.konfirmasi_rencana === 1,
                      },
                      {
                        'far fa-times-circle':
                          item.proses_konfirmasi === 2 &&
                          item.konfirmasi_rencana === 0,
                      },
                    ]"
                    :style="
                      item.konfirmasi_rencana === 1
                        ? { color: 'blue' }
                        : { color: 'red' }
                    "
                  ></i>
                  {{ item.produk.nama }}
                </td>
                <td>{{ item.jumlah }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="card">
        <div class="card-header text-center">Komentar</div>
        <div
          class="card-body"
          :style="{ height: this.calendarHeight / 3 + 'px' }"
        >
          <ul>
            <li>comment 1</li>
            <li>comment 2</li>
            <li>comment 3</li>
            <li>comment 4</li>
            <li>comment 5</li>
            <li>comment 6</li>
            <li>comment 7</li>
          </ul>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="exampleModal">
        <div
          class="modal-dialog modal-dialog-centered modal-md"
          role="document"
        >
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
                  <input
                    type="number"
                    class="form-control"
                    v-model="quantity"
                  />
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
  </div>
</template>

<style scoped>
</style>