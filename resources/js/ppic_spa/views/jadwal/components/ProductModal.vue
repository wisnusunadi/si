<script>
import axios from "axios";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
  components: {
    vSelect,
  },

  props: {
    start_date: "",
    end_date: "",
  },

  data: function () {
    return {
      produk: [],
      produkValue: "",
      quantity: 0,
      colors: [
        "#007bff",
        "#6c757d",
        "#28a745",
        "#dc3545",
        "#ffc107",
        "#17a2b8",
      ],
      color: "#6c757d",
      gbj_stok: 0,
      gk_stok: 0,
    };
  },

  computed: {
    options: function () {
      return this.produk.map((data) => ({
        label: `${data.produk.nama_coo} ${data.nama}`,
        value: data.id,
      }));
    },
  },

  methods: {
    updateProduk: function () {
      axios.get("/api/ppic/product").then((response) => {
        this.produk = response.data;
      });
    },

    changeProduk: function () {
      axios.get("/api/ppic/product/" + this.produkValue).then((response) => {
        this.gbj_stok = response.data.gbj_stok;
        this.gk_stok = response.data.gk_stok;
      });
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
          tanggal_mulai: this.start_date + "T23:59:59",
          tanggal_selesai: this.end_date,
          status: this.$store.state.status,
          warna: this.color,
        })
        .then((response) => {
          this.$store.commit("updateJadwal", response.data);

          let element = this.$refs.modal;
          $(element).modal("hide");
          this.produkValue = "";
          this.quantity = 0;
        })
        .catch((err) => {
          console.log(err);
          alert("error handle input");
          console.log("why error?", err);
        });
    },

    handleClick: function (event) {
      this.color = event.target.style.backgroundColor;
    },
  },

  mounted: function () {
    this.$root.$on("product_modal_show", () => {
      this.updateProduk();
      let element = this.$refs.modal;
      $(element).modal("show");
    });

    this.$root.$on("product_modal_hide", () => {
      let element = this.$refs.modal;
      $(element).modal("hide");
      this.produkValue = "";
      this.gbj_stok = 0;
      this.gk_stok = 0;
    });
  },
};
</script>

<template>
  <div>
    <div class="modal fade" ref="modal">
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
              <vSelect
                :options="options"
                :reduce="(nama) => nama.value"
                v-model="produkValue"
                @input="changeProduk"
              />
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Stok:</label>
                <div>GBJ: {{ gbj_stok }}</div>
                <div>GK : {{ gk_stok }}</div>
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
  </div>
</template>