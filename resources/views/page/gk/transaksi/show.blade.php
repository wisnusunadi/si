@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
<style>
    .foo {
        float: left;
        width: 20px;
        height: 20px;
        margin: 5px;
        border: 1px solid rgba(0, 0, 0, .2);
    }

    .green {
        background: #28A745;
    }

    .blue {
        background: #17A2B8;
    }

    .topnav a {
        float: left;
        display: block;
        color: black;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-size: 17px;
        border-bottom: 3px solid transparent;
    }

    .topnav a:hover {
        border-bottom: 3px solid red;
    }

    .topnav a.active {
        border-bottom: 3px solid red;
    }

    .active-link {
        box-shadow: 12px 4px 8px 0 rgba(0, 0, 0, 0.2), 12px 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .nav-border {
        border-bottom: 2px solid black;
        content: "";
    }

    section {
        font-family: "Source Sans Pro"
    }

    img {
        /* Jika Gambar Disamping */
        width: 280px;
        /* Jika Gambar Diatas */
        /* width: 100px; */
    }

</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-5">
                {{-- @foreach ($did as $p) --}}
                <input type="hidden" name="" id="id" value="{{ $did }}">
                {{-- @endforeach --}}
                <div class="card mb-3">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="https://images.unsplash.com/photo-1526930382372-67bf22c0fce2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=687&amp;q=80"
                                alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body ml-5">
                                <div class="card-title">
                                    <h2 class="text-bold" id="nama">Nama Produk</h2>
                                    <h6 class="text-muted" id="kode">Kode Produk</h6>
                                </div>
                                <h5 class="card-text text-bold pt-2">Deskripsi</h5>
                                <p class="card-text" id="desk">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam,
                                    purus sit
                                    amet luctus venenatis, lectus magna fringilla urna, porttitor rhoncus dolor
                                    purus non enim praesent elementum facilisis leo, vel fringilla est ullamcorper
                                    eget nulla facilisi etiam dignissim diam quis enim lobortis scelerisque
                                    fermentum dui faucibus in ornare quam viverra</p>
                                <h5 class="card-text text-bold pt-1">Dimensi</h5>
                                <p class="text-bold" style="margin-bottom: 0">Panjang x Lebar x Tinggi</p>
                                <p><span class="panjang">50</span> x <span class="lebar">10</span> x <span
                                        class="tinggi">10</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @endforeach --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-1"></i>
                            Grafik Jumlah Terima / Transfer (Nama Produk) Per Tahun
                        </h3>
                        <div class="card-tools">
                            <div class="form-group row">
                                <label for="years" class="col-md-5 col-form-label">Tahun</label>
                                <div class="col-md-7">
                                    <select name="" id="" class="form-control">
                                        <option value="">2020</option>
                                        <option value="">2021</option>
                                        <option value="">2022</option>
                                        <option value="">2023</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" width="400" height="220"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-7">
                <div class="card">
                    <div class="card-title">
                        <div class="ml-3 mr-3">
                            <div class="row align-items-center">
                                <div class="col-lg-9 col-xl-8">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="input-icon">
                                                <input type="text" class="form-control" placeholder="Cari..."
                                                    id="kt_datatable_search_query">
                                                <span>
                                                    <i class="flaticon2-search-1 text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center">
                                                <label class="mr-3 mb-0 d-none d-md-block" for="">Tanggal</label>
                                                <input type="text" name="" id="tanggalmasuk" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="#" class="btn btn-outline-primary">Search</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xl-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="card-text">Keterangan Isi Kolom</p>
                                            <p class="card-text">
                                                <div class="foo green"></div> : Penerimaan
                                            </p>
                                            <p class="card-text">
                                                <div class="foo blue"></div> : Transfer
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-7">
                            <table class="table tableProdukView">
                                <thead>
                                    <tr>
                                        <th style="width: 150px">Tanggal</th>
                                        <th>Dari/Ke</th>
                                        <th>Tujuan</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge badge-success">10-04-2022</span></td>
                                        <td><span class="badge badge-success">Divisi IT</span> </td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-info">23-09-2022</span></td>
                                        <td><span class="badge badge-info">Divisi RnD</span> </td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-success">10-04-2022</span></td>
                                        <td><span class="badge badge-success">Divisi IT</span> </td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-info">23-09-2022</span></td>
                                        <td><span class="badge badge-info">Divisi RnD</span> </td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-success">10-04-2022</span></td>
                                        <td><span class="badge badge-success">Divisi IT</span> </td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-info">23-09-2022</span></td>
                                        <td><span class="badge badge-info">Divisi RnD</span> </td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-success">10-04-2022</span></td>
                                        <td><span class="badge badge-success">Divisi IT</span> </td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-info">23-09-2022</span></td>
                                        <td><span class="badge badge-info">Divisi RnD</span> </td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-success">10-04-2022</span></td>
                                        <td><span class="badge badge-success">Divisi IT</span> </td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge badge-info">23-09-2022</span></td>
                                        <td><span class="badge badge-info">Divisi RnD</span> </td>
                                        <td>Untuk Uji Coba</td>
                                        <td>100 Unit</td>
                                        <td><button type="button" class="btn btn-outline-info"
                                                onclick="detailProduk()"><i class="far fa-eye"> Detail</i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- Modal --}}
