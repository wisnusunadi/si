<template>
  <div class="row">
    <div class="col-lg-12">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a
            class="nav-link active"
            id="semua-penjualan-tab"
            data-toggle="tab"
            href="#semua-penjualan"
            role="tab"
            aria-controls="semua-penjualan"
            aria-selected="true"
            >Penjualan</a
          >
        </li>
        <li class="nav-item" role="presentation">
          <a
            class="nav-link"
            id="ekatalog-tab"
            data-toggle="tab"
            href="#ekatalog"
            role="tab"
            aria-controls="ekatalog"
            aria-selected="false"
            >E-Catalogue</a
          >
        </li>
        <li class="nav-item" role="presentation">
          <a
            class="nav-link"
            id="spa-tab"
            data-toggle="tab"
            href="#spa"
            role="tab"
            aria-controls="spa"
            aria-selected="false"
            >SPA</a
          >
        </li>
        <li class="nav-item" role="presentation">
          <a
            class="nav-link"
            id="spb-tab"
            data-toggle="tab"
            href="#spb"
            role="tab"
            aria-controls="spb"
            aria-selected="false"
            >SPB</a
          >
        </li>
      </ul>
      <div class="tab-content card" id="myTabContent">
        <div
          class="tab-pane fade show active card-body"
          id="semua-penjualan"
          role="tabpanel"
          aria-labelledby="semua-penjualan-tab"
        >
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
                    <div class="dropdown-header">Jenis</div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownJenis"
                        />
                        <label class="form-check-label" for="dropdownJenis">
                          Distributor
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownJenis"
                        />
                        <label class="form-check-label" for="dropdownJenis">
                          Perseorangan
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownJenis"
                        />
                        <label class="form-check-label" for="dropdownJenis">
                          Supplier
                        </label>
                      </div>
                    </div>
                    <div class="dropdown-header">Status</div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownStatus"
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
                        <label class="form-check-label" for="dropdownStatus">
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
              <table class="table table-hover" id="customer">
                <thead style="text-align: center">
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Telp</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- <tr>
                                        <td>1</td>
                                        <td>PT Dakai</td>
                                        <td>
                                            Jl. Tambak Osowilangun A7 Benowo
                                        </td>
                                        <td>08181828384</td>
                                        <td>-</td>
                                        <td>
                                            <div
                                                class="dropdown-toggle"
                                                data-toggle="dropdown"
                                                id="dropdownMenuButton"
                                                aria-haspopup="true"
                                                aria-expanded="false"
                                            >
                                                <i
                                                    class="fas fa-ellipsis-v"
                                                ></i>
                                            </div>
                                            <div
                                                class="dropdown-menu"
                                                aria-labelledby="dropdownMenuButton"
                                            >
                                                <a
                                                    data-toggle="modal"
                                                    data-target="#modaldetail"
                                                >
                                                    <button
                                                        class="dropdown-item"
                                                        type="button"
                                                    >
                                                        <i
                                                            class="fas fa-search"
                                                        ></i>
                                                        Detail
                                                    </button>
                                                </a>
                                                <a
                                                    data-toggle="modal"
                                                    data-target="#modaledit"
                                                >
                                                    <button
                                                        class="dropdown-item"
                                                        type="button"
                                                    >
                                                        <i
                                                            class="fas fa-pencil-alt"
                                                        ></i>
                                                        Edit
                                                    </button>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>PT Dakai</td>
                                        <td>
                                            Jl. Tambak Osowilangun A7 Benowo
                                        </td>
                                        <td>08181828384</td>
                                        <td>-</td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>PT Dakai</td>
                                        <td>
                                            Jl. Tambak Osowilangun A7 Benowo
                                        </td>
                                        <td>08181828384</td>
                                        <td>-</td>
                                        <td>
                                            <i class="fas fa-ellipsis-v"></i>
                                        </td>
                                    </tr> -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div
          class="tab-pane fade card-body"
          id="ekatalog"
          role="tabpanel"
          aria-labelledby="ekatalog-tab"
        >
          <div class="row" style="margin-bottom: 10px">
            <div class="col-12">
              <span class="dropdown float-right">
                <button
                  class="btn btn-outline-secondary dropdown-toggle"
                  type="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  id="filterekatalog"
                >
                  Filter
                </button>
                <div class="dropdown-menu" aria-labelledby="filterekatalog">
                  <form class="px-4" style="white-space: nowrap">
                    <div class="dropdown-header">Jenis</div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownJenis"
                        />
                        <label class="form-check-label" for="dropdownJenis">
                          Distributor
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownJenis"
                        />
                        <label class="form-check-label" for="dropdownJenis">
                          Perseorangan
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownJenis"
                        />
                        <label class="form-check-label" for="dropdownJenis">
                          Supplier
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
              <table class="table table-hover" id="ekatalogtable">
                <thead style="text-align: center">
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Telp</th>
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
        <div
          class="tab-pane fade card-body"
          id="spa"
          role="tabpanel"
          aria-labelledby="spa-tab"
        >
          <div class="row" style="margin-bottom: 10px">
            <div class="col-12">
              <span class="dropdown float-right">
                <button
                  class="btn btn-outline-secondary dropdown-toggle"
                  type="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  id="filterspa"
                >
                  Filter
                </button>
                <div class="dropdown-menu" aria-labelledby="filterspa">
                  <form class="px-4" style="white-space: nowrap">
                    <div class="dropdown-header">Jenis</div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownJenis"
                        />
                        <label class="form-check-label" for="dropdownJenis">
                          Distributor
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownJenis"
                        />
                        <label class="form-check-label" for="dropdownJenis">
                          Perseorangan
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownJenis"
                        />
                        <label class="form-check-label" for="dropdownJenis">
                          Supplier
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
              <table class="table table-hover" id="spatable">
                <thead style="text-align: center">
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Telp</th>
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
        <div
          class="tab-pane fade card-body"
          id="spb"
          role="tabpanel"
          aria-labelledby="spb-tab"
        >
          <div class="row" style="margin-bottom: 10px">
            <div class="col-12">
              <span class="dropdown float-right">
                <button
                  class="btn btn-outline-secondary dropdown-toggle"
                  type="button"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                  id="filterspb"
                >
                  Filter
                </button>
                <div class="dropdown-menu" aria-labelledby="filterspb">
                  <form class="px-4" style="white-space: nowrap">
                    <div class="dropdown-header">Jenis</div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownJenis"
                        />
                        <label class="form-check-label" for="dropdownJenis">
                          Distributor
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownJenis"
                        />
                        <label class="form-check-label" for="dropdownJenis">
                          Perseorangan
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="form-check">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          id="dropdownJenis"
                        />
                        <label class="form-check-label" for="dropdownJenis">
                          Supplier
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
    </div>
</template>

<script>
import "datatables.net-bs4/css/dataTables.bootstrap4.min.css";
export default {
  mounted: function () {
    this.customer();
  },
  methods: {
    customer: function () {
      $("#customer").DataTable({
        ajax: "/api/customer/data",
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
            data: "telp",
          },
          {
            data: "ket",
          },
          {
            render: function () {
              return `
                          <i class="fas fa-ellipsis-v"></i>
                    '`;
            },
          },
        ],
      });
    },
  },
};
</script>