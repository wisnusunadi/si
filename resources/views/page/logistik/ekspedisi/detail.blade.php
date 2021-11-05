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
</style>
@stop

@section('content_header')
<h1 class="m-0 text-dark">Detail Ekspedisi</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span style="font-size: 24px"><b>Info</b></span>
                            </li>
                            <li class="list-group-item">
                                <a>Nama</a>
                                <b class="float-right" id="nama_customer"></b>
                            </li>
                            <li class="list-group-item">
                                <a>Alamat</a>
                                <b class="float-right" id="alamat"></b>
                            </li>
                            <li class="list-group-item">
                                <a>Email</a>
                                <b class="float-right" id="email"></b>
                            </li>
                            <li class="list-group-item">
                                <a>Telepon</a>
                                <b class="float-right" id="telepon"></b>
                            </li>
                            <li class="list-group-item">
                                <a>Via</a>
                                <b class="float-right" id="via"></b>
                            </li>
                            <li class="list-group-item">
                                <a>Jurusan</a>
                                <b class="float-right" id="jurusan"></b>
                            </li>
                            <li class="list-group-item">
                                <a>Keterangan</a>
                                <b class="float-right" id="keterangan"></b>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <h5>Histori Pengiriman</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="showtable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No SO</th>
                                        <th>No PO</th>
                                        <th>No SJ</th>
                                        <th>Tanggal Kirim</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>SO-SPA10210001</td>
                                        <td>PO/ON/10/21/0001</td>
                                        <td>SJ/01/20/2001</td>
                                        <td>09-10-2021</td>
                                        <td><span class="badge blue-text">Dalam Pengiriman</span></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>SO-EKAT08210005</td>
                                        <td>PO/ON/08/21/0005</td>
                                        <td>SJ/01/20/2035</td>
                                        <td>02-08-2021</td>
                                        <td><span class="badge green-text">Selesai</span></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>SO-SPB08210005</td>
                                        <td>PO/ON/08/21/0005</td>
                                        <td>SJ/01/20/1927</td>
                                        <td>02-08-2021</td>
                                        <td><span class="badge green-text">Selesai</span></td>
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