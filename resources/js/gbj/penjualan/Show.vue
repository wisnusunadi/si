<template>
  <div class="row">
    <div class="col-lg-12 col-sm-12 col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <span class="dropdown float-right" id="filter">
                <button
                  class="btn btn-outline-info dropdown-toggle"
                  type="button"
                  id="filter"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  Filter
                </button>
                <form
                  class="dropdown-menu dropdown-menu-right p-3 text-nowrap"
                  aria-labelledby="filter"
                >
                  <div class="dropdown-header">Input No Seri</div>
                  <div class="form-group">
                    <div class="form-check">
                      <input
                        type="checkbox"
                        class="form-check-input"
                        id="status_no_seri"
                        value="selesai"
                      />
                      <label class="form-check-label" for="status_no_seri">
                        Selesai
                      </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-check">
                      <input
                        type="checkbox"
                        class="form-check-input"
                        id="status_no_seri"
                        value="sebagian_diinput"
                      />
                      <label class="form-check-label" for="status_no_seri">
                        Sebagian diinput
                      </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-check">
                      <input
                        type="checkbox"
                        class="form-check-input"
                        id="status_no_seri"
                        value="belum_diinput"
                      />
                      <label class="form-check-label" for="status_no_seri">
                        Belum diinput
                      </label>
                    </div>
                  </div>
                  <div class="dropdown-header">Batas Kontrak</div>
                  <div class="form-group">
                    <div class="form-check">
                      <input
                        type="checkbox"
                        class="form-check-input"
                        id="status_kontrak"
                        value="selesai"
                      />
                      <label class="form-check-label" for="status_kontrak">
                        Lebih dari 3 Hari
                      </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-check">
                      <input
                        type="checkbox"
                        class="form-check-input"
                        id="status_kontrak"
                        value="sebagian_diinput"
                      />
                      <label class="form-check-label" for="status_kontrak">
                        Kurang dari 3 Hari
                      </label>
                    </div>
                  </div>
                  <button class="btn btn-primary float-right">Cari</button>
                </form>
              </span>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 col-sm-12 col-md-12">
              <table class="table" style="text-align: center" id="table">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Batas Pengiriman</th>
                    <th>Nomor PO</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>28-09-2021</td>
                    <td>
                      <span style="color: red"><b>15-10-2021</b></span>
                    </td>
                    <td>0003/PO/ON/10/21</td>
                    <td>-</td>
                    <td>
                      <span class="badge yellow-text">Sebagian diinput</span>
                    </td>
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
                        <router-link :to="{ path: '/gbj/penjualan/detail/3' }"
                          ><button class="dropdown-item" type="button">
                            <i class="fas fa-search"></i> Detail
                          </button></router-link
                        >
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>23-09-2021</td>
                    <td>
                      <span style="color: #ffc700"><b>17-10-2021</b></span>
                    </td>
                    <td>0002/PO/ON/10/21</td>
                    <td>-</td>
                    <td><span class="badge red-text">Belum diinput</span></td>
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
                        <router-link :to="{ path: '/gbj/penjualan/detail/2' }"
                          ><button class="dropdown-item" type="button">
                            <i class="fas fa-search"></i> Detail
                          </button></router-link
                        >
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>21-09-2021</td>
                    <td><span>13-10-2021</span></td>
                    <td>0001/PO/ON/10/21</td>
                    <td>-</td>
                    <td><span class="badge green-text">Selesai</span></td>
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
                        <router-link :to="{ path: '/gbj/penjualan/detail/1' }"
                          ><button class="dropdown-item" type="button">
                            <i class="fas fa-search"></i> Detail
                          </button></router-link
                        >
                      </div>
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
</template>

<script>
export default {
  mounted() {
    $("#table").DataTable({
      ajax: "http://localhost:8000/api/gbj/penjualan",
      columns: [
        {
          data: "DT_RowIndex",
          orderable: false,
          searchable: false,
        },
        {
          data: "tanggal",
          name: "tanggal",
        },
        {
          data: null,
          render: function (data) {
            if (data.batas_kontrak > 3) {
              return (
                '<span style="color:#FFC700;"><b>' +
                data.batas_kontrak +
                " Hari Lagi</b></span>"
              );
            } else if (data.batas_kontrak <= 3) {
              return (
                '<span style="color:red;"><b>' +
                data.batas_kontrak +
                " Hari Lagi</b></span>"
              );
            } else {
              return data.batas_kontrak;
            }
          },
          orderable: false,
          searchable: false,
        },
        {
          data: "nomor_po",
          name: "nomor_po",
        },
        {
          data: "keterangan",
          name: "keterangan",
        },
        {
          data: null,
          render: function (data) {
            if (data.status_no_seri == "selesai") {
              return (
                '<span class="badge green-text">' +
                data.status_no_seri +
                "</span>"
              );
            } else if (data.status_no_seri == "sebagian diinput") {
              return (
                '<span class="badge yellow-text">' +
                data.status_no_seri +
                "</span>"
              );
            } else if (data.status_no_seri == "belum diinput") {
              return (
                '<span class="badge red-text">' +
                data.status_no_seri +
                "</span>"
              );
            }
          },
          orderable: false,
          searchable: false,
        },
        {
          data: null,
          render: function (data) {
            return (
              `<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <router-link :to="{ path: '/gbj/penjualan/detail/` +
              data.id +
              `' }"><button class="dropdown-item" type="button"><i class="fas fa-search"></i> Detail</button></router-link>
                        </div>`
            );
          },
          orderable: false,
          searchable: false,
        },
      ],
    });
  },
};
</script>