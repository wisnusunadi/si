@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0  text-dark">Master Stok</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                @if(Auth::user()->divisi_id == "2")
                <li class="breadcrumb-item"><a href="{{route('direksi.dashboard')}}">Beranda</a></li>
                <li class="breadcrumb-item active">Master Stok</li>
                @endif
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
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
                                    <table class="table" style="text-align:center;" id="showtable">
                                        <thead>
                                            <tr>
                                                <th class="nowrap" rowspan="2">No</th>
                                                <th rowspan="2">Nama Produk</th>
                                                <th class="nowrap" colspan="2">Stok</th>
                                                <th class="nowrap" colspan="5">Penjualan</th>
                                                <th rowspan="2">Aksi</th>
                                            </tr>
                                            <tr>
                                                <th>GBJ</th>
                                                <th>Sisa</th>
                                                <th>Total</th>
                                                <th>Sepakat</th>
                                                <th>Nego</th>
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
</section>
@endsection

@section('adminlte_js')
<script>
    $(function() {
        var showtable = $('#showtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/ppic/master_stok/data',
                'type': 'GET',
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
                    data: 'nama_produk'
                },
                {
                    data: 'gbj',

                },
                {
                    data: 'penjualan',
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
                    data: 'batal',

                },
                {
                    data: 'po',
                },
                {
                    data: 'aksi',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });
</script>
@stop