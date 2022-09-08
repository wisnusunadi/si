@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Penjualan Eksternal</h1>
        </div>
    </div>
</div>
@endsection
@section('content')
<section class="content">
    <div class="container-fluid">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="#ekatalog" class="nav-link active" id="ekatalog-tab" role="tab" aria-controls="ekatalog"
                    aria-selected="false">E-Katalog</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#spa" class="nav-link" id="spa-tab" role="tab" aria-controls="spa"
                    aria-selected="false">SPA</a>
            </li>
        </ul>
        <div class="tab-content card" id="myTabContent">
            <div class="tab-pane fade show active card-body" id="ekatalog" role="tabpanel" aria-labelledby="ekatalog-tab">
                <div class="card-body">
                    <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-proses_kirim-tab" data-toggle="pill"
                                href="#pills-proses_kirim" role="tab" aria-controls="pills-proses_kirim"
                                aria-selected="true">Sales Order</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-selesai_kirim-tab" data-toggle="pill"
                                href="#pills-selesai_kirim" role="tab" aria-controls="pills-selesai_kirim"
                                aria-selected="false">Purchase Order</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-proses_kirim" role="tabpanel"
                            aria-labelledby="pills-proses_kirim-tab">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered salesorder" style="width: 100%"
                                            id="belum-dicek">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nomor AKN</th>
                                                    <th>Tanggal Buat</th>
                                                    <th>Tanggal Edit</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>123456789</td>
                                                    <td>12-12-2019</td>

                                                    <td>12-12-2019</td>
                                                    <td>
                                                        <button
                                                            class="btn btn-outline-success previewButtonSalesOrder"><i
                                                                class="fas fa-eye"></i> Detail</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="pills-selesai_kirim" role="tabpanel"
                            aria-labelledby="pills-selesai_kirim-tab">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered purchaseorder" style="width: 100%"
                                            id="sudah-dicek">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nomor PO</th>
                                                    <th>Nomor AKN</th>
                                                    <th>Nomor DO</th>
                                                    <th>Tanggal PO</th>
                                                    <th>Tanggal DO</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>123456789</td>
                                                    <td>123456789</td>
                                                    <td>123456789</td>
                                                    <td>12-12-2019</td>
                                                    <td>12-12-2019</td>
                                                    <td>
                                                        <button class="btn btn-outline-success previewButtonPurchaseOrder"><i
                                                                class="fas fa-eye"></i> Detail</button>
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
            <div class="tab-pane fade show card-body" id="spa" role="tabpanel" aria-labelledby="spa-tab">
                <div class="card-body">
                    <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-proses_kirim-tab" data-toggle="pill"
                                href="#pills-proses_kirim" role="tab" aria-controls="pills-proses_kirim"
                                aria-selected="true">Sales Order</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-selesai_kirim-tab" data-toggle="pill"
                                href="#pills-selesai_kirim" role="tab" aria-controls="pills-selesai_kirim"
                                aria-selected="false">Purchase Order</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-proses_kirim" role="tabpanel"
                            aria-labelledby="pills-proses_kirim-tab">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered salesorder" style="width: 100%"
                                            id="belum-dicek">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nomor AKN</th>
                                                    <th>Tanggal Buat</th>
                                                    <th>Tanggal Edit</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>123456789</td>
                                                    <td>12-12-2019</td>

                                                    <td>12-12-2019</td>
                                                    <td>
                                                        <button
                                                            class="btn btn-outline-success previewButtonSalesOrder"><i
                                                                class="fas fa-eye"></i> Detail</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="pills-selesai_kirim" role="tabpanel"
                            aria-labelledby="pills-selesai_kirim-tab">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered purchaseorder" style="width: 100%"
                                            id="sudah-dicek">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nomor PO</th>
                                                    <th>Nomor AKN</th>
                                                    <th>Nomor DO</th>
                                                    <th>Tanggal PO</th>
                                                    <th>Tanggal DO</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>123456789</td>
                                                    <td>123456789</td>
                                                    <td>123456789</td>
                                                    <td>12-12-2019</td>
                                                    <td>12-12-2019</td>
                                                    <td>
                                                        <button class="btn btn-outline-success previewButtonPurchaseOrder"><i
                                                                class="fas fa-eye"></i> Detail</button>
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
        </div>
    </div>
    {{-- Modal Sales Order--}}
    <div class="modal fade salesorderModal" id="" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row filter">
                        <div class="col-12">
                            <div class="card card-detail removeshadow">
                                <div class="card-body border-0">
                                    <h5 class="card-title pl-2 py-2"><b>PT. Aneka Industri Gas
                                            Medik</b></h5>
                                    <ul class="fa-ul card-text">
                                        <li class="py-2"><span class="fa-li"><i
                                                    class="fas fa-address-card fa-fw"></i></span>
                                            Komplek Green Sedayu Bizpark Blok GS 5 No. 122
                                            Cakung Timur, Cakung, Jakarta Timur, DKI Jakarta
                                        </li>
                                        <li class="py-2"><span class="fa-li"><i
                                                    class="fas fa-map-marker-alt fa-fw"></i></span>
                                            DKI Jakarta
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card card-orange card-outline card-tabs">
                                <div class="card-header p-0 pt-1 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="tabs-detail-tab" data-toggle="pill"
                                                href="#tabs-detail" role="tab" aria-controls="tabs-detail"
                                                aria-selected="true">Informasi</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tabs-produk-tab" data-toggle="pill"
                                                href="#tabs-produk" role="tab" aria-controls="tabs-produk"
                                                aria-selected="false">Produk</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-three-tabContent">
                                        <div class="tab-pane fade active show" id="tabs-detail" role="tabpanel"
                                            aria-labelledby="tabs-detail-tab">

                                            <div class="row d-flex justify-content-between">

                                                <div class="p-2">
                                                    <div class="margin">
                                                        <div><small class="text-muted">No AKN</small></div>
                                                        <div><b>
                                                                123456789
                                                            </b>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="p-2">
                                                    <div class="margin">
                                                        <div><small class="text-muted">Tanggal Buat</small></div>
                                                        <div><b>
                                                                23-05-2022
                                                            </b>
                                                        </div>
                                                    </div>
                                                    <div class="margin">
                                                        <div><small class="text-muted">Tanggal
                                                                Edit</small></div>
                                                        <div><b>
                                                                23-05-2022
                                                            </b>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tabs-produk" role="tabpanel"
                                            aria-labelledby="tabs-produk-tab">
                                            <div class="table-responsive">
                                                <div class="card removeshadow overflowy">
                                                    <div class="card-body">

                                                        <table class="table"
                                                            style="max-width:100%; overflow-x: hidden; background-color:white;"
                                                            id="tabledetailpesan">
                                                            <thead>
                                                                <tr>
                                                                    <th rowspan="2">No</th>
                                                                    <th rowspan="2">Produk</th>
                                                                    <th colspan="2">Qty</th>
                                                                    <th rowspan="2">Harga</th>
                                                                </tr>
                                                                <tr>
                                                                    <th><i class="fas fa-shopping-cart"></i>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td rowspan="1" class="nowraptxt">1</td>
                                                                    <td><b class="wb">VANWARD
                                                                            DUPLEX SENTRAL GAS
                                                                            MEDIK VACUUM/
                                                                            MEDICAL VADUUM
                                                                            SYSTEM 1.25 kW</b>
                                                                    </td>
                                                                    <td colspan="2" class="nowraptxt tabnum">
                                                                        1</td>
                                                                    <td rowspan="1" class="nowraptxt tabnum">
                                                                        Rp. 208.000.000</td>
                                                                </tr>

                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="4">Total Harga
                                                                    </td>
                                                                    <td class="tabnum nowraptxt">
                                                                        Rp. 208.000.000</td>
                                                                </tr>
                                                            </tfoot>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary btnso">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Puchase Order--}}
    <div class="modal fade purchaseorderModal" id="" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row filter">
                        <div class="col-12">
                            <div class="card card-detail removeshadow">
                                <div class="card-body border-0">
                                    <h5 class="card-title pl-2 py-2"><b>PT. Aneka Industri Gas
                                            Medik</b></h5>
                                    <ul class="fa-ul card-text">
                                        <li class="py-2"><span class="fa-li"><i
                                                    class="fas fa-address-card fa-fw"></i></span>
                                            Komplek Green Sedayu Bizpark Blok GS 5 No. 122
                                            Cakung Timur, Cakung, Jakarta Timur, DKI Jakarta
                                        </li>
                                        <li class="py-2"><span class="fa-li"><i
                                                    class="fas fa-map-marker-alt fa-fw"></i></span>
                                            DKI Jakarta
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card card-orange card-outline card-tabs">
                                <div class="card-header p-0 pt-1 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="tabs-detail2-tab" data-toggle="pill"
                                                href="#tabs-detail2" role="tab" aria-controls="tabs-detail2"
                                                aria-selected="true">Informasi</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tabs-produk2-tab" data-toggle="pill"
                                                href="#tabs-produk2" role="tab" aria-controls="tabs-produk2"
                                                aria-selected="false">Produk</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-three-tabContent">
                                        <div class="tab-pane fade active show" id="tabs-detail2" role="tabpanel"
                                            aria-labelledby="tabs-detail2-tab">

                                            <div class="row d-flex justify-content-between">

                                                <div class="p-2">
                                                    <div class="margin">
                                                        <div><small class="text-muted">No PO</small></div>
                                                        <div><b>
                                                                123456789
                                                            </b>
                                                        </div>
                                                    </div>
                                                    <div class="margin">
                                                        <div><small class="text-muted">No AKN</small></div>
                                                        <div><b>
                                                                123456789
                                                            </b>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="p-2">
                                                    <div class="margin">
                                                        <div><small class="text-muted">Tanggal Buat</small></div>
                                                        <div><b>
                                                                23-05-2022
                                                            </b>
                                                        </div>
                                                    </div>
                                                    <div class="margin">
                                                        <div><small class="text-muted">Tanggal
                                                                Edit</small></div>
                                                        <div><b>
                                                                23-05-2022
                                                            </b>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="p-2">
                                                    <div class="margin">
                                                        <div><small class="text-muted">Ekspedisi</small></div>
                                                        <div><b>
                                                                JNE
                                                            </b>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tabs-produk2" role="tabpanel"
                                            aria-labelledby="tabs-produk2-tab">
                                            <div class="table-responsive">
                                                <div class="card removeshadow overflowy">
                                                    <div class="card-body">

                                                        <table class="table"
                                                            style="max-width:100%; overflow-x: hidden; background-color:white;"
                                                            id="tabledetailpesan">
                                                            <thead>
                                                                <tr>
                                                                    <th rowspan="2">No</th>
                                                                    <th rowspan="2">Produk</th>
                                                                    <th colspan="2">Qty</th>
                                                                    <th rowspan="2">Harga</th>
                                                                </tr>
                                                                <tr>
                                                                    <th><i class="fas fa-shopping-cart"></i>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td rowspan="1" class="nowraptxt">1</td>
                                                                    <td><b class="wb">VANWARD
                                                                            DUPLEX SENTRAL GAS
                                                                            MEDIK VACUUM/
                                                                            MEDICAL VADUUM
                                                                            SYSTEM 1.25 kW</b>
                                                                    </td>
                                                                    <td colspan="2" class="nowraptxt tabnum">
                                                                        1</td>
                                                                    <td rowspan="1" class="nowraptxt tabnum">
                                                                        Rp. 208.000.000</td>
                                                                </tr>

                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <td colspan="4">Total Harga
                                                                    </td>
                                                                    <td class="tabnum nowraptxt">
                                                                        Rp. 208.000.000</td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary btnpo">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('adminlte_js')

@endsection
