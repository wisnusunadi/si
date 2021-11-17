@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Lacak</h1>
@stop

@section('adminlte_css')
<style>
    .filter {
        margin: 5px;
    }

    .hide {
        display: none !important;
    }

    @media screen and (min-width: 1440px) {
        section {
            font-size: 14px;
        }

        .dropdown-item {
            font-size: 14px;
        }
    }

    @media screen and (max-width: 1439px) {
        section {
            font-size: 12px;
        }

        .dropdown-item {
            font-size: 12px;
        }
    }
</style>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <div class="card-title">Pencarian</div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-horizontal">
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-5" style="text-align: right">Masukkan Data</label>
                                        <div class="col-4">
                                            <input type="text" class="form-control col-form-label @error('data') is-invalid @enderror" id="data" name="data" />
                                            <div class="invalid-feedback" id="msgdata">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="" class="col-form-label col-5" style="text-align: right">Data Lacak</label>
                                        <div class="col-4">
                                            <select name="pilih_data" id="pilih_data" class="select2 select-info form-control custom-select col-form-label pilih_data" placeholder="Pilih Data" disabled>
                                                <option value=""></option>
                                                <option value="no_po">No Purchase Order</option>
                                                <option value="no_akn">No AKN</option>
                                                <option value="no_seri">No Seri</option>
                                                <option value="no_so">No Sales Order</option>
                                                <option value="no_sj">No Surat Jalan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-5"></div>
                                        <div class="col-4">
                                            <span class="float-right filter"><button type="button" class="btn btn-success" id="btncari" disabled><i class="fas fa-search"></i> Cari</button></span>
                                            <span class="float-right filter"><button type="button" class="btn btn-outline-danger" id="btnbatal"><i class="fas fa-sync"></i> Reset</button></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card hide" id="nopo">
                    <div class="card-body">
                        <h4>Hasil Pencarian Purchase Order</h4>
                        <div class="table-responsive">
                            <table class="table table-hover" id="potable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No PO</th>
                                        <th>Tanggal</th>
                                        <th>Customer</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card hide" id="noseri">
                    <div class="card-body">
                        <h4>Hasil Pencarian No Seri</h4>
                        <div class="table-responsive">
                            <table class="table table-hover" id="noseritable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Seri</th>
                                        <th>Nama Produk</th>
                                        <th>Tanggal</th>
                                        <th>Posisi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card hide" id="noakn">
                    <div class="card-body">
                        <h4>Hasil Pencarian No AKN</h4>
                        <div class="table-responsive">
                            <table class="table table-hover" id="noakntable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No AKN</th>
                                        <th>No PO</th>
                                        <th>Tanggal</th>
                                        <th>Posisi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card hide" id="noso">
                    <div class="card-body">
                        <h4>Hasil Pencarian Sales Order</h4>
                        <div class="table-responsive">
                            <table class="table table-hover" id="nosotable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No SO</th>
                                        <th>No PO</th>
                                        <th>Jenis</th>
                                        <th>Tanggal</th>
                                        <th>Posisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card hide" id="nosj">
                    <div class="card-body">
                        <h4>Hasil Pencarian Surat Jalan</h4>
                        <div class="table-responsive">
                            <table class="table table-hover" id="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Surat Jalan</th>
                                        <th>No PO</th>
                                        <th>Tanggal Kirim</th>
                                        <th>Posisi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('adminlte_js')
<script>
    $(function() {
        $('.pilih_data').select2({
            placeholder: "Pilih Data Lacak",
            allowClear: true
        });
        $('#potable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/penjualan/lacak/data/no_po/0',
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false
            }, {
                data: 'no_po',
                orderable: false,
                searchable: false
            }, {
                data: 'tgl_po',
                orderable: false,
                searchable: false
            }, {
                data: 'nama_customer',
                orderable: false,
                searchable: false
            }, {
                data: 'log',
                orderable: false,
                searchable: false
            }, ]
        });
        $('#nosotable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/penjualan/lacak/data/no_so/0',
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                data: 'DT_RowIndex',
                orderable: false,
                searchable: false
            }, {
                data: 'so',
                orderable: false,
                searchable: false
            }, {
                data: 'tgl_po',
                orderable: false,
                searchable: false
            }, {
                data: 'log',
                orderable: false,
                searchable: false
            }, ]
        });
        $('#noakntable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/penjualan/lacak/data/no_akn/0',
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'tgl_buat',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'log',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    orderable: false,
                    searchable: false
                }
            ]
        });
        $('#noseritable').DataTable();

        $('#data').on('keyup change', function() {
            if ($(this).val() != "") {
                $('.pilih_data').removeAttr('disabled');
                if ($('.pilih_data').find(":selected").val() != "") {
                    $('#btncari').removeAttr('disabled');
                } else if ($('.pilih_data').find(":selected").val() == "") {
                    $('#btncari').attr('disabled', true);
                }
            } else {
                $('#btncari').attr('disabled', true);
            }
        });
        $('.pilih_data').on('keyup change', function() {
            console.log($(this).val());
            if ($(this).val() != "") {
                if ($('#data').val() != "") {
                    $('#btncari').removeAttr('disabled');
                } else {
                    $('#btncari').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#btncari').attr('disabled', true);
            }
        });

        $('#btncari').on('click', function() {
            if ($('.pilih_data').val() == "no_seri") {
                $('#noseri').removeClass('hide');
                $('#nopo').addClass('hide');
                $('#noakn').addClass('hide');
                $('#noso').addClass('hide');
                $('#nosj').addClass('hide');
            } else if ($('.pilih_data').val() == "no_po") {
                var data = $('#data').val();
                $('#potable').DataTable().ajax.url('/api/penjualan/lacak/data/no_po/' + data).load();
                $('#nopo').removeClass('hide');
                $('#noseri').addClass('hide');
                $('#noakn').addClass('hide');
                $('#noso').addClass('hide');
                $('#nosj').addClass('hide');
            } else if ($('.pilih_data').val() == "no_akn") {
                var data = $('#data').val();
                $('#noakntable').DataTable().ajax.url('/api/penjualan/lacak/data/no_akn/' + data).load();
                $('#noakn').removeClass('hide');
                $('#noseri').addClass('hide');
                $('#nopo').addClass('hide');
                $('#noso').addClass('hide');
                $('#nosj').addClass('hide');
            } else if ($('.pilih_data').val() == "no_so") {
                var data = $('#data').val();
                $('#nosotable').DataTable().ajax.url('/api/penjualan/lacak/data/no_so/' + data).load();
                $('#noakn').addClass('hide');
                $('#noseri').addClass('hide');
                $('#nopo').addClass('hide');
                $('#noso').removeClass('hide');
                $('#nosj').addClass('hide');
            } else if ($('.pilih_data').val() == "no_sj") {
                $('#nosj').removeClass('hide');
                $('#noseri').addClass('hide');
                $('#nopo').addClass('hide');
                $('#noso').addClass('hide');
                $('#noakn').addClass('hide');
            }
            // $('#btncari').attr("disabled", true);
            // $('.pilih_data').attr("disabled", true);
            // $('#data').attr('disabled', true);
        });

        $('#btnbatal').on('click', function() {
            $('.pilih_data').prop('selectedIndex', -1);
            $('#data').val('');
            $('#data').removeAttr('disabled');
            $('#pilih_data').attr('disabled', true);
            $('#btncari').attr('disabled', true);
            $('#noseri').addClass('hide');
            $('#nopo').addClass('hide');
            $('#noso').addClass('hide');
        });
    })
</script>
@stop