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
    .filter{
        margin: 5px;
    }
    .dataTables_filter{
        display: none;
    }
</style>

<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0">Riwayat Transaksi</h1>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
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
                                                <div class="d-flex align-items-center">
                                                    <label class="mr-3 mb-0 d-none d-md-block" for="">Tanggal</label>
                                                    <input type="text" name="" id="datetimepicker1"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2 my-md-0">
                                                <div class="d-flex align-items-center">
                                                    <label class="mr-3 mb-0 d-none d-md-block" for="">Dari / Ke</label>
                                                    <select name="" id="divisi" class="form-control">
                                                        <option value="">All</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2 my-md-0">
                                                <div class="d-flex align-items-center">
                                                    <label class="mr-3 mb-0 d-none d-md-block" for="">Jenis Produk</label>
                                                    <select name="" id="jenis" class="form-control">
                                                        <option value="">All</option>
                                                        <option value="Sparepart">Sparepart</option>
                                                        <option value="Unit">Unit</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="card-text">Keterangan Isi Kolom<b>:</b></p>
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
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    {{-- Tanggal Masuk dan Tanggal Keluar --}}
                                    <table class="table table-bordered pertanggal" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:220px">Tanggal</th>
                                                <th style="width:220px">Dari/Ke</th>
                                                <th>Jenis</th>
                                                <th>Produk</th>
                                                <th>Unit</th>
                                                <th>Jumlah</th>
                                                <th>Tujuan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card-body" id="produk" role="tabpanel" aria-labelledby="produk-tab">
                        <div class="col-xl-12 d-flex justify-content-end">
                            <span class="float-right filter">
                                <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <div class="dropdown-menu">
                                    <div class="px-3 py-3">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Jenis Produk</label>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="Sparepart" id="sparepart" />
                                                <label class="form-check-label" for="jenis1">
                                                    Sparepart
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="Unit" id="unit" />
                                                <label class="form-check-label" for="jenis2">
                                                    Unit
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </span>
                        </div>
                        <div class="table-produk">
                            <table class="table table-bordered" id="gudang_barang">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Produk</th>
                                        <th>Produk</th>
                                        <th>Jenis Produk</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
                <h5 class="modal-title">Produk <span id="produkk">Ambulatory</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-seri">
                    <thead>
                        <tr>
                            <th>No Seri</th>
                            <th>Kerusakan</th>
                            <th>Tingkat Kerusakan</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@stop

@section('adminlte_js')
<script>
     function detailtanggal() {
        $('#modal-per-tanggal').modal('show');
    }
    var start_date;
    var end_date;
    var DateFilterFunction = (function (oSettings, aData, iDataIndex) {
        var dateStart = parseDateValue(start_date);
        var dateEnd = parseDateValue(end_date);

        var evalDate = parseDateValue(aData[0]);
        if ((isNaN(dateStart) && isNaN(dateEnd)) ||
            (isNaN(dateStart) && evalDate <= dateEnd) ||
            (dateStart <= evalDate && isNaN(dateEnd)) ||
            (dateStart <= evalDate && evalDate <= dateEnd)) {
            return true;
        }
        return false;
    });

    function parseDateValue(rawDate) {
        var dateArray = rawDate.split("-");
        var parsedDate = new Date(dateArray[2], parseInt(dateArray[1]) - 1, dateArray[
        0]);
        return parsedDate;
    }

    $(document).ready(function () {
        var $dTable = $('.pertanggal').DataTable({
            "lengthChange": false,
            ajax: {
            url: "/api/gk/transaksi/all",
            type: "post",
            },
            columns: [
                {data: 'tanggal'},
                {data: 'divisi'},
                {data: 'jenis'},
                {data: 'produk'},
                {data: 'unitt'},
                {data: 'jumlah'},
                {data: 'tujuan'},
                {data: 'aksi'},
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            },
        });

        $('#datetimepicker1').daterangepicker({
            autoUpdateInput: false
        });

        $('#datetimepicker1').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format(
                'DD-MM-YYYY'));
            start_date = picker.startDate.format('DD-MM-YYYY');
            end_date = picker.endDate.format('DD-MM-YYYY');
            $.fn.dataTableExt.afnFiltering.push(DateFilterFunction);
            $dTable.draw();
        });

        $('#datetimepicker1').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            start_date = '';
            end_date = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
            $dTable.draw();
        });

        $("#sparepart, #unit").on("click", function () {
            if ($("#sparepart").is(":checked") && $("#unit").is(":checked")) {
                gudang_barang.columns(3).search("").draw();
            } else if ($("#sparepart").is(":checked") && !$("#unit").is(":checked")) {
                gudang_barang.columns(3).search("Sparepart").draw();
            } else if (!$("#sparepart").is(":checked") && $("#unit").is(":checked")) {
                gudang_barang.columns(3).search("Unit").draw();
            } else {
                gudang_barang.columns(3).search("").draw();
            }
        });
        var gudang_barang = $('#gudang_barang').DataTable({
            destroy: true,
            "lengthChange": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "/api/gk/transaksi/by-product",
                type: "post",

            },
            columns: [
                {data: 'DT_RowIndex'},
                {data: 'kode'},
                {data: 'produk'},
                {data: 'jenis'},
                {data: 'aksi'},
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
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
        $("#divisi").select2();


        $("#divisi").on("change", function () {
            $dTable.columns(1).search($(this).val()).draw();
        });
        $("#jenis").on("change", function () {
            $dTable.columns(2).search($(this).val()).draw();
        });

        $(document).on('click', '.detailModal', function() {
            var id = $(this).data('id');
            var prd = $(this).data('produk');
            $('span#produkk').text(prd);

            $('.table-seri').dataTable({
                autoWidth: false,
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/api/gk/transaksi/noseri/" + id,
                },
                columns: [
                    {data: 'noser'},
                    {data: 'rusak'},
                    {data: 'tingkat'},
                    {data: 'layout'},
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                }
            });
            detailtanggal();
        })
    });


    function detailProdukModal() {
        $('.produk-show').removeClass('hidden-product');
    }
    $.ajax({
        url: '/api/gbj/sel-divisi',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if(res) {
                $("#divisi").empty();
                $("#divisi").append('<option value="">All</option>');
                $.each(res, function(key, value) {
                    $("#divisi").append('<option value="'+value.nama+'">'+value.nama+'</option');
                });
            } else {
                $("#divisi").empty();
            }
        }
    });
</script>
@stop
