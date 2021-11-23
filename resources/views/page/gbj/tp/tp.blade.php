@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
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

    .gambar-resize {
        width: 250px;
        height: 250px;
    }

    .card-center {
        left: 20%;
    }
    .nomor-so{
        background-color: #717FE1;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .nomor-akn{
        background-color: #DF7458;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .nomor-po{
        background-color: #85D296;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
    .instansi{
        background-color: #36425E;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tabList">
                    <li class="nav-item" role="presentation">
                        <a href="#semua-produk" class="nav-link active" id="semua-produk-tab" data-toggle="tab"
                            role="tab" aria-controls="semua-produk" aria-selected="true">Semua Riwayat Transaksi</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#produk" class="nav-link" id="produk-tab" data-toggle="tab" role="tab"
                            aria-controls="produk" aria-selected="true">Per Produk</a>
                    </li>
                </ul>
                <div class="tab-content card" id="myTabContent">
                    <div class="tab-pane fade show active card-body" id="semua-produk" role="tabpanel"
                        aria-labelledby="semua-produk-tab">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="row align-items-center">
                                    <div class="col-lg-9 col-xl-8">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 my-2 my-md-0">
                                                <div class="input-icon">
                                                    <input type="text" class="form-control" placeholder="Cari..."
                                                        id="kt_datatable_search_query">
                                                    <span>
                                                        <i class="flaticon2-search-1 text-muted"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2 my-md-0">
                                                <div class="d-flex align-items-center">
                                                    <label class="mr-3 mb-0 d-none d-md-block" for="">Dari</label>
                                                    <select name="" id="divisi" class="form-control">
                                                        <option value="">All</option>
                                                        <option value="">Divisi IT</option>
                                                        <option value="">Divisi QC</option>
                                                        <option value="">Divisi SO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2 my-md-0">
                                                <div class="d-flex align-items-center">
                                                    <label class="mr-3 mb-0 d-none d-md-block" for="">Tanggal</label>
                                                    <input type="text" name="" id="datetimepicker1"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                                        <a href="#" class="btn btn-outline-primary">Search</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="card">
                                    <div class="card-body">
                                        <p class="card-text">Keterangan Kolom <b>Dari/Ke:</b></p>
                                        <p class="card-text">
                                            <div class="foo green"></div> : Dari
                                        </p>
                                        <p class="card-text">
                                            <div class="foo blue"></div> : Ke
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    {{-- Tanggal Masuk dan Tanggal Keluar --}}
                                    <table class="table table-hover pertanggal" width="100%" id="history">
                                        <thead>
                                            <tr>
                                                <th>Tanggal Masuk</th>
                                                <th>Tanggal Keluar</th>
                                                <th>Dari/Ke</th>
                                                <th>Tujuan</th>
                                                <th>Nomor SO</th>
                                                <td>Aksi</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>10-09-2022</td>
                                                <td>10-09-2022</td>
                                                <td><span class="badge badge-success">Divisi IT</span><br><span class="badge badge-info">Divisi QC</span></td>
                                                <td>Uji Coba Produk</td>
                                                <td>641311666541</td>
                                                <td><button class="btn btn-info" onclick="detailprodukriwayat()"><i
                                                            class="far fa-eye"></i> Detail</button></td>
                                            </tr>
                                            <tr>
                                                <td>10-09-2022</td>
                                                <td>10-09-2022</td>
                                                <td><span class="badge badge-info">Divisi IT</span></td>
                                                <td>Uji Coba Produk</td>
                                                <td>641311666541</td>
                                                <td><button class="btn btn-info" onclick="detailprodukriwayat()"><i
                                                            class="far fa-eye"></i> Detail</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card-body" id="produk" role="tabpanel" aria-labelledby="produk-tab">
                        <div class="table-produk">
                            <table class="table table-bordered" id="gudang-barang">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Produk</th>
                                        <th>Produk</th>
                                        <th>Stok Gudang</th>
                                        <th>Stok</th>
                                        <th>Kelompok</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>ZTP80AS-UPGRADE</td>
                                        <td>STERILISATOR KERING</td>
                                        <td>100 Unit</td>
                                        <td>80 Unit</td>
                                        <td>Alat Kesehatan</td>
                                        <td><a class="btn btn-info" href="{{ url('gbj/tp/1') }}"><i
                                                    class="far fa-eye"></i> Detail</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>ZTP80AS-UPGRADE</td>
                                        <td>STERILISATOR KERING</td>
                                        <td>100 Unit</td>
                                        <td>80 Unit</td>
                                        <td>Alat Kesehatan</td>
                                        <td><a class="btn btn-info" href="{{ url('gbj/tp/1') }}"><i
                                                    class="far fa-eye"></i> Detail</a>
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
<<<<<<< HEAD:resources/views/page/gbj/tp.blade.php
<div class="row produk-show hidden-product">
    <div class="col-md-4">
        <div class="card">
            <div class="card mt-2 ml-5 mr-5 card-center" style="width: 13rem;">
                <img class="card-img-top"
                    src="https://images.unsplash.com/photo-1526930382372-67bf22c0fce2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=687&amp;q=80"
                    alt="">
            </div>
            <div class="card" style="background-color: #786FC4; color: white;">
                <div class="card-body">
                    <h5 class="card-text pb-2"><b>Kode Produk</b></h5>
                    <p class="card-text" id="kode_produk">5415313</p>
                    <h5 class="card-text pb-2"><b>Nama Produk</b></h5>
                    <p class="card-text" id="nama_produk">Ambulatory</p>
                    <h5 class="card-text pb-2"><b>Deskripsi</b></h5>
                    <p class="card-text" id="deskripsi">Produk Inovatif dan Kreatif</p>
                    <h5 class="card-text pb-2"><b>Dimensi</b></h5>
                    <div class="row">
                        <div class="col-sm">
                            <p>Panjang</p>
                        </div>
                        <div class="col-sm">
                            <p>Lebar</p>
                        </div>
                        <div class="col-sm">
                            <p>Tinggi</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <p id="panjang">12 mm</p>
                        </div>
                        <div class="col-sm">
                            <p id="lebar">13 mm</p>
                        </div>
                        <div class="col-sm">
                            <p id="tinggi">14 mm</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-title">
                <div class="mb-7 mt-5 ml-3">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-xl-8">
                            <div class="row align-items-center">
                                <div class="col-md-4 my-2 my-md-0">
                                    <div class="input-icon">
                                        <input type="text" class="form-control" placeholder="Cari..."
                                            id="kt_datatable_search_query">
                                        <span>
                                            <i class="flaticon2-search-1 text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 my-2 my-md-0">
                                    <div class="d-flex align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block" for="">Tanggal</label>
                                        <input type="text" name="" id="tanggalmasuk" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4 my-2 my-md-0">
                                    <a href="#" class="btn btn-outline-primary">Search</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xl-4 mt-5 mt-lg-0">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-text">Keterangan Kolom <b>Dari/Ke:</b></p>
                                    <p class="card-text">
                                        <div class="foo green"></div> : Dari
                                    </p>
                                    <p class="card-text">
                                        <div class="foo blue"></div> : Ke
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-7">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th>Nomor SO</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Keluar</th>
                                <th>Dari/Ke</th>
                                <th>Tujuan</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>652146416541654</td>
                                <td scope="row">10-04-2021</td>
                                <td>23-09-2021</td>
                                <td><span class="badge badge-success">Divisi IT</span></td>
                                <td>Untuk Uji Coba</td>
                                <td>100 Unit</td>
                                <td><button type="button" class="btn btn-outline-info" onclick="detailProduk()"><i
                                            class="far fa-eye"> Detail</i></button></td>
                            </tr>
                            <tr>
                                <td>652146416541654</td>
                                <td scope="row">10-04-2021</td>
                                <td>23-09-2021</td>
                                <td><span class="badge badge-info">Divisi QC</span></td>
                                <td>Untuk Uji Coba</td>
                                <td>100 Unit</td>
                                <td><button type="button" class="btn btn-outline-info" onclick="detailProduk()"><i
                                            class="far fa-eye"> Detail</i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item"><a class="page-link" href="#">«</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">»</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
=======
>>>>>>> 993c403b85758d6690fe1ade67e173cb80a9ff68:resources/views/page/gbj/tp/tp.blade.php



<!-- Modal -->
<div class="modal fade modalProduk" id="" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row row-cols-2">
                            {{-- col --}}
                            <div class="col"> <label for="">Tanggal Masuk</label>
                                <div class="card nomor-so">
                                    <div class="card-body">
                                        10-04-2022
                                    </div>
                                </div>
                            </div>
                            {{-- col --}}
                            <div class="col"> <label for="">Tanggal Keluar</label>
                                <div class="card nomor-akn">
                                    <div class="card-body">
                                        23-09-2022
                                    </div>
                                </div>
                            </div>
                            {{-- col --}}
                            <div class="col"> <label for="">Nomor SO</label>
                                <div class="card nomor-po">
                                    <div class="card-body">
                                        89798797856456
                                    </div>
                                </div>
                            </div>
                            {{-- col --}}
                            <div class="col"> <label for="">Jumlah Produk</label>
                                <div class="card instansi">
                                    <div class="card-body">
                                        5 Produk
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped add-produk">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Tipe</th>
                                    <th>Merk</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                    <td>100 Unit</td>
                                    <td>ABPM50</td>
                                    <td>ELITECH</td>
                                    <td><button class="btn btn-info" onclick="detailtanggal()"><i class="far fa-eye"></i> Detail</button> </td>
                                </tr>
                                <tr>
                                    <td>AMBULATORY BLOOD PRESSURE MONITOR</td>
                                    <td>100 Unit</td>
                                    <td>RGB</td>
                                    <td>ELITECH</td>
                                    <td><button class="btn btn-info" onclick="detailtanggal()"><i class="far fa-eye"></i> Detail</button> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Per Tanggal-->
<div class="modal fade" id="modal-per-tanggal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
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
                            <th>No Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">65465465465</td>
                            <td>Layout 1</td>
                        </tr>
                        <tr>
                            <td scope="row">65465465465</td>
                            <td>Layout 2</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<<<<<<< HEAD:resources/views/page/gbj/tp.blade.php
<!-- Modal -->
<div class="modal fade modalDetail" id="" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Produk <span id="title">Ambulatory</span></h5>
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
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="row">1</td>
                            <td>54131313151</td>
                            <td>Layout 1</td>
                        </tr>
                        <tr>
                            <td scope="row">2</td>
                            <td>54131313151</td>
                            <td>Layout 1</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
=======

>>>>>>> 993c403b85758d6690fe1ade67e173cb80a9ff68:resources/views/page/gbj/tp/tp.blade.php
@stop

@section('adminlte_js')
<script>
    $('#datetimepicker1').daterangepicker({});

    $.ajax({
        url: '/api/gbj/sel-divisi',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if(res) {
                console.log(res);
                $("#divisi").empty();
                $("#divisi").append('<option value="">All</option>');
                $.each(res, function(key, value) {
                    $("#divisi").append('<option value="'+value.id+'">'+value.nama+'</option');
                });
            } else {
                $("#divisi").empty();
            }
        }
    });

    $('#divisi').on('change', function(e) {
        var id = $(this).data('id');
        console.log(id);
    })

    // function date_filter()

    $(document).on('click', '.editmodal', function() {
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: "/api/transaksi/all-detail/" + id,
            success: function(res) {
                console.log(res);
                $('span#title').text(res.data[0].title);
            }
        });

        $('.table-seri').DataTable().destroy();
        $('.table-seri').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/api/transaksi/all-detail/" + id,
                // data: {id: id},
                // type: "post",
                // dataType: "json",
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'seri', name: 'seri'},
                { data: 'layout', name: 'layout'},
            ],
        })

        detailProduk();
    })


    function detailtanggal() {
        $('#modal-per-tanggal').modal('show');
    }

    function detailProduk() {
        $('.modalDetail').modal('show');
    }

    function detailprodukriwayat() { 
        $('.modalProduk').modal('show');
     }

    $(document).ready(function () {
        $('.pertanggal').dataTable({
            bFilter: false,
            responsive: true
        });

        $('#history').DataTable().destroy();
        $('#history').dataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/api/transaksi/all",
                // data: {id: id},
                // type: "post",
                // dataType: "json",
            },
            columns: [
                // { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'date_in', name: 'date_in'},
                { data: 'date_out', name: 'date_out'},
                { data: 'divisi', name: 'divisi'},
                { data: 'tujuan', name: 'tujuan'},
                { data: 'so', name: 'so'},
                { data: 'product', name: 'product'},
                { data: 'jumlah', name: 'jumlah'},
                { data: 'action', name: 'action'},
            ],
            "oLanguage": {
                "sSearch": "Cari:"
            }
        });

        $('#gudang-barang').dataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "/api/transaksi/history",
                // data: {id: id},
                // type: "post",
                // dataType: "json",
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'kode_produk', name: 'kode_produk'},
                { data: 'product', name: 'product'},
                { data: 'stock', name: 'stock'},
                { data: 'stock', name: 'stock'},
                { data: 'kelompok', name: 'kelompok'},
                { data: 'action', name: 'action'},
            ],
            "oLanguage": {
                "sSearch": "Cari:"
            }
        });
        $('.add-produk').dataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        $(document).on("click", "#semua-produk-tab", function () {
            $('.produk-show').addClass('hidden-product');
        });
        $(document).on("click", "#produk-tab", function () {
            $('.produk-show').addClass('hidden-product');
        });
        $('.table-seri').dataTable({
            "oLanguage": {
                "sSearch": "Cari:"
            }
        });
    });

    function detailProdukModal() {
        $('.produk-show').removeClass('hidden-product');
    }

    $(document).on('click', '.detailmodal', function() {
        var id = $(this).data('id');
        console.log(id);

        $.ajax({
            url: "/api/transaksi/history-detail/" + id,
            type: "get",
            dataType: "json",
            success: function(res) {
                console.log(res);
                $('p#kode_produk').text(res.header[0].kode);
                $('p#nama_produk').text(res.header[0].nama);
                $('p#deskripsi').text(res.header[0].deskripsi);
                $('p#panjang').text(res.header[0].panjang);
                $('p#lebar').text(res.header[0].lebar);
                $('p#tinggi').text(res.header[0].tinggi);
            }
        });

        $('#datatable').dataTable({
            // processing: true,
            // serverSide: true,
            // responsive: true,
            // ajax: {
            //     url: "/api/transaksi/history-detail/" + id,
            //     // data: {id: id},
            //     // type: "post",
            //     // dataType: "json",
            // },
            // columns: [
            //     // { data: 'DT_RowIndex', name: 'DT_RowIndex'},
            //     { data: 'so', name: 'so'},
            //     { data: 'date_in', name: 'date_in'},
            //     { data: 'date_out', name: 'date_out'},
            //     { data: 'divisi', name: 'divisi'},
            //     { data: 'tujuan', name: 'tujuan'},
            //     { data: 'jumlah', name: 'jumlah'},
            //     { data: 'action', name: 'action'},
            // ],
            "oLanguage": {
                "sSearch": "Cari:"
            }
        });
        $('.produk-show').removeClass('hidden-product');
    })

</script>
@stop
