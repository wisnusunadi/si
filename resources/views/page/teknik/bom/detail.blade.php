@extends('adminlte.page')

@section('title', 'ERP')

@section('adminlte_css')
<style>
    .margin-right{
        margin-right:5px;
    }
    .group0{
        background-color: steelblue;
        color: #fff;
    }
    .group1{
        background-color: #DDE4EE;
        color: #5487BA;
    }

    table tr td:nth-child(3), table tr td:nth-child(4){
        text-align: center;
    }

    .align-center {
        text-align: center;
    }

    .align-right {
        text-align: right;
    }

    .borderright {
        border-right: 1px solid #E1EBF2;
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

    .nowraptxt {
        white-space: nowrap;
    }

    .tabnum{
        font-variant-numeric: tabular-nums;
    }
    /* .form-inline button {
    padding: 10px 20px;
    background-color: dodgerblue;
    border: 1px solid #ddd;
    color: white;
    cursor: pointer;
    } */
    .card{
        box-shadow: none;
    }

    .form-inline button:hover {
        background-color: darkgrey;
    }

    #variasi_bom_id {
        width: 100%;
    }

    #tahun {
        width: 70%;
    }

    #btntambah {
        margin-bottom: 10px;
    }

    p{
            margin-bottom: 0;
    }

    img{
        width: 100%;
        object-fit: contain;
        object-position: center;
    }

    #tablebop > thead{
        background-color: #800000;
        color: white;
        text-align:center;
    }
    #tablebom > thead{
        background-color: steelblue;
        color: white;
        text-align:center;
    }

    .nav-item .nav-link #pills-bop-tab:active{
        background-color: steelblue;
        color: white;
    }
    @media (min-width: 993px) {
        body {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }
        .margin{
            margin-top:15px;
        }
        .detail_prd{
            text-align: center;
        }
        .card-img-top{
            width: 210px;
            height: 210px;
        }
    }


    @media (max-width: 992px) {
        body {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }

        .margin{
            margin-top:13px;
        }

        .form-inline input {
            margin: 10px 0;
        }

        .form-inline {
            flex-direction: column;
            align-items: stretch;
        }

        #variasi_bom_id {
            width: 100%;
        }

        #tahun {
            width: 100%;
        }

        .form-inline button {
            float: right;
        }

        #btntambah {
            margin-bottom: 5px;
        }
        .margin{
            margin-top:15px;
        }
        .kelompok_prd{
            display: none !important
        }

    }
</style>
@stop

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Detail Bill Of Material</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('penjualan.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{route('teknik.bom.show')}}">Bill Of Material</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5><b>Detail Info</b></h5>
                            <div class="row">
                                <div class="col-lg-12 col-md-4  d-flex flex-wrap justify-content-center">
                                    <img class="card-img-top" src="{{ asset('assets/image/produk/st004.jpg') }}" alt="Card image cap">
                                </div>
                                <div class="col-lg-12 col-md-8">
                                    <div class="margin detail_prd">
                                        <p><b>ALUMINIUM ALLOY DUAL HEAD DELUXE STETHOSCOPE FOR ADULT - ST004</b></p>
                                        <p class="text-muted kelompok_prd">Alat Kesehatan</p>
                                    </div>
                                    <div class="margin">
                                        <p class="text-muted">Variasi</p>
                                        <a>Versi 1.0</a>, <a>Versi 2.0</a>, <a>Versi 3.0</a>
                                    </div>
                                    <div class="margin">
                                        <p class="text-muted">Ketersediaan Produksi</p>
                                        <p>15 unit</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-bop-tab" data-toggle="pill" href="#pills-bop" role="tab" aria-controls="pills-bop" aria-selected="true">Bill Of Process</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-bom-tab" data-toggle="pill" href="#pills-bom" role="tab" aria-controls="pills-bom" aria-selected="false">Bill Of Material</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-bop" role="tabpanel" aria-labelledby="pills-bop-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table" id="tablebop">
                                                <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Deskripsi Kerja</th>
                                                            <th>Kebutuhan</th>
                                                            <th>Alat</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-bom" role="tabpanel" aria-labelledby="pills-bom-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <form class="form-inline  d-flex flex-wrap justify-content-center" id="filter">
                                                <div class="form-group col-xs-12 col-sm-8 col-md-6 col-lg-5">
                                                    <label for="variasi_id">Cari Variasi : </label>
                                                    <select class="form-control custom-select" name="variasi_bom_id" id="variasi_bom_id"></select>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table" id="tablebom">
                                                <thead>
                                                        <tr>
                                                            <th>Kode</th>
                                                            <th>Nama</th>
                                                            <th>Jenis</th>
                                                            <th>Jumlah</th>
                                                            <th>Deskripsi</th>
                                                            <th>Turunan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
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
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@stop

@section('adminlte_js')
<script>
    $(function() {
        $('#tablebom').DataTable({});
        $('#tablebop').DataTable({});
        //var table = $('#showtable').DataTable({
        //     "ajax": {
        //         'url': '/api/penjualan/rencana/show/0/0',
        //         'dataType': 'json',
        //         'type': 'POST',
        //         'headers': {
        //             'X-CSRF-TOKEN': '{{csrf_token()}}'
        //         }
        //     },
        //     // "scrollY": true,
        //     // "scrollX": true,
        //     // "scrollCollapse": true,
        //     // "fixedColumns": {
        //     //     left: 0
        //     // },
        //     // "columnDefs": [{
        //     //     "visible": false,
        //     //     "targets": groupColumn
        //     // }],
        //     // "order": [
        //     //     [groupColumn, 'asc']
        //     // ],
        //     // "displayLength": 10,
        //     // "drawCallback": function(settings) {
        //     //     var api = this.api();
        //     //     var rows = api.rows({
        //     //         page: 'current'
        //     //     }).nodes();
        //     //     var last = null;

        //     //     api.column(groupColumn, {
        //     //         page: 'current'
        //     //     }).data().each(function(group, i) {
        //     //         if (last !== group) {
        //     //             $(rows).eq(i).before(
        //     //                 '<tr class="group blue-text"><td colspan="8">' + group + '</td></tr>'
        //     //             );
        //     //             last = group;
        //     //         }
        //     //     });
        //     // }
        //});

        // // Order by the grouping
        // $('#showtable tbody').on('click', 'tr.group', function() {
        //     var currentOrder = table.order()[0];
        //     if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
        //         table.order([groupColumn, 'desc']).draw();
        //     } else {
        //         table.order([groupColumn, 'asc']).draw();
        //     }
        // });

        $('#variasi_bom_id').select2({
            placeholder: "Pilih Variasi",
            ajax: {
                minimumResultsForSearch: 20,
                dataType: 'json',
                theme: "bootstrap",
                delay: 250,
                type: 'GET',
                url: '/api/customer/select_rencana/',
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

        $('#variasi_bop_id').select2({
            placeholder: "Pilih Variasi",
            ajax: {
                minimumResultsForSearch: 20,
                dataType: 'json',
                theme: "bootstrap",
                delay: 250,
                type: 'GET',
                url: '/api/customer/select_rencana/',
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


        $("#tahun").autocomplete({
            source: function(request, response) {

                $.ajax({
                    dataType: 'json',
                    url: "/api/penjualan/rencana/select_tahun",
                    data: {
                        term: request.term
                    },
                    success: function(data) {

                        var transformed = $.map(data, function(el) {
                            return {
                                label: el.tahun,
                                id: el.id
                            };
                        });
                        response(transformed);
                    },
                    error: function() {
                        response([]);
                    }
                });
            }
        });

    });
</script>

@stop
