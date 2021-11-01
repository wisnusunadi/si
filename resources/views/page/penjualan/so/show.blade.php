@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Sales Order</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <span class="float-right filter">
                                    <a href="{{route('penjualan.so.create')}}"><button class="btn btn-outline-info">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button></a>
                                </span>
                                <span class="float-right filter">
                                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3">
                                            <div class="form-group">
                                                <label for="jenis_penjualan">Jenis Penjualan</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="ekatalog" id="defaultCheck1" />
                                                    <label class="form-check-label" for="defaultCheck1">
                                                        E-Catalogue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2" />
                                                    <label class="form-check-label" for="defaultCheck2">
                                                        SPA
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="spa" id="defaultCheck2" />
                                                    <label class="form-check-label" for="defaultCheck2">
                                                        SPB
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <span class="float-right">
                                                    <button class="btn btn-primary">
                                                        Cari
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table" style="text-align:center;">
                                        <thead>
                                            <th>No</th>
                                            <th>No SO</th>
                                            <th>No PO</th>
                                            <th>Jenis</th>
                                            <th>Tanggal</th>
                                            <th>Customer</th>
                                            <th>DO</th>
                                            <th>Tanggal DO</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>SO-EKAT000001</td>
                                                <td>PO/ON/10/2021/0001</td>
                                                <td>
                                                    <span class="badge purple-text">E-Catalogue</span>
                                                </td>
                                                <td>21-10-2021</td>
                                                <td>PT. A</td>
                                                <td>DO/31/10/2021</td>
                                                <td>31-10-2021</td>
                                                <td>-</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SO-SPA000002</td>
                                                <td>PO/ON/10/2021/0001</td>
                                                <td>
                                                    <span class="badge blue-text">SPA</span>
                                                </td>
                                                <td>21-10-2021</td>
                                                <td>Pak K</td>
                                                <td>DO/31/10/2021</td>
                                                <td>31-10-2021</td>
                                                <td>-</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SO-SPB000003</td>
                                                <td>PO/ON/10/2021/0001</td>
                                                <td>
                                                    <span class="badge orange-text">SPB</span>
                                                </td>
                                                <td>21-10-2021</td>
                                                <td>Bu B</td>
                                                <td>DO/31/10/2021</td>
                                                <td>31-10-2021</td>
                                                <td>-</td>
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
@stop

@section('adminlte_js')
@stop