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
                @foreach($data as $d)
                <div class="row">
                    <div class="col-5">
                        <div class="margin">
                            <div><small class="text-muted">Distributor & Instansi</small></div>
                        </div>
                        <div class="margin">
                            <b id="distributor">{{$d->customer->nama}}</b><small> (Distributor)</small>
                        </div>
                        <div class="margin">
                            <div><b id="no_akn">{{$d->satuan}}</b></div>
                            <small>({{$d->instansi}})</small>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="margin">
                            <div><small class="text-muted">No AKN</small></div>
                            <div><b id="no_akn">{{$d->no_paket}}</b></div>
                        </div>
                        <div class="margin">
                            <div><small class="text-muted">No SO</small></div>
                            <div><b id="no_so">{{$d->pesanan->so}}</b></div>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="margin">
                            <div><small class="text-muted">No PO</small></div>
                            <div><b id="no_so">{{$d->pesanan->no_po}}</b></div>
                        </div>
                        <div class="margin">
                            <div><small class="text-muted">Batas Pengiriman</small></div>
                            <div class="urgent"><b>{!!$param!!}</b></div>
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="margin">
                            <div><small class="text-muted">Status</small></div>
                            <div>{!!$status!!}</div>
                        </div>
                    </div>
                </div>
                @endforeach
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
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="check_all" id="check_all" name="check_all" />
                                                <label class="form-check-label" for="check_all">
                                                </label>
                                            </div>
                                        </th>
                                        <th>No</th>
                                        <th>Tanggal Kirim</th>
                                        <th>Pengirim / Ekspedisi</th>
                                        <th>Nama Produk</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr>
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
                                    </tr> -->
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
                <div class="modal-header bg-info">
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
        var showtable = $('#showtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                'url': '/api/logistik/so/data/detail/' + '{{$d->pesanan_id}}',
                'headers': {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            },
            language: {
                processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
            },
            columns: [{
                data: 'checkbox',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'no',

            }, {
                data: 'tgl_kirim',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'pengirim',

            }, {
                data: 'nama_produk',

            }, {
                data: 'jumlah',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'status',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }, {
                data: 'button',
                className: 'nowrap-text align-center',
                orderable: false,
                searchable: false
            }]

        });

        $('#showtable').on('click', '.noserishow', function() {
            var data = $(this).attr('data-id');
            $('#showtable').find('tr').removeClass('bgcolor');
            $(this).closest('tr').addClass('bgcolor');
            $('#noseridetail').removeClass('hide');
            $('input[name ="check_all"]').prop('checked', false);
            console.log(data);
        })

        $(document).on('submit', '#form-logistik-create', function(e) {
            e.preventDefault();
            var action = $(this).attr('action');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: action,
                data: $('#form-logistik-create').serialize(),
                success: function(response) {

                    if (response['data'] == "success") {
                        swal.fire(
                            'Berhasil',
                            'Berhasil melakukan edit data',
                            'success'
                        );
                        $("#editmodal").modal('hide');
                        $('#showtable').DataTable().ajax.reload();
                    } else if (response['data'] == "error") {
                        swal.fire(
                            'Gagal',
                            'Gagal melakukan edit data',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    alert($('#form-logistik-create').serialize());
                }
            });
            return false;
        });

        function detailpesanan(id, pesanan_id) {
            $('#detailpesanan').DataTable({

                processing: true,
                serverSide: true,
                ajax: {
                    'url': '/api/logistik/so/detail/select/' + id + '/' + pesanan_id,
                    'headers': {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama_produk',
                    },
                    {
                        data: 'jumlah',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        }



        var checkedAry = [];
        $('#showtable').on('click', 'input[name="check_all"]', function() {
            if ($('input[name="check_all"]:checked').length > 0) {
                $('.detail_produk_id').prop('checked', true);
                checkedAry = []
                checkedAry.push('0')
                $('#kirim_produk').removeAttr('disabled');
            } else if ($('input[name="check_all"]:checked').length <= 0) {
                $('.detail_produk_id').prop('checked', false);
                $('#kirim_produk').prop('disabled', true);
            }
        });


        $('#showtable').on('click', '.detail_produk_id', function() {
            if ($('.detail_produk_id:checked').length > 0) {
                $('#kirim_produk').removeAttr('disabled');
                checkedAry = [];
                $.each($(".detail_produk_id:checked"), function() {
                    checkedAry.push($(this).closest('tr').find('.detail_produk_id').attr('data-id'));
                });

            } else if ($('.detail_produk_id:checked').length <= 0) {
                $('#kirim_produk').attr('disabled', true);
            }
        })

        function ekspedisi_select() {
            $('.ekspedisi_id').select2({
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
            }).change(function() {
                if ($(this).val() != "") {
                    $('#ekspedisi_id').removeClass('is-invalid');
                    $('#msgekspedisi_id').text("");
                    if ($('#no_invoice').val() != "" && $('#tgl_mulai').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val() != "")) {
                        $('#btnsimpan').removeAttr('disabled');
                    } else {
                        $('#btnsimpan').attr('disabled', true);
                    }
                } else if ($(this).val() == "") {
                    $('#ekspedisi_id').addClass('is-invalid');
                    $('#msgekspedisi_id').text("No Kendaraan harus diisi");
                    $('#btnsimpan').attr('disabled', true);
                }
                var value = $(this).val();
                console.log(value);
            });
        }
        $(document).on('click', '.editmodal', function(event) {
            event.preventDefault();
            console.log(checkedAry);
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            var pesanan_id = '{{$d->pesanan_id}}';

            $.ajax({
                url: "/logistik/so/create/" + checkedAry + '/' + pesanan_id,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#editmodal').modal("show");
                    $('#edit').html(result).show();
                    detailpesanan(checkedAry, pesanan_id);
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

            if ($(this).val() == "ekspedisi") {
                $('#ekspedisi').removeClass('hide');
                $('#nonekspedisi').addClass('hide');
                $('#nama_pengirim').val("");
                if ($('#no_invoice').val() != "" && $('#tgl_mulai').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val() != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }

            } else if ($(this).val() == "nonekspedisi") {
                $('#ekspedisi').addClass('hide');
                $('#nonekspedisi').removeClass('hide');
                $('.ekspedisi_id').val("");
                if ($('#no_invoice').val() != "" && $('#tgl_mulai').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val() != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            }
        });

        $(document).on('change keyup', '#no_invoice', function(event) {
            if ($(this).val() != "") {
                $('#no_invoice').removeClass('is-invalid');
                $('#msgno_invoice').text("");
                if ($('#no_invoice').val() != "" && $('#tgl_mulai').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val() != "")) {
                    $('#btnsimpan').removeAttr('disabled');
                } else {
                    $('#btnsimpan').attr('disabled', true);
                }
            } else if ($(this).val() == "") {
                $('#no_invoice').addClass('is-invalid');
                $('#msgno_invoice').text("No Invoice harus diisi");
                $('#btnsimpan').attr('disabled', true);
            }
        });

        $(document).on('change keyup', '#tgl_kirim', function(event) {
            if ($(this).val() != "") {
                $('#tgl_kirim').removeClass('is-invalid');
                $('#msgtgl_kirim').text("");
                if ($('#no_invoice').val() != "" && $('#tgl_mulai').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val() != "")) {
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


        // $(document).on('change keyup', '.ekspedisi_id', function(event) {
        //     if ($(this).val() != "") {
        //         $('#ekspedisi_id').removeClass('is-invalid');
        //         $('#msgekspedisi_id').text("");
        //         if ($('#no_invoice').val() != "" && $('#tgl_mulai').val() != "" && ($('#nama_pengirim').val() != "" || $('#ekspedisi_id').val("") != "")) {
        //             $('#btnsimpan').removeAttr('disabled');
        //         } else {
        //             $('#btnsimpan').attr('disabled', true);
        //         }
        //     } else if ($(this).val() == "") {
        //         $('#ekspedisi_id').addClass('is-invalid');
        //         $('#msgekspedisi_id').text("No Kendaraan harus diisi");
        //         $('#btnsimpan').attr('disabled', true);
        //     }
        // });
    })
</script>
@stop