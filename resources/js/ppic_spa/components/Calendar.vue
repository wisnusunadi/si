<template>
  <div id="calendar-component">
    <div class="icon-text" style="justify-content: flex-end">
      <span id="trash-icon"><i class="fas fa-trash-alt"></i></span>
    </div>
    <FullCalendar ref="calendar" :options="calendarOptions" />

    <!-- modal -->
    <div v-if="selectModal" class="modal" :class="{ 'is-active': selectModal }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Pilih Produk</p>
          <button class="delete" @click="selectModal = !selectModal"></button>
        </header>
        <section class="modal-card-body">
          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Warna Label</label>
            </div>
            <div class="field-body">
              <div class="field">
                <div class="control">
                  <button
                    v-for="item in warna"
                    :key="item"
                    class="button"
                    @click="handleClick"
                    :style="{
                      padding: '20px',
                      margin: '8px',
                      backgroundColor: item,
                      borderColor: item,
                    }"
                  ></button>
                </div>
              </div>
            </div>
          </div>
          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Produk</label>
            </div>
            <div class="field-body">
              <div class="field">
                <div class="control">
                  <vSelect
                    :options="options"
                    :reduce="(nama) => nama.value"
                    v-model="produk"
                    @input="changeProduk"
                  />
                </div>
                <p
                  class="help is-danger"
                  :class="{ 'is-hidden': !error_produk_modal }"
                >
                  produk harus dipilih
                </p>
              </div>
            </div>
          </div>
          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Jumlah</label>
            </div>
            <div class="field-body">
              <div class="field">
                <div class="control">
                  <input class="input" type="number" min="1" v-model="jumlah" />
                </div>
                <p
                  class="help is-danger"
                  :class="{ 'is-hidden': !error_jumlah_modal }"
                >
                  jumlah harus diisi
                </p>
              </div>
            </div>
          </div>
          <div class="field is-horizontal">
            <div class="field-label is-normal">
              <label class="label">Stok</label>
            </div>
            <div class="field-body">
              <div class="field">
                <div class="control">
                  <div>GBJ: {{ gbj_stok }}</div>
                  <div>GK : {{ gk_stok }}</div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <footer class="modal-card-foot">
          <button
            class="button"
            :class="{ 'is-loading': this.$store.state.isLoading }"
            :style="{ backgroundColor: color, borderColor: color }"
            @click="handleSubmit"
          >
            Tambah
          </button>
        </footer>
      </div>
    </div>

    <div class="modal" :class="{ 'is-active': detailModal }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Daftar Produk</p>
          <button class="delete"></button>
        </header>
        <section>
          <table class="table">
            <thead>
              <tr>
                <th>Produk</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="produk in selectedEvents" :key="produk.id">
                <td>{{ produk.nama }}</td>
                <td>{{ produk.jumlah }}</td>
              </tr>
            </tbody>
          </table>
        </section>
        <footer class="modal-card-foot"></footer>
      </div>
    </div>
  </div>
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";

import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

import $ from "jquery";
import axios from "axios";

