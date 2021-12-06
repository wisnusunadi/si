@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Sales Order</h1>
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
                                                <th class="nowrap" colspan="4">Penjualan</th>
                                                <th class="nowrap" rowspan="2">Aksi</th>
                                            </tr>
                                            <tr>
                                                <th>GBJ</th>
                                                <th>GK</th>
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
                    data: 'gk',

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
                }
            ]
        });
    });
</script>
@stop