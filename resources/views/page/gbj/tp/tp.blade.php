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
                                                    <select name="" id="divisi" class="form-control ">
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
                                                <td><span class="badge badge-success">Divisi IT</span></td>
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

<!-- Modal Per Tanggal-->
<div class="modal fade" id="modal-per-tanggal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="title">Produk Ambulatory</span></h5>
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

    function detailtanggal() {
        $('#modal-per-tanggal').modal('show');
    }

    function detailProduk() {
        $('.modalDetail').modal('show');
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
            autoWidth: false,
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
            autoWidth: false,
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
        $(document).on("click", "#semua-produk-tab", function () {
            $('.produk-show').addClass('hidden-product');
        });
        $(document).on("click", "#produk-tab", function () {
            $('.produk-show').addClass('hidden-product');
        });

    });

    function detailProdukModal() {
        $('.produk-show').removeClass('hidden-product');
    }

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
                // { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'seri', name: 'seri'},
                { data: 'layout', name: 'layout'},
            ],
            "oLanguage": {
                "sSearch": "Cari:"
            }
        });

        detailtanggal();
    })
</script>
@stop
