@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Sales Order</h1>
@stop

@section('adminlte_css')
<style>
    .ok {
        color: green;
        font-weight: 600;
    }

    .nok {
        color: red;
        font-weight: 600;
    }

    .warning {
        color: #FFC700;
        font-weight: 600;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class=""></div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
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
                                    <table class="table" style="text-align:center;" id="showtable">
                                        <thead>
                                            <th>#</th>
                                            <th>No</th>
                                            <th>No Seri</th>
                                            <th>Hasil</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input ok" type="checkbox" value="" id="" disabled />
                                                    </div>
                                                </td>
                                                <td>1</td>
                                                <td>TD0015012021001</td>
                                                <td><i class="fas fa-check-circle ok"></i></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input yet" type="checkbox" value="spa" id="" />
                                                    </div>
                                                </td>
                                                <td>2</td>
                                                <td>TD0015012021002</td>
                                                <td><i class="fas fa-question-circle warning"></i></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input " type="checkbox" value="spa" id="" />
                                                    </div>
                                                </td>
                                                <td>3</td>
                                                <td>TD0015012021003</td>
                                                <td><i class="fas fa-times-circle nok"></i></td>
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
<script>
    $(function() {
        var showtable = $('#showtable').DataTable({})

    })
</script>
@stop