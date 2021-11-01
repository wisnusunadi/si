@extends('adminlte.page')

@section('title', 'ERP')

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
                                <a>Nama</a><b class="float-right" id="nama_customer"></b>
                            </li>
                            <li class="list-group-item">
                                <a>Alamat</a>
                                <b class="float-right" id="alamat"></b>
                            </li>
                            <li class="list-group-item">
                                <a>Telepon</a>
                                <b class="float-right" id="telepon"></b>
                            </li>
                            <li class="list-group-item">
                                <a>NPWP</a>
                                <b class="float-right" id="npwp"></b>
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
                <h5>Histori Penjualan</h5>
                <div class="card">
                    <div class="card-body">
                        <table class="table" id="historitabel">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>No PO</th>
                                    <th>Tanggal PO</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')

@stop