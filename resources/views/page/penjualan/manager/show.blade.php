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
                        <li class="breadcrumb-item active">Approval Sales Order</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
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

            .overflowy {
                max-height: 550px;
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

            .overflowy {
                max-height: 450px;
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
        }

    </style>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="showtable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No SO</th>
                                        <th>No PO</th>
                                        <th>No AKN</th>
                                        <th>Customer</th>
                                        <th>Tanggal Kontrak</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>SO/EKAT/II/2022/0001</td>
                                        <td>POJSN.021721.211</td>
                                        <td>AK1-57835-23819</td>
                                        <td>PT Emiindo Jaya Bersama</td>
                                        <td><div>21-02-2022</div> <small class="text-danger"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</small></td>
                                        <td><a data-toggle="modal" class="detailmodal">
                                            <button class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>SO/EKAT/III/2022/0056</td>
                                        <td>POJSN.021721.211</td>
                                        <td>AK1-57835-23819</td>
                                        <td>PT Cipta Jaya Medindo</td>
                                        <td><div>06-03-2022</div> <small><i id="info" class="fas fa-clock"></i> Sisa 9 Hari Lagi</small></td>
                                        <td>
                                            <a data-toggle="modal" class="detailmodal">
                                            <button class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="detailmodal" tabindex="-1" role="dialog" aria-labelledby="detailmodal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header">
                        <h4 id="modal-title">Detail</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="detail">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btntolak">Tolak Perubahan</button>
                        <button type="button" class="btn btn-primary" id="btnsetuju">Setujui Perubahan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('adminlte_js')
<script>
    $(function(){
        $('#showtable').DataTable();
        $('#edittable').DataTable();
        $(document).on('click', '.detailmodal', function(event) {
                event.preventDefault();
                var href = $(this).attr('data-attr');
                var id = $(this).data("id");
                var label = $(this).data("target");
                $.ajax({
                    url: '/manager/penjualan/detail',
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
    })
</script>
@endsection

