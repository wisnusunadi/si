@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="row">
    <div class="col-12">
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
                                            <button type="button" class="btn btn-info" data-toggle="modal"
                                                data-target="#modal-create">
                                                <i class="fas fa-plus"></i>&nbsp;Tambah
                                            </button>
                                        </span>
                                        <span class="dropdown float-right" id="semuaprodukfilter"
                                            style="margin-right: 5px">
                                            <button class="btn btn-outline-info dropdown-toggle" type="button"
                                                id="semuaprodukfilter" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false"><i class="fas fa-filter"></i>&nbsp;
                                                Filter
                                            </button>
                                            <div class="dropdown-menu p-3 text-nowrap"
                                                aria-labelledby="semuaprodukfilter">
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
                                    <th>Nama Produk</th>
                                    <th>Stok Gudang</th>
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
                                    <td>80 Unit</td>
                                    <td>Alat Kesehatan</td>
                                    <td>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <div class="dropdown-menu">
                                                <button type="button" class="dropdown-item editProduk">
                                                    <i class="far fa-edit"></i>&nbsp;Edit
                                                </button>
                                                <button type="button" class="dropdown-item viewProduk">
                                                    <i class="far fa-eye"></i>&nbsp;Detail
                                                </button>
                                                <button type="button" class="dropdown-item stokProduct">
                                                    <i class="fas fa-cubes"></i>&nbsp;Daftar Stok
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>ZTP80AS-UPGRADE</td>
                                    <td>STERILISATOR KERING</td>
                                    <td>100 Unit</td>
                                    <td>80 Unit</td>
                                    <td>Alat Kesehatan</td>
                                    <td>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <div class="dropdown-menu">
                                                <button type="button" class="dropdown-item editProduk">
                                                    <i class="far fa-edit"></i>&nbsp;Edit
                                                </button>
                                                <button type="button" class="dropdown-item viewProduk">
                                                    <i class="far fa-eye"></i>&nbsp;Detail
                                                </button>
                                                <button type="button" class="dropdown-item stokProduct">
                                                    <i class="fas fa-cubes"></i>&nbsp;Daftar Stok
                                                </button>
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
    </div>
</div>

{{-- Modal Create --}}
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade" id="modal-create" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Produk GBJ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Produk</label>
                            <select name="" class="form-control produk-add">
                                <option value="">Buku</option>
                                <option value="">Bolpoin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="">Nama Produk</label>
                        <input type="text" name="nama" class="form-control @error('title') is-invalid @enderror"
                            placeholder="Nama Produk">
                        @error('title')
                        <span class="invalid-feedback">Silahkan isi Nama Produk</span>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="">Satuan</label>
                       <select name="" id="satuan-tambah" class="form-control">
                           <option value="">mm</option>
                           <option value="">unit</option>
                       </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Deskripsi</label>
                    <textarea class="form-control" id="" cols="5" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="">Dimensi</label>
                    <div class="d-flex justify-content-between">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Panjang">
                            <div class="input-group-prepend">
                                <div class="input-group-text">mm</div>
                            </div>
                        </div>&nbsp;
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Lebar">
                            <div class="input-group-prepend">
                                <div class="input-group-text">mm</div>
                            </div>
                        </div>&nbsp;
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Tinggi">
                            <div class="input-group-prepend">
                                <div class="input-group-text">mm</div>
                            </div>
                        </div>&nbsp;
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" name="gambar" class="custom-file-input gambar" id="inputGroupFile02" />
                        <label class="custom-file-label" for="inputGroupFile02">Pilih File</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary">Kirim</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<!-- Button trigger modal -->

<!-- Modal -->
<div class="modal fade modal-edit" id="" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Produk Sterilisator</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Produk</label>
                            <select name="" class="form-control produk-add">
                                <option value="">Buku</option>
                                <option value="">Bolpoin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="">Nama Produk</label>
                        <input type="text" class="form-control" placeholder="Nama Produk">
                    </div>
                    <div class="col">
                        <label for="">Satuan</label>
                       <select name="" id="" class="form-control satuan-edit">
                           <option value="">mm</option>
                           <option value="">unit</option>
                       </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Deskripsi</label>
                    <textarea class="form-control" id="" cols="5" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="">Dimensi</label>
                    <div class="d-flex justify-content-between">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Panjang">
                            <div class="input-group-prepend">
                                <div class="input-group-text">mm</div>
                            </div>
                        </div>&nbsp;
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Lebar">
                            <div class="input-group-prepend">
                                <div class="input-group-text">mm</div>
                            </div>
                        </div>&nbsp;
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" placeholder="Tinggi">
                            <div class="input-group-prepend">
                                <div class="input-group-text">mm</div>
                            </div>
                        </div>&nbsp;
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="inputGroupFile02" />
                        <label class="custom-file-label" for="inputGroupFile02">Pilih File</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary">Kirim</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal View --}}

