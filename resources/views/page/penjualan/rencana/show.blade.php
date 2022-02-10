@extends('adminlte.page')

@section('title', 'ERP')

@section('adminlte_css')
<style>
    table tr td:nth-child(2),
    table tr td:nth-child(5),
    {
        text-align: center;
    }

    table tr td:nth-child(3),
    table tr td:nth-child(4),
    table tr td:nth-child(7),
    table tr td:nth-child(6), {
        text-align: right;
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

    #btntambah {
        margin-bottom: 10px;
    }

    @media (min-width: 993px) {
        body {
            font-size: 14px;
        }

        .btn {
            font-size: 14px;
        }
    }

    @media (max-width: 992px) {
        body {
            font-size: 12px;
        }

        .btn {
            font-size: 12px;
        }

        .form-inline input {
            margin: 10px 0;
        }

        .form-inline {
            flex-direction: column;
            align-items: stretch;
        }

        #customer_id {
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
                                    <form class="form-inline" id="filter">
                                        <div class=" form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <label for="customer_id">Distributor: </label>
                                            <select class="form-control custom-select" name="customer_id" id="customer_id"></select>
                                        </div>
                                        <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <label for="tahun">Tahun: </label>
                                            <input class="form-control" type="number" id="tahun" placeholder="Masukkan Tahun" name="tahun" disabled>

                                        </div>
                                        <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-1">
                                            <button class="btn btn-warning" type="submit" id="btncari" disabled>Cari</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <span class="float-left ">
                                            <div class="input-group-prepend">
                                                <button type="button" class="btn btn-outline-success dropdown-toggle" data-toggle="dropdown">
                                                    <i class="far fa-file-excel"></i> &nbsp;Export
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{route('penjualan.rencana.laporan',['customer'=> '0','tahun'=> '0'])}}" id="lap_semua">Laporan Semua</a>
                                                    <a class="dropdown-item" href="{{route('penjualan.rencana.laporan_detail',['customer'=> '0','tahun'=> '0'])}}" id="lap_detail">Laporan Detail</a>
                                                </div>
                                            </div>
                                        </span>
                                        <span class="float-right" id="btntambah"><a href="{{route('penjualan.rencana.create')}}" class="btn btn-outline-info"><i class="fas fa-plus"></i>&nbsp;Tambah Rencana</a></span>
                                    </div>
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
                                                    <th>Qty</th>
                                                    <th>Harga</th>
                                                    <th>Subtotal</th>
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


<script>
    $(function() {
        var groupColumn = 0;
        $('#showtable').DataTable({
            destroy: true,
            processing: true,
            dom: 'Bfrtip',
            serverSide: false,
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            ajax: {
                'url': '/api/penjualan/rencana/show/0/0',
                'dataType': 'json',
                'type': 'POST',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            },
            // buttons: [{
            //     extend: 'excel',
            //     title: 'Laporan Penjualan',
            //     text: '<i class="far fa-file-excel"></i> Export',
            //     className: "btn btn-info"
            // }, ],
            columns: [{
                data: 'instansi',
                orderable: false,
                searchable: false
            }, {
                data: 'produk',
                className: 'borderright'
            }, {
                data: 'jumlah',
                className: 'nowraptxt align-center'
            }, {
                data: 'harga',
                className: 'nowraptxt align-right',
                render: $.fn.dataTable.render.number(',', '.', 2),
            }, {
                data: 'sub',
                className: 'nowraptxt align-right borderright',
                render: $.fn.dataTable.render.number(',', '.', 2),
            }, {
                data: 'jumlah_real',
                className: 'nowraptxt align-center'
            }, {
                data: 'harga_real',
                className: 'nowraptxt align-right',
                render: $.fn.dataTable.render.number(',', '.', 2),
            }, {
                data: 'sub_real',
                className: 'nowraptxt align-right',
                render: $.fn.dataTable.render.number(',', '.', 2),
            }],
            "fixedColumns": {
                left: 0
            },
            "columnDefs": [{
                "visible": false,
                "targets": groupColumn
            }],
            "order": [
                [groupColumn, 'asc']
            ],
            "displayLength": 10,
            "drawCallback": function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;

                api.column(groupColumn, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group" style="background-color:steelblue; color:white;"><td colspan="8"><b>' + group + '</b></td></tr>'
                        );
                        last = group;
                    }
                });
            }
        });
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

        $('#customer_id').select2({
            placeholder: "Pilih Distributor",
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


        $('#customer_id').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#tahun").attr('disabled', false);
            } else {
                $("#tahun").attr('disabled', true);
            }
        });

        $('#tahun').on('keyup change', function() {
            if ($(this).val() != "") {
                $("#btncari").attr('disabled', false);
            } else {
                $("#btncari").attr('disabled', true);

            }
        });


        $('#filter').submit(function() {
            $("#parent_export").attr('disabled', false);
            var customer_id = $('#customer_id').val();
            var tahun = $('#tahun').val();

            $('#showtable').DataTable().ajax.url('/api/penjualan/rencana/show/' + customer_id + '/' + tahun).load();

            var link = '/penjualan/rencana/laporan/' + customer_id + '/' + tahun;
            var link2 = '/penjualan/rencana/laporan_detail/' + customer_id + '/' + tahun;

            console.log(link);
            $('#lap_semua').attr({
                href: link
            });

            $('#lap_detail').attr({
                href: link2
            });

            return false;
        });

    });
</script>

@stop
