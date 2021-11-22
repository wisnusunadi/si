@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>

    .topnav a {
        float: left;
        display: block;
        color: black;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
        border-bottom: 3px solid transparent;
    }

    .active {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    .my-custom-scrollbar {
    position: relative;
    height: 200px;
    overflow: auto;
    }
    .table-wrapper-scroll-y {
    display: block;
    }
    table th {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    }
    
</style>
<section class="content-header">
    <div class="container-fluid">
        <h1>Penerimaan Produk Gudang Karantina</h1>
    </div><!-- /.container-fluid -->
</section>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <nav>
                    <div class="nav nav-tabs topnav" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                            aria-controls="home" aria-selected="true">Daftar Penerimaan</a>
                        <a id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                            aria-selected="false">Pembuatan Penerimaan</a>
                    </div>
                </nav>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-rancangan">
                        <thead class="">
                            <tr>
                                <th style="width: 220px">Tanggal Masuk</th>
                                <th>Dari</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row">10-04-2021</td>
                                <td>Divisi IT</td>
                                <td><a href="{{ url('gk/terimaProduk/1') }}" class="btn btn-outline-info"><i class="far fa-edit"></i>Edit Produk</a></td>
                            </tr>
                            <tr>
                                <td scope="row"></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <section class="content">
            <div class="row">
                .col-lg-4
            </div>
        </section>
        {{-- <section class="content">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-primary b-radius">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 mt-3">
                                            <form method="post">
                                                <div class="form-group">
                                                    <label for="">Tanggal Masuk</label>
                                                    <input type="date" name="" id="datePicker" class="form-control"
                                                        placeholder="">
                                                </div>
                                                <div class="form-group row top-min">
                                                    <label for=""
                                                        class="col-12 font-weight-bold col-form-label">Dari</label>
                                                    <div class="col-12">
                                                        <select class="form-control dari" name="dari">
                                                            <option value="Divisi IT">Divisi IT</option>
                                                            <option value="Divisi QC">Divisi QC</option>
                                                            <option value="Divisi Perakitan">Divisi Perakitan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="card card-noborder b-radius">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 table-responsive mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fab fa-whmcs"></i> Sparepart Karantina</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn" id="" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col-12 d-flex justify-content-end mb-2"><button class="btn btn-outline-info add_sparepart"><i class="fas fa-plus"></i> Tambah</button></div>
                                            <div class="table-wrapper-scroll-y my-custom-scrollbar">
                                                <table class="table table-hover add_sparepart_table">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Nama Produk</th>
                                                            <th>Unit</th>
                                                            <th>Jumlah</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tambah_data">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 table-responsive mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title"><i class="fas fa-tools"></i> Unit Karantina</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn" id="" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="col-12 d-flex justify-content-end mb-2"><button class="btn btn-outline-info add_unit"><i class="fas fa-plus"></i> Tambah</button></div>
                                            <div class="table-wrapper-scroll-y my-custom-scrollbar">
                                                <table class="table table-hover add_unit_table">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>Nama Produk</th>
                                                            <th>Jumlah</th>
                                                            <th>Tujuan</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tambah_data">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <div class="btn-simpan">
                                        <button class="btn btn-success" type="button">Terima</button>&nbsp;
                                        <button class="btn btn-info" type="button">Rancang</button>&nbsp;
                                        <button class="btn btn-secondary " type="button">Batal</button>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section> --}}
    </div>
</div>


<div class="modal fade modalAddSparepart" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Tanggal Masuk</label>
                                <div class="card" style="background-color: #C8E1A7">
                                    <div class="card-body">
                                        23-09-2021
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body">
                                        Produk 1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Unit</label>
                                <div class="card" style="background-color: #FFCC83">
                                    <div class="card-body">
                                        Unit 1
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Dari</label>
                                <div class="card" style="background-color: #FFE0B4">
                                    <div class="card-body">
                                        Divisi IT
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah</label>
                                <div class="card" style="background-color: #FFECB2">
                                    <div class="card-body">
                                        100 pcs
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped scan-produk">
                                    <thead>
                                        <tr>
                                            <th>No Seri</th>
                                            <th>Kerusakan</th>
                                            <th>Tingkat Kerusakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td>
                                                <select name="" id="" class="form-control">
                                                    <option value="">Level 1</option>
                                                    <option value="">Level 1</option>
                                                    <option value="">Level 1</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modalAddUnit" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Tanggal Masuk</label>
                                <div class="card" style="background-color: #C8E1A7">
                                    <div class="card-body">
                                        23-09-2021
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Nama Produk</label>
                                <div class="card" style="background-color: #F89F81">
                                    <div class="card-body">
                                        Produk 1
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Dari</label>
                                <div class="card" style="background-color: #FFE0B4">
                                    <div class="card-body">
                                        Divisi IT
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Jumlah</label>
                                <div class="card" style="background-color: #FFECB2">
                                    <div class="card-body">
                                        100 pcs
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped scan-produk">
                                    <thead>
                                        <tr>
                                            <th>No Seri</th>
                                            <th>Kerusakan</th>
                                            <th>Tingkat Kerusakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control"></td>
                                            <td><input type="text" class="form-control"></td>
                                            <td>
                                                <select name="" id="" class="form-control">
                                                    <option value="">Level 1</option>
                                                    <option value="">Level 1</option>
                                                    <option value="">Level 1</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
@stop
@section('adminlte_js')
<script>
    document.getElementById('datePicker').valueAsDate = new Date();

    function addSparepart() {
        $('.modalAddSparepart').modal('show');
    }

    function addUnit() {
        $('.modalAddUnit').modal('show');
    }

    function transfer() {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Data yang sudah di transfer tidak dapat diubah!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        });
    };

    $('.scan-produk').DataTable({
        "ordering": false,
        "autoWidth": false,
        searching: false,
        "lengthChange": false,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
    });
    $(document).on('click','.add_sparepart', function () {
        let table_sparepart = '<tr><td><select name="" id="" class="form-control"><option value="">Produk 1</option><option value="">Produk 2</option><option value="">Produk 3</option></select></td><td><select name="" id="" class="form-control"><option value="">Unit 1</option><option value="">Unit 2</option><option value="">Unit 3</option></select></td><td><input type="number" name="" id="" class="form-control"></td><td><button class="btn btn-primary" onclick="addSparepart()"><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
        $('.add_sparepart_table tbody').append(table_sparepart);
    });
    $(document).on('click','.add_unit', function () {
        let table_unit = '<tr><td><select name="" id="" class="form-control"><option value="">Produk 1</option><option value="">Produk 2</option><option value="">Produk 3</option></select></td><td><input type="number" name="" id="" class="form-control"></td><td><input type="text" class="form-control"></td><td><button class="btn btn-primary" onclick="addUnit()"><i class="fas fa-qrcode"></i> Tambah No Seri</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Delete</button></td></tr>';
        $('.add_unit_table tbody').append(table_unit);
    });
    $(document).on('click', '.btn-delete', function (e) {
        $(this).parent().parent().remove();
        var check = $('tbody.tambah_data tr').length;
    });

    $(document).ready(function () {
        $('.table-rancangan').DataTable({
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
    });
</script>
@stop
