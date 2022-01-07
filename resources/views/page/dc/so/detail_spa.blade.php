@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Sales Order</h1>
@stop

@section('adminlte_css')
<style>
    .urgent {
        color: #dc3545;
        font-weight: 600;
    }

    .info {
        color: #FFC700;
        font-weight: 600;
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
        float: right;
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
        max-width: 30ch;
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
                    <div class="card-body">
                        <h5>Info</h5>
                        <div class="row">
                            <div class="col-4">
                                <div class="filter">
                                    <div><small class="text-muted">Customer</small></div>
                                    <div><b>{{$data->spa->customer->nama}}</b></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="filter">
                                    <div><small class="text-muted">No SO</small></div>
                                    <div><b>{{$data->so}}</b></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="filter">
                                    <div><small class="text-muted">Status</small></div>
                                    <div><b>{!!$status!!}</b></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-7 col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Daftar Barang</h5>
                    <div class="table-responsive">
                        <table class="table table-hover" style="text-align: center; width:100%;" id="showtable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th class="nowrap-text">Tgl Surat Jalan</th>
                                    <th>Nama</th>
                                    <th>No AKD</th>
                                    <th>Bulan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr>
                                    <td class="nowrap-text">1</td>
                                    <td class="nowrap-text">19-10-2021</td>
                                    <td class="nowrap-text minimizechar">B-ULTRASOUND DIAGNOSTIC SYSTEM CMS-600 PLUS PRINTER TROLLEY UPS</td>
                                    <td class="nowrap-text">21102900256</td>
                                    <td class="nowrap-text">X</td>
                                    <td class="nowrap-text">5</td>
                                    <td class="nowrap-text"><span class="badge green-text">Tersedia</span></td>
                                    <td class="nowrap-text"></a>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="noserishow dropdown-item" type="button">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </a>
                                            <a href="{{route('dc.coo.pdf')}}">
                                                <button class="dropdown-item" type="button">
                                                    <i class="fas fa-file"></i>
                                                    Laporan PDF
                                                </button>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>21-10-2021</td>
                                    <td class="minimizechar">PULSE OXIMETER/OXYMETER FOX-2</td>
                                    <td>20502210102</td>
                                    <td></td>
                                    <td>2</td>
                                    <td><span class="badge red-text">Belum Tersedia</span></td>
                                    <td class="nowrap-text"></a>
                                        <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="noserishow dropdown-item" type="button">
                                                <i class="fas fa-eye"></i>
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-12 hide" id="noseri">
            <div class="card">
                <div class="card-body">
                    <div>
                        <h5 style="display: inline;" class="filter">No Seri</h5>
                        <span class="float-right filter">
                            <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-id="">
                                <button class="btn btn-warning" id="cekbrg" disabled="true">
                                    <i class="fas fa-pencil-alt"></i> COO
                                </button>
                            </a>
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" style="text-align: center; width:100%;" id="noseritable">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="check_all" id="check_all" name="check_all" />
                                            <label class="form-check-label" for="check_all">
                                            </label>
                                        </div>
                                    </th>
                                    <th>No COO</th>
                                    <th>No Seri</th>
                                    <th>Diketahui Oleh</th>
                                    <th>Laporan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="1" name="no_seri_id[]" />
                                            <label class="form-check-label" for="1">
                                            </label>
                                        </div>
                                    </td>
                                    <td>-</td>
                                    <td>FX012183103841</td>
                                    <td>Kusmardiana Rahayu <div><small>Q.A. Manager</small></div>
                                    </td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="2" id="2" name="no_seri_id[]" />
                                            <label class="form-check-label" for="2">
                                            </label>
                                        </div>
                                    </td>
                                    <td>-</td>
                                    <td>FX012183103826</td>
                                    <td>Kusmardiana Rahayu <div><small>Q.A. Manager</small></div>
                                    </td>
                                    <td>-</td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="createmodal" role="dialog" aria-labelledby="createmodal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Tambah COO</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="create">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editmodal" role="dialog" aria-labelledby="editmodal" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content" style="margin: 10px">
                    <div class="modal-header bg-warning">
                        <h4 class="modal-title">COO</h4>
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
</section>
@endsection

@section('adminlte_js')
<script>
    $(function() {
        $('#showtable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'datatype': 'JSON',
                'url': '/api/dc/so/detail/{{$data->id}}',
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
                }, {
                    data: 'tgl_surat',
                },
                {
                    data: 'nama_paket',
                },
                {
                    data: 'no_akd',
                }, {
                    data: 'bulan',
                }, {
                    data: 'status',
                }, {
                    data: 'button',
                    orderable: false,
                    searchable: false
                }
            ]
        });


        $('#noseritable').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                'type': 'POST',
                'datatype': 'JSON',
                'url': '/api/dc/so/detail/seri/' + 0,

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
                data: 'nocoo',

            }, {
                data: 'noseri',

            }, {
                data: 'diket',

            }, {
                data: 'laporan',

            }]
        });

        function listnoseri(seri_id, data) {

            $('#listnoseri').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/dc/so/detail/seri/select/' + seri_id + '/' + data,
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
                }, {
                    data: 'noseri'
                }]
            });
        }

        var checkedAry = [];
        $('#noseritable').on('click', 'input[name="check_all"]', function() {
            if ($('input[name="check_all"]:checked').length > 0) {
                $('#cekbrg').prop('disabled', false);
                $('.nosericheck').prop('checked', true);
                checkedAry = []
                checkedAry.push('0');
            } else if ($('input[name="check_all"]:checked').length <= 0) {
                $('.nosericheck').prop('checked', false);
                $('#cekbrg').prop('disabled', true);
            }
        });

        $('#noseritable ').on('click', '.nosericheck', function() {
            $('#check_all').prop('checked', false);
            if ($('.nosericheck:checked').length > 0) {
                $('#cekbrg').prop('disabled', false);
                checkedAry = [];
                $.each($(".nosericheck:checked"), function() {
                    checkedAry.push($(this).closest('tr').find('.nosericheck').attr('data-id'));
                });
            } else if ($('.nosericheck:checked').length <= 0) {
                $('#cekbrg').prop('disabled', true);
            }
        });

        $('#showtable').on('click', '.noserishow', function() {
            var data = $(this).attr('data-id');
            $('#showtable').find('tr').removeClass('bgcolor');
            $(this).closest('tr').addClass('bgcolor');
            $('#noseri').removeClass('hide');
            $('#noseritable').DataTable().ajax.url('/api/dc/so/detail/seri/' + data).load();
            //  console.log(data);
        });

        $(document).on('submit', '#form-create-coo', function(e) {
            e.preventDefault();
            var action = $(this).attr('action');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: action,
                data: $('#form-create-coo').serialize(),
                success: function(response) {
                    //   alert(response);
                    if (response['data'] == "success") {
                        swal.fire(
                            'Berhasil',
                            'Berhasil melakukan Penambahan Data Pengujian',
                            'success'
                        ).then(function() {
                            location.reload();
                        });
                        $("#editmodal").modal('hide');
                        //$('#noseritable').DataTable().ajax.reload();

                    } else if (response['data'] == "error") {
                        swal.fire(
                            'Gagal',
                            'Gagal melakukan Penambahan Data Pengujian',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    alert($('#form-create-coo').serialize());
                }
            });
            return false;
        });

        $(document).on('click', '.createmodal', function(event) {
            event.preventDefault();
            var href = $(this).attr('data-attr');
            var id = $(this).data('id');
            $.ajax({
                url: "/dc/coo/create/" + id,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#createmodal').modal("show");
                    $('#create').html(result).show();
                    $('.bulan').select2({
                        placeholder: 'Pilih Bulan',
                        allowClear: true
                    });
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

        $(document).on('click', '.editmodal', function(event) {
            event.preventDefault();
            console.log(checkedAry);
            data = $(".nosericheck").data().value;
            var id = $(this).data('id');
            $.ajax({
                url: "/dc/coo/edit/" + checkedAry + "/" + data,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#editmodal').modal("show");
                    $('#edit').html(result).show();
                    $('.bulan_edit').select2({
                        placeholder: 'Pilih Bulan',
                        allowClear: true
                    });
                    listnoseri(checkedAry, data);
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

        $(document).on('keyup change', 'select[name="bulan"]', function() {
            if ($(this).val() != "" && (($('input[type="radio"][name="diketahui"]:checked').val() == "spa") || ($('#jabatan').val() != "" && $('#nama').val() != ""))) {
                $('#btnsimpan').removeClass('disabled');
            } else {
                $('#btnsimpan').addClass('disabled');
            }
        });

        $(document).on('change', 'input[type="radio"][name="diketahui"]', function() {
            $('#jabatan').val("");
            $('#nama').val("");
            console.log($(this).val());
            if ($(this).val() != "custom") {
                $('#jabatan_label').addClass('hide');
                $('#nama_label').addClass('hide');
                if ($('select[name="bulan"]').val() != "") {
                    $('#btnsimpan').removeClass('disabled');
                } else {
                    $('#btnsimpan').addClass('disabled');
                }
            } else {
                $('#jabatan_label').removeClass('hide');
                $('#nama_label').removeClass('hide');
                if ($('select[name="bulan"]').val() != "" && $('#jabatan').val() != "" && $('#nama').val() != "") {
                    $('#btnsimpan').removeClass('disabled');
                } else {
                    $('#btnsimpan').addClass('disabled');
                }
            }
        });

        $(document).on('keyup change', 'input[name="nama"]', function() {
            if ($(this).val() != "" && $('#jabatan').val() != "" && $('select[name="bulan"]').val() != "") {
                $('#btnsimpan').removeClass('disabled');
            } else {
                $('#btnsimpan').addClass('disabled');
            }
        });

        $(document).on('keyup change', 'input[name="jabatan"]', function() {
            if ($(this).val() != "" && $('#nama').val() != "" && $('select[name="bulan"]').val() != "") {
                $('#btnsimpan').removeClass('disabled');
            } else {
                $('#btnsimpan').addClass('disabled');
            }
        });

    })
</script>

@endsection