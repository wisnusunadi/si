<script>
// import library
import axios from "axios";

// import component
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

import Table from "./Table.vue";
import ProductModal from "./ProductModal.vue";
import ConfirmModal from "./ConfirmModal.vue";

export default {
  components: {
    FullCalendar,
    Table,
    ProductModal,
    ConfirmModal,
  },

  props: {
    status: String,
  },

  data: function () {
    return {
      event_ref: null,
      calendarApi: null,
      calendarHeight: 0,
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
        selectable: this.editable,

        events: this.convertJadwal(this.$store.state.jadwal),

        select: this.handleSelect,
        eventClick: this.handleEventClick,
        eventMouseEnter: this.handleEventMouseEnter,
        eventMouseLeave: this.handleEventMouseLeave,
      };
    },

    messageArray: function () {
      let result = {};
      let jadwal = this.$store.state.jadwal;
      for (let i = 0; i < jadwal.length; i++) {
        result[jadwal[i].id] = `
          produk: ${jadwal[i].produk.produk.nama_coo} ${jadwal[i].produk.nama} <br />
          Jumlah: ${jadwal[i].jumlah}  <br />
          <br />
          Apakah Anda ingin menghapus produk ini dari jadwal?
        `;
      }

      return result;
    },

    editable: function () {
      if (
        this.$store.state.jadwal.length === 0 &&
        this.$store.state.status === "penyusunan"
      )
        return true;

      if (this.$store.state.user.divisi_id !== 24) return false;

      if (this.$store.state.state === "perencanaan") return true;
      else return false;
    },
  },

  methods: {
    convertJadwal: function (jadwal) {
      return jadwal.length == 0
        ? []
        : jadwal.map((item) => ({
            id: item.id,
            title: `${item.produk.produk.nama_coo} ${item.produk.nama}`,
            start: item.tanggal_mulai,
            end: item.tanggal_selesai,
            backgroundColor: item.warna,
            borderColor: item.warna,
          }));
    },

    handleSelect: function (selectInfo) {
      if (this.editable) {
        let calendarApi = selectInfo.view.calendar;
        calendarApi.unselect();
        this.$store.state.start_date_str = selectInfo.startStr;
        this.$store.state.end_date_str = selectInfo.endStr;

        axios.get("/api/ppic/product").then((response) => {
          this.produk = response.data;
          this.$root.$emit("product_modal_show");
        });
      }
    },

    handleEventClick: function (clickEventInfo) {
      if (this.editable) {
        let obj = clickEventInfo.event._def;
        this.$store.state.message = this.messageArray[obj.publicId];
        this.event_ref = clickEventInfo;

        this.$store.state.emit = "delete-event";
        this.$root.$emit("confirm_modal_show");
      }
    },

    handleEventMouseEnter: function (eventInfo) {
      const id = eventInfo.event._def.publicId;
      this.$root.$emit("hover_event", id, "yellow");
      // eventInfo.event.setProp("borderColor", "yellow");
    },

    handleEventMouseLeave: function (eventInfo) {
      const id = eventInfo.event._def.publicId;
      this.$root.$emit("hover_event", id, "");
      eventInfo.event.setProp("borderColor", eventInfo.event.backgroundColor);
    },

    deleteEvent: function () {
      const id = this.event_ref.event._def.publicId;
      const status = this.status;

      axios
        .post("/api/ppic/delete-event", {
          id: id,
          status: status,
        })
        .then((response) => {
          this.$store.commit("updateJadwal", response.data);
          this.$root.$emit("confirm_modal_hide");
        });
    },

    // common
    getCalendarHeight: function () {
      this.calendarHeight = this.$refs.calendar_card.clientHeight;
    },
  },

  created: function () {
    window.addEventListener("resize", this.getCalendarHeight);
  },

  mounted: function () {
    this.getCalendarHeight();
    this.calendarApi = this.$refs.fullCalendar.getApi();

    if (this.status == "penyusunan") {
      this.headerToolbar = "";
      this.calendarApi.next();
    }

    this.$root.$on("delete-event", () => {
      this.deleteEvent();
    });
  },

  destroyed: function () {
    window.removeEventListener("resize", this.getCalendarHeight);
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
      <Table :height="calendarHeight" />
    </div>
  </div>
</template>