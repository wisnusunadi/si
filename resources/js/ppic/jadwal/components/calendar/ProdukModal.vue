<template>
  <div class="modal fade" id="exampleModal">
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
</template>

<script>
export default {
  data: function () {
    return {
      start_date_str: "",
      end_date_str: "",
      event_ref: null,

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
    };
  },

  methods: {
    handleClick: function (event) {
      this.color = event.target.style.backgroundColor;
    },

    handleSubmit: function () {
      if (!this.produkValue || Number(this.quantity) <= 0) {
        alert("input error");
        return;
      }

      axios
        .post("/api/ppic/add-event", {
          produk_id: this.produkValue,
          jumlah: this.quantity,
          tanggal_mulai: this.start_date_str,
          tanggal_selesai: this.end_date_str,
          status: this.$route.params.status,
          warna: this.color,
        })
        .then((response) => {
          this.$store.commit("updateJadwal", response.data);
          $("#exampleModal").modal("hide");
          this.produkValue = "";
          this.quantity = 0;
        });
    },
  },
};
</script>