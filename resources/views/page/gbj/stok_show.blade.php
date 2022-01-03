@extends('adminlte.page')

@section('title', 'Beta Version')

@section('adminlte_css')
<style>
    .hasTooltip span {
        display: none;
        color: #000;
        text-decoration: none;
        padding: 3px;
    }

    .hasTooltip:hover span {
        display: block;
        top: 5%;
        right: 105%;
        background-color: #FFF;
        border: 1px solid #CCC;
        margin: 2px 10px;
    }

    .limitchar {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }

    .medt {
        width: 250px;
    }

    .larget {
        width: 350px;
    }
</style>
@stop
@section('content_header')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Gudang Produk</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Gudang Produk</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="semua-produk-tab" data-toggle="tab" href="#semua-produk" role="tab" aria-controls="semua-produk" aria-selected="true">Semua Produk</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="produk-tab" data-toggle="tab" href="#produk" role="tab" aria-controls="produk" aria-selected="false">Per Produk</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tanggal-tab" data-toggle="tab" href="#tanggal" role="tab" aria-controls="tanggal" aria-selected="false">Per Tanggal</a>
                    </li>
                </ul>
                <div class="tab-content card" id="myTabContent">
                    <div class="tab-pane fade show active card-body" id="semua-produk" role="tabpanel" aria-labelledby="semua-produk-tab">
                        <div class="row">
                            <div class="col-lg-12">
                                <span class="btn-group  float-right" role="group" aria-label="Button group with nested dropdown">
                                    <button type="button" class="btn btn-outline-info active" id="tablebtn"><i class="fas fa-list"></i></button>
                                    <button type="button" class="btn btn-outline-info" id="gridbtn"><i class="fas fa-th"></i></button>
                                </span>
                                <span class="dropdown float-right" id="filter" style="margin-right:5px;">
                                    <button class=" btn btn-outline-info dropdown-toggle" type="button" id="dropdownFilter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Filter
                                    </button>
                                    <ul id="filter_dd" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownFilter">
                                        <li><span class="dropdown-header">Kelompok Produk</span></li>
                                        <li><span class="dropdown-item jenis_po po_online" id="jenis_po" name="jenis_po"><input type="checkbox" value="alat_kesehatan"> Alat Kesehatan</span></li>
                                        <li><span class="dropdown-item jenis_po po_offline" id="jenis_po" name="jenis_po"><input type="checkbox" value="sarana_kesehatan"> Sarana Kesehatan</span></li>
                                        <li><span class="dropdown-item jenis_po po_offline" id="jenis_po" name="jenis_po"><input type="checkbox" value="aksesoris"> Aksesoris</span></li>
                                        <li><span class="dropdown-item jenis_po po_offline" id="jenis_po" name="jenis_po"><input type="checkbox" value="lain"> Lain-lain</span></li>
                                    </ul>
                                </span>
                            </div>
                        </div>

                        <div class="row" style="margin-top:5px;">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table id="semuaproduk" class="table table-hover table-striped" width="100%">
                                        <thead style="text-align: center; font-size: 15px; ">
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Produk</th>
                                                <th>Merk</th>
                                                <th>Nama Produk</th>
                                                <th>Kelompok Produk</th>
                                                <th>Stok</th>
                                                <th>Satuan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodies">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card-body" id="produk" role="tabpanel" aria-labelledby="produk-tab">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="produk" class="col-sm-4 col-form-label" style="text-align:right;">Cari Produk</label>
                                        <div class="col-sm-8">
                                            <div class="select2-info">
                                                <select class="select2 custom-select form-control @error('produk') is-invalid @enderror produk" data-dropdown-css-class="select2-info" style="width: 50%;" name="produk" id="produk">
                                                    <option value=""></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <span class="btn-group  float-right" role="group" aria-label="Button group with nested dropdown">
                                    <button type="button" class="btn btn-outline-info active" id="tablebtn"><i class="fas fa-list"></i></button>
                                    <button type="button" class="btn btn-outline-info" id="gridbtn"><i class="fas fa-th"></i></button>
                                </span>
                                <span class="dropdown float-right" id="filter" style="margin-right:5px;">
                                    <button class=" btn btn-outline-info dropdown-toggle" type="button" id="dropdownFilter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Filter
                                    </button>
                                    <ul id="filter_dd" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownFilter">
                                        <li><span class="dropdown-header">Asal / Tujuan</span></li>
                                        <li><span class="dropdown-item jenis_po po_online" id="jenis_po" name="jenis_po"><input type="checkbox" value="alat_kesehatan"> Produksi</span></li>
                                        <li><span class="dropdown-item jenis_po po_offline" id="jenis_po" name="jenis_po"><input type="checkbox" value="sarana_kesehatan"> QC</span></li>
                                        <li><span class="dropdown-item jenis_po po_offline" id="jenis_po" name="jenis_po"><input type="checkbox" value="aksesoris"> Sarana Kesehatan</span></li>
                                        <li><span class="dropdown-item jenis_po po_offline" id="jenis_po" name="jenis_po"><input type="checkbox" value="lain"> Teknik</span></li>
                                    </ul>
                                </span>
                            </div>
                        </div>

                        <div class="row" style="margin-top:5px;">
                            <div class="col-lg-3">
                                <div class="card">
                                    <div class="card-body"></div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="table-responsive">
                                    <table id="semuaproduk" class="table table-hover table-striped" width="100%">
                                        <thead style="text-align: center; font-size: 15px; ">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
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
                                                <td>Produksi</td>
                                                <td>Ref Hasil Produksi 0001/BPPB/09/21</td>
                                                <td><span style="color:green;"><i class="fas fa-plus"></i><span class="float-right">1000</span></span></td>
                                                <td><a href=""><i class="fas fa-search"></i></a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card-body" id="tanggal" role="tabpanel" aria-labelledby="tanggal-tab">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="detail_produk_id" class="col-sm-5 col-form-label" style="text-align:right;">Tanggal</label>
                                        <div class="col-sm-2">
                                            <input type="date" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <span class="btn-group  float-right" role="group" aria-label="Button group with nested dropdown">
                                    <button type="button" class="btn btn-outline-info active" id="tablebtn"><i class="fas fa-list"></i></button>
                                    <button type="button" class="btn btn-outline-info" id="gridbtn"><i class="fas fa-th"></i></button>
                                </span>
                                <span class="dropdown float-right" id="filter" style="margin-right:5px;">
                                    <button class=" btn btn-outline-info dropdown-toggle" type="button" id="dropdownFilter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Filter
                                    </button>
                                    <ul id="filter_dd" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownFilter">
                                        <li><span class="dropdown-header">Pilih Produk</span></li>
                                        <li><span class="dropdown-item jenis_po po_online" id="jenis_po" name="jenis_po">
                                                <select class="select2 custom-select form-control @error('produk') is-invalid @enderror produk" data-dropdown-css-class="select2-info" style="width: 50%;" name="produk" id="produk">
                                                    <option value="Tes">Tes</option>
                                                </select>
                                            </span>
                                        </li>
                                    </ul>
                                </span>
                            </div>
                        </div>

                        <div class="row" style="margin-top:5px;">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table id="tanggalproduk" class="table table-hover" width="100%">
                                        <thead style="text-align: center; font-size: 15px; ">
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
                                                <td><span style="color:green;"><i class="fas fa-plus"></i><span class="float-right">1000</span></span></td>
                                                <td><a href=""><i class="fas fa-search"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>24-09-2021</td>
                                                <td>CMS-600 PLUS</td>
                                                <td>Produksi</td>
                                                <td>Ref Hasil Produksi 0001/BPPB/09/21</td>
                                                <td><span style="color:red;"><i class="fas fa-plus"></i><span class="float-right">10</span></span></td>
                                                <td><a href=""><i class="fas fa-search"></i></a></td>
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

        <div class="modal fade" id="historimutasimodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color:	#006400;">
                        <h4 class="modal-title" id="myModalLabel" style="color:white;">Histori Mutasi</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="historimutasi">

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('adminlte_js')
<script>
    $(function() {
        $(".tooltip").tooltip();
        $("#example1").popover({
                trigger: "manual",
                html: true,
                animation: false,
                content: function() {
                    return $(this).next('.pop').html();
                }
            })
            .on("mouseenter", '.pop', function() {
                var _this = this;
                $(this).popover("show");
                $(".pop").on("mouseleave", function() {
                    $(_this).popover('hide');
                });
            }).on("mouseleave", '.pop', function() {
                var _this = this;
                setTimeout(function() {
                    if (!$(".pop:hover").length) {
                        $(_this).popover("hide");
                    }
                }, 300);
            });

        $("#example2").popover({
                trigger: "manual",
                html: true,
                animation: false,
                content: function() {
                    return $(this).next('.pop').html();
                }
            })
            .on("mouseenter", '.pop', function() {
                var _this = this;
                $(this).popover("show");
                $(".pop").on("mouseleave", function() {
                    $(_this).popover('hide');
                });
            }).on("mouseleave", '.pop', function() {
                var _this = this;
                setTimeout(function() {
                    if (!$(".pop:hover").length) {
                        $(_this).popover("hide");
                    }
                }, 300);
            });

        $(document).on('click', '.historimutasimodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var dataid = $(this).attr('data-id');
            $.ajax({
                url: "/gudang_produk_gbj/mutasi/" + dataid,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#historimutasimodal').modal("show");
                    $('#historimutasi').html(result).show();
                    console.log(result);
                    $('#detaildata').DataTable({
                        processing: false,
                        serverSide: false,
                        ajax: href,
                        columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        }, {
                            data: 'barcode',
                            name: 'barcode'
                        }],
                        "language": {
                                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                            }
                    });
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

        $('input[type="radio"][name="tampilan"]').on('change', function() {
            console.log($(this).val());
            if ($(this).val() == "tanggal") {
                $('#hariinitable').attr('hidden', true);
                $('#tanggal-form').removeAttr('hidden');
                $('#produktable').attr('hidden', true);
                $('#produk-form').attr('hidden', true);
                $('#produk').attr('disabled', true);
                $('#produk').val(null).trigger('change');

                $("#tipe_produk").text("-");
                $("#nama_produk").text("-");
                $("#jumlah_stok").text("-");
                $("#kartu_stock_tambah").attr('hidden', true);
            } else if ($(this).val() == "produk") {
                $('#hariinitable').attr('hidden', true);
                $('#tanggal-form').attr('hidden', true);
                $('#produk-form').removeAttr('hidden');
                $('#produktable').attr('hidden', true);
                $('#produk').removeAttr('disabled');
                $('#tanggal').val("");
            }
        });

        $('select[name="produk"]').on('change', function() {
            var k = $(this).val();
            if (k) {
                $('#hariinitable').attr('hidden', true);
                $('#produktable').removeAttr('hidden');
                $(".tambahurl").attr('href', '/gudang_produk_gbj/create/' + k);
                $.ajax({
                    url: '/gudang_produk_gbj/produk/' + k,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        if (data != null) {
                            $("#tipe_produk").text(data['gudang_produk']['detail_produk']['produk']['nama']);
                            $("#nama_produk").text(data['gudang_produk']['detail_produk']['nama']);
                            $("#jumlah_stok").text(data['jumlah_saldo'] + " pcs");
                            $("#kartu_stock_tambah").attr('hidden', true);
                        } else {
                            $("#tipe_produk").text("-");
                            $("#nama_produk").text("-");
                            $("#jumlah_stok").text("-");
                            $("#kartu_stock_tambah").removeAttr('hidden');
                        }
                    },
                    error: function(data) {
                        $("#tipe_produk").text("-");
                        $("#nama_produk").text("-");
                        $("#jumlah_stok").text("-");
                        $("#kartu_stock_tambah").removeAttr('hidden');
                    }
                });

                $('#example2').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    ajax: "/gudang_produk_gbj/produk/show/" + k,
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'tanggal',
                            name: 'tanggal',
                            className: 'text-center',
                            type: 'date-euro',
                        },
                        {
                            data: 'divisi_id',
                            name: 'divisi_id',
                            className: 'text-left',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'keterangan',
                            name: 'keterangan',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'jumlah_masuk',
                            name: 'jumlah_masuk',
                            className: 'text-right',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'jumlah_keluar',
                            name: 'jumlah_keluar',
                            className: 'text-right',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'jumlah_saldo',
                            name: 'jumlah_saldo',
                            className: 'text-right',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'aksi',
                            name: 'aksi',
                            className: 'text-center',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
                });
            }
        });

        $('#tanggal').on('keyup change', function() {
            var tanggal = $(this).val();
            if (tanggal != "") {
                $('#hariinitable').removeAttr('hidden');
                $('#produktable').attr('hidden', true);
                $('#example1').DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    ajax: "/gudang_produk_gbj/tanggal/show/" + tanggal,
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'produk',
                            name: 'produk'
                        },
                        {
                            data: 'divisi_id',
                            name: 'divisi_id',
                            className: 'text-left'
                        },
                        {
                            data: 'keterangan',
                            name: 'keterangan',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'jumlah_masuk',
                            name: 'jumlah_masuk',
                            className: 'text-right',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'jumlah_keluar',
                            name: 'jumlah_keluar',
                            className: 'text-right',
                            orderable: false,
                            searchable: false

                        },
                        {
                            data: 'aksi',
                            name: 'aksi',
                            className: 'text-center',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
        }
                });
            }
        })
    });
</script>
@stop