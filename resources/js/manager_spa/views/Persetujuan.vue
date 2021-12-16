<template>
  <div>
    <div class="columns">
      <div class="column is-12">
        <div class="box">
          <table
            v-if="
              this.$store.state.konfirmasi == 0 &&
              this.$store.state.state !== 'perencanaan'
            "
            class="table is-fullwidth has-text-centered"
          >
            <thead v-if="this.$store.state.state === 'persetujuan'">
              <tr>
                <th rowspan="2">Produk</th>
                <th rowspan="2">Jumlah</th>
                <th rowspan="2">Tanggal Mulai</th>
                <th rowspan="2">Tanggal Selesai</th>
                <th colspan="2">Aksi</th>
              </tr>
              <tr>
                <th>
                  <div class="field">
                    <div class="control">
                      <label class="checkbox">
                        <input
                          type="checkbox"
                          v-model="all_acc_jadwal"
                          @click="selectAllSetuju"
                        />
                        Setuju
                      </label>
                    </div>
                  </div>
                </th>
                <th>
                  <div class="field">
                    <div class="control">
                      <label class="checkbox">
                        <input
                          type="checkbox"
                          v-model="all_reject_jadwal"
                          @click="selectAllTolak"
                        />
                        Tolak
                      </label>
                    </div>
                  </div>
                </th>
              </tr>
            </thead>
            <thead v-if="this.$store.state.state === 'perubahan'">
              <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
              </tr>
            </thead>
            <tbody v-if="this.$store.state.state === 'persetujuan'">
              <tr v-for="data in this.$store.state.jadwal" :key="data.id">
                <td>{{ `${data.produk.produk.nama} ${data.produk.nama}` }}</td>
                <td>{{ data.jumlah }}</td>
                <td>{{ data.tanggal_mulai }}</td>
                <td>{{ data.tanggal_selesai }}</td>
                <td>
                  <div class="field">
                    <div class="control">
                      <input
                        type="checkbox"
                        v-model="acc_jadwal"
                        :value="data"
                        @click="accCheckbox"
                      />
                    </div>
                  </div>
                </td>
                <td>
                  <div class="field">
                    <div class="control">
                      <input
                        type="checkbox"
                        v-model="reject_jadwal"
                        :value="data"
                        @click="rejectCheckbox"
                      />
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
            <tbody v-if="this.$store.state.state === 'perubahan'">
              <tr v-for="data in this.$store.state.jadwal" :key="data.id">
                <td>{{ `${data.produk.produk.nama} ${data.produk.nama}` }}</td>
                <td>{{ data.jumlah }}</td>
                <td>{{ data.tanggal_mulai }}</td>
                <td>{{ data.tanggal_selesai }}</td>
              </tr>
            </tbody>
          </table>
          <div v-else class="has-text-centered">data kosong</div>
        </div>
      </div>
    </div>
    <div
      v-if="this.$store.state.jadwal.length > 0"
      class="is-flex is-justify-content-flex-end"
    >
      <div v-if="this.$store.state.state === 'persetujuan'">
        <button
          v-if="acc_jadwal.length + reject_jadwal.length === jadwal.length"
          class="button is-success"
          @click="handleSend('kirim')"
        >
          Kirim
        </button>
      </div>
      <div v-if="this.$store.state.state === 'perubahan'">
        <button class="button is-success" @click="handleSend('setuju')">
          Setuju
        </button>
        <button class="button is-danger" @click="handleSend('tolak')">
          Tolak
        </button>
      </div>
    </div>

    <!-- modal -->
    <div v-if="showModal" class="modal" :class="{ 'is-active': showModal }">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Komentar</p>
          <button
            class="delete"
            aria-label="close"
            @click="showModal = !showModal"
          ></button>
        </header>
        <section class="modal-card-body">
          <div class="field">
            <div class="control">
              <textarea class="textarea" placeholder="Komentar"></textarea>
            </div>
          </div>
        </section>
        <footer class="modal-card-foot">
          <button class="button is-success" @click="handle_func">Kirim</button>
          <button class="button" @click="showModal = false">Batal</button>
        </footer>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      jadwal_diterima: [],

      acc_jadwal: [],
      reject_jadwal: [],
      all_acc_jadwal: false,
      all_reject_jadwal: false,

      showModal: false,
      handle_func: null,
    };
  },

  methods: {
    resetData() {
      this.acc_jadwal = [];
      this.reject_jadwal = [];
      this.all_acc_jadwal = false;
      this.all_reject_jadwal = false;
    },

    selectAllSetuju: function () {
      this.reject_jadwal = [];
      this.acc_jadwal = [];
      this.all_reject_jadwal = false;
      if (!this.all_acc_jadwal) {
        for (let i = 0; i < this.jadwal.length; i++) {
          this.acc_jadwal.push(this.jadwal[i]);
        }
      }
    },

    selectAllTolak: function () {
      this.reject_jadwal = [];
      this.acc_jadwal = [];
      this.all_acc_jadwal = false;
      if (!this.all_reject_jadwal) {
        for (let i = 0; i < this.jadwal.length; i++) {
          this.reject_jadwal.push(this.jadwal[i]);
        }
      }
    },

    accCheckbox: function (event) {
      this.reject_jadwal = this.reject_jadwal.filter(
        (item) => item !== event.target._value
      );
    },

    rejectCheckbox: function (event) {
      this.acc_jadwal = this.acc_jadwal.filter(
        (item) => item !== event.target._value
      );
    },

    async handleKirim() {
      this.$store.commit("setIsLoading", true);
      await axios.post(
        "/api/ppic/update-many-event/" + this.$store.state.status,
        {
          data: this.acc_jadwal,
          konfirmasi: 1,
        }
      );

      await axios
        .post("/api/ppic/update-many-event/" + this.$store.state.status, {
          data: this.reject_jadwal,
          konfirmasi: 2,
        })
        .then((response) => {
          this.$store.commit("setJadwal", response.data);
          this.resetData();
        });
      this.$store.commit("setIsLoading", false);
      this.showModal = false;
    },

    async handleSetuju() {
      this.$store.commit("setIsLoading", true);
      await axios
        .post("/api/ppic/update-many-event/" + this.$store.state.status, {
          state: "perencanaan",
          konfirmasi: 0,
        })
        .then((response) => {
          this.$store.commit("setJadwal", response.data);
          this.resetData();
        });
      this.$store.commit("setIsLoading", false);
      this.showModal = false;
    },

    async handleTolak() {
      this.$store.commit("setIsLoading", true);
      await axios
        .post("/api/ppic/update-many-event/" + this.$store.state.status, {
          state: "persetujuan",
          konfirmasi: 1,
        })
        .then((response) => {
          this.$store.commit("setJadwal", response.data);
          this.resetDate();
        });
      this.$store.commit("setIsLoading", false);
      this.showModal = false;
    },

    handleSend(status) {
      this.showModal = true;
      if (status === "kirim") {
        this.handle_func = this.handleKirim;
      } else if (status === "setuju") {
        this.handle_func = this.handleSetuju;
      } else if (status === "tolak") {
        this.handle_func = this.handleTolak;
      }
    },
  },

  computed: {
    jadwal() {
      return this.$store.state.jadwal;
    },
  },

  watch: {
    jadwal(curVal) {
      this.jadwal_diterima = curVal;
    },
  },
};
</script>