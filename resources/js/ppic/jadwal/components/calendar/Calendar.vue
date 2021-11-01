<template>
  <full-calendar ref="fullCalendar" id="calendar" :options="calendarOptions" />
</template>

<script>
// import component
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

export default {
  components: {
    FullCalendar,
  },

  computed: {
    calendarOptions: {
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
    },
  },

  methods: {
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
  },
};
</script>