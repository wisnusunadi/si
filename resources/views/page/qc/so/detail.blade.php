@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Sales Order</h1>
@stop

@section('adminlte_css')
<style>
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
        float: right;
    }

    .margin {
        margin-bottom: 5px;
    }

    .filter {
        margin: 5px;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4>Info Penjualan</h4>
                <div class="row">
                    <div class="col-5">
                        <div class="margin">
                            <b id="no_akn">AK1-P2110-4365736 </b>
                        </div>
                        <div class="margin">
                            <b id="distributor">CIPTAJAYA RETAIL INDONESIA PT </b><small>(Distributor)</small>
                        </div>
                        <div class="margin">
                            <div><b id="no_akn">DINAS KESEHATAN PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA</b></div>
                            <small>(Pemerintah Daerah Provinsi Kalimantan Selatan)</small>
                        </div>

                    </div>
                    <div class="col-3"></div>
                    <div class="col-3 filter">
                        <div class="margin">
                            <span class="text-muted">No SO</span>
                            <b id="no_po" class="align-right">SO/EKAT/09/21/00001 </b>
                        </div>
                        <div class="margin">
                            <span class="text-muted">No PO</span>
                            <b id="no_po" class="align-right">PO/ON/09/21/00001 </b>
                        </div>
                        <div class="margin">
                            <div><span class="text-muted">Batas Uji</span>
                                <b id="no_po" class="align-right">29 November 2020 </b>
                            </div>
                            <div class="align-right"><small class="nok"><i class="fas fa-exclamation-circle"></i> Pengujian sisa 3 hari lagi</small></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
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
                                            <input class="form-check-input" type="checkbox" value="selesai" id="status1" name="status" />
                                            <label class="form-check-label" for="status1">
                                                Selesai Diperiksa
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="sebagian" id="status2" name="status" />
                                            <label class="form-check-label" for="status2">
                                                Sebagian Diperiksa
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="belum" id="status3" name="status" />
                                            <label class="form-check-label" for="status3">
                                                Belum Diperiksa
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
                            <table class="table" style="text-align:center;" id="showtable">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">Nama Produk</th>
                                        <th rowspan="2">Jumlah</th>
                                        <th colspan="2">Hasil</th>
                                        <th rowspan="2">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th><i class="fas fa-check ok"></i></th>
                                        <th><i class="fas fa-times nok"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>ELITECH MINI/MEDICAL COMPRESSOR NEBULIZER PROMIST 2</td>
                                        <td>2</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td><i class="fas fa-search"></i></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>ELITECH ULTRASONIC POCKET DOPPLER</td>
                                        <td>5</td>
                                        <td>2</td>
                                        <td>0</td>
                                        <td><i class="fas fa-search"></i></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>MTB 2 MTR</td>
                                        <td>10</td>
                                        <td>5</td>
                                        <td>2</td>
                                        <td>0</td>
                                        <td><i class="fas fa-search"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <span class="float-right filter">
                            <button class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-clipboard-check"></i> Filter
                            </button>
                            <div class="dropdown-menu">
                                <div class="px-3 py-3">
                                    <div class="form-group">
                                        <label for="jenis_penjualan">Status</label>
                                    </div>
                                    <div class="form-group">
                                        <button>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="sebagian" id="status2" name="status" />
                                            <label class="form-check-label" for="status2">
                                                Sebagian Diperiksa
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="belum" id="status3" name="status" />
                                            <label class="form-check-label" for="status3">
                                                Belum Diperiksa
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
                            <table class="table" style="text-align:center;" id="showtable">
                                <thead>
                                    <th>#</th>
                                    <th>No</th>
                                    <th>No Seri</th>
                                    <th>Hasil</th>
                                    <th>Aksi</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input ok" type="checkbox" value="" id="" disabled />
                                            </div>
                                        </td>
                                        <td>1</td>
                                        <td>TD0015012021001</td>
                                        <td><i class="fas fa-check-circle ok"></i></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input yet" type="checkbox" value="spa" id="" />
                                            </div>
                                        </td>
                                        <td>2</td>
                                        <td>TD0015012021002</td>
                                        <td><i class="fas fa-question-circle warning"></i></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input " type="checkbox" value="spa" id="" />
                                            </div>
                                        </td>
                                        <td>3</td>
                                        <td>TD0015012021003</td>
                                        <td><i class="fas fa-times-circle nok"></i></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</div>
@stop
@section('adminlte_js')
<script>
    $(function() {
        var showtable = $('#showtable').DataTable({})

    })
</script>
@stop