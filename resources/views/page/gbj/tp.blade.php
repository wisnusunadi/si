@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tabList">
                    <li class="nav-item" role="presentation">
                        <a href="#semua-produk" class="nav-link active" id="semua-produk-tab" data-toggle="tab" role="tab" aria-controls="semua-produk" aria-selected="true">Semua Riwayat Transaksi</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#produk" class="nav-link" id="produk-tab" data-toggle="tab" role="tab" aria-controls="produk" aria-selected="true">Per Produk</a>
                    </li>
                </ul>
                <div class="tab-content card" id="myTabContent">
                    <div class="tab-pane fade show active card-body" id="semua-produk" role="tabpanel" aria-labelledby="semua-produk-tab">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="" id="tanggal" class="col-sm-5 text-right">Tanggal</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" id="datetimepicker1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    {{-- Tanggal Masuk dan Tanggal Keluar --}}
                                    <table class="table table-hover pertanggal" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Tanggal Masuk</th>
                                                <th>Tanggal Keluar</th>
                                                <th>Dari</th>
                                                <th>Tujuan</th>
                                                <th>Produk</th>
                                                <th>Jumlah</th>
                                                <td>Aksi</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>10-09-2022</td>
                                                <td>10-09-2022</td>
                                                <td>Divisi IT</td>
                                                <td>Uji Coba Produk</td>
                                                <td>Ambulatory</td>
                                                <td>100 Unit</td>
                                                <td><button class="btn btn-info" onclick="detailtanggal()"><i class="far fa-eye"></i> Detail</button></td>
                                            </tr>
                                            <tr>
                                                <td>10-09-2022</td>
                                                <td>10-09-2022</td>
                                                <td>Divisi IT</td>
                                                <td>Uji Coba Produk</td>
                                                <td>Ambulatory</td>
                                                <td>100 Unit</td>
                                                <td><button class="btn btn-info" onclick="detailtanggal()"><i class="far fa-eye"></i> Detail</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card-body" id="produk" role="tabpanel" aria-labelledby="produk-tab">
                        <div class="row">
                            <div class="col-lg-12">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade modal-view-1" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Riwayat Produk Ambulator</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Keluar</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">10-11-2021</td>
                            <td>10-11-2021</td>
                            <td>100 Unit</td>
                            <td><button class="btn btn-info" onclick="detail()"><i class="far fa-eye"></i> Detail</button></td>
                        </tr>
                        <tr>
                            <td scope="row">10-11-2021</td>
                            <td>10-11-2021</td>
                            <td>100 Unit</td>
                            <td><button class="btn btn-info" onclick="detail()"><i class="far fa-eye"></i> Detail</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade modal-view-2" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <div class="row">
                        <div class="col">
                            <b>Produk</b><p>Ambulatory</p>
                        </div>
                        <div class="col">
                            <b>Tanggal Masuk</b><p>10-11-2021</p>
                        </div>
                        <div class="col">
                            <b>Tanggal Masuk</b><p>10-11-2021</p>
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
                            <th>No Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">564654654</td>
                            <td>Layout 1</td>
                        </tr>
                        <tr>
                            <td scope="row">65464654</td>
                            <td>Layout 1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Per Tanggal-->
<div class="modal fade" id="modal-per-tanggal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Produk Ambulatory</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">65465465465</td>
                            <td>Layout 1</td>
                        </tr>
                        <tr>
                            <td scope="row">65465465465</td>
                            <td>Layout 2</td>
                        </tr>
                    </tbody>
                </table>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    function preview() { 
        $('.modal-view-1').modal('show')
    }
    function detail() {
        $('.modal-view-2').modal('show')
    }
    $('#datetimepicker1').daterangepicker({});

    function detailtanggal() { 
        $('#modal-per-tanggal').modal('show');
    }
</script>
@stop
