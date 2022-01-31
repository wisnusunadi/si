@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
<style>
    .hapus {
        display: none;
    }

    .nomor-so {
        background-color: #717FE1;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .nomor-akn {
        background-color: #DF7458;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .nomor-po {
        background-color: #85D296;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 18px
    }

    .float {
        width: 60px;
        height: 60px;
        bottom: 40px;
        right: 40px;
        background-color: #6aadff;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        box-shadow: 2px 2px 3px #999;
    }

    .my-float {
        margin-top: 22px;
    }
    #DataTables_Table_0_filter{
        display: none;
    }
</style>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Penerimaan Selain Perakitan</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs" id="myTab" role="tabList">
                            <input type="hidden" name="userid" id="userid" value="{{ Auth::user()->id }}">
                            <li class="nav-item" role="presentation">
                                <a href="#produk" class="nav-link active" id="produk-tab" data-toggle="tab" role="tab"
                                    aria-controls="produk" aria-selected="true">Rancangan Perakitan</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a href="#semua-produk" class="nav-link" id="semua-produk-tab" data-toggle="tab"
                                    role="tab" aria-controls="semua-produk" aria-selected="true">Buat Perakitan</a>
                            </li>
                        </ul>
                        <div class="tab-content card" id="myTabContent">
                            <div class="tab-pane fade show active card-body" id="produk" role="tabpanel"
                                aria-labelledby="produk-tab">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label for="" id="tanggal" class="col-sm-5 text-right">Tanggal
                                                    Masuk</label>
                                                <div class="col-sm-2">
                                                    <input type="text" class="form-control" id="datetimepicker1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive ">
                                            <table class="table table-hover pertanggal" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal Masuk</th>
                                                        <th>Dari</th>
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
                            <div class="tab-pane fade show card-body" id="semua-produk" role="tabpanel"
                                aria-labelledby="semua-produk-tab">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card card-primary b-radius">
                                                    <div class="card-body">
                                                        <ul class="list-group list-group-flush">
                                                            <li class="list-group-item">
                                                                <div class="row">
                                                                    <div class="col-12 mt-3">
                                                                        <form method="post">
                                                                            <div class="form-group row top-min">
                                                                                <label for=""
                                                                                    class="col-12 font-weight-bold col-form-label">Tanggal
                                                                                    Masuk</label>
                                                                                <div class="col-12">
                                                                                    <input type="date"
                                                                                        class="form-control tgl_masuk"
                                                                                        id="tgl_masuk" name="tgl_masuk">
                                                                                    <div class="invalid-feedback">
                                                                                        Silahkan Masukkan Tanggal Masuk
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row top-min ">
                                                                                <label for=""
                                                                                    class="col-12 font-weight-bold col-form-label">Dari</label>
                                                                                <div class="col-12">
                                                                                    <select
                                                                                        class="custom-select division"
                                                                                        id="divisi" name="dari">
                                                                                    </select>
                                                                                    <div class="invalid-feedback">
                                                                                        Silahkan Masukkan Dari Divisinya
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group row top-min">
                                                                                <label for=""
                                                                                    class="col-12 font-weight-bold col-form-label">Keterangan</label>
                                                                                <div class="col-12">
                                                                                    <textarea name="deskripsi"
                                                                                        id="deskripsi"
                                                                                        class="form-control tujuan"></textarea>
                                                                                    <div class="invalid-feedback">
                                                                                        Silahkan Masukkan Keterangan
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-12 d-flex justify-content-end mb-3">
                                                <a href="#" class="float btn-tambah">
                                                    <i class="fa fa-plus my-float"></i>
                                                </a>
                                            </div>
                                            <div class="col-12 table-responsive mb-4">
                                                <table class="table table-hover addData">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 220px">Produk</th>
                                                            <th>Jumlah</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tambah_data">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-12 d-flex justify-content-end">
                                                <div class="btn-simpan hapus">
                                                    <button class="btn btn-success" type="button"
                                                        id="btnSubmit">Terima</button>&nbsp;
                                                    <button class="btn btn-info" type="button"
                                                        id="btnDraft">Rancang</button>&nbsp;
                                                    <button id="btnCancel" class="btn btn-secondary " type="button">Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Detail Rancangan-->
