<script>
import ConfirmModal from "./ConfirmModal.vue";

export default {
  components: {
    ConfirmModal,
  },

  props: {
    height: Number,
  },

  data: function () {
    return {
      comment: [],
    };
  },

  methods: {
    showButtonAction: function () {
      if (this.$store.state.state === "perencanaan") {
        if (this.$store.state.konfirmasi == 0) return "perencanaan";
        else if (this.$store.state.konfirmasi == 2) return "perencanaan";
      } else if (this.$store.state.state === "persetujuan") {
        if (this.$store.state.konfirmasi == 1) return "perubahan";
        else if (this.$store.state.konfirmasi == 2) return "perencanaan";
      } else if (this.$store.state.state === "perubahan") {
        if (this.$store.state.konfirmasi == 1) return "perencanan";
        else if (this.$store.state.konfirmasi == 2) return "perubahan";
      }
    },

    handleClick: function (state) {
      if (state === "persetujuan")
        this.$store.state.message =
          'Apakah anda yakin untuk mengirim permintaan <span class="badge badge-info">persetujuan</span> ini?';
      if (state === "perubahan")
        this.$store.state.message =
          'Apakah anda yakin untuk mengirim permintaan <span class="badge badge-warning">perubahan</span> ini?';

      this.$store.state.emit = "send-confirm";
      this.$root.$emit("confirm_modal_show");
    },

    convertState: function (state) {
      if (state === 1) return "perencanaan";
      else if (state === 2) return "persetujuan";
      else if (state === 3) return "perubahan";
      else if (state === "perencanaan") return 1;
      else if (state === "persetujuan") return 2;
      else if (state === "perubahan") return 3;
    },

    convertStatus: function (status) {
      if (status === 1) return "penyusunan";
      else if (status === 2) return "pelaksanaan";
      else if (status === 3) return "selesai";
    },

    getComment: function () {
      axios.get("/api/ppic/komentar").then((response) => {
        this.comment = response.data;
      });
    },

    sendConfirm: function () {
      console.log("send confirm");
      axios
        .post("/api/ppic/update-event", {
          state: this.convertState(this.$store.state.state) + 1,
          status: this.$store.state.status,
        })
        .then((response) => {
          this.$store.commit("updateJadwal", response.data);

          $("#confirmation").modal("hide");
          this.$swal({
            icon: "success",
            text: "Berhasil mengirim permintaan",
          });
        });
    },
  },

  mounted: function () {
    this.getComment();

    this.$root.$on("update_comment", () => {
      this.getComment();
    });

    this.$root.$on("hover_event", (id, color) => {
      let $ref = this.$refs["sample-ref-" + id][0];
      $ref.style.backgroundColor = color;
    });

    this.$root.$on("send-confirm", () => {
      this.sendConfirm();
    });
  },
};
</script>

<template>
  <div>
    <div v-if="$store.state.user.divisi_id === 24">
      <button
        v-if="showButtonAction() === 'perencanaan'"
        class="btn btn-block btn-info mb-3"
        @click="handleClick('persetujuan')"
      >
        Permintaan Persetujuan
      </button>
      <button
        v-if="showButtonAction() === 'perubahan'"
        class="btn btn-block btn-warning mb-3"
        @click="handleClick('perubahan')"
      >
        Permintaan Perubahan Jadwal
      </button>
    </div>

    <div class="card">
      <div class="card-header text-center">Daftar Produksi</div>
      <div
        class="card-body table-responsive p-0"
        :style="{ height: this.height / 2 + 'px' }"
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
                      'far fa-check-circle': item.konfirmasi === 1,
                    },
                    {
                      'far fa-times-circle': item.konfirmasi === 2,
                    },
                  ]"
                  :style="
                    item.konfirmasi === 1 ? { color: 'blue' } : { color: 'red' }
                  "
                ></i>
                {{ `${item.produk.produk.tipe} ${item.produk.nama}` }}
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
        class="card-body table-responsive p-0"
        :style="{ height: this.height / 2 + 'px' }"
      >
        <table id="table-komentar" class="table table-hover">
          <thead>
            <tr>
              <th>Tanggal</th>
              <th>Jenis</th>
              <th>Komentar</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="komen in comment" :key="komen.id">
              <td>{{ komen.tanggal }}</td>
              <td>
                {{
                  convertState(komen.state) + " " + convertStatus(komen.status)
                }}
              </td>
              <td>{{ komen.komentar }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>