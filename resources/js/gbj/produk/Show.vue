<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Gudang Produk Barang Jadi</h3>
                    <!-- <router-link :to=""></router-link> -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered" id="gudang-barang">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Seri</th>
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
                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
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