@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
        .hidden {
        display: none !important;
    }
    .active {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .otg:hover {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    body{
        font-size: 14px;
    }
</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div id="jml-produk-20" class="card active otg" style="background-color: #FEF7EA">
                                    <div class="card-body text-center">
                                        <h4>10</h4>
                                        <p class="card-text">Produk dengan jumlah stok 3 sampai 4</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div id="jml-produk-5" class="card otg" style="background-color: #FFBD67">
                                    <div class="card-body text-center">
                                        <h4>10</h4>
                                        <p class="card-text">Produk dengan jumlah stok 5 sampai 10</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div id="jml-produk-4" class="card otg" style="background-color: #FF6464">
                                    <div class="card-body text-center">
                                        <h4>10</h4>
                                        <p class="card-text">Produk dengan jumlah lebih dari 10</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="jml-produk-20-table">
                            <table class="table jml-produk-20-tab">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 1</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 2</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 1</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 2</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 1</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 2</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 1</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 2</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 1</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 2</td>
                                        <td>20 Unit</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="jml-produk-5-table hidden">
                            <table class="table jml-produk-5-tab">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 3</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 3</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 3</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 3</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 3</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 3</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 3</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 3</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 3</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 3</td>
                                        <td>20 Unit</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="jml-produk-4-table hidden">
                            <table class="table jml-produk-4-tab">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Produk</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 4</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 5</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 4</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 5</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 4</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 5</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 4</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 5</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>Produk 4</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>Produk 5</td>
                                        <td>20 Unit</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row row-cols-4">
                            <div class="col">
                                <div id="produk-masuk-3-bulan" class="card otg active" style="background-color: #FEF7EA;font-size: 12px;">
                                    <div class="card-body text-center">
                                        <h4>10</h4>
                                        <p class="card-text font-weight">Produk masuk 3 bulan sampai 6 bulan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div id="produk-masuk-6-bulan" class="card otg" style="background-color: #FFBD67; font-size: 12px;">
                                    <div class="card-body text-center">
                                        <h4>10</h4>
                                        <p class="card-text font-weight">Produk masuk 6 bulan sampai 1 tahun</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div id="produk-masuk-1-tahun" class="card otg" style="background-color: #FA8282; font-size: 12px;">
                                    <div class="card-body text-center">
                                        <h4>10</h4>
                                        <p class="card-text font-weight">Produk masuk 1 tahun sampai 3 tahun</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div id="produk-masuk-3-tahun" class="card otg" style="background-color: #FF6464; font-size: 12px;">
                                    <div class="card-body text-center">
                                        <h4>10</h4>
                                        <p class="card-text font-weight">Produk masuk lebih dari 3 tahun</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Produk Masuk 3 Bulan --}}
                        <div class="produk-masuk-3-bulan-table">
                            <table class="table waktu-produk">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Nama Produk</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>10-04-2021</td>
                                        <td>Produk 1</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>23-09-2021</td>
                                        <td>Produk 2</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>10-04-2021</td>
                                        <td>Produk 1</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>23-09-2021</td>
                                        <td>Produk 2</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>10-04-2021</td>
                                        <td>Produk 1</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>23-09-2021</td>
                                        <td>Produk 2</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>10-04-2021</td>
                                        <td>Produk 1</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>23-09-2021</td>
                                        <td>Produk 2</td>
                                        <td>20 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">1</td>
                                        <td>10-04-2021</td>
                                        <td>Produk 1</td>
                                        <td>10 Unit</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">2</td>
                                        <td>23-09-2021</td>
                                        <td>Produk 2</td>
                                        <td>20 Unit</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        {{-- Produk Masuk 6 Bulan --}}
                        <div class="produk-masuk-6-bulan-table hidden">
                        <table class="table waktu-produk ">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 3</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 3</td>
                                    <td>20 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 3</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 3</td>
                                    <td>20 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 3</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 3</td>
                                    <td>20 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 3</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 3</td>
                                    <td>20 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 3</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 3</td>
                                    <td>20 Unit</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        {{-- Produk Masuk 1 Tahun --}}
                        <div class="produk-masuk-1-tahun-table hidden">
                        <table class="table waktu-produk ">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 4</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 4</td>
                                    <td>20 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 4</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 4</td>
                                    <td>20 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 4</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 4</td>
                                    <td>20 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 4</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 4</td>
                                    <td>20 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 3</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 3</td>
                                    <td>20 Unit</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        {{-- Produk Masuk 3 Tahun --}}
                        <div class="produk-masuk-3-tahun-table hidden">
                        <table class="table waktu-produk">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 5</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 5</td>
                                    <td>20 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 5</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 5</td>
                                    <td>20 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 5</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 5</td>
                                    <td>20 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 5</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 5</td>
                                    <td>20 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>10-04-2021</td>
                                    <td>Produk 5</td>
                                    <td>10 Unit</td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>23-09-2021</td>
                                    <td>Produk 5</td>
                                    <td>20 Unit</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-dolly-flatbed"></i> Daftar Stok Layout
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm"><h5><b>Layout 1</b></h5></div>
                            <div class="col-sm text-right">Layout :</div>
                            <div class="col-sm">
                                <select class="select2 form-control" multiple="multiple">
                                <option selected>All Layout</option>
                                <option>Layout 1</option>
                                <option>Layout 2</option>
                                <option>Layout 3</option>
                                <option>Layout 4</option>
                                <option>Layout 5</option>
                                <option>Layout 6</option>
                                <option>Layout 7</option>
                              </select>
                            </div>
                        </div>
                        <table class="table tableStokLayout">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Layout</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td>Layout 1</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Produk 2</td>
                                    <td>100 Unit</td>
                                    <td>Layout 2</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td>Layout 1</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Produk 2</td>
                                    <td>100 Unit</td>
                                    <td>Layout 2</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td>Layout 1</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Produk 2</td>
                                    <td>100 Unit</td>
                                    <td>Layout 2</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td>Layout 1</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Produk 2</td>
                                    <td>100 Unit</td>
                                    <td>Layout 2</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td>Layout 1</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Produk 2</td>
                                    <td>100 Unit</td>
                                    <td>Layout 2</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-tools"></i> Daftar Tingkat Kerusakan Produk
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="col-xl-12 d-flex justify-content-end">
                            <span class="float-right filter">
                                <button class="btn btn-outline-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-filter"></i> Filter Jenis
                                </button>
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Jenis</label>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="ekatalog" id="jenis1" />
                                                <label class="form-check-label" for="jenis1">
                                                    All
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="ekatalog" id="jenis1" />
                                                <label class="form-check-label" for="jenis1">
                                                    Sparepart
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="spa" id="jenis2" />
                                                <label class="form-check-label" for="jenis2">
                                                    Unit
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </span> &nbsp;
                            <span class="float-right filter">
                                <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-filter"></i> Filter Kerusakan
                                </button>
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Kerusakan</label>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="ekatalog" id="jenis1" />
                                                <label class="form-check-label" for="jenis1">
                                                    All
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="ekatalog" id="jenis1" />
                                                <label class="form-check-label" for="jenis1">
                                                    Level 1
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="spa" id="jenis2" />
                                                <label class="form-check-label" for="jenis2">
                                                    Level 2
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="spa" id="jenis2" />
                                                <label class="form-check-label" for="jenis2">
                                                    Level 3
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </span>
                        </div>
                        <table class="table tableKerusakan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Tingkat Kerusakan</th>
                                    <th>Jenis</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>65465456464</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td>Level 1</td>
                                    <td>Sparepart</td>
                                    <td><a href="{{ url('gk/gudang') }}" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>65465456464</td>
                                    <td>Produk 2</td>
                                    <td>100 Unit</td>
                                    <td>Level 2</td>
                                    <td>Unit</td>
                                    <td><a href="{{ url('gk/gudang') }}" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>65465456464</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td>Level 1</td>
                                    <td>Sparepart</td>
                                    <td><a href="{{ url('gk/gudang') }}" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>65465456464</td>
                                    <td>Produk 2</td>
                                    <td>100 Unit</td>
                                    <td>Level 2</td>
                                    <td>Unit</td>
                                    <td><a href="{{ url('gk/gudang') }}" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>65465456464</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td>Level 1</td>
                                    <td>Sparepart</td>
                                    <td><a href="{{ url('gk/gudang') }}" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>65465456464</td>
                                    <td>Produk 2</td>
                                    <td>100 Unit</td>
                                    <td>Level 2</td>
                                    <td>Unit</td>
                                    <td><a href="{{ url('gk/gudang') }}" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>65465456464</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td>Level 1</td>
                                    <td>Sparepart</td>
                                    <td><a href="{{ url('gk/gudang') }}" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>65465456464</td>
                                    <td>Produk 2</td>
                                    <td>100 Unit</td>
                                    <td>Level 2</td>
                                    <td>Unit</td>
                                    <td><a href="{{ url('gk/gudang') }}" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">1</td>
                                    <td>65465456464</td>
                                    <td>Produk 1</td>
                                    <td>100 Unit</td>
                                    <td>Level 1</td>
                                    <td>Sparepart</td>
                                    <td><a href="{{ url('gk/gudang') }}" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i></a></td>
                                </tr>
                                <tr>
                                    <td scope="row">2</td>
                                    <td>65465456464</td>
                                    <td>Produk 2</td>
                                    <td>100 Unit</td>
                                    <td>Level 2</td>
                                    <td>Unit</td>
                                    <td><a href="{{ url('gk/gudang') }}" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@section('adminlte_js')