<div class="modal fade modal-rancangan" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Rancangan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm">
                                <label for="">Tanggal Masuk</label>
                                <div class="card nomor-so">
                                    <div class="card-body">
                                        <span id="in"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Dari</label>
                                <div class="card nomor-akn">
                                    <div class="card-body">
                                        <span id="from"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="">Tujuan</label>
                                <div class="card nomor-po">
                                    <div class="card-body">
                                        <span id="tujuan"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-rancangan">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" id="btnSave">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambahan Perakitan-->
<!-- Modal Detail-->
<div class="modal fade tambahan-perakitan" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Detail Produk <span id="titlee"></span></b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk1">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb"></th>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <br>
                <button type="button" class="btn btn-primary float-right" id="btnSeri">Simpan</button>
                <button class="btn btn-info" data-toggle="modal" data-target="#ubah-layout">Ubah Layout</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambahan Rancangan-->
<!-- Modal Detail-->
<div class="modal fade tambahan-rancangan" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Detail Produk <span id="title"></span></b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped scan-produk">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="head-cb-rancang"></th>
                            <th>Nomor Seri</th>
                            <th>Layout</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <button class="btn btn-info" data-toggle="modal" data-target="#ubah-layout-rancang">Ubah Layout</button>
                <button type="button" class="btn btn-primary float-right" id="seriBtn">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Layout-->
