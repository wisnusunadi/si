@extends('adminlte.page')

@section('title', 'ERP')

@section('adminlte_css')
<style>
    li.list-group-item {
        border: 0 none;
    }

    .smtxt {
        font-size: 13px;
    }

    #showtable {
        text-align: center;
        white-space: nowrap;
    }

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

    .minimizechar {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 25ch;
    }

    .dropdown-toggle:hover {
        color: #4682B4;
    }

    .dropdown-toggle:active {
        color: #C0C0C0;
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

@section('content_header')
<h1 class="m-0 text-dark">Pengiriman</h1>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <span class="float-right filter">
                                    <button class="btn btn-outline-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <div class="dropdown-menu">
                                        <div class="px-3 py-3">
                                            <div class="form-group">
                                                <label for="jenis_penjualan">No Resi</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="semua" id="no_resi1" name="no_resi" />
                                                    <label class="form-check-label" for="no_resi1">
                                                        Semua
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="belum_tersedia" id="no_resi2" name="no_resi" />
                                                    <label class="form-check-label" for="no_resi2">
                                                        Belum Tersedia
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="tersedia" id="no_resi3" name="no_resi" />
                                                    <label class="form-check-label" for="no_resi3">
                                                        Tersedia
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="jenis_penjualan">Status Pengiriman</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="semua" id="status_pengiriman1" name="status_pengiriman" />
                                                    <label class="form-check-label" for="status_pengiriman1">
                                                        Semua
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="draft_pengiriman" id="status_pengiriman2" name="status_pengiriman" />
                                                    <label class="form-check-label" for="status_pengiriman2">
                                                        Draft Pengiriman
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" value="dalam_pengiriman" id="status_pengiriman3" name="status_pengiriman" />
                                                    <label class="form-check-label" for="status_pengiriman3">
                                                        Dalam Pengiriman
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
                                    <table class="table" id="showtable" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No SO</th>
                                                <th>No SJ</th>
                                                <th>Ekspedisi / Pengirim</th>
                                                <th>Tanggal Kirim</th>
                                                <th>Nama Customer</th>
                                                <th>Provinsi</th>
                                                <th>Status</th>
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
    </div>
</section>
@stop
@section('adminlte_js')
<script>
    $(function() {
        $(document).on('submit', '#form-pengiriman-update', function(e) {
            e.preventDefault();
            var action = $(this).attr('action');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: action,
                data: $('#form-pengiriman-update').serialize(),
                success: function(response) {
                    if (response['data'] == "success") {
                        swal.fire(
                            'Berhasil',
                            'Berhasil melakukan ubah data Pengiriman',
                            'success'
                        );
                        $("#editmodal").modal('hide');
                        $('#showtable').DataTable().ajax.reload();
                    } else if (response['data'] == "error") {
                        swal.fire(
                            'Gagal',
                            'Gagal melakukan ubah data Pengiriman',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    alert($('#form-customer-update').serialize());
                }
            });
            return false;
        });

        $(document).on('click', '.editmodal', function() {
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            var logistik_id = $(this).attr('data-id');
            console.log(href);
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                success: function(result) {
                    $('#editmodal').modal("show");
                    $('#edit').html(result).show();
                    ekspedisi_select();
                    barang_detail(logistik_id);
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

        $(document).on('click', '#ubahstatus', function() {
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            $.ajax({
                type: "GET",
                url: "/api/logistik/pengiriman/status/update/" + id + "/" + status,
                success: function(response) {
                    if (response['data'] == "success") {
                        swal.fire(
                            'Berhasil',
                            'Berhasil melakukan ubah data Pengiriman',
                            'success'
                        );
                        $("#editmodal").modal('hide');
                        $('#showtable').DataTable().ajax.reload();
                    } else if (response['data'] == "error") {
                        swal.fire(
                            'Gagal',
                            'Gagal melakukan ubah data Pengiriman',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    alert($('#form-customer-update').serialize());
                }
            });
        });

        $(document).on('change', 'input[type="radio"][name="pengiriman"]', function(event) {
            $('#ekspedisi_id').removeClass('is-invalid');
            $('#msgekspedisi_id').text("");
            $('#nama_pengirim').removeClass('is-invalid');
            $('#msgnama_pengirim').text("");
            $('#no_polisi').removeClass('is-invalid');
            $('#msgno_polisi').text("");
            $('#btnsimpan').attr('disabled', true);
            if ($('input[type="radio"][name="pengiriman"]:checked').val() == "ekspedisi") {
                $('#ekspedisi').removeClass('hide');
                $('#nonekspedisi').addClass('hide');
                $('#no_polisi').val("");
                $('#nama_pengirim').val("");
                if ($('.ekspedisi_id').val() != "" && $('.ekspedisi_id').val() != null) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            } else if ($('input[type="radio"][name="pengiriman"]:checked').val() == "nonekspedisi") {
                $('#ekspedisi').addClass('hide');
                $('#nonekspedisi').removeClass('hide');
                $('.ekspedisi_id').val(null).trigger('change');
                if ($('#nama_pengirim').val() != "") {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            }
        });

        $(document).on('change keyup', '#no_resi', function(event) {
            if ($(this).val() != "") {
                $('#no_resi').removeClass('is-invalid');
                $('#msgno_resi').text("");
                $('#btnsimpan').removeAttr('disabled');
            } else if ($(this).val() == "") {
                $('#no_resi').addClass('is-invalid');
                $('#msgno_resi').text("No Resi harus diisi");
                $('#btnsimpan').attr('disabled', true);
            }
        });

        $(document).on('change keyup', '#nama_pengirim', function(event) {
            if ($(this).val() != "") {
                $('#nama_pengirim').removeClass('is-invalid');
                $('#msgnama_pengirim').text("");
                if ($('#no_invoice').val() != "" && $('#tgl_mulai').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val() != "")) {
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

        // $(document).on('change keyup', '#no_polisi', function(event) {
        //     if ($(this).val() != "") {
        //         $('#no_polisi').removeClass('is-invalid');
        //         $('#msgno_polisi').text("");
        //         if (($('#no_polisi').val() != "" && $('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "") {
        //             $('#btnsimpan').removeAttr('disabled');
        //         } else {
        //             $('#btnsimpan').attr('disabled', true);
        //         }
        //     } else if ($(this).val() == "") {
        //         $('#no_polisi').addClass('is-invalid');
        //         $('#msgno_polisi').text("No Kendaraan harus diisi");
        //         $('#btnsimpan').attr('disabled', true);
        //     }
        // });

        $(document).on('change keyup', '.ekspedisi_id', function(event) {
            if ($(this).val() != "") {
                $('#ekspedisi_id').removeClass('is-invalid');
                $('#msgekspedisi_id').text("");
                if (($('#nama_pengirim').val() != "") || $('#ekspedisi_id').val() != "") {
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

        function ekspedisi_select() {
            $('.ekspedisi_id').select2({
                placeholder: "Pilih Ekspedisi",
                allowClear: true,
                ajax: {
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Ekspedisi",
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/logistik/ekspedisi/select',
                    data: function(params) {
                        return {
                            term: params.term
                        }
                    },
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
        }

        function barang_detail(id) {
            console.log('id ' + id);
            $('#barangtable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/logistik/pengiriman/data/' + id,
                    'type': 'GET',
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
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
                        data: 'nama_produk'
                    },
                    {
                        data: 'jumlah',
                    },
                ]
            });
        }
        var showtable = $('#showtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/logistik/pengiriman/data',
                'type': 'GET',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
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
                    data: 'so'
                },
                {
                    data: 'sj',

                },
                {
                    data: 'ekspedisi',

                },
                {
                    data: 'tgl_kirim',

                }, {
                    data: 'nama_customer',

                }, {
                    data: 'provinsi',

                }, {
                    data: 'status',

                }, {
                    data: 'button',

                }
            ]
        });

    });
</script>
@stop