<!-- Modal -->
<div class="modal fade modal-view" id="" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Produk Sterilisator</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <img class="card-img-top" src="https://images.unsplash.com/photo-1636096111790-01540e4b36fd?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=687&q=80" alt="">
                        </div>
                    </div>
                    <div class="col-6">
                        <p><b>Nama Produk</b></p>
                        <p>STERILISATOR KERING</p>
                        <p><b>Deskripsi Produk</b></p>
                        <p>Inovasi Produk Terbaru dari industri kami</p>
                        <p><b>Dimensi</b></p>
                        <div class="row">
                            <div class="col-sm">Panjang</div>
                            <div class="col-sm">Lebar</div>
                            <div class="col-sm">Tinggi</div>
                        </div>
                        <div class="row">
                            <div class="col-sm"><span id="panjang">1</span></div>
                            <div class="col-sm"><span id="lebar">122</span></div>
                            <div class="col-sm"><span id="tinggi">12</span></div>
                        </div>
                        <p><b>Produk</b></p>
                        <p>Buku</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Daftar Stok-->
<div class="modal fade daftar-stok" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Daftar Stok Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table scan-produk">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb"></th>
                            <th>No. Seri</th>
                            <th>Layout</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" class="cb-child" value="1"></td>
                            <td>5474598674958698645</td>
                            <td>
                                <select name="" id="" class="form-control">
                                    <option value="1">Layout 1</option>
                                    <option value="2">Layout 2</option>
                                </select>
                            </td> 
                            <td>
                                    <button class="btn btn-info viewStock"><i class="far fa-eye"></i> View</button>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="cb-child" value="2"></td>
                            <td>5474598674958698645</td>
                            <td>
                                <select name="" id="" class="form-control">
                                    <option value="1">Layout 1</option>
                                    <option value="2">Layout 2</option>
                                </select>
                            </td> 
                            <td>
                                <button class="btn btn-info viewStock"><i class="far fa-eye"></i> View</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target=".edit-stok">Ubah Layout</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail-->
<div class="modal fade modalViewStock" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <div class="row">
                        <div class="col">
                            <b>Produk</b><p>Ambulatory</p>
                        </div>
                        <div class="col">
                            <b>Nomor SO</b><p>8457938475938475</p>
                        </div>
                    </div>
                </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal Masuk</th>
                            <th>Dari</th>
                            <th>Tujuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">10-04-2022</td>
                            <td>Divisi IT</td>
                            <td>Uji Coba</td>
                        </tr>
                        <tr>
                            <td scope="row">10-04-2022</td>
                            <td>Divisi IT</td>
                            <td>Uji Coba</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade edit-stok" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Layout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Layout</label>
                    <select name="" id="change-layout" class="form-control">
                      <option value="1">Layout 1</option>
                      <option value="2">Layout 2</option>
                  </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" onclick="ubahData()">Simpan</button>
            </div>
        </div>
    </div>
</div>
<style>
    img {
        width: 100%;
    }

</style>
@stop

@section('adminlte_js')
{{-- <script src="{{ asset('native/js/gbj/produk.js') }}"></script> --}}
<script>
    $('.scan-produk').DataTable({
            "ordering":false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "columnDefs": [
                { "width": "5%", "targets": 0},
            ]
    });
    $('#inputGroupFile02').on('change', function () {
        //get the file name
        var fileName = $(this).val();
        //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    });
    $('.editProduk').click(function (e) {
        $('.modal-edit').modal('show');
        $('.produk-edit ').select2();
        $('.satuan-edit').select2();
        $('.layout-edit').select2();
    });
    $('.viewProduk').click(function (e) {
        $('.modal-view').modal('show');
    });
    $(document).ready(function () {
        $('.produk-add ').select2();
        $('.layout-add').select2();

        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            $('.cb-child').prop('checked', isChecked)
        });
    });

    function ubahData() {
        let checkbox_terpilih = $('.scan-produk tbody .cb-child:checked');
        let layout = $('#change-layout').val();
        $.each(checkbox_terpilih, function (index, elm) {
            let b = $(checkbox_terpilih).parent().next().next().children().val(layout);
        });
        $('.edit-stok').modal('hide');
    }

    $('.stokProduct').click(function (e) {
        $('.daftar-stok').modal('show');
    });

    $('.editStok').click(function (e) {
        $('.edit-stok').modal('show');
    });

    $('.viewStock').click(function (e) {
        $('.modalViewStock').modal('show');
    });
    $('#satuan-tambah').select2();
</script>
@stop