<div class="modal fade" id="ubah-layout" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Layout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Layout</label>
                    <select name="" id="change_layout" class="form-control">
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary " onclick="ubahData()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ubah-layout-rancang" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Layout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Layout</label>
                    <select name="" id="change_layout_rancang" class="form-control">
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                <button type="button" class="btn btn-primary" onclick="ubahDataRancang()">Simpan</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('adminlte_js')
<script>
    var function_layout = [];
    (function ($) {
        $.fn.inputFilter = function (inputFilter) {
            return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function () {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
            });
        };
    }(jQuery));

    $(document).on('click', '.btn-tambah', function () {
        $.ajax({
            success: function () {
                addData()
                $('.btn-simpan').removeClass('hapus');
            }
        });
    });
    var i = 0;

    function addData() {
        $.ajax({
            url: '/api/gbj/sel-gbj',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                $(".productt").append('<option value="">Pilih Item</option>');
                $.each(res, function (key, value) {
                    $(".productt").append('<option value="' + value.id + '">' + value.produk.nama +
                        ' ' + value.nama + '</option');
                });
            }
        });


        let tambah_data = '<tr id="row' + i + '"><td><select name="gdg_brg_jadi_id[' + i + ']" id="gdg' + i +
            '" class="form-control productt"></select></td><td><input type="text" class="form-control number" id="qty" name="qty[' +
            i +
            ']"></td><td><button class="btn btn-primary" id="btnPlus"><i class="fas fa-qrcode"></i> Tambah</button>&nbsp;<button class="btn btn-danger btn-delete"><i class="fas fa-trash"></i> Hapus</button></td></tr>';
        $('tbody.tambah_data').append(tambah_data);
        $('#gdg' + i + '').select2();
        $(".number").inputFilter(function (value) {
            return /^\d*$/.test(value); // Allow digits only, using a RegExp
        });
        i++;
    }

    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        $(this).parent().parent().remove();
        var check = $('tbody.tambah_data tr').length;
        if (check != 0) {
            $('.btn-simpan').removeClass('hapus');
        } else {
            $('.btn-simpan').addClass('hapus');
        }
    });
    var id = '';
    let jml = '';
    var gbj;
    $(document).on('click', '.editmodal', function () {
        id = $(this).data('id');


        $.ajax({
            url: '/api/draft/data',
            type: "post",
            data: {
                id: id,
            },
            success: function (res) {
                console.log(res);
                $('span#in').text(res.data[0].in);
                $('span#from').text(res.data[0].from);
                $('span#tujuan').text(res.data[0].tujuan);
            },

        });
        $('.table-rancangan').DataTable().destroy();
        $('.table-rancangan').DataTable({
            processing: true,
            serverSide: true,
            "lengthChange": false,
            autoWidth: false,
            "searching": false,
            ajax: {
                url: '/api/draft/data',
                type: "post",
                data: {
                    id: id,
                },
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'nama_produk'
                },
                {
                    data: 'jml'
                },
                {
                    data: 'action'
                },
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });
        modalRancangan();
    });

    $("#head-cb-rancang").on('click', function () {
        var isChecked = $("#head-cb-rancang").prop('checked')
        $('.scan-produk').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', isChecked);

    });

    $(document).on('click', '.detail', function (e) {
        var id = $(this).data('id');
        gbj = $(this).data('gbj');
        var tr = $(this).closest('tr');
        jml = tr.find('#qty').val();
        did = tr.find('#tfid').val();
        console.log(did);
        $('span#title').text($(this).data('nama') + $(this).data('var'));

        $('.scan-produk').DataTable({
            destroy: true,
            "ordering": false,
            "autoWidth": false,
            searching: false,
            "lengthChange": false,
            processing: true,
            serverSide: false,
            ajax: {
                url: "/api/draft/data-seri",
                type: "post",
                data: {
                    t_gbj_detail_id: id,
                },
            },
            columns: [{
                    data: 'checkbox'
                },
                {
                    data: 'serii'
                },
                {
                    data: 'posisi'
                },
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });

        tambahanRancangan();
    });

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
        $('#head-cb-rancang').prop('checked', false);
        $('#head-cb').prop('checked', false);
        $('.division').select2();
        $('.productt').select2();

        $.ajax({
            url: '/api/gbj/sel-layout',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                console.log(res);
                $.each(res, function (key, value) {
                    $("#change_layout").append('<option value="' + value.id + '">' + value
                        .ruang + '</option');
                });
                function_layout.push(res);
            }
        });

        $.each(function_layout[0], function (index, val) {
            $("#change_layout_rancang").append('<option value="' + val.id + '">' + val.ruang +
                '</option');
        });

        var table = $('.pertanggal').DataTable({
            processing: true,
            "lengthChange": false,
            ajax: {
                url: '/api/draft/data',
                type: "post",
            },
            columns: [{
                    data: 'in',
                    name: 'in'
                },
                {
                    data: 'from',
                    name: 'from'
                },
                {
                    data: 'tujuan'
                },
                {
                    data: 'action'
                }
            ],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
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
            table.draw();
        });

        $('#datetimepicker1').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
            start_date = '';
            end_date = '';
            $.fn.dataTable.ext.search.splice($.fn.dataTable.ext.search.indexOf(DateFilterFunction, 1));
            table.draw();
        });

        $("#head-cb").on('click', function () {
            var isChecked = $("#head-cb").prop('checked')
            // $('.cb-child').prop('checked', isChecked)
            $('.scan-produk1').DataTable()
                .column(0)
                .nodes()
                .to$()
                .find('input[type=checkbox]')
                .prop('checked', isChecked);
        });

        // divisi
        $.ajax({
            url: '/api/gbj/sel-divisi',
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res) {
                    console.log(res);
                    $(".division").empty();
                    $(".division").append('<option value="">Pilih Item</option>');
                    $.each(res, function (key, value) {
                        $(".division").append('<option value="' + value.id + '">' + value
                            .nama + '</option');
                    });
                } else {
                    $(".division").empty();
                }
            }
        });

    });

    const seri = {};
    const layout = {};
    const serir = {};
    var nose;
    var lay;
    var i = 0;
    $(document).on('click', '#btnPlus', function () {
        $('#head-cb').prop('checked', false);
        var tr = $(this).closest('tr');
        x = tr.find('#qty').val();
        y = tr.find('.productt').val();
        yText = tr.find('.productt option:selected').text();
        $('span#titlee').text(yText);
        tambahanPerakitan(x);
        i++;
    })

    $(document).on('click', '#btnCancel', function() {
        location.reload();
    })


    $(document).on('click', '#btnSubmit', function (e) {

        if ($('#tgl_masuk').val() == '' && $('#divisi').val() == '' && $('#deskripsi').val() == '') {
            $('.tgl_masuk').addClass('is-invalid');
            $('#divisi').addClass('is-invalid');
            $('#deskripsi').addClass('is-invalid');
            // alert('Data tidak boleh kosong');
        } else if ($('#tgl_masuk').val() == '' && $('#divisi').val() == '') {
            $('.tgl_masuk').addClass('is-invalid');
            $('#divisi').addClass('is-invalid');
            $('#deskripsi').removeClass('is-invalid');
        } else if ($('#divisi').val() == '' && $('#deskripsi').val() == '') {
            $('.tgl_masuk').removeClass('is-invalid');
            $('#divisi').addClass('is-invalid');
            $('#deskripsi').addClass('is-invalid');
        } else if ($('#tgl_masuk').val() == '' && $('#deskripsi').val() == '') {
            $('.tgl_masuk').addClass('is-invalid');
            $('#divisi').removeClass('is-invalid');
            $('#deskripsi').addClass('is-invalid');
        } else if ($('#tgl_masuk').val() == '') {
            $('.tgl_masuk').addClass('is-invalid');
            $('#divisi').removeClass('is-invalid');
            $('#deskripsi').removeClass('is-invalid');
        } else if ($('#divisi').val() == '') {
            $('#divisi').addClass('is-invalid');
            $('.tgl_masuk').removeClass('is-invalid');
            $('#deskripsi').removeClass('is-invalid');
        } else if ($('#deskripsi').val() == '') {
            $('.tgl_masuk').removeClass('is-invalid');
            $('#divisi').removeClass('is-invalid');
            $('#deskripsi').addClass('is-invalid');
        } else {
            $('.tgl_masuk').removeClass('is-invalid');
            $('#divisi').removeClass('is-invalid');
            $('#deskripsi').removeClass('is-invalid');

            const tgl_masuk = $('#tgl_masuk').val();
            const hari_ini = Date.now();
            const get = new Date(hari_ini);
            const get_tgl = moment(get).format('YYYY-MM-DD');
            if (tgl_masuk > get_tgl) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Tanggal masuk tidak boleh lebih besar dari tanggal hari ini',
                    icon: 'warning',
                    confirmButtonText: 'Oke'
                })
            } else {
                e.preventDefault();

                Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                        title: 'Please wait',
                        text: 'Data is transferring...',
                        allowOutsideClick: false,
                        showConfirmButton: false
                    });
                        Swal.fire(
                        'Sukses!',
                        'Data Berhasil Disimpan',
                        'success'
                        )
                        $.ajax({
                            url: "/api/draft/final",
                            type: "post",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                tgl_masuk: $('#tgl_masuk').val(),
                                dari: $('#divisi').val(),
                                deskripsi: $('#deskripsi').val(),
                                userid: $('#userid').val(),
                                data: seri,
                            },
                            success: function (res) {

                            }
                        })
                        location.reload();
                    }
                })

            }
        }
    });

    $(document).on('click', '#btnDraft', function (e) {
        e.preventDefault();

        if ($('#tgl_masuk').val() == '' && $('#divisi').val() == '' && $('#deskripsi').val() == '') {
            $('.tgl_masuk').addClass('is-invalid');
            $('#divisi').addClass('is-invalid');
            $('#deskripsi').addClass('is-invalid');
            // alert('Data tidak boleh kosong');
        } else if ($('#tgl_masuk').val() == '' && $('#divisi').val() == '') {
            $('.tgl_masuk').addClass('is-invalid');
            $('#divisi').addClass('is-invalid');
            $('#deskripsi').removeClass('is-invalid');
        } else if ($('#divisi').val() == '' && $('#deskripsi').val() == '') {
            $('.tgl_masuk').removeClass('is-invalid');
            $('#divisi').addClass('is-invalid');
            $('#deskripsi').addClass('is-invalid');
        } else if ($('#tgl_masuk').val() == '' && $('#deskripsi').val() == '') {
            $('.tgl_masuk').addClass('is-invalid');
            $('#divisi').removeClass('is-invalid');
            $('#deskripsi').addClass('is-invalid');
        } else if ($('#tgl_masuk').val() == '') {
            $('.tgl_masuk').addClass('is-invalid');
            $('#divisi').removeClass('is-invalid');
            $('#deskripsi').removeClass('is-invalid');
        } else if ($('#divisi').val() == '') {
            $('#divisi').addClass('is-invalid');
            $('.tgl_masuk').removeClass('is-invalid');
            $('#deskripsi').removeClass('is-invalid');
        } else if ($('#deskripsi').val() == '') {
            $('.tgl_masuk').removeClass('is-invalid');
            $('#divisi').removeClass('is-invalid');
            $('#deskripsi').addClass('is-invalid');
        } else {
            $('.tgl_masuk').removeClass('is-invalid');
            $('#divisi').removeClass('is-invalid');
            $('#deskripsi').removeClass('is-invalid');

            const prd = [];
            const jml = [];

            $('select[name^="gdg_brg_jadi_id"]').each(function () {
                prd.push($(this).val());
            });

            $('input[name^="qty"]').each(function () {
                jml.push($(this).val());
            });

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, draft it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).prop('disabled', true);
                        Swal.fire(
                        'Sukses!',
                        'Data Berhasil Disimpan',
                        'success'
                        )
                        $.ajax({
                            url: "/api/draft/rancang",
                            type: "post",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                tgl_masuk: $('#tgl_masuk').val(),
                                dari: $('#divisi').val(),
                                deskripsi: $('#deskripsi').val(),
                                userid: $('#userid').val(),
                                data: seri,
                            },
                            success: function (res) {
                                console.log(res);
                            }
                        })
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                })


        }
    });

    $(document).on('click', '#seriBtn', function (e) {
        let no_seri = [];
        let layout = [];
        serir[did] = {gbj: gbj, jumlah: jml, data: []};
        let a = $('.scan-produk').DataTable().column(0).nodes()
            .to$().find('input[type=checkbox]:checked');
        $(a).each(function (index, elm) {
            let noseri_temp = $(elm).val();
            let layout_temp = $(elm).parent().next().next().children().val()

            serir[did].data.push({
                noseri: noseri_temp,
                layout: layout_temp
            })
        });


        $('.cb-child-rancang').each(function () {
            if ($(this).is(':checked')) {
                // a.push($(this).val());
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Noseri Berhasil disimpan',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
            
        })
        console.log(serir);
        $('.tambahan-rancangan').modal('hide');
    })
    $(document).on('click', '#btnSave', function () {
        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, save it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                'Sukses!',
                'Data Berhasil Diterima',
                'success'
                )
                $.ajax({
                    url: "/api/tfp/create-final",
                    type: "post",
                    data: {
                        "_token": "{{csrf_token()}}",
                        id: id,
                        seri: serir,
                    },
                    success: function (res) {
                        console.log(res);
                        // location.reload();
                    }
                })

            }
        })

    })

    // load modal
    function ubahData() {
        let checkbox_terpilih = $('.scan-produk1 tbody .cb-child:checked');
        let layout = $('#change_layout').val();
        $.each(checkbox_terpilih, function (index, elm) {
            let b = $(checkbox_terpilih).parent().next().next().children().val(layout);
        });
        $('#ubah-layout').modal('hide');
    }

    function ubahDataRancang() {
        let checkbox_terpilih = $('.scan-produk tbody .cb-child-rancang:checked');
        let layout = $('#change_layout_rancang').val();
        $.each(checkbox_terpilih, function (index, elm) {
            let b = $(checkbox_terpilih).parent().next().next().children().val(layout);
        });
        $('#ubah-layout-rancang').modal('hide');
    }

    $('#datetimepicker1').daterangepicker({});

    function modalRancangan() {
        $('.modal-rancangan').modal('show');
    }

    function tambahanRancangan(x) {
        $('.tambahan-rancangan').modal('show');
    }

    function tambahanPerakitan(x) {
        $('.tambahan-perakitan').modal('show');
        $('.scan-produk1').DataTable().destroy();
        $('.scan-produk1 tbody').empty();

        let a = 1;
        for (let index = 0; index < x; index++) {
            $('.scan-produk1 tbody').append('<tr id="row' + a +
                '"><td><input type="checkbox" class="cb-child"  value="' + y +
                '"></td><td><input type="text" name="noseri_id[][' + a + ']" id="noseri_id[' + a +
                ']" class="form-control seri" style="text-transform:uppercase"><div class="invalid-feedback">Nomor seri ada yang sama.</div></td><td><select name="layout_id[' +
                a + ']" id="layout_id' + a + '" class="form-control layout"></select></td></tr>');
            $.each(function_layout[0], function (indexInArray, valueOfElement) {
                $('#layout_id' + a).append('<option value="' + valueOfElement.id + '">' + valueOfElement.ruang +
                    '</option>');
            });
            a++;
        }
        var tableScan = $('.scan-produk1').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
            }
        });

        $(document).on('click', '#btnSeri', function () {
            let no_seri = [];
            let layout = [];
            let a = $('.scan-produk1').DataTable().column(0).nodes().to$().find('input[type="checkbox"]:checked').map(function () {
                no_seri.push($(this).parent().next().children().val());
                layout.push($(this).parent().next().next().children().val());
            }).get();
            console.log("data");
            console.log(no_seri);
            console.log(layout);
            let arr = [];
            const data = tableScan.$('.seri').map(function () {
                return $(this).val();
            }).get();

            data.forEach(function (item) {
                if (item != '') {
                    arr.push(item);
                }
            })

            const count = arr =>
                arr.reduce((a, b) => ({
                    ...a,
                    [b]: (a[b] || 0) + 1
                }), {})

            const duplicates = dict =>
                Object.keys(dict).filter((a) => dict[a] > 1)

            if (duplicates(count(arr)).length > 0) {
                $('.seri').removeClass('is-invalid');
                $('.seri').filter(function () {
                    for (let index = 0; index < duplicates(count(arr)).length; index++) {
                        if ($(this).val() == duplicates(count(arr))[index]) {
                            return true;
                        }
                    }
                }).addClass('is-invalid');

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Nomor seri ' + duplicates(count(arr)) + ' ada yang sama.',
                })
            } else {
                const ids = [];
                const noserii = [];
                const lay = [];
                seri[y] = {
                    "jumlah": x,
                    "noseri": [],
                    "layout": []
                };
                console.log(seri);
                $('.cb-child').each(function () {
                    if ($(this).is(":checked")) {
                        ids.push($(this).parent().next().children().val());
                        lay.push($(this).parent().next().next().children().val());
                        $.ajax({
                            url: "/api/gbj/ceknoseri",
                            type: "post",
                            data: {
                                noseri: ids
                            },
                            success: function (res) {
                                if (res.msg) {
                                    seri[y].noseri = no_seri;
                                    seri[y].layout = layout;
                                    $('.tambahan-perakitan').modal('hide');

                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: res.error
                                    })
                                }
                            }
                        })
                    }
                });
            }
        });
    }

</script>
@stop
