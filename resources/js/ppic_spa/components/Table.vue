<template>
  <div>
    <h1 class="subtitle">{{ getMonthYear() }}</h1>
    <div class="buttons">
      <button
        v-if="
          this.$store.state.state_ppic === 'pembuatan' ||
          this.$store.state.state_ppic === 'revisi'
        "
        class="button is-success"
        @click="showAddProdukModal"
      >
        <span>Tambah <i class="fas fa-plus"></i></span>
      </button>
      <button
        v-if="
          this.$store.state.state_ppic === 'pembuatan' ||
          this.$store.state.state_ppic === 'revisi'
        "
        class="button"
        :class="{
          'is-loading': this.$store.state.isLoading,
          'is-primary': this.$store.state.state_ppic === 'pembuatan',
          'is-danger': this.$store.state.state_ppic === 'revisi',
        }"
        @click="sendEvent('persetujuan')"
      >
        Kirim
      </button>
      <button
        v-if="this.$store.state.state_ppic === 'disetujui'"
        class="button is-success"
        :class="{ 'is-loading': this.$store.state.isLoading }"
        @click="sendEvent('perubahan')"
      >
        Minta Perubahan
      </button>
    </div>
    <div class="table-container">
      <table
        class="table has-text-centered is-bordered"
        style="white-space: nowrap"
      >
        <thead>
          <tr>
            <th rowspan="2">Nama Produk</th>
            <th rowspan="2">Jumlah</th>
            <th
              v-if="
                this.$store.state.state_ppic === 'pembuatan' ||
                this.$store.state.state_ppic === 'revisi'
              "
              rowspan="2"
            >
              Aksi
            </th>
            <th :colspan="last_date">Tanggal</th>
          </tr>
          <tr>
            <th v-for="i in Array.from(Array(last_date).keys())" :key="i">
              {{ i + 1 }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in format_events" :key="item.id">
            <td>{{ item.title }}</td>
            <td>{{ item.jumlah }}</td>
            <td
              v-if="
                $store.state.state_ppic === 'pembuatan' ||
                $store.state.state_ppic === 'revisi'
              "
            >
              <div>
                <span class="is-clickable" @click="updateEvent(item.id)">
                  <i class="fas fa-edit"></i>
                </span>
                &nbsp;&nbsp;&nbsp;
                <span class="is-clickable" @click="deleteEvent(item.id)">
                  <i class="fas fa-trash"></i>
                </span>
              </div>
            </td>
            <td
              v-for="i in Array.from(Array(last_date).keys())"
              :key="i"
              :class="{
                background_yellow: isDate(item.tanggal, i + 1),
                background_black: weekend_date.indexOf(i + 1) !== -1,
              }"
            ></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="modal" :class="{ 'is-active': addProdukModal }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">
            {{ this.action === "add" ? "Pilih Produk" : "Ubah Jadwal" }}
          </p>
          <button
            class="delete"
            @click="addProdukModal = !addProdukModal"
          ></button>
        </header>
        <section class="modal-card-body">
          <div class="columns">
            <div class="column is-6">
              <div class="field">
                <label class="label">Tanggal Mulai</label>
                <div class="control">
                  <input
                    type="date"
                    :min="dateFormatter(year, month, 1)"
                    :max="dateFormatter(year, month, last_date)"
                    class="input"
                    v-model="start_date"
                  />
                </div>
              </div>
            </div>
            <div class="column is-6">
              <div class="field">
                <label class="label">Tanggal Selesai</label>
                <div class="control">
                  <input
                    type="date"
                    :min="dateFormatter(year, month, 1)"
                    :max="dateFormatter(year, month, last_date)"
                    class="input"
                    v-model="end_date"
                  />
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
                    v-model="produk"
                    @input="changeProduk"
                  />
                </div>
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
            v-if="action === 'add'"
            class="button is-success"
            :class="{ 'is-loading': this.$store.state.isLoading }"
            @click="submitAddProduk"
          >
            Tambah
          </button>
          <button
            v-else-if="action"
            class="button is-info"
            :class="{ 'is-loading': this.$store.state.isLoading }"
            @click="submitAddProduk"
          >
            Ubah
          </button>
        </footer>
      </div>
    </div>

    <div class="modal" :class="{ 'is-active': deleteProdukModal }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Hapus Produk</p>
          <button class="delete" @click="deleteProdukModal = false"></button>
        </header>
        <section class="modal-card-body">
          <p>
            Apakah anda yakin ingin menghapus produk ini dari daftar jadwal?
          </p>
        </section>
        <footer class="modal-card-foot">
          <div class="buttons is-justify-content-space-between">
            <button class="button is-success" @click="submitAddProduk">
              Ok
            </button>
            <button class="button is-danger" @click="deleteProdukModal = false">
              Batal
            </button>
          </div>
        </footer>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
  name: "table-component",

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
    vSelect,
  },

  data() {
    return {
      last_date: 1,
      month: 0,
      year: 0,
      weekend_date: [],

      addProdukModal: false,
      deleteProdukModal: false,

      jadwal_id: 0,
      start_date: "",
      end_date: "",
      produk: {},
      jumlah: 1,
      action: "add",

      data_produk: [],
      gk_stok: 0,
      gbj_stok: 0,
    };
  },

  async created() {
    this.$store.commit("setIsLoading", true);

    await axios.get("/api/ppic/data/gbj").then((response) => {
      this.data_produk = response.data;
    });

    this.$store.commit("setIsLoading", false);

    let date = new Date();
    if (this.status === "pelaksanaan") {
      this.month = date.getMonth();
      this.year = date.getFullYear();
    } else if (this.status === "penyusunan") {
      this.month = date.getMonth() + 1;
      this.year = date.getFullYear();
      if (this.month === 12) {
        this.month = 0;
        this.year += 1;
      }
    }

    date = new Date(this.year, this.month + 1, 0);
    this.last_date = date.getDate();

    date.setMonth(this.month);
    for (let i = 1; i <= this.last_date; i++) {
      date.setDate(i);
      if (date.getDay() == 6 || date.getDay() == 0) this.weekend_date.push(i);
    }

    console.log("created table");
    console.log(this.events);
    console.log(this.format_events);
  },

  methods: {
    isDate(tanggal, i) {
      for (const id in tanggal) {
        let start = new Date(tanggal[id][0]);
        let end = new Date(tanggal[id][1]);

        let start_number = start.getDate();
        let end_number = end.getDate();

        // handle end equal to last date
        if (start.getMonth() !== end.getMonth()) end_number = this.last_date;

        if (i >= start_number && i <= end_number) return true;
      }

      return false;
    },

    async showAddProdukModal() {
      this.resetData();
      this.action = "add";
      this.addProdukModal = true;
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

    async submitAddProduk() {
      this.$store.commit("setIsLoading", true);
      if (this.action === "add") {
        let data = {
          produk_id: this.produk.value,
          jumlah: this.jumlah,
          tanggal_mulai: this.start_date,
          tanggal_selesai: this.end_date,
          status: this.status,
          state: this.$store.state.state,
          konfirmasi: this.$store.state.konfirmasi,
          warna: "#007bff",
        };

        await axios
          .post("/api/ppic/create/perakitan", data)
          .then((response) => {
            this.$store.commit("setJadwal", response.data);
          });
      } else if (this.action === "update") {
        await axios
          .post("/api/ppic/update/perakitan/" + this.jadwal_id, {
            tanggal_mulai: this.start_date,
            tanggal_selesai: this.end_date,
            status: this.status,
          })
          .then((response) => {
            this.$store.commit("setJadwal", response.data);
          });
      } else if (this.action === "delete") {
        await axios
          .post("/api/ppic/delete/perakitan/" + this.jadwal_id)
          .then(() => {
            axios
              .get("/api/ppic/data/perakitan/" + this.status)
              .then((response) => {
                this.$store.commit("setJadwal", response.data);
              });
          });
      }
      this.$store.commit("setIsLoading", false);
      this.addProdukModal = false;
      this.deleteProdukModal = false;
      this.resetData();
    },

    async updateEvent(id) {
      this.action = "update";
      this.jadwal_id = id;

      let item = this.events.find((item) => item.id == id);

      this.start_date = item.start;
      this.end_date = item.end;
      this.jumlah = item.jumlah;
      this.produk = this.options.find((data) => data.value == item.produk_id);

      await axios
        .get("/api/ppic/data/gbj", {
          params: {
            id: item.produk_id,
          },
        })
        .then((response) => {
          if (response.data.length > 0) this.gbj_stok = response.data[0].stok;
          else this.gk_stok = 0;
        })
        .catch((error) => {
          console.log(error);
        });

      await axios
        .get("/api/ppic/data/gk/unit", {
          params: {
            id: item.produk_id,
          },
        })
        .then((response) => {
          if (response.data.length > 0) this.gk_stok = response.data[0].jml;
          else this.gk_stok = 0;
        })
        .catch((error) => {
          console.log(error);
        });

      this.addProdukModal = true;
    },

    deleteEvent(id) {
      this.action = "delete";
      this.jadwal_id = id;

      this.deleteProdukModal = true;
    },

    async sendEvent(state) {
      this.$store.commit("setIsLoading", true);
      await axios
        .post("/api/ppic/update/perakitans/" + this.status, {
          state: state,
          konfirmasi: 0,
        })
        .then((response) => {
          this.$store.commit("setJadwal", response.data);
        })
        .catch((error) => {
          console.log("error update data perakitan");
          console.log(error);
        });

      await axios
        .post("/api/ppic/create/komentar", {
          tanggal_permintaan: new Date(),
          state: this.$store.state.state,
          status: this.status,
        })
        .catch((error) => {
          console.log("error create komentar");
          console.log(error);
        });

      this.$store.commit("setIsLoading", false);
    },

    // helper
    resetData() {
      this.start_date = "";
      this.end_date = "";
      this.produk = {};
      this.jumlah = 1;
      this.gk_stok = 0;
      this.gbj_stok = 0;
    },

    dateFormatter(year, month, date) {
      let real_month = month + 1;
      real_month = real_month.toString();
      if (real_month.length == 1) {
        real_month = "0" + real_month;
      }

      let real_date = date.toString();
      if (real_date.length == 1) {
        real_date = "0" + real_date;
      }

      let result = year.toString() + "-" + real_month + "-" + real_date;
      return result;
    },

    getMonthYear() {
      let temp_month = "";
      switch (this.month) {
        case 0:
          temp_month = "Januari";
          break;
        case 1:
          temp_month = "Februari";
          break;
        case 2:
          temp_month = "Maret";
          break;
        case 3:
          temp_month = "April";
          break;
        case 4:
          temp_month = "Mei";
          break;
        case 5:
          temp_month = "Juni";
          break;
        case 6:
          temp_month = "Juli";
          break;
        case 7:
          temp_month = "Agustus";
          break;
        case 8:
          temp_month = "September";
          break;
        case 9:
          temp_month = "Oktober";
          break;
        case 10:
          temp_month = "November";
          break;
        case 11:
          temp_month = "Desember";
          break;
      }

      return temp_month + " " + this.year.toString();
    },
  },

  computed: {
    options: function () {
      let data = this.data_produk.map((data) => ({
        label: `${data.produk.nama} ${data.nama}`,
        value: data.id,
      }));

      return data;
    },

    format_events() {
      let data = [];
      let exists = {};
      for (let i = 0; i < this.events.length; i++) {
        exists = data.find(
          (item) => item.produk_id === this.events[i].produk_id
        );
        if (data.length === 0 || exists === undefined) {
          data.push({
            produk_id: this.events[i].produk_id,
            title: this.events[i].title,
            jumlah: this.events[i].jumlah,
            tanggal: {
              [this.events[i].id]: [this.events[i].start, this.events[i].end],
            },
          });
        } else {
          exists.tanggal[this.events[i].id] = [
            this.events[i].start,
            this.events[i].end,
          ];
          exists.jumlah += this.events[i].jumlah;
        }
      }

      return data;
    },
  },
};
</script>

<style scoped>
.background_yellow {
  background: yellow;
}

.background_black {
  background: black;
}
</style>