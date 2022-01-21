@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Sales Order</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @if(Auth::user()->divisi_id == "23")
                <li class="breadcrumb-item"><a href="{{route('qc.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Sales Order QC</li>
                @elseif(Auth::user()->divisi_id == "2")
                <li class="breadcrumb-item"><a href="{{route('direksi.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Sales Order QC</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
<style>
    .urgent {
        color: #dc3545;
        font-weight: 600;
    }

    .warning {
        color: #FFC700;
        font-weight: 600;
    }

    .filter {
        margin: 5px;
    }

    .info {
        color: #4682B4
    }

    @media screen and (min-width: 1440px) {

        section {
            font-size: 14px;
        }

        #detailmodal {
            font-size: 14px;
        }

        .btn {
            font-size: 12px;
        }
    }

    @media screen and (max-width: 1439px) {

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
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-proses_uji-tab" data-toggle="pill" href="#pills-proses_uji" role="tab" aria-controls="pills-proses_uji" aria-selected="true">Dalam Proses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-selesai_uji-tab" data-toggle="pill" href="#pills-selesai_uji" role="tab" aria-controls="pills-selesai_uji" aria-selected="false">Selesai Proses</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-proses_uji" role="tabpanel" aria-labelledby="pills-proses_uji-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <span class="float-right filter">
                                            <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-filter"></i> Filter
                                            </button>
                                            <form id="filter">
                                                <div class="dropdown-menu">
                                                    <div class="px-3 py-3">
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Jenis Penjualan</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="ekatalog" id="defaultCheck1" />
                                                                <label class="form-check-label" for="defaultCheck1">
                                                                    E-Catalogue
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2" />
                                                                <label class="form-check-label" for="defaultCheck2">
                                                                    SPA
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="spb" id="defaultCheck3" />
                                                                <label class="form-check-label" for="defaultCheck3">
                                                                    SPB
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <span class="float-right">
                                                                <button class="btn btn-primary" type="submit">
                                                                    Cari
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table" style="text-align:center;width:100%;" id="showtable">
                                                <thead>
                                                    <th>No</th>
                                                    <th>No SO</th>
                                                    <th>No PO</th>
                                                    <th>Batas Pengujian</th>
                                                    <th>Customer</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="pills-selesai_uji" role="tabpanel" aria-labelledby="pills-selesai_uji-tab">
                                <div class="row">
                                    <div class="col-12">
                                        <span class="float-right filter">
                                            <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-filter"></i> Filter
                                            </button>
                                            <form id="filter_selesai">
                                                <div class="dropdown-menu">
                                                    <div class="px-3 py-3">
                                                        <div class="form-group">
                                                            <label for="jenis_penjualan">Jenis Penjualan</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="ekatalog" id="defaultCheck1" />
                                                                <label class="form-check-label" for="defaultCheck1">
                                                                    E-Catalogue
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2" />
                                                                <label class="form-check-label" for="defaultCheck2">
                                                                    SPA
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="spb" id="defaultCheck3" />
                                                                <label class="form-check-label" for="defaultCheck3">
                                                                    SPB
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <span class="float-right">
                                                                <button class="btn btn-primary" type="submit">
                                                                    Cari
                                                                </button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table" style="text-align:center;width:100%;" id="selesaitable">
                                                <thead>
                                                    <th>No</th>
                                                    <th>No SO</th>
                                                    <th>No PO</th>
                                                    <th>Batas Pengujian</th>
                                                    <th>Customer</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </thead>
                                                <tbody>
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
    </div>
</section>
@stop
@section('adminlte_js')
<script>
    $(function() {
        var showtable = $('#showtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/qc/so/data/semua',
                'type': 'POST',
                'datatype': 'JSON',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }

            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                data: 'DT_RowIndex',
                className: 'nowrap-text align-center',
                orderable: true,
                searchable: false
            }, {
                data: 'so',

            }, {
                data: 'no_po',

            }, {
                data: 'batas_uji',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false,
            }, {
                data: 'nama_customer',

            }, {
                data: 'status',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'button',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }]
        })
        $('#filter').submit(function() {
            var values = [];
            $("input:checked").each(function() {
                values.push($(this).val());
            });
            if (values != 0) {
                var x = values;

            } else {
                var x = ['semua']
            }
            console.log(x);
            $('#showtable').DataTable().ajax.url('/api/qc/so/data/' + x).load();
            return false;
        });


        var showtable = $('#selesaitable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/qc/so/data/selesai/semua',
                'type': 'POST',
                'datatype': 'JSON',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }

            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                data: 'DT_RowIndex',
                className: 'nowrap-text align-center',
                orderable: true,
                searchable: false
            }, {
                data: 'so',

            }, {
                data: 'no_po',

            }, {
                data: 'batas_uji',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false,
            }, {
                data: 'nama_customer',

            }, {
                data: 'status',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'button',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }]
        });

        $('#filter_selesai').submit(function() {
            var values = [];
            $("input:checked").each(function() {
                values.push($(this).val());
            });
            if (values != 0) {
                var x = values;

            } else {
                var x = ['semua']
            }
            console.log(x);
            $('#selesaitable').DataTable().ajax.url('/api/qc/so/data/selesai/' + x).load();
            return false;
        });
    })
</script>
@stop