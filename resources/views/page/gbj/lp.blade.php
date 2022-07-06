@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    .hapus {
        display: none;
    }

    .nomor-so {
        background-color: #717FE1;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .nomor-akn {
        background-color: #DF7458;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    } 

    .nomor-po {
        background-color: #85D296;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .float {
        width: 60px;
        height: 60px;
        bottom: 40px;
        right: 40px;
        background-color: #6aadff;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        box-shadow: 2px 2px 3px #999;
    }

    .my-float {
        margin-top: -10px;
    }
    #DataTables_Table_0_filter{
        display: none;
    }
</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Penerimaan Selain Perakitan</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs" id="myTab" role="tabList">
                            <input type="hidden" name="userid" id="userid" value="{{ Auth::user()->id }}">
                            <li class="nav-item" role="presentation">
                                <a href="#produk" class="nav-link active" id="produk-tab" data-toggle="tab" role="tab"
                                    aria-controls="produk" aria-selected="true">Rancangan Perakitan</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#semua-produk" class="nav-link" id="semua-produk-tab" data-toggle="tab"
                                    role="tab" aria-controls="semua-produk" aria-selected="true">Buat Perakitan</a>
                            </li>
                        </ul>
                        <div class="tab-content card" id="myTabContent">
                            <div class="tab-pane fade show active card-body" id="produk" role="tabpanel"
                                aria-labelledby="produk-tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label for="" id="tanggal" class="col-sm-5 text-right">Tanggal
                                                    Masuk</label>
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control" id="datetimepicker1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive ">
                                            <table class="table table-hover pertanggal" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal Masuk</th>
                                                        <th>Dari</th>
                                                        <th>Tujuan</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show card-body" id="semua-produk" role="tabpanel"
                                aria-labelledby="semua-produk-tab">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card card-primary b-radius">
                                                    <div class="card-body">
                                                        <form method="post">
                                                            <div class="form-group row top-min">
                                                                <label for=""
                                                                    class="col-12 font-weight-bold col-form-label">Tanggal
                                                                    Masuk</label>
                                                                <div class="col-12">
                                                                    <input type="date"
                                                                        class="form-control tgl_masuk"
                                                                        id="tgl_masuk" name="tgl_masuk">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row top-min ">
                                                                <label for=""
                                                                    class="col-12 font-weight-bold col-form-label">Dari</label>
                                                                <div class="col-12">
                                                                    <select
                                                                        class="custom-select division"
                                                                        id="divisi" name="dari">
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row top-min">
                                                                <label for=""
                                                                    class="col-12 font-weight-bold col-form-label">Keterangan</label>
                                                                <div class="col-12">
                                                                    <textarea name="deskripsi"
                                                                        id="deskripsi"
                                                                        class="form-control tujuan"></textarea>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="card card-primary b-radius">
                                                    <div class="card-body">
                                                        <div class="form-group row top-min">
                                                            <label for=""
                                                                class="col-12 font-weight-bold col-form-label">Produk</label>
                                                            <div class="col-12">
                                                                <select name="" id="gdg_brg_jadi_id" class="form-control product"></select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row top-min">
                                                            <label for=""
                                                                class="col-12 font-weight-bold col-form-label">Jumlah</label>
                                                            <div class="col-12">
                                                                <input type="text" class="form-control" id="jumlah">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12 d-flex justify-content-end">
                                                                <button type="button" class="btn btn-primary btn-tambah">Tambah</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-12 table-responsive mb-4">
                                                <table class="table table-hover addData">
                                                    <thead>
                                                        <tr>
                                                            <th>Produk</th>
                                                            <th>Jumlah</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tambah_data">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-12 d-flex justify-content-end">
                                                <div class="btn-simpan hapus">
                                                    <button class="btn btn-success" type="button"
                                                        id="btnSubmit">Terima</button>&nbsp;
                                                    <button class="btn btn-info" type="button"
                                                        id="btnDraft">Rancang</button>&nbsp;
                                                    <button id="btnCancel" class="btn btn-secondary " type="button">Batal</button>
                                                </div>
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
        </div>
    </div>
</section>

