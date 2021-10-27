<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="row" style="margin-bottom: 10px">
            <div class="col-12">
              <span class="float-right">
                <router-link :to="{ name: 'create' }"
                  ><button class="btn btn-outline-info">
                    Tambah
                  </button></router-link
                >
              </span>
              <span class="dropdown float-right">
                <button
                  class="btn btn-outline-secondary dropdown-toggle"
                  type="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  id="filterpenjualan"
                >
                  Filter
                </button>
                <div class="dropdown-menu" aria-labelledby="filterpenjualan">
                  <form class="px-4" style="white-space: nowrap">
                    <div class="dropdown-header">Status</div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownStatus"
                          value="ekatalog"
                        />
                        <label class="form-check-label" for="dropdownStatus">
                          E-Catalogue
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownStatus"
                        />
                        <label
                          class="form-check-label"
                          for="dropdownStatus"
                          value="spa"
                        >
                          SPA
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownStatus"
                          value="spb"
                        />
                        <label class="form-check-label" for="dropdownStatus">
                          SPB
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary">
                        Cari
                      </button>
                    </div>
                  </form>
                </div>
              </span>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <table class="table table-hover" id="customertable">
                <thead style="text-align: center">
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Telp</th>
                    <th>NPWP</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>PT Dakai</td>
                    <td>Jl. Tambak Osowilangun A7 Benowo</td>
                    <td>08181828384</td>
                    <td></td>
                    <td>-</td>
                    <td>
                      <div
                        class="dropdown-toggle"
                        data-toggle="dropdown"
                        id="dropdownMenuButton"
                        aria-haspopup="true"
                        aria-expanded="false"
                      >
                        <i class="fas fa-ellipsis-v"></i>
                      </div>
                      <div
                        class="dropdown-menu"
                        aria-labelledby="dropdownMenuButton"
                      >
                        <a data-toggle="modal" data-target="#modaldetail">
                          <button class="dropdown-item" type="button">
                            <i class="fas fa-search"></i>
                            Detail
                          </button>
                        </a>
                        <a data-toggle="modal" data-target="#modaledit">
                          <button class="dropdown-item" type="button">
                            <i class="fas fa-pencil-alt"></i>
                            Edit
                          </button>
                        </a>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>PT Dakai</td>
                    <td>Jl. Tambak Osowilangun A7 Benowo</td>
                    <td>08181828384</td>
                    <td></td>
                    <td>-</td>
                    <td>
                      <i class="fas fa-ellipsis-v"></i>
                    </td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>PT Dakai</td>
                    <td>Jl. Tambak Osowilangun A7 Benowo</td>
                    <td>08181828384</td>
                    <td></td>
                    <td>-</td>
                    <td>
                      <i class="fas fa-ellipsis-v"></i>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="modaldetail"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modaldetail"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="margin: 10px">
          <div class="modal-header bg-success">
            <h4 class="modal-title">Detail</h4>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-4">
                <div class="card">
                  <div class="card-body">
                    <ul class="list-group">
                      <li class="list-group-item">
                        <span style="font-size: 24px"><b>Info</b></span>
                      </li>
                      <li class="list-group-item">
                        <a>Nama</a
                        ><b class="float-right">{{ detail.nama_customer }}</b>
                      </li>
                      <li class="list-group-item">
                        <a>Alamat</a>
                        <b class="float-right">{{ detail.alamat }}</b>
                      </li>
                      <li class="list-group-item">
                        <a>Telepon</a>
                        <b class="float-right">{{ detail.telepon }}</b>
                      </li>
                      <li class="list-group-item">
                        <a>NPWP</a>
                        <b class="float-right">{{ detail.npwp }}</b>
                      </li>
                      <li class="list-group-item">
                        <a>Keterangan</a>
                        <b class="float-right">{{ detail.keterangan }}</b>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-8">
                <h5>Histori Penjualan</h5>
                <div class="card">
                  <div class="card-body">
                    <table class="table" id="historitabel">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Jenis</th>
                          <th>No PO</th>
                          <th>Tanggal PO</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="modaledit"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modaledit"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="margin: 10px">
          <div class="modal-header bg-warning"><h4>Edit</h4></div>
          <form @submit.prevent="handleSubmit">
            <div class="modal-body">
              <div v-if="edit.afterSubmit == 'error'">
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
              <div v-else-if="edit.afterSubmit == 'success'">
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
                        v-model="edit.nama_customer"
                        v-bind:class="{
                          'is-invalid': edit.nama_customerer,
                        }"
                      />
                      <div
                        class="invalid-feedback"
                        v-if="edit.msg.nama_customer"
                      >
                        {{ edit.msg.nama_customer }}
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
                        v-model="edit.npwp"
                        v-bind:class="{
                          'is-invalid': edit.npwper,
                        }"
                      />
                      <div class="invalid-feedback" v-if="edit.msg.npwp">
                        {{ edit.msg.npwp }}
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
                        v-model="edit.alamat"
                        v-bind:class="{
                          'is-invalid': edit.alamater,
                        }"
                      />
                      <div class="invalid-feedback" v-if="edit.msg.alamat">
                        {{ edit.msg.alamat }}
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
                        v-model="edit.telepon"
                        v-bind:class="{
                          'is-invalid': edit.teleponer,
                        }"
                      />
                      <div class="invalid-feedback" v-if="edit.msg.telepon">
                        {{ edit.msg.telepon }}
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
                        v-model="edit.keterangan"
                      ></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <span class="float-left"
                ><button
                  type="button"
                  class="btn btn-danger"
                  data-dismiss="modal"
                >
                  Batal
                </button></span
              >
              <span class="float-right"
                ><button
                  type="submit"
                  class="btn btn-warning"
                  v-bind:class="{ disabled: edit.btndis }"
                >
                  Simpan
                </button></span
              >
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
export default {
  data() {
    return {
      detail: {
        nama_customer: "",
        alamat: "",
        keterangan: "",
        telepon: "",
        npwp: "",
        histori: {},
      },
      edit: {
        msg: [],
        nama_customer: "",
        alamat: "",
        keterangan: "",
        telepon: "",
        npwp: "",
        afterSubmit: "",

        nama_customerer: false,
        npwper: false,
        keteranganer: false,
        teleponer: false,
        alamater: false,
        btndis: true,
      },
    };
  },
  methods: {
    custtable: function () {
      $("#customertable").DataTable({
        ajax: "/api/penjualan_produk/data",
        processing: true,
        serverSide: true,
        columns: [
          {
            data: "DT_RowIndex",
            orderable: false,
            searchable: false,
          },
          {
            data: "nama",
          },
          {
            data: "alamat",
          },
          {
            data: "telepon",
          },
          {
            data: "npwp",
          },
          {
            data: "keterangan",
          },
          {
            data: null,
            render: function () {
              return (
                '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">' +
                '<i class="fas fa-ellipsis-v"></i>' +
                "</div>" +
                '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                '<a data-toggle="modal" data-target="#modaldetail"><button class="dropdown-item" type="button"><i class="fas fa-search"></i>Detail</button></a>' +
                '<a data-toggle="modal" data-target="#modaledit"> <button  class="dropdown-item"  type="button"><i class="fas fa-pencil-alt"></i>Edit</button></a>' +
                "</div>"
              );
            },
          },
        ],
      });
    },
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
    edit: {
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
          if (
            this.nama_customer != "" &&
            this.npwp != "" &&
            this.telepon != ""
          ) {
            this.btndis = false;
          } else {
            this.btndis = true;
          }
        }
      },
    },
  },
};
</script>
