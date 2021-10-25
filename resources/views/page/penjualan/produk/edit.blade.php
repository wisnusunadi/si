@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Produk</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <form action="">
            <div class="row d-flex justify-content-center">
                <div class="col-11">
                    <h5>Info Umum Paket</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group row">
                                        <label for="nama_produk" class="col-4 col-form-label" style="text-align: right">Nama Paket</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control" placeholder="Masukkan Nama Paket" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nama_produk" class="col-4 col-form-label" style="text-align: right">Harga</label>
                                        <div class="input-group col-5">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="text" class="form-control" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="Masukkan Harga" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-11">
                    <h5>Detail Produk Paket</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <table class="table" style="text-align: center" id="edittable">
                                        <thead>
                                            <tr>
                                                <th colspan="5">
                                                    <button type="button" class="btn btn-primary float-right" @click="
                                                                            addRow()
                                                                        ">
                                                        <i class="fas fa-plus"></i>
                                                        Produk
                                                    </button>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>
                                                    Kelompok
                                                </th>
                                                <th>Jumlah</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control custom-select" name="produk_id">
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td></td>
                                                <td>
                                                    <div class="form-group d-flex justify-content-center">
                                                        <input type="number" class="form-control" name="jumlah" style="width: 50%" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <a @click="
                                                                            removeRow(
                                                                                index
                                                                            )
                                                                        "><i class="fas fa-minus" style="color: red"></i></a>
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
        </form>
    </div>
</div>
@endsection

@section('adminlte_js')

@endsection