<!-- Modal Detail Rancangan-->
<div class="modal fade modal-rancangan" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Rancangan</h5>
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
                                <div class="card nomor-so">
                                    <div class="card-body">
                                        <span id="in"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Dari</label>
                                <div class="card nomor-akn">
                                    <div class="card-body">
                                        <span id="from"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Tujuan</label>
                                <div class="card nomor-po">
                                    <div class="card-body">
                                        <span id="tujuan"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-rancangan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" id="btnSave">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambahan Perakitan-->
<!-- Modal Detail-->
<div class="modal fade tambahan-perakitan" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Detail Produk <span id="titlee"></span></b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk1">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb"></th>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <br>
                <button type="button" class="btn btn-primary float-right" id="btnSeri">Simpan</button>
                <button class="btn btn-info" data-toggle="modal" data-target="#ubah-layout">Ubah Layout</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambahan Rancangan-->
<!-- Modal Detail-->
<div class="modal fade tambahan-rancangan" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Detail Produk <span id="title"></span></b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb-rancang"></th>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <button class="btn btn-info" data-toggle="modal" data-target="#ubah-layout-rancang">Ubah Layout</button>
                <button type="button" class="btn btn-primary float-right" id="seriBtn">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Layout-->
<div class="modal fade" id="ubah-layout" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
                    <select name="" id="change_layout" class="form-control">
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary " onclick="ubahData()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ubah-layout-rancang" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
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
                    <select name="" id="change_layout_rancang" class="form-control">
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" onclick="ubahDataRancang()">Simpan</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('adminlte_js')
<script>
    let produk = [];
    $(document).ready(function () {
        // Data Divisi
        $('.division').select2({
            placeholder: "Pilih Tujuan",
            allowClear: true
        });
        $.ajax({
            url: '/api/gbj/sel-divisi',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if(res) {
                    console.log(res);
                    $("#divisi").empty();
                    $("#divisi").append('<option selected></option>');
                    $.each(res, function(key, value) {
                        $("#divisi").append('<option value="'+value.id+'">'+value.nama+'</option');
                    });
                } else {
                    $("#divisi").empty();
                }
            }
        });
        // Data Produk
        $('.product').select2({
            placeholder: "Pilih Produk",
            allowClear: true
        });
        $.ajax({
            url: '/api/gbj/sel-gbj',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if(res) {
                    console.log(res);
                    $("#gdg_brg_jadi_id").empty();
                    $("#gdg_brg_jadi_id").append('<option selected></option>');
                    $.each(res, function(key, value) {
                        $("#gdg_brg_jadi_id").append('<option value="'+value.id+'">'+value.produk.nama+' '+value.nama+'</option');
                    });
                } else {
                    $("#gdg_brg_jadi_id").empty();
                }
            }
        });
    });
    $(document).on('click', '.btn-tambah', function() {
        // Data Not Change
        let tgl = $('#tgl_masuk').val();
        let from = $('#divisi').val();
        let ket = $('#deskripsi').val();
        // Data Change
        let prd = $('#gdg_brg_jadi_id').val();
        let namaprd = $('#gdg_brg_jadi_id option:selected').text();
        let jml = $('#jumlah').val();
        produk.push({
            prd: prd,
            jml: jml,
            noseri: [],
        });
        addData(namaprd, jml);
    });

    $(document).on('click', '.btn-add-seri', function() {
        let prd = $('#gdg_brg_jadi_id').val();
        let namaprd = $('#gdg_brg_jadi_id option:selected').text();
        let jml = $('#jumlah').val();
        produk.push({
            prd: prd,
            jml: jml
        });
        addData(namaprd, jml);
    });

    $(document).on('click', '.btn-hapus', function () {
        let id = $(this).data('id');
        produk.splice(id, 1);
        $(this).parent().parent().remove();
    })

    function addData(namaprd, jml) {
        let arr = 0;
        let tambah_data = '<tr>'+
                            '<td>'+namaprd+'</td>'+
                            '<td>'+jml+'</td>'+
                            '<td><button class="btn btn-primary btn-add-seri" data-namaprd="'+namaprd+'" data-jml="'+jml+'"><i class="fas fa-qrcode"></i> Tambah</button>&nbsp;<button class="btn btn-danger btn-hapus"><i class="fas fa-trash" data-id="'+arr+'"></i> Hapus</button></td>'+
                        '</tr>';
        $('.tambah_data').append(tambah_data);
    }
</script>
@stop
