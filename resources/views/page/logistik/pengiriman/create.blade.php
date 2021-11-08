@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Pengiriman</h1>
@stop

@section('adminlte_css')
<style>
    .ok {
        color: green;
        font-weight: 600;
    }

    .nok {
        color: #dc3545;
        font-weight: 600;
    }

    .warning {
        color: #FFC700;
        font-weight: 600;
    }

    .list-group-item {
        border: 0 none;
    }

    .align-right {
        text-align: right;
    }

    .align-center {
        text-align: center;
    }

    .margin {
        margin-bottom: 5px;
    }

    .filter {
        margin: 5px;
    }

    .hide {
        display: none !important;
    }

    .bgcolor {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }

    .fa-search:hover {
        color: #ADD8E6;
    }

    .fa-search:active {
        color: #808080;
    }

    .nowrap-text {
        white-space: nowrap;
    }
</style>
@stop
@section('content')
<div class="content">
    <div class="row d-flex justify-content-center">

        <div class="col-11">
            <div class="row d-flex justify-content-center">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-horizontal">
                                <h5>Data Pengiriman</h5>

                                <div class="form-group row">
                                    <label class="col-form-label col-5 align-right" for="sales_order_id">No Sales Order</label>
                                    <div class="col-5">
                                        <select class="select2 select-info form-control sales_order_id" name="sales_order_id" id="sales_order_id" style="width: 100%;">
                                            <option value=""></option>
                                        </select>
                                        <div class="invalid-feedback" id="msgsales_order_id"></div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-5 align-right" for="no_sj">No Surat Jalan</label>
                                    <div class="col-4">
                                        <input type="text" class="form-control col-form-label" name="no_sj" id="no_sj">
                                        <div class="invalid-feedback" id="msgno_sj"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-5 align-right" for="tgl_kirim">Tanggal Pengiriman</label>
                                    <div class="col-3">
                                        <input type="date" class="form-control col-form-label" name="tgl_kirim" id="tgl_kirim">
                                        <div class="invalid-feedback" id="msgtgl_kirim"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5 align-right">Pengiriman</label>
                                    <div class="col-5 col-form-label">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pengiriman" id="pengiriman1" value="ekspedisi" />
                                            <label class="form-check-label" for="pengiriman1">Ekspedisi</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="pengiriman" id="pengiriman2" value="nonekspedisi" />
                                            <label class="form-check-label" for="pengiriman2">Non Ekspedisi</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row d-flex justify-content-center">
                                    <div class="card col-10 hide" id="ekspedisi">
                                        <div class="card-body">
                                            <h6><b>Ekspedisi</b></h6>
                                            <div class="form-group row">
                                                <label class="col-form-label col-5 align-right" for="ekspedisi_id">Jasa Pengiriman</label>
                                                <div class="col-5">
                                                    <select class="select2 select-info form-control ekspedisi_id" name="ekspedisi_id" id="ekspedisi_id" style="width: 100%;">
                                                        <option value=""></option>
                                                    </select>
                                                    <div class="invalid-feedback" id="msgekspedisi_id"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card col-10 hide" id="nonekspedisi">
                                        <div class="card-body">
                                            <h6><b>Non Ekspedisi</b></h6>
                                            <div class="form-group row">
                                                <label class="col-form-label col-5 align-right" for="nama_pengirim">Nama Pengirim</label>
                                                <div class="col-7">
                                                    <input type="text" class="form-control col-form-label" name="nama_pengirim" id="nama_pengirim">
                                                    <div class="invalid-feedback" id="msgnama_pengirim"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-5 align-right" for="no_polisi">No Kendaraan</label>
                                                <div class="col-7">
                                                    <input type="text" class="form-control col-form-label" name="no_polisi" id="no_polisi">
                                                    <div class="invalid-feedback" id="msgno_polisi"></div>
                                                </div>
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
                    <div class="card">
                        <div class="card-body">
                            <h5>Data Barang</h5>
                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-center" style="width:100%;" id="produktable">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Barang</th>
                                                    <th>Jumlah</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>MTB 2 MTR</td>
                                                    <td>10</td>
                                                    <td><a href="#" id="remove"><i class="fas fa-minus nok"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>CENTRAL MONITOR PM-9000+ + PC + INSTALASI</td>
                                                    <td>1</td>
                                                    <td><a href="#" id="remove"><i class="fas fa-minus nok"></i></a></td>
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
            <div class="row">
                <div class="col-6 float-left">
                    <a href="{{route('logistik.pengiriman.show')}}">
                        <button type="button" class="btn btn-danger">Batal</button>
                    </a>
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-info float-right" id="btntambah" disabled>Simpan</button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('adminlte_js')
<script>
    $(function() {
        function ekspedisi_select() {
            $('.ekspedisi_id').select2({
                placeholder: "Pilih Ekspedisi",
                allowClear: true,
            });
        }

        function no_so_select() {
            $('.sales_order_id').select2({
                placeholder: "Pilih Sales Order",
                allowClear: true,
            });
        }

        $("#produktable").on('click', '#remove', function(e) {
            $(this).closest('tr').remove();
            numberRowsPart($("#produktable"));
        });

        ekspedisi_select();
        no_so_select();

        var showtable = $('#showtable').DataTable({});

        $('#showtable').on('click', '.noserishow', function() {
            var data = $(this).attr('data-id');
            $('#showtable').find('tr').removeClass('bgcolor');
            $(this).closest('tr').addClass('bgcolor');
            $('#noseridetail').removeClass('hide');
            console.log(data);
        })

        function load_noseritable(id) {
            $('#noseritable').DataTable({});
        }

        $('.detail_produk_id').on('change', function() {
            if ($('.detail_produk_id:checked').length > 0) {
                $('#kirim_produk').removeAttr('disabled');
            } else if ($('.detail_produk_id:checked').length <= 0) {
                $('#kirim_produk').attr('disabled', true);
            }
        })

        $(document).on('click', '.editmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            $.ajax({
                url: "/logistik/so/create",
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#editmodal').modal("show");
                    $('#edit').html(result).show();
                    console.log(id);
                    ekspedisi_select();
                    // $("#editform").attr("action", href);
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

        $(document).on('change', 'input[type="radio"][name="pengiriman"]', function(event) {
            $('#ekspedisi_id').removeClass('is-invalid');
            $('#msgekspedisi_id').text("");
            $('#nama_pengirim').removeClass('is-invalid');
            $('#msgnama_pengirim').text("");
            $('#no_polisi').removeClass('is-invalid');
            $('#msgno_polisi').text("");
            if ($(this).val() == "ekspedisi") {
                $('#ekspedisi').removeClass('hide');
                $('#nonekspedisi').addClass('hide');
                $('#no_polisi').val("");
                $('#nama_pengirim').val("");
                $('.ekspedisi_id').val("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "")) {
                    $('#btntambah').removeAttr('disabled');
                } else {
                    $('#btntambah').attr('disabled', true);
                }

            } else if ($(this).val() == "nonekspedisi") {
                $('#ekspedisi').addClass('hide');
                $('#nonekspedisi').removeClass('hide');
                $('#no_polisi').val("");
                $('#nama_pengirim').val("");
                $('.ekspedisi_id').val("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "")) {
                    $('#btntambah').removeAttr('disabled');
                } else {
                    $('#btntambah').attr('disabled', true);
                }
            }
        });

        $(document).on('change keyup', '#no_sj', function(event) {
            if ($(this).val() != "") {
                $('#no_sj').removeClass('is-invalid');
                $('#msgno_sj').text("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "")) {
                    $('#btntambah').removeAttr('disabled');
                } else {
                    $('#btntambah').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#no_sj').addClass('is-invalid');
                $('#msgno_sj').text("No Surat Jalan harus diisi");
                $('#btntambah').attr('disabled', true);
            }
        });

        $(document).on('change keyup', '#tgl_kirim', function(event) {
            if ($(this).val() != "") {
                $('#tgl_kirim').removeClass('is-invalid');
                $('#msgtgl_kirim').text("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "")) {
                    $('#btntambah').removeAttr('disabled');
                } else {
                    $('#btntambah').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#tgl_kirim').addClass('is-invalid');
                $('#msgtgl_kirim').text("Tanggal Kirim harus diisi");
                $('#btntambah').attr('disabled', true);
            }
        });

        $(document).on('change keyup', '#nama_pengirim', function(event) {
            if ($(this).val() != "") {
                $('#nama_pengirim').removeClass('is-invalid');
                $('#msgnama_pengirim').text("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "")) {
                    $('#btntambah').removeAttr('disabled');
                } else {
                    $('#btntambah').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#nama_pengirim').addClass('is-invalid');
                $('#msgnama_pengirim').text("Nama Pengirim harus diisi");
                $('#btntambah').attr('disabled', true);
            }
        });

        $(document).on('change keyup', '#no_polisi', function(event) {
            if ($(this).val() != "") {
                $('#no_polisi').removeClass('is-invalid');
                $('#msgno_polisi').text("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "")) {
                    $('#btntambah').removeAttr('disabled');
                } else {
                    $('#btntambah').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#no_polisi').addClass('is-invalid');
                $('#msgno_polisi').text("No Kendaraan harus diisi");
                $('#btntambah').attr('disabled', true);
            }
        });

        $(document).on('change keyup', '.ekspedisi_id', function(event) {
            if ($(this).val() != "") {
                $('#ekspedisi_id').removeClass('is-invalid');
                $('#msgekspedisi_id').text("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val("") != "")) {
                    $('#btntambah').removeAttr('disabled');
                } else {
                    $('#btntambah').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#ekspedisi_id').addClass('is-invalid');
                $('#msgekspedisi_id').text("No Kendaraan harus diisi");
                $('#btntambah').attr('disabled', true);
            }
        });
    })
</script>
@stop