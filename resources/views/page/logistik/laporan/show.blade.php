@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Laporan</h1>
@stop

@section('adminlte_css')
<style>
    .filter {
        margin: 5px;
    }

    .hide {
        display: none !important;
    }
</style>
@stop

@section('content')
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary">
                    <div class="card-title">Pencarian</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- <form method="POST" action="/api/laporan/create"> -->
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label for="jasa_pengiriman" class="col-form-label col-5" style="text-align: right">Ekspedisi</label>
                                    <div class="col-5 col-form-label">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jasa_pengiriman" id="jasa_pengiriman1" value="ekspedisi" />
                                            <label class="form-check-label" for="jasa_pengiriman1">Ekspedisi</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jasa_pengiriman" id="jasa_pengiriman2" value="nonekspedisi" />
                                            <label class="form-check-label" for="jasa_pengiriman2">Non Ekspedisi</label>
                                        </div>
                                        <div class="feedback" id="msgjasa_pengiriman">
                                            <small class="text-muted">Jasa Pengiriman boleh dikosongi</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" id="ekspedisi_id">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Ekspedisi</label>
                                    <div class="col-4">
                                        <select class="select2 select-info form-control ekspedisi_id" name="ekspedisi_id" id="ekspedisi_id">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tanggal_mulai" class="col-form-label col-5" style="text-align: right">Tanggal Mulai</label>
                                    <div class="col-2">
                                        <input type="date" class="form-control col-form-label @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" readonly />
                                        <div class="invalid-feedback" id="msgtanggal_mulai">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tanggal_akhir" class="col-form-label col-5" style="text-align: right">Tanggal Akhir</label>
                                    <div class="col-2">
                                        <input type="date" class="form-control col-form-label @error('tanggal_akhir') is-invalid @enderror" id="tanggal_akhir" name="tanggal_akhir" readonly />
                                        <div class="invalid-feedback" id="msgtanggal_akhir">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-5"></div>
                                    <div class="col-4">
                                        <span class="float-right filter"><button type="button" class="btn btn-success" id="btncetak" disabled>Cetak</button></span>
                                        <span class="float-right filter"><button type="button" class="btn btn-outline-danger" id="btnbatal">Batal</button></span>
                                    </div>
                                </div>
                            </div>
                            <!-- </form> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row hide" id="semuaform">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Laporan Pengiriman</h5>
                    <div class="table-responsive">
                        <table class="table table-hover" id="showtable">
                            <thead style="text-align: center;">
                                <tr>
                                    <th>No</th>
                                    <th>No SO</th>
                                    <th>No SJ</th>
                                    <th>No Invoice</th>
                                    <th>No Resi</th>
                                    <th>Customer</th>
                                    <th>Alamat</th>
                                    <th>Provinsi</th>
                                    <th>Telepon</th>
                                    <th>Jasa Ekspedisi</th>
                                    <th>Tanggal Kirim</th>
                                    <th>Tanggal Sampai</th>
                                    <th>Nama Produk</th>
                                    <th>Qty</th>
                                    <th>Ongkos Kirim</th>
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
@endsection

@section('adminlte_js')
<script>
    $(function() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd;
        console.log(today);
        $("#tanggal_mulai").attr("max", today);
        $("#tanggal_akhir").attr("max", today);

        $('#showtable').DataTable({
            processing: true,
            dom: 'Bfrtip',
            serverSide: false,
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            buttons: [{
                    extend: 'excel',
                    title: 'Laporan Penjualan',
                    text: '<i class="far fa-file-excel"></i> Export',
                    className: "btn btn-info"
                },
                {
                    extend: 'print',
                    title: 'Laporan Penjualan',
                    text: '<i class="fas fa-print"></i> Cetak',
                    className: "btn btn-primary"
                },
            ],
        });

        $('.customer_id').on('keyup change', function() {
            if ($(this).val() != "") {
                $('input[type="radio"][name="penjualan"]').removeAttr('disabled');
                if ($('input[type="radio"][name="penjualan"]').val() != undefined && $('#tanggal_mulai').val() != "" && $('#tanggal_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('input[type="radio"][name="pengiriman"]').on('change', function() {
            if ($(this).val() != "") {
                $('#tanggal_mulai').removeAttr('readonly');
                if ($('#tanggal_mulai').val() != "" && $('#tanggal_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('#tanggal_mulai').on('keyup change', function() {
            $("#tanggal_akhir").val("");
            $("#btncetak").removeAttr('disabled');
            if ($(this).val() != "") {
                $('#tanggal_akhir').removeAttr('readonly');
                $("#tanggal_akhir").attr("min", $(this).val())
                if ($('input[type="radio"][name="penjualan"]').val() != undefined && $('#tanggal_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {

                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#tanggal_akhir").val("");
                $("#btncetak").attr('disabled', true);
            }
        });

        $('#tanggal_akhir').on('keyup change', function() {
            if ($(this).val() != "") {
                if ($('#tanggal_mulai').val() != "" && $('input[type="radio"][name="penjualan"]').val() != undefined) {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('.customer_id').select2({
            allowClear: true,
            placeholder: 'Pilih Data',
            ajax: {
                tags: [],
                dataType: 'json',
                delay: 250,
                type: 'GET',
                url: '/api/customer/select/',
                processResults: function(data) {
                    console.log(data);
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.nama
                            };
                        })
                    };
                },
            }
        });

        $("#btnbatal").on('click', function() {
            $("#btncetak").attr('disabled', true);
            $(".customer_id").val(null).trigger("change");
            $('input[type="radio"][name="penjualan"]').prop('checked', false);
            $('#tanggal_mulai').val('');
            $('#tanggal_mulai').attr('readonly', true);
            $('#tanggal_akhir').val('');
            $('#tanggal_akhir').attr('readonly', true);
            $('#semuaform').addClass('hide');
            $('#ekatalogform').addClass('hide');
            $('#spaform').addClass('hide');
            $('#spbform').addClass('hide');
        });

        $('#btncetak').on('click', function() {
            if ($('input[type="radio"][name="penjualan"]:checked').val() == "semua") {
                $('#semuaform').removeClass('hide');
                $('#ekatalogform').addClass('hide');
                $('#spaform').addClass('hide');
                $('#spbform').addClass('hide');
            } else if ($('input[type="radio"][name="penjualan"]:checked').val() == "ekatalog") {
                $('#semuaform').addClass('hide');
                $('#ekatalogform').removeClass('hide');
                $('#spaform').addClass('hide');
                $('#spbform').addClass('hide');
            } else if ($('input[type="radio"][name="penjualan"]:checked').val() == "spa") {
                $('#semuaform').addClass('hide');
                $('#ekatalogform').addClass('hide');
                $('#spaform').removeClass('hide');
                $('#spbform').addClass('hide');
            } else if ($('input[type="radio"][name="penjualan"]:checked').val() == "spb") {
                $('#semuaform').addClass('hide');
                $('#ekatalogform').addClass('hide');
                $('#spaform').addClass('hide');
                $('#spbform').removeClass('hide');
            }
        })
    });
</script>
@endsection