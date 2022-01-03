<template>
  <div class="content">
    <form action="">
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
                        pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$"
                        value=""
                        data-type="currency"
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
                            <i class="fas fa-plus"></i>
                            Produk
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
                      <!-- <tr v-for="(row, index) in rows"> -->
                      <tr>
                        <td>{{ index + 1 }}</td>
                        <td>
                          <div class="form-group">
                            <v-select :options="produkopt"></v-select>
                          </div>
                        </td>
                        <td>
                          {{ row.kelompok_produk }}
                        </td>
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
            ><button type="button" class="btn btn-info">Tambah</button></span
          >
        </div>
      </div>
    </form>
  </div>
</template>
<script>
import axios from "axios";
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
};
</script>
<style>
.centered {
  margin: 0 auto;
}
</style>
