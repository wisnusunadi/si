@extends('adminlte.page')

@section('title', 'ERP')

@section('adminlte_css')
<style>
    table tr td:nth-child(2), table tr td:nth-child(5), table tr td:nth-child(6) {
        text-align:center;
    }
    table tr td:nth-child(3), table tr td:nth-child(4), table tr td:nth-child(7), table tr td:nth-child(8)  {
        text-align:right;
    }

    .align-center{
        text-align:center;
    }
    .align-right{
        text-align:right;
    }

    .borderright {
        border-right: 1px solid #F0ECE3;
    }

    .form-inline {
    display: flex;
    flex-flow: row wrap;
    align-items: center;
    margin: 20px;
    }

    .form-inline label {
    margin: 5px 10px 5px 0;
    }

    .form-inline input {
    vertical-align: middle;
    margin: 5px 10px 5px 0;
    padding: 10px;
    background-color: #fff;
    border: 1px solid #ddd;
    }

    /* .form-inline button {
    padding: 10px 20px;
    background-color: dodgerblue;
    border: 1px solid #ddd;
    color: white;
    cursor: pointer;
    } */

    .form-inline button:hover {
    background-color: darkgrey;
    }

    #customer_id {
        width: 70%;
    }

    #tahun {
        width: 70%;
    }

    #btntambah{
        margin-bottom: 10px;
    }

    @media (max-width: 992px) {
        body{
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }

        .form-inline input {
            margin: 10px 0;
        }

        .form-inline {
            flex-direction: column;
            align-items: stretch;
        }

        #customer_id {
            width: 100%; !important
        }

        #tahun {
            width: 100%;
        }

        .form-inline button{
            float: right;
        }

        #btntambah{
            margin-bottom: 5px;
        }
    }
</style>
@stop

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Rencana Penjualan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @if(Auth::user()->divisi_id == "26" || Auth::user()->divisi_id == "8")
                <li class="breadcrumb-item"><a href="{{route('penjualan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Rencana Penjualan</li>
                @elseif(Auth::user()->divisi_id == "2")
                <li class="breadcrumb-item"><a href="{{route('direksi.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Rencana Penjualan</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <form class="form-inline">
                                            <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <label for="customer_id">Distributor: </label>
                                            <select class="form-control custom-select" name="customer_id" id="customer_id"></select>
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <label for="tahun">Tahun: </label>
                                            <input class="form-control" type="number" id="tahun" placeholder="Masukkan Tahun" name="tahun">
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                            <button class="btn btn-warning" type="button" id="btncari">Cari</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="float-right" id="btntambah"><a href="{{route('penjualan.rencana.create')}}" class="btn btn-outline-info"><i class="fas fa-plus"></i>&nbsp;Tambah Rencana</a></div>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="showtable" style="width:100%;">
                                                <thead style="text-align:center;">
                                                    <tr>
                                                        <th rowspan="2">Instansi</th>
                                                        <th rowspan="2" class="borderright">Produk</th>
                                                        <th colspan="3" class="borderright">Rencana</th>
                                                        <th colspan="4">Realisasi</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Qty</th>
                                                        <th>Harga</th>
                                                        <th class="borderright">Subtotal</th>
                                                        <th>Tanggal PO</th>
                                                        <th>Qty</th>
                                                        <th>Harga</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Dinkes Lombok Barat</td>
                                                        <td>MAP 380+UPS</td>
                                                        <td>3</td>
                                                        <td>28.160.000</td>
                                                        <td>-</td>
                                                        <td>24-02-2022</td>
                                                        <td>2</td>
                                                        <td>27.500.000</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dinkes Lombok Barat</td>
                                                        <td>ULTRA MIST</td>
                                                        <td>4</td>
                                                        <td>2.904.000</td>
                                                        <td>-</td>
                                                        <td>24-02-2022</td>
                                                        <td>2</td>
                                                        <td>3.000.000</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dinkes Lombok Barat</td>
                                                        <td>ECG-1200G + TROLLEY + UPS</td>
                                                        <td>6</td>
                                                        <td>69.740.000</td>
                                                        <td>-</td>
                                                        <td>24-02-2022</td>
                                                        <td>7</td>
                                                        <td>69.740.000</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dinkes Batola</td>
                                                        <td>CMS-600 PLUS + PRINTER + TROLLEY</td>
                                                        <td>16</td>
                                                        <td>135.740.000</td>
                                                        <td>-</td>
                                                        <td>24-02-2022</td>
                                                        <td>11</td>
                                                        <td>135.740.000</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dinkes Batola</td>
                                                        <td>ANTROPOMETRI KIT</td>
                                                        <td>15</td>
                                                        <td>1.218.000</td>
                                                        <td>-</td>
                                                        <td>24-02-2022</td>
                                                        <td>22</td>
                                                        <td>1.096.000</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dinkes Kotabaru</td>
                                                        <td>CMS-600 PLUS + PRINTER + TROLLEY</td>
                                                        <td>7</td>
                                                        <td>135.740.000</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dinkes Kab. Kayong Utara Alkes 2022</td>
                                                        <td>TENSIONE</td>
                                                        <td>8</td>
                                                        <td>4.290.000</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dinkes Kab. Kayong Utara Alkes 2022</td>
                                                        <td>TENSIONE</td>
                                                        <td>8</td>
                                                        <td>4.290.000</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dinkes Kab. Kayong Utara Alkes 2022</td>
                                                        <td>TENSIONE</td>
                                                        <td>8</td>
                                                        <td>4.290.000</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dinkes Kab. Kayong Utara Alkes 2022</td>
                                                        <td>TENSIONE</td>
                                                        <td>8</td>
                                                        <td>4.290.000</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dinkes Kab. Kayong Utara Alkes 2022</td>
                                                        <td>TENSIONE</td>
                                                        <td>8</td>
                                                        <td>4.290.000</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                        <td>-</td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="2" class="align-center">Total Penjualan</th>
                                                        <th colspan="3" class="align-right">19.173.157.009</th>
                                                        <th colspan="4" class="align-right">4.783.278.167</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content" style="margin: 10px">
                                <div class="modal-header yellow-bg">
                                    <h4 class="modal-title"><b>Ubah</b></h4>
                                </div>
                                <div class="modal-body" id="edit">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="hapusmodal" role="dialog" aria-labelledby="hapusmodal" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content" style="margin: 10px">
                                <div class="modal-header yellow-bg">
                                    <h4 class="modal-title"><b>Hapus</b></h4>
                                </div>
                                <div class="modal-body" id="hapus">
                                    <div class="row">
                                        <div class="col-12">
                                            <form method="post" action="" id="form-hapus" data-target="">
                                                @method('DELETE')
                                                @csrf
                                                <div class="card">
                                                    <div class="card-body">Apakah Anda yakin ingin menghapus data ini?</div>
                                                    <div class="card-footer">
                                                        <span class="float-left">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                        </span>
                                                        <span class="float-right">
                                                            <button type="submit" class="btn btn-danger " id="btnhapus">Hapus</button>
                                                        </span>
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
    </div>
