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
                                        <b-button v-b-modal.modal-add variant="info"><i
                                                class="fas fa-plus"></i>&nbsp;Tambah</b-button>
                                        <!-- Modal Create -->
                                        <b-modal id="modal-add" centered title="Tambah Produk Baru">

                                            <div class="row">
                                                <div class="col">
                                                    <label for="">Nama Produk</label>
                                                <input type="text" class="form-control" placeholder="Nama Produk">
                                                </div>
                                                <div class="col">
                                                    <label for="">Stok</label>
                                                <input type="text" class="form-control" placeholder="Stok">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Deskripsi</label>
                                                <textarea class="form-control" id="" cols="5" rows="5"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Dimensi</label>
                                                <div class="d-flex justify-content-between">
                                                    <input type="text" class="form-control" placeholder="Panjang">&nbsp;
                                                    <input type="text" class="form-control" placeholder="Lebar">&nbsp;
                                                    <input type="text" class="form-control" placeholder="Tinggi">&nbsp;
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="">Produk</label>
                                                        <select name="" id="" class="form-control">
                                                            <option value="">Buku</option>
                                                            <option value="">Bolpoin</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <label for="">Layout</label>
                                                    <select name="" id="" class="form-control">
                                                        <option value="">Buku</option>
                                                        <option value="">Bolpoin</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <b-form-file placeholder="Silahkan pilih foto produk"
                                                drop-placeholder="Letakkan foto disini"></b-form-file>
                                            <div slot="modal-footer" class="w-100">
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-secondary">Close</button>&nbsp;
                                                <button class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </b-modal>
                                        <!-- Modal Update -->
                                        <b-modal id="modal-edit" centered title="Edit Produk STERILISATOR KERING">

                                            <div class="form-group">
                                                <label for="">Nama Produk</label>
                                                <input type="text" class="form-control" value="STERILISATOR KERING">
                                            </div>
                                            <div class="form-group">
                                                <label for="">Deskripsi</label>
                                                <textarea class="form-control" id="" cols="5" rows="5">lalalalayyyy</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Dimensi</label>
                                                <div class="d-flex justify-content-between">
                                                    <input type="text" class="form-control" placeholder="Panjang" value="5">&nbsp;
                                                    <input type="text" class="form-control" placeholder="Lebar" value="10">&nbsp;
                                                    <input type="text" class="form-control" placeholder="Tinggi" value="15">&nbsp;
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="">Produk</label>
                                                        <select name="" id="" class="form-control">
                                                            <option value="" selected>Buku</option>
                                                            <option value="">Bolpoin</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <label for="">Layout</label>
                                                    <select name="" id="" class="form-control">
                                                        <option value="" selected>Buku</option>
                                                        <option value="">Bolpoin</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <b-form-file placeholder="Silahkan pilih foto produk"
                                                drop-placeholder="Letakkan foto disini"></b-form-file>
                                            <div slot="modal-footer" class="w-100">
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-secondary">Close</button>&nbsp;
                                                <button class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </b-modal>
                                        <!-- Modal View -->
                                        <b-modal id="modal-view" centered title="Detail Produk STERILISATOR KERING">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <img src="https://images.unsplash.com/photo-1615486510940-4e96763c7f6d?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1170&q=80" alt="">
                                                    </div>
                                                    <div class="col-6">
                                                        <p><b>Nama Produk</b></p>
                                                        <p>STERILISATOR KERING</p>
                                                        <p><b>Deskripsi Produk</b></p>
                                                        <p>Inovasi Produk Terbaru dari industri kami</p>
                                                        <p><b>Dimensi</b></p>
                                                        <div class="d-flex"><p>Panjang: 50</p>   
                                                        <p>Lebar: 50</p>   
                                                        <p>Tinggi: 50</p>   </div>
                                                        <p><b>Produk</b></p>
                                                        <p>Buku</p>
                                                        <p><b>Layout</b></p>
                                                        <p>Buku</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </b-modal>
                                    </span>
                                    <span class="dropdown float-right" id="semuaprodukfilter" style="margin-right: 5px">
                                        <button class="btn btn-outline-info dropdown-toggle" type="button"
                                            id="semuaprodukfilter" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false"><i class="fas fa-filter"></i>&nbsp;
                                            Filter
                                        </button>
                                        <div class="dropdown-menu p-3 text-nowrap" aria-labelledby="semuaprodukfilter">
                                            <div class="dropdown-header">Kelompok Produk</div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="sp_kelompok"
                                                        value="alat_kesehatan" />
                                                    <label class="form-check-label" for="sp_kelompok">
                                                        Alat Kesehatan
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="sp_kelompok"
                                                        value="sarana_kesehatan" />
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
                                            <b-button v-b-modal.modal-edit class="dropdown-item"><i
                                                class="far fa-edit"></i>&nbsp;Edit</b-button>
                                            <b-button v-b-modal.modal-view class="dropdown-item"><i class="far fa-eye"></i>&nbsp;View</b-button>
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
<style scoped>
    img{
        width: 100%;
    }
</style>