<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";

export default {
  components: {
    FullCalendar, // make the <FullCalendar> tag available
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

        events: [],

        select: this.handleSelect,
        eventClick: this.handleEventClick,
        eventMouseEnter: this.handleEventMouseEnter,
        eventMouseLeave: this.handleEventMouseLeave,
      },

      calendar: null,
    };
  },

  methods: {
    convertJadwal: function (jadwal) {
      return jadwal.length == 0
        ? []
        : jadwal.map((item) => ({
            id: item.id,
            title: `${item.produk.produk.nama} ${item.produk.nama}`,
            start: item.tanggal_mulai,
            end: item.tanggal_selesai,
            backgroundColor: item.warna,
            borderColor: item.warna,
          }));
    },

    handleSelect: function (selectInfo) {
      //   if (this.editable) {
      //     let calendarApi = selectInfo.view.calendar;
      //     calendarApi.unselect();
      //     this.$store.state.start_date_str = selectInfo.startStr;
      //     this.$store.state.end_date_str = selectInfo.endStr;
      //     axios.get("/api/ppic/product").then((response) => {
      //       this.produk = response.data;
      //       this.$root.$emit("product_modal_show");
      //     });
      //   }
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

    deleteEvent: function () {
      //   const id = this.event_ref.event._def.publicId;
      //   const status = this.status;
      //   axios
      //     .post("/api/ppic/delete-event", {
      //       id: id,
      //       status: status,
      //     })
      //     .then((response) => {
      //       this.$store.commit("updateJadwal", response.data);
      //       this.$root.$emit("confirm_modal_hide");
      //     });
    },
  },

  mounted() {
    // this.calendar = this.$refs.calendar;
    // console.log("jadwal from komponen");
    // console.log(this.$store.state.jadwal_perakitan);
  },

  computed: {
    jadwal_perakitan() {
      return this.$store.state.jadwal_perakitan;
    },
  },

  watch: {
    jadwal_perakitan(newVal, oldVal) {
      this.calendarOptions.events = this.convertJadwal(newVal);
    },
  },
};
</script>

<template>
  <div id="calendar-component">
    <FullCalendar ref="calendar" :options="calendarOptions" />
  </div>
</template>