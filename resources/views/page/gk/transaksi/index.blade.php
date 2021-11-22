@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    .foo {
        float: left;
        width: 20px;
        height: 20px;
        margin: 5px;
        border: 1px solid rgba(0, 0, 0, .2);
    }

    .green {
        background: #28A745;
    }

    .blue {
        background: #17A2B8;
    }

    .gambar-resize {
        width: 250px;
        height: 250px;
    }

    .card-center {
        left: 20%;
    }
    .nomor-so{
        background-color: #717FE1;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .nomor-akn{
        background-color: #DF7458;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .nomor-po{
        background-color: #85D296;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .instansi{
        background-color: #36425E;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .filter{
        margin: 5px;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tabList">
                    <li class="nav-item" role="presentation">
                        <a href="#semua-produk" class="nav-link active" id="semua-produk-tab" data-toggle="tab"
                            role="tab" aria-controls="semua-produk" aria-selected="true">Semua Riwayat Transaksi</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#produk" class="nav-link" id="produk-tab" data-toggle="tab" role="tab"
                            aria-controls="produk" aria-selected="true">Per Produk</a>
                    </li>
                </ul>
                <div class="tab-content card" id="myTabContent">
                    <div class="tab-pane fade show active card-body" id="semua-produk" role="tabpanel"
                        aria-labelledby="semua-produk-tab">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="row align-items-center">
                                    <div class="col-lg-9 col-xl-8">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 my-2 my-md-0">
                                                <div class="d-flex align-items-center">
                                                    <label class="mr-3 mb-0 d-none d-md-block" for="">Tanggal</label>
                                                    <input type="text" name="" id="datetimepicker1"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2 my-md-0">
                                                <div class="d-flex align-items-center">
                                                    <label class="mr-3 mb-0 d-none d-md-block" for="">Dari / Ke</label>
                                                    <select name="" id="" class="form-control">
                                                        <option value="">All</option>
                                                        <option value="">Divisi IT</option>
                                                        <option value="">Divisi QC</option>
                                                        <option value="">Divisi SO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2 my-md-0">
                                                <div class="d-flex align-items-center">
                                                    <label class="mr-3 mb-0 d-none d-md-block" for="">Jenis Produk</label>
                                                    <select name="" id="" class="form-control">
                                                        <option value="">All</option>
                                                        <option value="">Sparepart</option>
                                                        <option value="">Unit</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                                        <a href="#" class="btn btn-outline-primary">Search</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card-group">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="card-text">Keterangan Kolom <b>Dari/Ke:</b></p>
                                                    <p class="card-text">
                                                        <div class="foo green"></div> : Dari
                                                    </p>
                                                    <p class="card-text">
                                                        <div class="foo blue"></div> : Ke
                                                    </p>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="card-text">Keterangan Kolom <b>Tanggal:</b></p>
                                                    <p class="card-text">
                                                        <div class="foo green"></div> : Tanggal Masuk
                                                    </p>
                                                    <p class="card-text">
                                                        <div class="foo blue"></div> : Tanggal Keluar
                                                    </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    {{-- Tanggal Masuk dan Tanggal Keluar --}}
                                    <table class="table table-bordered pertanggal" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:220px">Tanggal</th>
                                                <th style="width:220px">Dari/Ke</th>
                                                <th>Jenis</th>
                                                <th>Produk</th>
                                                <th>Unit</th>
                                                <th>Jumlah</th>
                                                <th>Tujuan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span class="badge badge-success">10-04-2022</span> / <span class="badge badge-info">23-10-2022</span></td>
                                                <td><span class="badge badge-success">Divisi IT</span> / <span class="badge badge-info">Divisi QC</span></td>
                                                <td>Sparepart</td>
                                                <td>Produk 1</td>
                                                <td>Unit 1</td>
                                                <td>100 Unit</td>
                                                <td>Uji Coba</td>
                                                <td><button class="btn btn-outline-info" onclick="detailtanggal()"><i class="far fa-eye"></i> Detail</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card-body" id="produk" role="tabpanel" aria-labelledby="produk-tab">
                        <div class="col-xl-12 d-flex justify-content-end">
                            <span class="float-right filter">
                                <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Jenis Produk</label>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="ekatalog" id="jenis1" />
                                                <label class="form-check-label" for="jenis1">
                                                    All
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="ekatalog" id="jenis1" />
                                                <label class="form-check-label" for="jenis1">
                                                    Sparepart
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="spa" id="jenis2" />
                                                <label class="form-check-label" for="jenis2">
                                                    Unit
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </span>
                        </div>
                        <div class="table-produk">
                            <table class="table table-bordered" id="gudang-barang">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Produk</th>
                                        <th>Produk</th>
                                        <th>Jenis Produk</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>ZTP80AS-UPGRADE</td>
                                        <td>STERILISATOR KERING</td>
                                        <td>Sparepart</td>
                                        <td><a class="btn btn-info" href="{{ url('gk/transaksi/1') }}"><i
                                                    class="far fa-eye"></i> Detail</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>ZTP80AS-UPGRADE</td>
                                        <td>STERILISATOR KERING</td>
                                        <td>Unit</td>
                                        <td><a class="btn btn-info" href="{{ url('gk/transaksi/1') }}"><i
                                                    class="far fa-eye"></i> Detail</a>
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
</div>


<!-- Modal Per Tanggal-->
<div class="modal fade" id="modal-per-tanggal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Produk Ambulatory</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-seri">
                    <thead>
                        <tr>
                            <th>No Seri</th>
                            <th>Kerusakan</th>
                            <th>Tingkat Kerusakan</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">65465465465</td>
                            <td>Kerusakan Komponen</td>
                            <td>Level 1</td>
                            <td>Layout 1</td>
                        </tr>
                        <tr>
                            <td scope="row">65465465465</td>
                            <td>Kerusakan Komponen</td>
                            <td>Level 1</td>
                            <td>Layout 1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@stop

@section('adminlte_js')
<script>
    $('#datetimepicker1').daterangepicker({});


    function detailtanggal() {
        $('#modal-per-tanggal').modal('show');
    }

    $(document).ready(function () {
        $('.pertanggal').dataTable({
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $('#gudang-barang').dataTable({
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $('.add-produk').dataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $(document).on("click", "#semua-produk-tab", function () {
            $('.produk-show').addClass('hidden-product');
        });
        $(document).on("click", "#produk-tab", function () {
            $('.produk-show').addClass('hidden-product');
        });
        $('.table-seri').dataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
    });

    function detailProdukModal() {
        $('.produk-show').removeClass('hidden-product');
    }

</script>
@stop
