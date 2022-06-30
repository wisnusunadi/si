@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Permintaan Barang</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @if(Auth::user()->divisi_id == "2")
                <li class="breadcrumb-item"><a href="{{route('direksi.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Permintaan Barang</li>
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

    .info {
        color: #3a7bb0;
    }

    .filter {
        margin: 5px;
    }

    .minimizechar {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 25ch;
    }

    .align-center {
        text-align: center;
    }

    @media screen and (min-width: 1440px) {
        body {
            font-size: 14px;
        }

        .dropdown-item {
            font-size: 14px;
        }
    }

    @media screen and (max-width: 1439px) {
        body {
            font-size: 12px;
        }

        .dropdown-item {
            font-size: 12px;
        }
    }

    .borderright {
        border-right: 1px solid #F0ECE3;
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
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped table-bordered" style="text-align:center;" id="showtable">
                                        <thead>
                                            <tr class="bg-navy">
                                                <th class="nowrap" rowspan="2">No</th>
                                                <th rowspan="2">Nama Produk</th>
                                                <th class="nowrap borderright" colspan="2">Stok</th>
                                                <th class="nowrap borderright" colspan="6">Penjualan</th>
                                                <th rowspan="2">Aksi</th>
                                            </tr>
                                            <tr class="bg-secondary">
                                                <th>GBJ</th>
                                                <th>Sisa</th>
                                                <th>Permintaan</th>
                                                <th>Sepakat</th>
                                                <th>Nego</th>
                                                <th>Draft</th>
                                                <th>Batal</th>
                                                <th>PO</th>
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
        </div>
    </div>
    <div class="modal fade" id="detailmodal" role="dialog" aria-labelledby="detailmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="margin: 10px">
                <div class="modal-header">
                    <h4 class="modal-title">Info</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detail">

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('adminlte_js')
<script>
    $(function() {
        $(document).on('click', '.detailmodal', function(event) {
            event.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                url: "/ppic/master_stok/detail/" + id,
                beforeSend: function() {
                    $('#loader').show();
                },
                success: function(result) {
                    $('#detailmodal').modal("show");
                    $('#detail').html(result).show();
                    detailtable(id);
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


        var showtable = $('#showtable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/ppic/master_stok/data',
                'type': 'POST',
                'dataType': 'JSON',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    className: 'borderright',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama_produk',
                    className: 'borderright',
                },
                {
                    data: 'gbj',
                },
                {
                    data: 'penjualan',
                    className: 'borderright',
                },
                {
                    data: 'total',
                },
                {
                    data: 'sepakat',
                },
                {
                    data: 'nego',
                },
                {
                    data: 'draft',
                },
                {
                    data: 'batal',
                },
                {
                    data: 'po',
                    className: 'borderright',
                },
                {
                    data: 'aksi',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        function detailtable(id) {
            $('#detailtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/ppic/master_stok/detail/' + id,
                    'type': 'POST',
                    'dataType': 'JSON',
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'so',
                    },
                    {
                        data: 'customer',
                    },
                    {
                        data: 'tgl_order',
                    },
                    {
                        data: 'tgl_delivery',
                    },
                    {
                        data: 'jumlah',
                    },
                    {
                        data: 'status',
                    },
                ]
            });
        }
    });
</script>
@stop
