<template>
  <div class="content">
    <form @submit.prevent="handleSubmit">
      <div class="row d-flex justify-content-center">
        <div class="col-8">
          <h5>Info Customer</h5>
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="form-group row">
                    <label
                      for="nama_produk"
                      class="col-4 col-form-label"
                      style="text-align: right"
                      >Nama Customer</label
                    >
                    <div class="col-6">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Masukkan Nama Customer"
                        v-model="nama_customer"
                        na
                      />
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-8">
                    <router-link :to="{ name: 'show' }"
                        ><span class="float-left"
                            ><button type="button" class="btn btn-danger">
                                Batal
                            </button></span
                        ></router-link
                    >
                    <span class="float-right"
                        ><button type="submit" class="btn btn-info">
                            Tambah
                        </button></span
                    >
                </div>
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
      nama_customer: "",
      keterangan: "",
      telepon: "",
      alamat: "",
    };
  },

  methods: {
    handleSubmit: function (event) {
      event.preventDefault();
      axios.post("/api/customer/create", {
        nama: this.nama_customer,
        telp: this.telepon,
        alamat: this.alamat,
        ket: this.keterangan,
      });
      //   console.log({
      //     nama: this.nama_customer,
      //     telp: this.telepon,
      //     alamat: this.alamat,
      //     ket: this.keterangan,
      //   });
      this.$router.push({ name: "show" });
    },
  },
};
</script>