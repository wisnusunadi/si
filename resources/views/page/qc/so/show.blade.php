@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Sales Order</h1>
@stop

@section('adminlte_css')
<style>
    #urgent {
        color: red;
        font-weight: 600;
    }

    #warning {
        color: #FFC700;
        font-weight: 600;
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
                                            <th>No</th>
                                            <th>No SO</th>
                                            <th>No PO</th>
                                            <th>Tanggal Buat</th>
                                            <th>Batas Kontrak</th>
                                            <th>Customer</th>
                                            <th>DO</th>
                                            <th>Tanggal DO</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>SO-EKAT000001</td>
                                                <td>PO/ON/10/2021/0001</td>
                                                <td>21-09-2021</td>
                                                <td><span id="warning">08-11-2021</span></td>
                                                <td>PT. A</td>
                                                <td>DO/31/10/2021</td>
                                                <td>31-10-2021</td>
                                                <td>-</td>
                                                <td><span class="badge orange-text">Gudang</span></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>SO-SPA000002</td>
                                                <td>PO/ON/10/2021/0001</td>
                                                <td>21-08-2021</td>
                                                <td><span id="urgent">31-10-2021</span></td>
                                                <td>Pak K</td>
                                                <td>DO/31/10/2021</td>
                                                <td>31-10-2021</td>
                                                <td>-</td>
                                                <td><span class="badge yellow-text">QC</span></td>
                                                <td><a href="/qc/so/detail/1"><i class="fas fa-search"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>SO-SPB000003</td>
                                                <td>PO/ON/10/2021/0001</td>
                                                <td>31-07-2021</td>
                                                <td>01-10-2021</td>
                                                <td>Bu B</td>
                                                <td>DO/31/10/2021</td>
                                                <td>31-10-2021</td>
                                                <td>-</td>
                                                <td><span class="badge green-text">Pengiriman</span></td>
                                                <td><a href="/qc/so/detail/1"><i class="fas fa-search"></i></a></td>
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