@stop

@section('adminlte_js')
<script src="{{ asset('assets/rowgroup/dataTables.rowGroup.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/rowgroup/rowGroup.bootstrap4.min.css') }}">

<!-- <script src="{{ asset('assets/button/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/button/jszip.min.js') }}"></script>
<script src="{{ asset('assets/button/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/button/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/button/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/button/buttons.print.min.js') }} "></script>
<link rel="stylesheet" href="{{ asset('assets/button/buttons.bootstrap4.min.css') }}"> -->

<script>
    $(function(){
        var groupColumn = 0;
        var table = $('#showtable').DataTable({
            "scrollY":true,
            "scrollX":true,
            "scrollCollapse":true,
            "fixedColumns":   {
                left: 0
            },
            "columnDefs": [
                { "visible": false, "targets": groupColumn }
            ],
            "order": [[ groupColumn, 'asc' ]],
            "displayLength": 10,
            "drawCallback": function ( settings ) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;

                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="group bg-secondary"><td colspan="8">'+group+'</td></tr>'
                        );
                        last = group;
                    }
                });
            }
        } );

        // Order by the grouping
        $('#showtable tbody').on( 'click', 'tr.group', function () {
            var currentOrder = table.order()[0];
            if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
                table.order( [ groupColumn, 'desc' ] ).draw();
            }
            else {
                table.order( [ groupColumn, 'asc' ] ).draw();
            }
        });

        $('#customer_id').select2({
            placeholder: "Pilih Distributor",
            ajax: {
                minimumResultsForSearch: 20,
                dataType: 'json',
                theme: "bootstrap",
                delay: 250,
                type: 'GET',
                url: '/api/customer/select/',
                data: function(params) {
                    return {
                        term: params.term
                    }
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.nama
                            };
                        })
                    };
                },
            }
        });

        $('')
    });
</script>
@stop



