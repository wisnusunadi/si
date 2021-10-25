@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Customer</h1>
@stop

@section('adminlte_css')
<style>

</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-12">
                                <span class="float-right">
                                    <a href="{{route('penjualan.customer.create')}}"><button class="btn btn-outline-info">
                                            Tambah
                                        </button></a>
                                </span>
                                <span class="dropdown float-right">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="filterpenjualan">
                                        Filter
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="filterpenjualan">
                                        <form class="px-4" style="white-space:nowrap;">
                                            <div class="dropdown-header">
                                                Status
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="dropdownStatus" value="ekatalog" />
                                                    <label class="form-check-label" for="dropdownStatus">
                                                        E-Catalogue
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="dropdownStatus" />
                                                    <label class="form-check-label" for="dropdownStatus" value="spa">
                                                        SPA
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="dropdownStatus" value="spb" />
                                                    <label class="form-check-label" for="dropdownStatus">
                                                        SPB
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary float-right">
                                                    Cari
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="customertable">
                                        <thead style="text-align:center;">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Alamat</th>
                                                <th>Telp</th>
                                                <th>NPWP</th>
                                                <th>Keterangan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>PT Dakai</td>
                                                <td>
                                                    Jl. Tambak Osowilangun A7 Benowo
                                                </td>
                                                <td>08181828384</td>
                                                <td></td>
                                                <td>-</td>
                                                <td>
                                                    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </div>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a data-toggle="modal" data-target="#modaldetail">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-search"></i>
                                                                Detail
                                                            </button>
                                                        </a>
                                                        <a data-toggle="modal" data-target="#modaledit">
                                                            <button class="dropdown-item" type="button">
                                                                <i class="fas fa-pencil-alt"></i>
                                                                Edit
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>PT Dakai</td>
                                                <td>
                                                    Jl. Tambak Osowilangun A7 Benowo
                                                </td>
                                                <td>08181828384</td>
                                                <td></td>
                                                <td>-</td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>PT Dakai</td>
                                                <td>
                                                    Jl. Tambak Osowilangun A7 Benowo
                                                </td>
                                                <td>08181828384</td>
                                                <td></td>
                                                <td>-</td>
                                                <td>
                                                    <i class="fas fa-ellipsis-v"></i>
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
                            <span class="float-right"><button type="submit" class="btn btn-warning" v-bind:class="{ disabled: edit.btndis }">
                                    Simpan
                                </button></span>
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