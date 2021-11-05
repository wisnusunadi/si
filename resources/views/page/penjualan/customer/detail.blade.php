@extends('adminlte.page')

@section('title', 'ERP')


@section('adminlte_css')
<style>
    li.list-group-item {
        border: 0 none;
    }

    #historitabel {
        text-align: center;
    }
</style>
@stop

@section('content_header')
<h1 class="m-0 text-dark">Customer</h1>
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
                                <b class="float-right" id="nama_customer">{{$customer->nama}}</b>
                            </li>
                            <li class="list-group-item">
                                <a>Alamat</a>
                                <b class="float-right" id="alamat">{{$customer->alamat}}</b>
                            </li>
                            <li class="list-group-item">
                                <a>Telepon</a>
                                <b class="float-right" id="telepon">{{$customer->telp}}</b>
                            </li>
                            <li class="list-group-item">
                                <a>NPWP</a>
                                <b class="float-right" id="npwp">{{$customer->npwp}}</b>
                            </li>
                            <li class="list-group-item">
                                <a>Keterangan</a>
                                <b class="float-right" id="keterangan">{{$customer->ket}}</b>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <h5>Histori Penjualan</h5>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="showtable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No SO</th>
                                        <th>No PO</th>
                                        <th>Tanggal PO</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr>
                                    <td>1</td>
                                    <td>SO-SPA10210001</td>
                                    <td>PO/ON/10/21/0001</td>
                                    <td>09-10-2021</td>
                                    <td>SPA</td>
                                    <td><span class="badge orange-text">QC</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>SO-EKAT08210005</td>
                                    <td>PO/ON/08/21/0005</td>
                                    <td>02-08-2021</td>
                                    <td>E-Catalogue</td>
                                    <td><span class="badge green-text">Selesai</span></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>SO-SPB08210005</td>
                                    <td>PO/ON/08/21/0005</td>
                                    <td>02-08-2021</td>
                                    <td>SPB</td>
                                    <td><span class="badge red-text">PO</span></td>
                                </tr> -->
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
        var showtable = $('#showtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/customer/detail/' + '{{$customer->id}}',
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
                    data: 'so'
                },
                {
                    data: 'nopo',

                },
                {
                    data: 'tglpo',

                },
                {
                    data: 'jenis',

                }, {
                    data: 'status',

                }
            ]
        });
    });
</script>
@stop