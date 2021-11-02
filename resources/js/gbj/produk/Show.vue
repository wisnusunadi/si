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
                            <div class="btn-group">
                                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Separated link</a>
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
                                <th>Foto Produk</th>
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
