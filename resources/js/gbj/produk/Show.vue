<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                        <h3 class="card-title">Produk Gudang Barang Jadi</h3>
                        </div>
                        <div class="col-4 d-flex justify-content-end">
                                      <div class="row">
            <div class="col-lg-12">
              <span class="float-right">
            <b-button v-b-modal.modal-center variant="info"><i class="fas fa-plus"></i>&nbsp;Tambah</b-button>

            <b-modal id="modal-center" centered title="Tambah Produk Baru">
                            <label for="">Nama Produk</label>
                            <input type="text" class="form-control" v-model="nama" placeholder="nama produk">
                            <label for="">Deskripsi</label>
                            <textarea class="form-control" id="" cols="5" rows="10"></textarea>
            </b-modal>
              </span>
              <span
                class="dropdown float-right"
                id="semuaprodukfilter"
                style="margin-right: 5px"
              >
                <button
                  class="btn btn-outline-info dropdown-toggle"
                  type="button"
                  id="semuaprodukfilter"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  Filter
                </button>
                <div
                  class="dropdown-menu p-3 text-nowrap"
                  aria-labelledby="semuaprodukfilter"
                >
                  <div class="dropdown-header">Kelompok Produk</div>
                  <div class="form-group">
                    <div class="form-check">
                      <input
                        type="checkbox"
                        class="form-check-input"
                        id="sp_kelompok"
                        value="alat_kesehatan"
                      />
                      <label class="form-check-label" for="sp_kelompok">
                        Alat Kesehatan
                      </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-check">
                      <input
                        type="checkbox"
                        class="form-check-input"
                        id="sp_kelompok"
                        value="sarana_kesehatan"
                      />
                      <label class="form-check-label" for="sp_kelompok">
                        Sarana Kesehatan
                      </label>
                    </div>
                  </div>
                </div>
              </span>
            </div>
          </div>
            </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered" id="gudang-barang">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Produk</th>
                                <th>Produk</th>
                                <th>Stok</th>
                                <th>Kelompok</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>ZTP80AS-UPGRADE</td>
                                <td>STERILISATOR KERING</td>
                                <td>100 Unit</td>
                                <td>Alat Kesehatan</td>
                                <td>
                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                        <div class="dropdown-menu">
                                            <button class="dropdown-item"><i class="fas fa-eye">Edit</i></button>
                                            <button class="dropdown-item"><i class="far fa-edit">View</i></button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
