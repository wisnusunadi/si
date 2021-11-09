@extends('adminlte.page')

@section('title', 'ERP')


@section('adminlte_css')
<style>
    #showtable {
        text-align: center;
        white-space: nowrap;
    }

    .ok {
        color: green;
        font-weight: 600;
    }

    .nok {
        color: #dc3545;
        font-weight: 600;
    }

    .warning {
        color: #FFC700;
        font-weight: 600;
    }

    .list-group-item {
        border: 0 none;
    }

    .align-right {
        text-align: right;
    }

    .align-center {
        text-align: center;
    }

    .margin {
        margin-bottom: 5px;
    }

    .filter {
        margin: 5px;
    }

    .hide {
        display: none !important;
    }

    .bgcolor {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .fa-search:hover {
        color: #ADD8E6;
    }

    .fa-search:active {
        color: #808080;
    }

    .nowrap-text {
        white-space: nowrap;
    }
</style>
@stop

@section('content_header')
<h1 class="m-0 text-dark">Pengiriman</h1>
@stop

@section('content')
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Info</h5>
                    <div class="row">
                        <div class="col-5">
                            <div class="margin">
                                <div><small class="text-muted">Subjek Pengiriman</small></div>
                            </div>
                            <div class="margin">
                                <b id="customer">CIPTAJAYA RETAIL INDONESIA PT </b>
                            </div>
                            <div class="margin">
                                <b id="alamat">Komplek Pergudangan Citra Raya Graha Blok E no 19 - 20 Bandung</b>
                            </div>
                            <div class="margin">
                                <b id="provinsi">Jawa Barat</b>
                            </div>
                            <div class="margin">
                                <b id="telepon">0819090567256</b>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="margin">
                                <div><small class="text-muted">Ekspedisi</small></div>
                                <div><b id="ekspedisi">MULIA BAKTI EXPRESS (BAYAR TUJUAN)</b></div>
                            </div>
                            <div class="margin">
                                <div><small class="text-muted">No Surat Jalan</small></div>
                                <div><b id="no_sj">SJ/09/09/21/5001</b></div>
                            </div>
                            <div class="margin">
                                <div><small class="text-muted">Tanggal Kirim</small></div>
                                <div><b id="no_sj">20-10-2021</b></div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="margin">
                                <div><small class="text-muted">No Sales Order</small></div>
                                <div><b id="no_so">SO/09/21/00001</b></div>
                            </div>
                            <div class="margin">
                                <div><small class="text-muted">No PO</small></div>
                                <div><b id="no_so">PO/ON/09/21/00001</b></div>
                            </div>
                            <div class="margin">
                                <div><small class="text-muted">Tanggal PO</small></div>
                                <div><b id="no_so">29-09-2021</b></div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="margin">
                                <div><small class="text-muted">Status</small></div>
                                <div><span class="badge blue-text">Dalam Pengiriman</span></div>
                            </div>
                            <div class="margin">
                                <div><small class="text-muted">Resi</small></div>
                                <div><b id="no_resi">JP3183810381</b></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-center" id="showtable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Elitech MTB 2 MTR</td>
                                    <td>10</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Elitech Pocket Fetal Doppler</td>
                                    <td>1</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop