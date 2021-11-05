@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Sales Order</h1>
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
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4>Info Penjualan</h4>
                <div class="row">
                    <div class="col-5">
                        <div class="margin">
                            <div><small class="text-muted">Distributor & Instansi</small></div>
                        </div>
                        <div class="margin">
                            <b id="distributor">CIPTAJAYA RETAIL INDONESIA PT </b><small>(Distributor)</small>
                        </div>
                        <div class="margin">
                            <div><b id="no_akn">DINAS KESEHATAN PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA</b></div>
                            <small>(Pemerintah Daerah Provinsi Kalimantan Selatan)</small>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="margin">
                            <div><small class="text-muted">No AKN</small></div>
                            <div><b id="no_akn">AK1-P2110-4365736</b></div>
                        </div>
                        <div class="margin">
                            <div><small class="text-muted">No SO</small></div>
                            <div><b id="no_so">SO/EKAT/09/21/00001 </b></div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="margin">
                            <div><small class="text-muted">No PO</small></div>
                            <div><b id="no_so">PO/ON/09/21/00001</b></div>
                        </div>
                        <div class="margin">
                            <div><small class="text-muted">Batas Pengiriman</small></div>
                            <div><b id="no_so">29-11-2020</b></div>
                            <div><small class="warning"><i class="fas fa-exclamation-circle"></i> Batas Pengiriman sisa 3 hari lagi</small></div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="margin">
                            <div><small class="text-muted">Status</small></div>
                            <div><span class="badge yellow-text">Sebagian Diperiksa</span></div>
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
                <div class="row">
                    <div class="col-12">
                        <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-id="">
                            <span class="float-right filter">
                                <button class="btn btn-primary" type="button" id="kirim_produk" disabled><i class="fas fa-paper-plane"></i> Pengiriman</button>
                            </span>
                        </a>
                        <span class="float-right filter">
                            <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <div class="dropdown-menu">
                                <div class="px-3 py-3">
                                    <div class="form-group">
                                        <label for="jenis_penjualan">Status</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="selesai" id="status1" name="status" />
                                            <label class="form-check-label" for="status1">
                                                Sudah Dikirim
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="sebagian" id="status2" name="status" />
                                            <label class="form-check-label" for="status2">
                                                Sebagian Dikirim
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="belum" id="status3" name="status" />
                                            <label class="form-check-label" for="status3">
                                                Belum Dikirim
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <span class="float-right">
                                            <button class="btn btn-primary">
                                                Cari
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </span>

                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table" style="text-align:center;" id="showtable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tanggal Kirim</th>
                                        <th>Nama Produk</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox" name="detail_produk_id" id="detail_produk_id" class="detail_produk_id" disabled></td>
                                        <td>26-10-2021</td>
                                        <td>ELITECH MINI/MEDICAL COMPRESSOR NEBULIZER PROMIST 2</td>
                                        <td>2</td>
                                        <td><span class="badge green-text">Sudah Dikirim</span></td>
                                        <td><a type="button" class="noserishow" data-id="1"><i class="fas fa-search"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" name="detail_produk_id" id="detail_produk_id" class="detail_produk_id" disabled></td>
                                        <td>26-10-2021</td>
                                        <td>ELITECH ULTRASONIC POCKET DOPPLER</td>
                                        <td>5</td>
                                        <td><span class="badge green-text">Sudah Dikirim</span></td>
                                        <td><a type="button" class="noserishow" data-id="2"><i class="fas fa-search"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" name="detail_produk_id" id="detail_produk_id" class="detail_produk_id available"></td>
                                        <td>-</td>
                                        <td>MTB 2 MTR</td>
                                        <td>10</td>
                                        <td><span class="badge red-text">Belum Dikirim</span></td>
                                        <td><a type="button" class="noserishow" data-id="3"><i class="fas fa-search"></i></a></td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox" name="detail_produk_id" id="detail_produk_id" class="detail_produk_id available"></td>
                                        <td>-</td>
                                        <td>CENTRAL MONITOR PM-9000+ + PC + INSTALASI</td>
                                        <td>1</td>
                                        <td><span class="badge red-text">Belum Dikirim</span></td>
                                        <td><a type="button" class="noserishow" data-id="3"><i class="fas fa-search"></i></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="margin: 10px">
                <div class="modal-header bg-warning">
                    <h4 class="modal-title">Edit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="edit">

                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('adminlte_js')
<script>
    $(function() {
        function ekspedisi_select() {
            $('.ekspedisi_id').select2();
        }

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
            if ($(this).val() != "ekspedisi") {
                $('#ekspedisi').removeClass('hide');
                $('#nonekspedisi').addClass('hide');
                $('#no_polisi').val("");
                $('#nama_pengirim').val("");
                $('.ekspedisi_id').val("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }

            } else if ($(this).val() != "nonekspedisi") {
                $('#ekspedisi').addClass('hide');
                $('#nonekspedisi').removeClass('hide');
                $('#no_polisi').val("");
                $('#nama_pengirim').val("");
                $('.ekspedisi_id').val("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            }
        });

        $(document).on('change keyup', '#no_sj', function(event) {
            if ($(this).val() != "") {
                $('#no_sj').removeClass('is-invalid');
                $('#msgno_sj').text("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#no_sj').addClass('is-invalid');
                $('#msgno_sj').text("No Surat Jalan harus diisi");
                $('#btnsimpan').attr('disabled', true);
            }
        });

        $(document).on('change keyup', '#tgl_kirim', function(event) {
            if ($(this).val() != "") {
                $('#tgl_kirim').removeClass('is-invalid');
                $('#msgtgl_kirim').text("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#tgl_kirim').addClass('is-invalid');
                $('#msgtgl_kirim').text("Tanggal Kirim harus diisi");
                $('#btnsimpan').attr('disabled', true);
            }
        });

        $(document).on('change keyup', '#nama_pengirim', function(event) {
            if ($(this).val() != "") {
                $('#nama_pengirim').removeClass('is-invalid');
                $('#msgnama_pengirim').text("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#nama_pengirim').addClass('is-invalid');
                $('#msgnama_pengirim').text("Nama Pengirim harus diisi");
                $('#btnsimpan').attr('disabled', true);
            }
        });

        $(document).on('change keyup', '#no_polisi', function(event) {
            if ($(this).val() != "") {
                $('#no_polisi').removeClass('is-invalid');
                $('#msgno_polisi').text("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#no_polisi').addClass('is-invalid');
                $('#msgno_polisi').text("No Kendaraan harus diisi");
                $('#btnsimpan').attr('disabled', true);
            }
        });

        $(document).on('change keyup', '.ekspedisi_id', function(event) {
            if ($(this).val() != "") {
                $('#ekspedisi_id').removeClass('is-invalid');
                $('#msgekspedisi_id').text("");
                if ($('#no_sj').val() != "" && $('#tgl_mulai').val() != "" && (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val("") != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#ekspedisi_id').addClass('is-invalid');
                $('#msgekspedisi_id').text("No Kendaraan harus diisi");
                $('#btnsimpan').attr('disabled', true);
            }
        });
    })
</script>
@stop