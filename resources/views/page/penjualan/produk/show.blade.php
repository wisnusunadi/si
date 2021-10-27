@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Produk</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row" style="margin-bottom: 5px">
                                <div class="col-12">
                                    <span class="float-right">
                                        <a href="{{route('penjualan.produk.create')}}">
                                            <button class="btn btn-info">
                                                <i class="fas fa-plus"></i> Tambah
                                            </button>
                                        </a>
                                    </span>
                                    <span class="float-right" style="margin-right: 5px">
                                        <button class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <form action="" class="px-4 py-3">
                                                <div class="dropdown-header">
                                                    Kelompok Produk
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="dropdownkelompokproduk" value="alat_kesehatan" />
                                                        <label class="form-check-label" for="dropdownkelompokproduk">
                                                            Alat Kesehatan
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="dropdownkelompokproduk" value="sarana_kesehatan" />
                                                        <label class="form-check-label" for="dropdownkelompokproduk">
                                                            Sarana Kesehatan
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="dropdownkelompokproduk" value="aksesoris" />
                                                        <label class="form-check-label" for="dropdownkelompokproduk">
                                                            Aksesoris
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="dropdownkelompokproduk" value="lain" />
                                                        <label class="form-check-label" for="dropdownkelompokproduk">
                                                            Lain - lain
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="dropdown-header">
                                                    Stok
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="dropdownstok" />
                                                        <label class="form-check-label" for="dropdownstok">
                                                            Tersedia
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="dropdownstok" />
                                                        <label class="form-check-label" for="dropdownstok">
                                                            Hampir Habis
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="dropdownstok" />
                                                        <label class="form-check-label" for="dropdownstok">
                                                            Habis
                                                        </label>
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary float-right">
                                                    Cari
                                                </button>
                                            </form>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <table class="table table-hover" id="showtable">
                                        <thead style="text-align: center;">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Produk</th>
                                                <th>Harga</th>
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
            <div class="modal fade" id="modaldetail" tabindex="-1" role="dialog" aria-labelledby="modaldetail" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-success">
                            <h4 class="modal-title">Detail</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <span style="font-size: 24px"><b>Info</b></span><span class="float-right green-text col-form-label"><b>Tersedia</b></span>
                                                </li>
                                                <li class="list-group-item">
                                                    <a>Nama Produk</a><span></span><b class="float-right" id="nama_produk"></b>
                                                </li>
                                                <li class="list-group-item">
                                                    <a>Harga</a><span></span><b class="float-right" id="harga_produk"></b>
                                                </li>
                                                <li class="list-group-item">
                                                    <a>Stok</a><span id="stok"></span><b class="float-right">-</b>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <h5>Detail Produk</h5>
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table" id="showdetailtable" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Produk</th>
                                                        <th>Kelompok</th>
                                                        <th>Jumlah</th>

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
            <div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="modaledit" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-warning">
                            <h4>Edit</h4>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer">
                            <span class="float-left"><button type="button" class="btn btn-danger" data-dismiss="modal">
                                    Batal
                                </button></span>
                            <span class="float-right"><button type="button" class="btn btn-warning">
                                    Simpan
                                </button></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('adminlte_js')
<script>
    $(function() {
        var showtable = $('#showtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/penjualan_produk/data',
                'type': 'POST',
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
                    data: 'nama'
                },
                {
                    data: 'harga',
                    render: $.fn.dataTable.render.number(',', '.', 2),
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'button',
                    orderable: false,
                    searchable: false
                }
            ]
        });
        $('#showtable tbody').on('click', '#showmodal', function() {
            var rows = showtable.rows($(this).parents('tr')).data();
            $('#nama_produk').text(rows[0].nama);
            var x = (rows[0].harga).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
            $('#harga_produk').text('Rp ' + x);

            var showdetailtable = $('#showdetailtable').DataTable({
                processing: true,
                destroy: true,
                serverSide: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                ajax: '/api/penjualan_produk/detail/' + rows[0].id,
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama'

                    },
                    {
                        data: 'kelompok'

                    },
                    {
                        data: 'jumlah'

                    },
                ],
            });
            $('#modaldetail').modal('show');
        });
    });
</script>
@endsection