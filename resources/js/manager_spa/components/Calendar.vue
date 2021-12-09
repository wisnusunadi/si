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

        events: [],

        select: this.handleSelect,
        // eventClick: this.handleEventClick,
        // eventMouseEnter: this.handleEventMouseEnter,
        // eventMouseLeave: this.handleEventMouseLeave,
        eventDragStop: this.handleEventDragStop,
        eventDrop: this.handleEventDrop,
        eventResize: this.handleEventResize,
      },

      calendar: null,
      showModal: false,
      warna: ["#007bff", "#6c757d", "#28a745", "#dc3545", "#ffc107", "#17a2b8"],
      tanggal_mulai: "",
      tanggal_selesai: "",
      data_gbj: [],

      status: "",

      produk: {},
      jumlah: 1,
      color: "#007bff",
      gbj_stok: 0,
      gk_stok: 0,

      error_produk_modal: false,
      error_jumlah_modal: false,
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

    async handleSelect(selectInfo) {
      this.$store.commit("setIsLoading", true);
      await axios.get("/api/ppic/gbj/data").then((response) => {
        this.data_gbj = response.data;
        console.log(this.data_gbj);
      });
      this.$store.commit("setIsLoading", false);
      this.showModal = true;
      this.tanggal_mulai = selectInfo.startStr;
      this.tanggal_selesai = selectInfo.endStr;
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
        await axios.post("/api/ppic/delete-event/" + event.id).then(() => {
          event.remove();
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

    sorting_jadwal() {
      // this.local_data_perakitan.sort(
      //   (a, b) => new Date(a.tanggal_mulai) - new Date(b.tanggal_mulai)
      //   // (a, b) => a.jumlah - b.jumlah
      // );
      // console.log(this.local_data_perakitan);
    },

    async handleEventDrop(info) {
      this.$store.commit("setIsLoading", true);
      await axios.post("/api/ppic/update-event/" + info.event.id, {
        tanggal_mulai: this.convert_date(info.event.start),
        tanggal_selesai: this.convert_date(info.event.end),
      });
      this.$store.commit("setIsLoading", false);
    },

    async handleEventResize(info) {
      this.$store.commit("setIsLoading", true);
      await axios.post("/api/ppic/update-event/" + info.event.id, {
        tanggal_selesai: this.convert_date(info.event.end),
      });
      this.$store.commit("setIsLoading", false);
    },

    handleClick(event) {
      this.color = event.target.style.backgroundColor;
    },

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
      await axios.post("/api/ppic/add-event", data).then((response) => {
        this.$store.commit("setJadwal", response.data);
      });
      this.$store.commit("setIsLoading", false);

      this.showModal = false;

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

    changeProduk: function () {
      // axios.get("/api/ppic/product/" + this.produkValue).then((response) => {
      //   this.gbj_stok = response.data.gbj_stok;
      //   this.gk_stok = response.data.gk_stok;
      // });
    },
  },

  mounted() {
    this.calendar = this.$refs.calendar.getApi();
  },

  computed: {
    jadwal() {
      return this.$store.state.jadwal;
    },

    options: function () {
      return this.data_gbj.map((data) => ({
        label: `${data.produk.nama} ${data.nama}`,
        value: { id: data.id, nama: `${data.produk.nama} ${data.nama}` },
      }));
    },
  },

  watch: {
    jadwal(newVal, oldVal) {
      this.calendarOptions.events = this.convertJadwal(newVal);
      if (!this.status && this.$store.state.status === "penyusunan") {
        this.calendar.next();
        this.status = this.$store.state.status;
      }
    },
  },
};
</script>

<template>
  <div id="calendar-component">
    <div class="icon-text" style="justify-content: flex-end">
      <span id="trash-icon"><i class="fas fa-trash-alt"></i></span>
    </div>
    <FullCalendar ref="calendar" :options="calendarOptions" />

    <!-- modal -->
    <div v-if="showModal" class="modal" :class="{ 'is-active': showModal }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Pilih Produk</p>
          <button
            class="delete"
            aria-label="close"
            @click="showModal = !showModal"
          ></button>
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
                  <div>GBJ: {{ 0 }}</div>
                  <div>GK : {{ 0 }}</div>
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
  </div>
</template>

<style lang="scss" scoped>
#trash-icon {
  font-size: 20px;
  align-items: center;

  &:hover {
    font-size: 28px;
  }
}
</style>