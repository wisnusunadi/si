<template>
  <div class="content">
    <form @submit.prevent="handleSubmit">
      <div class="row d-flex justify-content-center">
        <div class="col-8">
          <h5>Info Umum Paket</h5>
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="form-group row">
                    <label
                      for="nama_produk"
                      class="col-4 col-form-label"
                      style="text-align: right"
                      >Nama Paket</label
                    >
                    <div class="col-6">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Masukkan Nama Paket"
                        v-model="nama_paket"
                      />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label
                      for="nama_produk"
                      class="col-4 col-form-label"
                      style="text-align: right"
                      >Harga</label
                    >
                    <div class="input-group col-5">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                      </div>
                      <input
                        type="text"
                        class="form-control"
                        value=""
                        placeholder="Masukkan Harga"
                        v-model="harga"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row d-flex justify-content-center">
        <div class="col-8">
          <h5>Detail Produk Paket</h5>
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <table class="table" style="text-align: center">
                    <thead>
                      <tr>
                        <th colspan="5">
                          <button
                            type="button"
                            class="btn btn-primary float-right"
                            @click="addRow()"
                          >
                            <i class="fas fa-plus"></i> Produk
                          </button>
                        </th>
                      </tr>
                      <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kelompok</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(row, index) in rows">
                        <td>{{ index + 1 }}</td>
                        <td>
                          <div class="form-group">
                            <v-select
                              :options="[{ label: 'Canada', code: 'ca' }]"
                            ></v-select>
                          </div>
                        </td>
                        <td>{{ row.kelompok_produk }}Alat Kesehatan</td>
                        <td>
                          <div class="form-group d-flex justify-content-center">
                            <input
                              type="number"
                              class="form-control"
                              v-model="row.jumlah"
                              style="width: 50%"
                            />
                          </div>
                        </td>
                        <td>
                          <a @click="removeRow(index)"
                            ><i class="fas fa-minus" style="color: red"></i
                          ></a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row d-flex justify-content-center">
        <div class="col-8">
          <router-link :to="{ name: 'show' }"
            ><span class="float-left"
              ><button type="button" class="btn btn-danger">Batal</button></span
            ></router-link
          >
          <span class="float-right"
            ><button type="submit" class="btn btn-info">Tambah</button></span
          >
        </div>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      nama_paket: "",
      harga: "",
      previewImage: null,
      rows: [
        {
          produk_id: "",
          kelompok_produk: "",
          jumlah: "",
          produk_iderror: false,
          kelompok_produkerror: false,
          jumlaherror: false,
        },
      ],
    };
  },

  methods: {
    handleSubmit: function () {
      console.log({
        nama_paket: this.nama_paket,
        harga: this.harga,
      });
      alert("ok");
    },
    addRow: function () {
      this.rows.push({
        produk_id: "",
        kelompok_produk: "",
        jumlah: "",
      });
    },
    removeRow: function (index) {
      //console.log(row);
      this.rows.splice(index, 1);
    },
    uploadgambar(e) {
      const image = e.target.files[0];
      const reader = new FileReader();
      reader.readAsDataURL(image);
      reader.onload = (e) => {
        this.previewImage = e.target.result;
        console.log(this.previewImage);
      };
    },
  },
  watch: {
    harga: function (newValue) {
      const result = newValue
        .replace(/\D/g, "")
        .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      this.harga = result;
    },
  },
  // methods:{
  //     function formatNumber(n) {
  //     // format number 1000000 to 1,234,567
  //         return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
  //     }
  //     function formatCurrency(input, blur) {
  //     // appends $ to value, validates decimal side
  //     // and puts cursor back in right position.

  //     // get input value
  //     var input_val = input.val();

  //     // don't validate empty input
  //     if (input_val === "") { return; }

  //     // original length
  //     var original_len = input_val.length;

  //     // initial caret position
  //     var caret_pos = input.prop("selectionStart");

  //     // check for decimal
  //     if (input_val.indexOf(".") >= 0) {

  //         // get position of first decimal
  //         // this prevents multiple decimals from
  //         // being entered
  //         var decimal_pos = input_val.indexOf(".");

  //         // split number by decimal point
  //         var left_side = input_val.substring(0, decimal_pos);
  //         var right_side = input_val.substring(decimal_pos);

  //         // add commas to left side of number
  //         left_side = formatNumber(left_side);

  //         // validate right side
  //         right_side = formatNumber(right_side);

  //         // On blur make sure 2 numbers after decimal
  //         if (blur === "blur") {
  //         right_side += "00";
  //         }

  //         // Limit decimal to only 2 digits
  //         right_side = right_side.substring(0, 2);

  //         // join number by .
  //         input_val = "$" + left_side + "." + right_side;

  //     } else {
  //         // no decimal entered
  //         // add commas to number
  //         // remove all non-digits
  //         input_val = formatNumber(input_val);
  //         input_val = "$" + input_val;

  //         // final formatting
  //         if (blur === "blur") {
  //         input_val += ".00";
  //         }
  //     }

  //     // send updated string to input
  //     input.val(input_val);

  //     // put caret back in the right position
  //     var updated_len = input_val.length;
  //     caret_pos = updated_len - original_len + caret_pos;
  //     input[0].setSelectionRange(caret_pos, caret_pos);
  //     }
  // }
};
</script>
<style>
.centered {
  margin: 0 auto;
}
</style>