export default {
  name: "CalendarComponent",

  props: {
    events: {
      type: Array,
      required: true,
    },

    status: {
      type: String,
      required: true,
    },
  },

  components: {
    FullCalendar,
    vSelect,
  },

  data: function () {
    return {
      calendarOptions: {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        locale: "id",
        headerToolbar: {
          end: "",
        },
        selectable: true,
        editable: true,
        weekends: false,
        showNonCurrentDates: false,

        events: this.events,

        select: this.handleSelect,
        eventDragStop: this.handleEventDragStop,
        eventDrop: this.handleEventDrop,
        eventResize: this.handleEventResize,

        // dateClick: () => alert("date click"),
        // eventClick: this.handleEventClick,
        // eventMouseEnter: this.handleEventMouseEnter,
        // eventMouseLeave: this.handleEventMouseLeave,
      },

      selectModal: false,
      detailModal: false,
      warna: ["#007bff", "#6c757d", "#28a745", "#dc3545", "#ffc107", "#17a2b8"],

      selectedEvents: [],

      tanggal_mulai: "",
      tanggal_selesai: "",
      produk: {},
      jumlah: 1,
      color: "#007bff",

      gbj_stok: 0,
      gk_stok: 0,

      error_produk_modal: false,
      error_jumlah_modal: false,
    };
  },

  mounted() {
    if (this.status === "penyusunan") this.$refs.calendar.getApi().next();
  },

  methods: {
    async handleSelect(selectInfo) {
      let start = selectInfo.start.getDate();
      let end = selectInfo.end.getDate();
      console.log(end - start);
      // this.$store.commit("setIsLoading", true);

      await axios.get("/api/ppic/data/gbj").then((response) => {
        this.data_gbj = response.data;
      });

      // this.$store.commit("setIsLoading", false);
      this.selectModal = true;
      // this.tanggal_mulai = selectInfo.startStr;
      // this.tanggal_selesai = selectInfo.endStr;
    },

    async handleEventDragStop({ event, jsEvent }) {
      var trashEl = $("#trash-icon");
      var ofs = trashEl.offset();
      var x1 = ofs.left;
      var x2 = ofs.left + trashEl.outerWidth(true);
      var y1 = ofs.top;
      var y2 = ofs.top + trashEl.outerHeight(true);
      if (
        jsEvent.pageX >= x1 &&
        jsEvent.pageX <= x2 &&
        jsEvent.pageY >= y1 &&
        jsEvent.pageY <= y2
      ) {
        this.$store.commit("setIsLoading", true);
        await axios.post("/api/ppic/delete/perakitan/" + event.id).then(() => {
          event.remove();
          axios
            .get("/api/ppic/data/perakitan/" + this.$store.state.status)
            .then((response) => {
              this.$store.commit("setJadwal", response.data);
            });
        });
        this.$store.commit("setIsLoading", false);
      }
    },

    convert_date(date) {
      let year = date.getFullYear();
      let month = date.getMonth() + 1;
      let dt = date.getDate();

      if (dt < 10) {
        dt = "0" + dt;
      }
      if (month < 10) {
        month = "0" + month;
      }

      return year + "-" + month + "-" + dt;
    },

    async handleEventDrop(info) {
      this.$store.commit("setIsLoading", true);
      await axios
        .post("/api/ppic/update/perakitan/" + info.event.id, {
          tanggal_mulai: this.convert_date(info.event.start),
          tanggal_selesai: this.convert_date(info.event.end),
          status: this.$store.state.status,
        })
        .then((response) => {
          this.$store.commit("setJadwal", response.data);
        });
      this.$store.commit("setIsLoading", false);
    },

    async handleEventResize(info) {
      this.$store.commit("setIsLoading", true);
      await axios
        .post("/api/ppic/update/perakitan/" + info.event.id, {
          tanggal_selesai: this.convert_date(info.event.end),
          status: this.$store.state.status,
        })
        .then((response) => {
          this.$store.commit("setJadwal", response.data);
        });
      this.$store.commit("setIsLoading", false);
    },

    handleClick(event) {
      this.color = event.target.style.backgroundColor;
    },

    handleEventClick: function (clickEventInfo) {
      //   if (this.editable) {
      //     let obj = clickEventInfo.event._def;
      //     this.$store.state.message = this.messageArray[obj.publicId];
      //     this.event_ref = clickEventInfo;
      //     this.$store.state.emit = "delete-event";
      //     this.$root.$emit("confirm_modal_show");
      //   }
    },

    handleEventMouseEnter: function (eventInfo) {
      //   const id = eventInfo.event._def.publicId;
      //   this.$root.$emit("hover_event", id, "yellow");
      //   // eventInfo.event.setProp("borderColor", "yellow");
    },

    handleEventMouseLeave: function (eventInfo) {
      //   const id = eventInfo.event._def.publicId;
      //   this.$root.$emit("hover_event", id, "");
      //   eventInfo.event.setProp("borderColor", eventInfo.event.backgroundColor);
    },

    // modal
    async handleSubmit() {
      let err = 0;
      if (!this.produk) {
        this.error_produk_modal = true;
        err += 1;
      }
      if (this.jumlah < 1) {
        this.error_jumlah_modal = true;
        err += 1;
      }

      if (err) return;

      this.$store.commit("setIsLoading", true);
      let data = {
        produk_id: this.produk.id,
        jumlah: this.jumlah,
        tanggal_mulai: this.tanggal_mulai,
        tanggal_selesai: this.tanggal_selesai,
        status: this.$store.state.status,
        state: this.$store.state.state,
        konfirmasi: this.$store.state.konfirmasi,
        warna: this.color,
      };
      await axios.post("/api/ppic/create/perakitan", data).then((response) => {
        this.$store.commit("setJadwal", response.data);
      });
      this.$store.commit("setIsLoading", false);

      this.selectModal = false;

      this.produk = {};
      this.jumlah = 1;
      this.color = "#007bff";
      this.gbj_stok = 0;
      this.gk_stok = 0;

      this.error_produk_modal = false;
      this.error_jumlah_modal = false;

      this.tanggal_mulai;
      this.tanggal_selesai;
    },

    async changeProduk() {
      console.log(this.produk);
      this.$store.commit("setIsLoading", true);
      await axios
        .get("/api/ppic/data/gbj", {
          params: {
            id: this.produk.id,
          },
        })
        .then((response) => {
          if (response.data.length > 0) this.gbj_stok = response.data[0].stok;
          else this.gk_stok = 0;
          console.log(response.data);
        })
        .catch((error) => {
          console.log(error);
        });

      await axios
        .get("/api/ppic/data/gk/unit", {
          params: {
            id: this.produk.id,
          },
        })
        .then((response) => {
          if (response.data.length > 0) this.gk_stok = response.data[0].jml;
          else this.gk_stok = 0;
        })
        .catch((error) => {
          console.log(error);
        });

      this.$store.commit("setIsLoading", false);
    },

    // helper
    filterEvent(date) {
      let current = new Date(date);
      this.selectedEvents = [];

      this.events.forEach((event) => {
        let start = new Date(event.start);
        let end = new Date(event.end);

        if (current >= start && current < end) this.selectedEvents.push(event);
      });
    },
  },

  computed: {
    options: function () {
      return this.data_gbj.map((data) => ({
        label: `${data.produk.nama} ${data.nama}`,
        value: { id: data.id, nama: `${data.produk.nama} ${data.nama}` },
      }));
    },
  },

  watch: {
    events(newVal, oldVal) {
      if (this.$store.state.user.divisi_id == 24) {
        if (
          this.$store.state.state_ppic === "pembuatan" ||
          this.$store.state.state_ppic === "revisi"
        ) {
          this.calendarOptions.selectable = true;
          this.calendarOptions.editable = true;
        } else {
          this.calendarOptions.selectable = false;
          this.calendarOptions.editable = false;
        }
      } else {
        this.calendarOptions.selectable = false;
        this.calendarOptions.editable = false;
      }
    },
  },
};
</script>


<style lang="scss" scoped>
#trash-icon {
  font-size: 20px;
  align-items: center;

  &:hover {
    font-size: 28px;
  }
}
</style>