<script>
     $(document).on('click', '#jml-produk-20', function () {
            $('#jml-produk-20').addClass('active');
            $('.jml-produk-20-table').removeClass('hidden');
            $('#jml-produk-5').removeClass('active');
            $('#jml-produk-4').removeClass('active');
            $('.jml-produk-5-table').addClass('hidden');
            $('.jml-produk-4-table').addClass('hidden');
        })
        $(document).on('click', '#jml-produk-5', function () {
            $('#jml-produk-5').addClass('active');
            $('.jml-produk-5-table').removeClass('hidden');
            $('#jml-produk-20').removeClass('active');
            $('#jml-produk-4').removeClass('active');
            $('.jml-produk-20-table').addClass('hidden');
            $('.jml-produk-4-table').addClass('hidden');
        })
        $(document).on('click', '#jml-produk-4', function () {
            $('#jml-produk-4').addClass('active');
            $('.jml-produk-4-table').removeClass('hidden');
            $('#jml-produk-5').removeClass('active');
            $('#jml-produk-20').removeClass('active');
            $('.jml-produk-5-table').addClass('hidden');
            $('.jml-produk-20-table').addClass('hidden');
        })
        // Produk Masuk
        $(document).on('click', '#produk-masuk-3-bulan', function () {
            $('#produk-masuk-3-bulan').addClass('active');
            $('.produk-masuk-3-bulan-table').removeClass('hidden');
            $('#produk-masuk-6-bulan').removeClass('active');
            $('#produk-masuk-1-tahun').removeClass('active');
            $('#produk-masuk-3-tahun').removeClass('active');
            $('.produk-masuk-6-bulan-table').addClass('hidden');
            $('.produk-masuk-1-tahun-table').addClass('hidden');
            $('.produk-masuk-3-tahun-table').addClass('hidden');
        })
        $(document).on('click', '#produk-masuk-6-bulan', function () {
            $('#produk-masuk-6-bulan').addClass('active');
            $('.produk-masuk-6-bulan-table').removeClass('hidden');
            $('#produk-masuk-3-bulan').removeClass('active');
            $('#produk-masuk-1-tahun').removeClass('active');
            $('#produk-masuk-3-tahun').removeClass('active');
            $('.produk-masuk-3-bulan-table').addClass('hidden');
            $('.produk-masuk-1-tahun-table').addClass('hidden');
            $('.produk-masuk-3-tahun-table').addClass('hidden');
        })
        $(document).on('click', '#produk-masuk-1-tahun', function () {
            $('#produk-masuk-1-tahun').addClass('active');
            $('.produk-masuk-1-tahun-table').removeClass('hidden');
            $('#produk-masuk-6-bulan').removeClass('active');
            $('#produk-masuk-3-bulan').removeClass('active');
            $('#produk-masuk-3-tahun').removeClass('active');
            $('.produk-masuk-6-bulan-table').addClass('hidden');
            $('.produk-masuk-3-bulan-table').addClass('hidden');
            $('.produk-masuk-3-tahun-table').addClass('hidden');
        })
        $(document).on('click', '#produk-masuk-3-tahun', function () {
            $('.produk-masuk-3-tahun-table').removeClass('hidden');
            $('#produk-masuk-3-tahun').addClass('active');
            $('#produk-masuk-1-tahun').removeClass('active');
            $('.produk-masuk-1-tahun-table').addClass('hidden');
            $('#produk-masuk-6-bulan').removeClass('active');
            $('#produk-masuk-3-bulan').removeClass('active');
            $('.produk-masuk-6-bulan-table').addClass('hidden');
            $('.produk-masuk-3-bulan-table').addClass('hidden');
        })
        $('.select2').select2({});
        $('.jml-produk-20-tab').DataTable({
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $('.jml-produk-5-tab').DataTable({
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $('.jml-produk-4-tab').DataTable({
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $('.waktu-produk').DataTable({
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $('.tableStokLayout').DataTable({
            "ordering": true,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "columnDefs":[{
                "targets": '_all',
                "createdCell": function (td, cellData, rowData, row, ) { 
                    $(td).css('padding', '18.3px');
                }
            }],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $('.tableKerusakan').DataTable({
            "ordering": true,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
</script>
@stop
