@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Penjualan</h1>
@stop

@section('adminlte_css')
<style>
    .filter {
        margin: 5px;
    }

    thead {
        text-align: center;
    }

    td {
        text-align: center;
        white-space: nowrap;
    }

    #urgent {
        color: red;
    }

    #warning {
        color: #FFC700;
    }

    .minimizechar {
        display: inline-block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 13ch;
    }

    .dropdown-toggle:hover {
        color: #4682B4;
    }

    .dropdown-toggle:active {
        color: #C0C0C0;
    }

    @media screen and (max-width: 1440px) {

        label,
        .row {
            font-size: 12px;
        }

        h4 {
            font-size: 20px;
        }

        #detailmodal {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="semua-penjualan-tab" data-toggle="tab" href="#semua-penjualan" role="tab" aria-controls="semua-penjualan" aria-selected="true">Penjualan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="ekatalog-tab" data-toggle="tab" href="#ekatalog" role="tab" aria-controls="ekatalog" aria-selected="false">E-Catalogue</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="spa-tab" data-toggle="tab" href="#spa" role="tab" aria-controls="spa" aria-selected="false">SPA</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="spb-tab" data-toggle="tab" href="#spb" role="tab" aria-controls="spb" aria-selected="false">SPB</a>
                    </li>
                </ul>
                <div class="tab-content card" id="myTabContent">
                    <div class="tab-pane fade show active card-body" id="semua-penjualan" role="tabpanel" aria-labelledby="semua-penjualan-tab">
                        <div class="row">
                            <div class="col-12">
                                <span class="float-right filter">
                                    <a href="{{route('penjualan.penjualan.create')}}"><button class="btn btn-outline-info">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button>
                                    </a>
                                </span>
                                <span class="float-right filter">
                                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3">
                                            <div class="form-group">
                                                <label for="jenis_penjualan">Jenis Penjualan</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="ekatalog" id="jenis1" />
                                                    <label class="form-check-label" for="jenis1">
                                                        E-Catalogue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="spa" id="jenis2" />
                                                    <label class="form-check-label" for="jenis2">
                                                        SPA
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="spa" id="jenis3" />
                                                    <label class="form-check-label" for="jenis3">
                                                        SPB
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="jenis_penjualan">Status</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="sepakat" id="status1" />
                                                    <label class="form-check-label" for="status1">
                                                        Sepakat
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="spa" id="status2" />
                                                    <label class="form-check-label" for="status2">
                                                        Negosiasi
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="batal" id="status3" />
                                                    <label class="form-check-label" for="status3">
                                                        Batal
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="po" id="status4" />
                                                    <label class="form-check-label" for="status4">
                                                        PO
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="gudang" id="status5" />
                                                    <label class="form-check-label" for="status5">
                                                        Gudang
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="qc" id="status6" />
                                                    <label class="form-check-label" for="status6">
                                                        QC
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="logistik" id="status7" />
                                                    <label class="form-check-label" for="status7">
                                                        Logistik
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="pengiriman" id="status8" />
                                                    <label class="form-check-label" for="status8">
                                                        Pengiriman
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <span class="float-right">
                                                    <button class="btn btn-primary">
                                                        Cari
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="penjualantable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No SO</th>
                                                <th>Nomor AKN</th>
                                                <th>Nomor PO</th>
                                                <th>Tanggal Order</th>
                                                <th>Batas Kontrak</th>
                                                <th>Customer</th>
                                                <th>Jenis</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>SOEKAT090202101</td>
                                                <td>AKN1-79479274207</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>05-10-2021</td>
                                                <td>
                                                    <span class="urgent">19-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">RS Soeryadi Kendal</span></td>
                                                <td>E-Catalogue</td>
                                                <td>
                                                    <span class="red-text badge">Batal</span>
                                                </td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('penjualan.penjualan.edit', ['id' => 1, 'jenis' => 'ekatalog'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-pencil-alt"></i>
                                                                Edit
                                                            </button>
                                                        </a>
                                                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="{{route('penjualan.penjualan.detail.ekatalog', ['id' => 2])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SOSPB090202101</td>
                                                <td>-</td>
                                                <td>PO/ON/45/10/21</td>
                                                <td>14-10-2021</td>
                                                <td>
                                                    <span class="warning">28-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">Pak Amin Pasuruan</span></td>
                                                <td>SPB</td>
                                                <td>
                                                    <span class="green-text badge">Pengiriman</span>
                                                </td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('penjualan.penjualan.edit', ['id' => 1, 'jenis' => 'spb'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-pencil-alt"></i>
                                                                Edit
                                                            </button>
                                                        </a>
                                                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="{{route('penjualan.penjualan.detail.spb', ['id' => 2])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SOSPA090202101</td>
                                                <td>-</td>
                                                <td>PO/ON/37/10/21</td>
                                                <td>15-10-2021</td>
                                                <td>29-10-2021</td>
                                                <td><span class="minimizechar">PT Emiindo Jakarta</span></td>
                                                <td>SPA</td>

                                                <td>
                                                    <span class="yellow-text badge">Gudang</span>
                                                </td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('penjualan.penjualan.edit', ['id' => 1, 'jenis' => 'spa'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-pencil-alt"></i>
                                                                Edit
                                                            </button>
                                                        </a>
                                                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="{{route('penjualan.penjualan.detail.spa', ['id' => 2])}}">
                                                            <button class=" dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card-body" id="ekatalog" role="tabpanel" aria-labelledby="ekatalog-tab">
                        <div class="row">
                            <div class="col-12">
                                <span class="float-right filter">
                                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3">
                                            <div class="form-group">
                                                <label for="jenis_penjualan">Status</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="sepakat" id="status1" />
                                                    <label class="form-check-label" for="status1">
                                                        Sepakat
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="spa" id="status2" />
                                                    <label class="form-check-label" for="status2">
                                                        Negosiasi
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="batal" id="status3" />
                                                    <label class="form-check-label" for="status3">
                                                        Batal
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="po" id="status4" />
                                                    <label class="form-check-label" for="status4">
                                                        PO
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="gudang" id="status5" />
                                                    <label class="form-check-label" for="status5">
                                                        Gudang
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="qc" id="status6" />
                                                    <label class="form-check-label" for="status6">
                                                        QC
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="logistik" id="status7" />
                                                    <label class="form-check-label" for="status7">
                                                        Logistik
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="pengiriman" id="status8" />
                                                    <label class="form-check-label" for="status8">
                                                        Pengiriman
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <span class="float-right">
                                                    <button class="btn btn-primary">
                                                        Cari
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="ekatalogtable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor SO</th>
                                                <th>Nomor AKN</th>
                                                <th>Nomor PO</th>
                                                <th>Tanggal Order</th>
                                                <th>Batas Kontrak</th>
                                                <th>Customer</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>-</td>
                                                <td>AKN1-79479274207</td>
                                                <td>-</td>
                                                <td>05-10-2021</td>
                                                <td>
                                                    <span class="urgent">19-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">RS Soeryadi Kendal</span></td>
                                                <td>
                                                    <span class="red-text badge">Batal</span>
                                                </td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a href="{{route('penjualan.so.create')}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-plus"></i>
                                                                Tambah PO
                                                            </button>
                                                        </a>
                                                        <a href="{{route('penjualan.penjualan.edit', ['id' => 1, 'jenis' => 'ekatalog'])}}">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-pencil-alt"></i>
                                                                Edit
                                                            </button>
                                                        </a>
                                                        <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr="">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SOEKAT090202101</td>
                                                <td>AKN1-79479274207</td>
                                                <td>PO/ON/45/10/21</td>
                                                <td>14-10-2021</td>
                                                <td>
                                                    <span class="warning">28-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">PT Cipta Medika Pasuruan</span></td>

                                                <td>
                                                    <span class="green-text badge">Sepakat</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SOEKAT090202101</td>
                                                <td>AKN1-79479274207</td>
                                                <td>PO/ON/45/10/21</td>
                                                <td>15-10-2021</td>
                                                <td>29-10-2021</td>
                                                <td><span class="minimizechar">PT Emiindo Jakarta</span></td>
                                                <td>
                                                    <span class="yellow-text badge">Negosiasi</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card-body" id="spa" role="tabpanel" aria-labelledby="spa-tab">
                        <div class="row">
                            <div class="col-12">
                                <span class="float-right filter">
                                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3">
                                            <div class="form-group">
                                                <label for="jenis_penjualan">Status</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="po" id="status4" />
                                                    <label class="form-check-label" for="status4">
                                                        PO
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="gudang" id="status5" />
                                                    <label class="form-check-label" for="status5">
                                                        Gudang
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="qc" id="status6" />
                                                    <label class="form-check-label" for="status6">
                                                        QC
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="logistik" id="status7" />
                                                    <label class="form-check-label" for="status7">
                                                        Logistik
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="pengiriman" id="status8" />
                                                    <label class="form-check-label" for="status8">
                                                        Pengiriman
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <span class="float-right">
                                                    <button class="btn btn-primary">
                                                        Cari
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="spatable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor SO</th>
                                                <th>Nomor PO</th>
                                                <th>Tanggal Order</th>
                                                <th>Batas Kontrak</th>
                                                <th>Customer</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>SOSPA0902012910</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>05-10-2021</td>
                                                <td>
                                                    <span class="urgent">19-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">RS Soeryadi Kendal</span></td>
                                                <td>
                                                    <span class="red-text badge">PO</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SOSPA0902012910</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>14-10-2021</td>
                                                <td>
                                                    <span class="warning">28-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">PT Cipta Medika Pasuruan</span></td>
                                                <td>
                                                    <span class="yellow-text badge">Gudang</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SOSPA0902012910</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>15-10-2021</td>
                                                <td>29-10-2021</td>
                                                <td><span class="minimizechar">PT Emiindo Jakarta</span></td>
                                                <td>
                                                    <span class="green-text badge">Pengiriman</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card-body" id="spb" role="tabpanel" aria-labelledby="spb-tab">
                        <div class="row">
                            <div class="col-12">
                                <span class="float-right filter">
                                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3">
                                            <div class="form-group">
                                                <label for="jenis_penjualan">Status</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="po" id="status4" />
                                                    <label class="form-check-label" for="status4">
                                                        PO
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="gudang" id="status5" />
                                                    <label class="form-check-label" for="status5">
                                                        Gudang
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="qc" id="status6" />
                                                    <label class="form-check-label" for="status6">
                                                        QC
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="logistik" id="status7" />
                                                    <label class="form-check-label" for="status7">
                                                        Logistik
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="pengiriman" id="status8" />
                                                    <label class="form-check-label" for="status8">
                                                        Pengiriman
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <span class="float-right">
                                                    <button class="btn btn-primary">
                                                        Cari
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="spbtable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor SO</th>
                                                <th>Nomor PO</th>
                                                <th>Tanggal Order</th>
                                                <th>Batas Kontrak</th>
                                                <th>Customer</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>SOSPB0902012910</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>05-10-2021</td>
                                                <td>
                                                    <span class="urgent">19-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">RS Soeryadi Kendal</span></td>
                                                <td>
                                                    <span class="yellow-text badge">Gudang</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SOSPB0902012910</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>14-10-2021</td>
                                                <td>
                                                    <span class="warning">28-10-2021</span>
                                                </td>
                                                <td><span class="minimizechar">PT Cipta Medika Pasuruan</span></td>
                                                <td>
                                                    <span class="yellow-text badge">Gudang</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SOSPB0902012910</td>
                                                <td>PO/ON/51/10/21</td>
                                                <td>15-10-2021</td>
                                                <td>29-10-2021</td>
                                                <td><span class="minimizechar">PT Emiindo Jakarta</span></td>
                                                <td>
                                                    <span class="green-text badge">Pengiriman</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
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
    <div class="modal fade" id="detailmodal" tabindex="-1" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="margin: 10px">
                <div class="modal-header bg-warning">
                    <h4>Detail</h4>
                </div>
                <div class="modal-body" id="detail">

                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    $(function() {
        $(document).on('click', '.detailmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#detailmodal').modal("show");
                    $('#detail').html(result).show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });
    });
</script>
@stop