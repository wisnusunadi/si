@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">COO</h1>
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
        font-weight: 600;
    }

    .filter {
        margin: 5px;
    }
</style>
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
                                <div class="table-responsive">
                                    <table class="table table-striped" style="text-align:center;" id="showtable">
                                        <thead>
                                            <th>No</th>
                                            <th>No SO</th>
                                            <th>No AKN</th>
                                            <th>Customer</th>
                                            <th>Instansi</th>
                                            <th>Tanggal Surat Jalan</th>
                                            <th>Nama Produk</th>
                                            <th>No AKD</th>
                                            <th>No COO</th>
                                            <th>No Seri</th>
                                            <th>Laporan</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td rowspan="4">1</td>
                                                <td rowspan="4">SO/EKAT/X/02/98</td>
                                                <td rowspan="4">AK1-909090-1892180</td>
                                                <td rowspan="4">CV. Cipta Jaya Mandiri</td>
                                                <td rowspan="4">Pemerintah Kota Gorontalo</td>
                                                <td rowspan="2">30-09-2021</td>
                                                <td rowspan="2">Elitech MTB 2 MTR</td>
                                                <td rowspan="2">AKD4284020</td>
                                                <td>30031</td>
                                                <td>MTB2390193</td>
                                                <td><a href="/dc/so/detail/1">
                                                        <i class="fas fa-file"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>30031</td>
                                                <td>MTB2390193</td>
                                                <td><a href="/dc/so/detail/1">
                                                        <i class="fas fa-file"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">30-09-2021</td>
                                                <td rowspan="2">Elitech MTB 2 MTR</td>
                                                <td rowspan="2">AKD4284020</td>
                                                <td>30031</td>
                                                <td>MTB2390193</td>
                                                <td><a href="/dc/so/detail/1">
                                                        <i class="fas fa-file"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>30031</td>
                                                <td>MTB2390193</td>
                                                <td><a href="/dc/so/detail/1">
                                                        <i class="fas fa-file"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td rowspan="4">2</td>
                                                <td rowspan="4">SO/EKAT/X/02/98</td>
                                                <td rowspan="4">AK1-909090-1892180</td>
                                                <td rowspan="4">CV. Cipta Jaya Mandiri</td>
                                                <td rowspan="4">Pemerintah Kota Gorontalo</td>
                                                <td rowspan="2">30-09-2021</td>
                                                <td rowspan="2">Elitech MTB 2 MTR</td>
                                                <td rowspan="2">AKD4284020</td>
                                                <td>30031</td>
                                                <td>MTB2390193</td>
                                                <td><a href="/dc/so/detail/1">
                                                        <i class="fas fa-file"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>30031</td>
                                                <td>MTB2390193</td>
                                                <td><a href="/dc/so/detail/1">
                                                        <i class="fas fa-file"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td rowspan="2">30-09-2021</td>
                                                <td rowspan="2">Elitech MTB 2 MTR</td>
                                                <td rowspan="2">AKD4284020</td>
                                                <td>30031</td>
                                                <td>MTB2390193</td>
                                                <td><a href="/dc/so/detail/1">
                                                        <i class="fas fa-file"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>30031</td>
                                                <td>MTB2390193</td>
                                                <td><a href="/dc/so/detail/1">
                                                        <i class="fas fa-file"></i>
                                                    </a>
                                                </td>
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
        $('#showtable').DataTable({});
    });
</script>
@stop