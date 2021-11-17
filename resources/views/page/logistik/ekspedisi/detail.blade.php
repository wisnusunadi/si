@extends('adminlte.page')

@section('title', 'ERP')


@section('adminlte_css')
<style>
    li.list-group-item {
        border: 0 none;
    }

    #showtable {
        text-align: center;
        white-space: nowrap;
    }

    .filter {
        margin-top: 5px;
        margin-bottom: 5px;
    }

    .minimizechar {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 25ch;
    }

    .fa-search:hover {
        color: #4682B4;
    }

    .fa-search:active {
        color: #C0C0C0;
    }

    .hide {
        display: none !important;
    }
</style>
@stop

@section('content_header')
<h1 class="m-0 text-dark">Detail Ekspedisi</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Info Ekspedisi</h5>
                        <div class="row">
                            <div class="col-6 filter">
                                <div><small class="text-muted">Deskripsi Ekspedisi</small></div>
                                <div><b>JNE Regular</b></div>
                                <div><b>08172093108</b></div>
                                <div><b>Pergudangan Osowilangun Permai Blok E7 - E9 Benowo, Surabaya</b></div>
                                <div><b>Jawa Timur</b></div>
                            </div>

                            <div class="col-3">
                                <div class="filter">
                                    <div><small class="text-muted">Jalur</small></div>
                                    <div><span class="badge green-text">Darat</span> <span class="badge purple-text">Lain</span> <span class="badge orange-text">Udara</span> <span class="badge blue-text">Laut</span></div>
                                </div>
                                <div class="filter">
                                    <div><small class="text-muted">Jurusan</small></div>
                                    <div>Seluruh Indonesia</div>
                                </div>
                            </div>
                            <div class="col-3 filter">
                                <div><small class="text-muted">Keterangan</small></div>
                                <div>-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12  ">
                <h5>Histori Pengiriman</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="showtable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No SO</th>
                                        <th>No SJ</th>
                                        <th>Tanggal Kirim</th>
                                        <th>Nama Customer</th>
                                        <th>Alamat</th>
                                        <th>Telepon</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>SO-SPA10210001</td>
                                        <td>SJ/10/20/2001</td>
                                        <td>09-10-2021</td>
                                        <td class="minimizechar">RS Nurul Ikhsan</td>
                                        <td class="minimizechar">Jl. Jakarta No 18A-20A, Garut, Jawa Barat</td>
                                        <td>081119494950</td>
                                        <td><span class="badge blue-text">Dalam Pengiriman</span></td>
                                        <td><i class="fas fa-search"></i></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>SO-EKAT08210005</td>
                                        <td>SJ/08/21/0986</td>
                                        <td>02-08-2021</td>
                                        <td class="minimizechar">Bapak Hutapea</td>
                                        <td class="minimizechar">Jl. Moh. Hatta No 73, Medan, Sumatera Utara</td>
                                        <td>082139754850</td>
                                        <td><span class="badge green-text">Selesai</span></td>
                                        <td><i class="fas fa-search"></i></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>SO-SPB08210005</td>
                                        <td>SJ/01/20/1927</td>
                                        <td>02-08-2021</td>
                                        <td>Pemerintah Kab Badung</td>
                                        <td class="minimizechar">Jl. Bougenvil No 45, Badung, Bali</td>
                                        <td>082139754850</td>
                                        <td><span class="badge green-text">Selesai</span></td>
                                        <td><i class="fas fa-search"></i></td>
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
@stop

@section('adminlte_js')
<script>
    $(function() {
        $('#showtable').DataTable();

        function gg() {
            var showtable = $('#showtable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/customer/detail/' + '1',
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
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'DT_RowIndex',

                    },
                    {
                        data: 'DT_RowIndex',

                    },
                    {
                        data: 'DT_RowIndex',

                    }
                ]
            });
        }
    });
</script>
@stop