<template>
  <div class="content">
    <form @submit.prevent="handleSubmit">
      <div class="row d-flex justify-content-center">
        <div class="col-8">
          <h5>Info Customer</h5>
          <div class="card">
            <div class="card-body">
              <div v-if="afterSubmit == 'error'">
                <div
                  class="alert alert-danger alert-dismissible fade show"
                  role="alert"
                >
                  <strong>Gagal menambahkan!</strong> Periksa kembali data yang
                  diinput
                  <button
                    type="button"
                    class="close"
                    data-dismiss="alert"
                    aria-label="Close"
                  >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              </div>
              <div v-else-if="afterSubmit == 'success'">
                <div
                  class="alert alert-success alert-dismissible fade show"
                  role="alert"
                >
                  <strong>Berhasil menambahkan data</strong>, Terima kasih
                  <button
                    type="button"
                    class="close"
                    data-dismiss="alert"
                    aria-label="Close"
                  >
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              </div>
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
                        v-bind:class="{
                          'is-invalid': nama_customerer,
                        }"
                      />
                      <div class="invalid-feedback" v-if="msg.nama_customer">
                        {{ msg.nama_customer }}
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label
                      for="npwp"
                      class="col-4 col-form-label"
                      style="text-align: right"
                      >NPWP</label
                    >
                    <div class="col-5">
                      <input
                        type="text"
                        class="form-control"
                        value=""
                        placeholder="Masukkan NPWP"
                        v-model="npwp"
                        v-bind:class="{
                          'is-invalid': npwper,
                        }"
                      />
                      <div class="invalid-feedback" v-if="msg.npwp">
                        {{ msg.npwp }}
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label
                      for="alamat"
                      class="col-4 col-form-label"
                      style="text-align: right"
                      >Alamat</label
                    >
                    <div class="col-8">
                      <input
                        type="text"
                        class="form-control"
                        placeholder="Masukkan Alamat"
                        v-model="alamat"
                        v-bind:class="{
                          'is-invalid': alamater,
                        }"
                      />
                      <div class="invalid-feedback" v-if="msg.alamat">
                        {{ msg.alamat }}
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label
                      for="telepon"
                      class="col-4 col-form-label"
                      style="text-align: right"
                      >No Telp</label
                    >
                    <div class="col-5">
                      <input
                        type="text"
                        class="form-control"
                        value=""
                        placeholder="Masukkan Telepon"
                        v-model="telepon"
                        v-bind:class="{
                          'is-invalid': teleponer,
                        }"
                      />
                      <div class="invalid-feedback" v-if="msg.telepon">
                        {{ msg.telepon }}
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label
                      for="telepon"
                      class="col-4 col-form-label"
                      style="text-align: right"
                      >Keterangan</label
                    >
                    <div class="col-5">
                      <textarea
                        class="form-control"
                        name="keterangan"
                        id="keterangan"
                        v-model="keterangan"
                      ></textarea>
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
import axios from "axios";
export default {
  data() {
    return {
      msg: [],
      nama_customer: "",
      npwp: "",
      keterangan: "",
      telepon: "",
      alamat: "",
      afterSubmit: "",

      nama_customerer: false,
      npwper: false,
      keteranganer: false,
      teleponer: false,
      alamater: false,
      btndis: true,
    };
  },
  methods: {
    checkNamaCust: function (value) {
      axios.get(`tesapi`).then((res) => {
        return res;
      });
    },
    checkNpwp: function (value) {
      axios.get(`tesapi`).then((res) => {
        return res;
      });
    },
    checkTelepon: function (value) {
      axios.get(`tesapi`).then((res) => {
        return res;
      });
    },
    handleSubmit: function () {
      this.afterSubmit = "success";
      console.log("test");
    },
  },
  watch: {
    nama_customer: function () {
      if (this.nama_customer == "") {
        this.msg["nama_customer"] = "Nama tidak boleh kosong";
        this.nama_customerer = true;
        this.btndis = true;
      } else if (this.nama_customer != "") {
        // if (checkNpwp(this.nama_customerer).value >= 1) {
        //     this.msg["nama_customer"] = "Nama sudah terpakai";
        //     this.nama_customerer = true;
        //     this.btndis = true;
        // } else {
        //     this.msg["nama_customer"] = "";
        //     this.nama_customerer = false;
        //     this.btndis = false;
        // }
        this.msg["nama_customer"] = "";
        this.nama_customerer = false;
        if (this.telepon != "" && this.npwp != "" && this.alamat != "") {
          this.btndis = false;
        } else {
          this.btndis = true;
        }
      }
    },
    npwp: function () {
      if (this.npwp == "") {
        this.msg["npwp"] = "NPWP tidak boleh kosong";
        this.npwper = true;
        this.btndis = true;
      } else if (this.npwp != "") {
        // if (checkNpwp(this.npwper).value >= 1) {
        //     this.msg["npwp"] = "NPWP sudah terpakai";
        //     this.npwper = true;
        //     this.btndis = true;
        // } else {
        //     this.msg["npwp"] = "";
        //     this.npwper = false;
        //     this.btndis = false;
        // }
        this.msg["npwp"] = "";
        this.npwper = false;
        if (
          this.nama_customer != "" &&
          this.telepon != "" &&
          this.alamat != ""
        ) {
          this.btndis = false;
        } else {
          this.btndis = true;
        }
      }
    },
    telepon: function () {
      if (this.telepon == "") {
        this.msg["telepon"] = "Telepon tidak boleh kosong";
        this.teleponer = true;
        this.btndis = true;
      } else if (this.telepon != "") {
        if (!/^[0-9]+$/.test(this.telepon)) {
          this.msg["telepon"] = "Isi nomor telepon dengan angka";
          this.teleponer = true;
          this.btndis = true;
        } else {
          // if (checkTelepon(this.teleponer).value >= 1) {
          //     this.msg["telepon"] = "Nomor Telepon sudah terpakai";
          //     this.teleponer = true;
          //     this.btndis = true;
          // } else {
          //     this.msg["telepon"] = "";
          //     this.teleponer = false;
          //     this.btndis = false;
          // }
          this.msg["telepon"] = "";
          this.teleponer = false;
          if (
            this.nama_customer != "" &&
            this.npwp != "" &&
            this.alamat != ""
          ) {
            this.btndis = false;
          } else {
            this.btndis = true;
          }
        }
      }
    },
    alamat: function () {
      if (this.alamat == "") {
        this.msg["alamat"] = "Alamat tidak boleh kosong";
        this.alamater = true;
        this.btndis = true;
      } else if (this.alamat != "") {
        this.msg["alamat"] = "";
        this.alamater = false;
        if (this.nama_customer != "" && this.npwp != "" && this.telepon != "") {
          this.btndis = false;
        } else {
          this.btndis = true;
        }
      }
    },
  },
};
</script>
