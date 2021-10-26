@extends('adminlte.page')

@section('title', 'PPIC App')

@section('content_header')
<div class="d-flex justify-content-between">
    <h1 class="m-0 text-dark">PPIC Jadwal Produksi</h1>
    <button-header />
</div>
@stop

@section('content')
<div id="App">
    <div class="row" style="margin-top: 5px">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover pertanggal" width="100%">
                    <thead style="text-align: center; font-size: 15px">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Produk</th>
                            <th>Asal / Tujuan</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodies">
                        <tr>
                            <td>1</td>
                            <td>24-09-2021</td>
                            <td>FOX-BABY</td>
                            <td>Produksi</td>
                            <td>Ref Hasil Produksi 0001/BPPB/09/21</td>
                            <td>
                                <span style="color: green"><i class="fas fa-plus"></i><span class="float-right">1000</span></span>
                            </td>
                            <td>
                                <a href=""><i class="fas fa-search"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>24-09-2021</td>
                            <td>CMS-600 PLUS</td>
                            <td>Produksi</td>
                            <td>Ref Hasil Produksi 0001/BPPB/09/21</td>
                            <td>
                                <span style="color: red"><i class="fas fa-plus"></i><span class="float-right">10</span></span>
                            </td>
                            <td>
                                <a href=""><i class="fas fa-search"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop