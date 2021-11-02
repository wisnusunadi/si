<template>
    <div class="row">
        <div class="col-lg-12">
            <!-- navigation -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="semua-produk-tab" data-toggle="tab" href="#semua-produk" role="tab"
                        aria-controls="semua-produk" aria-selected="true">Semua Data</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="tanggal-tab" data-toggle="tab" href="#tanggal" role="tab"
                        aria-controls="tanggal" aria-selected="false">Per Tanggal</a>
                </li>
            </ul>
            <!-- Card -->
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h3 class="card-title">Daftar SO Gudang Barang Jadi</h3>
                        </div>
                    </div>
                </div>
                <!-- Modal Checked Product -->
                <b-modal id="modal-add" centered title="Input Produk">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Checklist</th>
                                <th>Nama Produk</th>
                                <th>Tipe</th>
                                <th>Merk</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row"><input type="checkbox" name="" id="" class="form-control"></th>
                                <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                <td>ABPM50</td>
                                <td>ELITECH</td>
                            </tr>
                            <tr>
                                <th scope="row"><input type="checkbox" name="" id="" class="form-control"></th>
                                <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                <td>RGB</td>
                                <td>ELITECH</td>
                            </tr>
                        </tbody>
                    </table>


                    <div slot="modal-footer" class="w-100">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-secondary">Close</button>&nbsp;
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </b-modal>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered" id="gudang-barang">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor SO</th>
                                <th>Customer</th>
                                <th>Batas Input</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>8457938475938475</td>
                                <td>Rumah Sakit Dr. Soetomo</td>
                                <td>10 Oktober 2021</td>
                                <td><span class="badge badge-primary">Sudah Dicek</span></td>
                                <td>
                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                        <div class="dropdown-menu">
                                            <b-button v-b-modal.modal-edit class="dropdown-item"><i
                                                    class="fas fa-plus"></i>&nbsp;Add Produk</b-button>
                                            <b-button v-b-modal.modal-view class="dropdown-item"><i
                                                    class="far fa-eye"></i>&nbsp;View</b-button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>8457938475938475</td>
                                <td>Rumah Sakit Dr. Soetomo</td>
                                <td>11 Oktober 2021</td>
                                <td><span class="badge badge-danger">Belum Dicek</span></td>
                                <td>
                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                        <div class="dropdown-menu">
                                            <b-button v-b-modal.modal-edit class="dropdown-item"><i
                                                    class="fas fa-plus"></i>&nbsp;Add Produk</b-button>
                                            <b-button v-b-modal.modal-view class="dropdown-item"><i
                                                    class="far fa-eye"></i>&nbsp;View</b-button>
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
