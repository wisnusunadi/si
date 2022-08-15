@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Penjualan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->divisi_id == '26')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Penjualan</li>
                    @elseif(Auth::user()->divisi_id == '2')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Penjualan</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
<style>
    .modal-body{
        max-height: 80vh;
        overflow-y: auto;
    }
    table { border-collapse: collapse; empty-cells: show; }

    td { position: relative; }

    .foo {
        border-radius: 50%;
        float: left;
        width: 10px;
        height: 10px;
        align-items: center !important;
    }

    tr.line-through td:not(:nth-last-child(-n+2)):before {
        content: " ";
        position: absolute;
        left: 0;
        top: 35%;
        border-bottom: 1px solid;
        width: 100%;
    }

    .alert-danger {
        color: #a94442;
        background-color: #f2dede;
        border-color: #ebccd1;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .separator {
        border-top: 1px solid #bbb;
        width: 90%;
    }

    .wb {
        word-break: break-all;
        white-space: normal;
    }

    .nowraptxt {
        white-space: nowrap;
    }

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
        color: #dc3545;
        font-weight: 600;
    }

    #warning {
        color: #FFC700;
        font-weight: 600;
    }

    #info {
        color: #3a7bb0;
        font-weight: 600;
    }

    .minimizechar {
        display: inline-block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 13ch;
    }

    .hide {
        display: none;
    }

    .dropdown-toggle:hover {
        color: #4682B4;
    }

    .dropdown-toggle:active {
        color: #C0C0C0;
    }

    td.details-control {
        content: "\f055";
        font-family: FontAwesome;
        left: -5px;
        position: absolute;
        top: 0;
    }

    tr.details td.details-control {
        background: url('../resources/details_close.png') no-repeat center center;
    }

    #detailekat {
        background-color: #E9DDE5;

    }

    #detailspa {
        background-color: #FFE6C9;
    }

    #detailspb {
        background-color: #E1EBF2;
        /* color: #7D6378; */

    }

    .tabnum {
        font-variant-numeric: tabular-nums;
    }

    .removeshadow {
        box-shadow: none;
    }

    .align-center {
        text-align: center;
    }

    .bordertopnone {
        border-top: 0;
        border-left: 0;
        border-right: 0;
        border-bottom: 0;
        vertical-align: top;
    }

    .margin {
        margin-left: 10px;
        margin-right: 10px;
        margin-top: 15px;
        margin-bottom: 15px;
    }

    .card-detail {
        align-items: center;
        flex-direction: row;
        shadow: none;
        border: none;
    }

    .card-detail img {
        width: 25%;
        border-top-right-radius: 0;
        border-bottom-left-radius: calc(0.25rem - 1px);
    }

    /* .overflowcard {
            max-height:
            700px;
        } */

    .bg-chart-light{
        background: rgba(192, 192, 192, 0.2);
    }

    .bg-chart-orange{
        background: rgb(236, 159, 5);
    }

    .bg-chart-yellow{
        background: rgb(255, 221, 0);
    }

    .bg-chart-green{
        background: rgb(11, 171, 100);
    }

    .bg-chart-blue{
        background: rgb(8, 126, 225);
    }

    @media screen and (min-width: 1440px) {

        body {
            font-size: 14px;
        }

        #detailmodal {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }

        .overflowcard {
            max-height:
            550px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
        }

        .labelket {
            text-align: right;
        }
    }

    @media screen and (max-width: 1439px) {
        body {
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

        .overflowcard {
            max-height: 500px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
        }

        .labelket {
            text-align: right;
        }
    }

    @media screen and (max-width: 991px) {
        .labelket {
            text-align: left;
        }

        .overflowcard {
            max-height: 150px;
            width: auto;
            overflow-y: scroll;
            box-shadow: none;
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
                            <div class="table-responsive">
                                <table class="table table-hover" id="penjualantable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor SO</th>
                                            <th>Nomor AKN</th>
                                            <th>Nomor PO</th>
                                            <th>Tanggal PO</th>
                                            <th>Tanggal Delivery</th>
                                            <th>Customer</th>
                                            <th>Status</th>
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
            </div>
        </div>
    </section>
@endsection
@section('adminlte_js')
    <script>
        $(function() {
            var penjualantable = $('#penjualantable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "/api/dc/so_in_process/data",
                    "dataType": "json",
                    'type': 'POST',
                    "headers": {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'so',
                    }, {
                        data: 'no_paket',
                    }, {
                        data: 'nopo',
                    }, {
                        data: 'tgl_order',
                    }, {
                        data: 'tgl_kontrak',
                    }, {
                        data: 'nama_customer',
                    }, {
                        data: 'status',
                    }, {
                        data: 'button',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endsection