<div class="modal fade modalDetail" id="" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Produk Ambulatory</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-seri">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Seri</th>
                            <th>Kerusakan</th>
                            <th>Tingkat Kerusakan</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">1</td>
                            <td>54131313151</td>
                            <td>Kerusakan Panel</td>
                            <td>Level 3</td>
                            <td>Layout 1</td>
                        </tr>
                        <tr>
                            <td scope="row">2</td>
                            <td>54131313151</td>
                            <td>Kerusakan Panel</td>
                            <td>Level 3</td>
                            <td>Layout 1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('adminlte_js')
<script>
    var id = $('#id').val();
    console.log(id);

    $.ajax({
        url: "/api/gk/transaksi/header/" + id,
        type: "get",
        success: function(res) {
            console.log(res);
            $('h2#nama').text(res.nama);
            $('h6#kode').text(res.kode);
            $('p#desk').text(res.desk);
            $('span#panjang').text(res.panjang);
            $('span#lebar').text(res.lebar);
            $('span#tinggi').text(res.tinggi);
        }
    })
    $('.table-seri').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
    });
    $('.tableProdukView').DataTable({
        searching: false,
        "lengthChange": false,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
    });
    $('#nav-deskripsi-tab').click(function (e) {
        e.preventDefault();
        $('.is-active').addClass('font-weight-bold');
        $('.is-active').removeClass('font-weight-light');
        $('.is-disable').addClass('font-weight-light');
        $('.is-disable').removeClass('font-weight-bold');
    });
    $('#nav-dimensi-tab').click(function (e) {
        e.preventDefault();
        $('.is-active').removeClass('font-weight-bold');
        $('.is-active').addClass('font-weight-light');
        $('.is-disable').removeClass('font-weight-light');
        $('.is-disable').addClass('font-weight-bold');
    });
    $('#tanggalmasuk').daterangepicker({});

    function detailProduk() {
        $('.modalDetail').modal('show');
    }

    const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        datasets: [{
            label: 'Terima',
            data: [15, 20, 30, 15, 29, 38, 35, 15, 18, 34, 10, 45],
            backgroundColor: [
                'rgba(255, 159, 64, 0.2)',
            ],
            borderWidth: 1
        },
        {
            label: 'Transfer',
            data: [11, 15, 20, 10, 25, 30, 20, 15, 20, 30, 13, 25],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
            ],
            borderWidth: 1
        },
    ]
    },
    options: {
        scales: {
            y: {
                beginAtZero: false
            }
        }
    }
});

</script>
@stop
