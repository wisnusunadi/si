@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Sales Order</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->divisi_id == '9')
                        <li class="breadcrumb-item"><a href="{{ route('dc.dashboard') }}">Beranda</a></li>
                    @elseif(Auth::user()->divisi_id == '9')
                        <li class="breadcrumb-item"><a href="{{ route('direksi.dashboard') }}">Beranda</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="{{ route('dc.so.show') }}">Sales Order DC</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
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

        .overflowy {
            max-height: 402px;
            overflow-y: scroll;
            box-shadow: none;
        }

        @media screen and (min-width: 1440px) {
            section {
                font-size: 14px;
            }

            .dropdown-item {
                font-size: 14px;
            }

            .cust {
                max-width: 40%;
            }
        }

        @media screen and (max-width: 1439px) {
            section {
                font-size: 12px;
            }

            .dropdown-item {
                font-size: 12px;
            }

            .cust {
                max-width: 40%;
            }
        }

        @media screen and (max-width: 992px) {
            .collapsable {
                display: none;
            }

            .form-check-inline {
                white-space: nowrap;
            }
        }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Info</h5>
                            <div class="row">
                                <div class="col-lg-11 col-md-12">
                                    <div class="row d-flex justify-content-between">
                                        <div class="p-2">
                                            <div class="filter">
                                                <div><small class="text-muted">Distributor</small></div>
                                                <div><b>{{ $data->ekatalog->customer->nama }}</b></div>
                                            </div>
                                            <div class="filter">
                                                <div><small class="text-muted">Instansi</small></div>
                                                <div><b>{{ $data->ekatalog->instansi }}</b></div>
                                            </div>
                                        </div>
                                        <div class="p-2">
                                            <div class="filter">
                                                <div><small class="text-muted">No SO</small></div>
                                                <div><b>{{ $data->so }}</b></div>
                                            </div>
                                            <div class="filter">
                                                <div><small class="text-muted">No AKN</small></div>
                                                <div><b>{{ $data->ekatalog->no_paket }}</b></div>
                                            </div>
                                        </div>
                                        <div class="p-2 cust">
                                            <div class="filter">
                                                <div><small class="text-muted">Status</small></div>
                                                <div><b>{!! $status !!}</b></div>
                                            </div>
                                            <div class="filter">
                                                <div><small class="text-muted">Deskripsi</small></div>
                                                <div><b>{{ $data->ekatalog->deskripsi }}</b></div>
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
            <div class="col-lg-7 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Daftar Barang</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" style="text-align: center; width:100%;" id="showtable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tgl Surat Jalan</th>
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

                                                                                                                <button class="dropdown-item" type="button">
                                                                                                                    <i class="fas fa-file"></i>
                                                                                                                    Laporan PDF
                                                                                                                </button>

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
            <div class="col-lg-5 col-md-6 hide" id="noseri">
                <div class="card">
                    <div class="card-body">


                        <div class="row">
                            <div class="col-12">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-belum-proses-tab" data-toggle="pill"
                                            href="#pills-belum-proses" role="tab" aria-controls="pills-belum-proses"
                                            aria-selected="true">Belum Tersedia</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-selesai-proses-tab" data-toggle="pill"
                                            href="#pills-selesai-proses" role="tab"
                                            aria-controls="pills-selesai-proses" aria-selected="false">Sudah Tersedia</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-belum-proses" role="tabpanel"
                                        aria-labelledby="pills-belum-proses-tab">
                                        @if (Auth::user()->divisi->kode == 'dc')
                                            <span class="float-right filter">
                                                <a data-toggle="modal" data-target="#createmodal" class="createmodal"
                                                    data-attr="" data-id="">
                                                    <button class="btn btn-info btn-sm" id="cekbrg" disabled="true">
                                                        <i class="fas fa-plus"></i> Tambah COO
                                                    </button>
                                                </a>
                                            </span>
                                        @endif
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped"
                                                style="text-align: center; width:100%;" id="noseri_belum_proses_table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="check_all" id="check_all" name="check_all" />
                                                                <label class="form-check-label" for="check_all">
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <th>No Seri</th>
                                                        <th>Tgl Kirim</th>
                                                        <th>Ttd Terima</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade show" id="pills-selesai-proses" role="tabpanel"
                                        aria-labelledby="pills-selesai-proses-tab">
                                        @if (Auth::user()->divisi->kode == 'dc')
                                            <span class="float-right filter">
                                                <a data-toggle="modal" data-target="#editmodal" class="editmodal"
                                                    data-attr="" data-id="">
                                                    <button class="btn btn-warning btn-sm" id="cekbrgedit"
                                                        disabled="true">
                                                        <i class="fas fa-pencil-alt"></i> Edit COO
                                                    </button>
                                                </a>
                                            </span>
                                        @endif
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped"
                                                style="text-align: center; width:100%;" id="noseri_selesai_proses_table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="check_all" id="check_all" name="check_all" />
                                                                <label class="form-check-label" for="check_all">
                                                                </label>
                                                            </div>
                                                        </th>
                                                        <th>No Seri</th>
                                                        <th>Tgl Kirim</th>
                                                        <th>Ttd Terima</th>
                                                        <th></th>
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
                </div>
            </div>
            <div class="modal fade" id="createmodal" role="dialog" aria-labelledby="createmodal" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-info">
                            <h4 class="modal-title">COO</h4>
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
            <div class="modal fade" id="tglkirim_modal" role="dialog" aria-labelledby="tglkirim_modal"
                aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content" style="margin: 10px">
                        <div class="modal-header bg-warning">
                            <h4 class="modal-title">Tgl Kirim COO</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="tglkirim_edit">

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalCetakCOO" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Cetak COO</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <small class="text-muted"><span class="text-danger">*</span>Silahkan cetak tiap 100 Nomor
                                Seri</small>
                            <table class="table tableNoSeriCetakCOO">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="checkall"></th>
                                        <th>Nomor Seri</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                            <button type="button" class="btn btn-primary cetakNoSeriCOO">Cetak</button>
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
            var divisi = "{{ Auth::user()->divisi->kode }}";
            $('#showtable').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/dc/so/detail/{{ $data->id }}',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                        className: 'collapsable nowrap-text',
                    },
                    {
                        data: 'nama_paket',
                    },
                    {
                        data: 'no_akd',
                        className: 'collapsable nowrap-text',
                    }, {
                        data: 'bulan',
                    }, {
                        data: 'status',
                        className: 'collapsable nowrap-text',
                    }, {
                        data: 'button',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            $('#noseri_belum_proses_table').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/dc/so/detail/seri/' + 0 + '/belum',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                        data: 'checkbox',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false,
                        visible: divisi == "dc" ? true : false
                    },
                    {
                        data: 'noseri',

                    },
                    {
                        data: 'tgl',
                        className: 'collapsable',
                    },
                    {
                        data: 'ket',
                        className: 'collapsable',
                    },
                    {
                        data: 'laporan',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#noseri_selesai_proses_table').DataTable({
                destroy: true,
                processing: true,
                serverSide: false,
                ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/dc/so/detail/seri/' + 0 + '/belum',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                language: {
                    processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                },
                columns: [{
                        data: 'checkbox',
                        className: 'nowrap-text align-center',
                        orderable: false,
                        searchable: false,
                        visible: divisi == "dc" ? true : false
                    },
                    {
                        data: 'noseri',

                    },
                    {
                        data: 'tgl',
                        className: 'collapsable',
                    },
                    {
                        data: 'ket',
                        className: 'collapsable',
                    },
                    {
                        data: 'laporan',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            function listnoseri(seri_id, data, table) {
                $('#' + table).DataTable({
                    destroy: true,
                    processing: true,
                    serverSide: false,
                    searching: false,
                    info: false,
                    ajax: {
                        'type': 'POST',
                        'datatype': 'JSON',
                        'url': '/api/dc/so/detail/seri/select/' + seri_id + '/' + data,
                        'headers': {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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

            var checkedAryBelumProses = [];
            $('#noseri_belum_proses_table').on('change', '#check_all', function() {
                var table = $('#noseri_belum_proses_table').DataTable();
                var rows = table.rows().nodes();
                checkedAryBelumProses = [];

                if ($(this).is(':checked')) {
                    $('.nosericheck', rows).prop('checked', true);
                    $('#cekbrg').prop('disabled', false);
                    $.each(rows, function() {
                        var dataObject = table.row(this).data();
                        checkedAryBelumProses.push(dataObject);
                    });
                } else {
                    $('.nosericheck', rows).prop('checked', false);
                    $('#cekbrg').prop('disabled', true);
                }
            });

            $('#noseri_belum_proses_table').on('click', '.nosericheck', function() {
                var table = $('#noseri_belum_proses_table').DataTable();
                var rows = table.rows().nodes();
                $('#noseri_belum_proses_table').find('#check_all').prop('checked', false);
                checkedAryBelumProses = [];

                if ($('.nosericheck:checked', rows).length > 0) {
                    $('#cekbrg').prop('disabled', false);
                    $.each($(".nosericheck:checked", rows), function() {
                        // Get the row index
                        var rowIndex = table.row($(this).closest('tr')).index();
                        // Get the data object for the row
                        var dataObject = table.row(rowIndex).data();
                        if (checkedAryBelumProses.findIndex(x => x.id === dataObject.id) === -1) {
                            checkedAryBelumProses.push(dataObject);
                        } else {
                            checkedAryBelumProses.splice(checkedAryBelumProses.findIndex(x => x
                                .id ===
                                dataObject.id), 1);
                        }
                    });
                } else if ($('.nosericheck:checked', rows).length <= 0) {
                    $('#cekbrg').prop('disabled', true);
                }
            });

            checkedArySelesaiProses = [];
            $('#noseri_selesai_proses_table').on('click', 'input[name="check_all"]', function() {
                var rows = $('#noseri_selesai_proses_table').DataTable().rows().nodes();
                checkedArySelesaiProses = [];
                if ($(this).is(':checked')) {
                    $('.nosericheck', rows).prop('checked', true);
                    $('#cekbrgedit').prop('disabled', false);
                    $.each(rows, function() {
                        var dataObject = $('#noseri_selesai_proses_table').DataTable().row(this)
                            .data();
                        checkedArySelesaiProses.push(dataObject);
                    });
                } else {
                    $('.nosericheck', rows).prop('checked', false);
                    $('#cekbrgedit').prop('disabled', true);
                }
            });

            $('#noseri_selesai_proses_table').on('click', '.nosericheck', function() {
                var rows = $('#noseri_selesai_proses_table').DataTable().rows().nodes();
                $('#noseri_selesai_proses_table').find('input[name="check_all"]').prop('checked', false);
                checkedArySelesaiProses = [];
                if ($('.nosericheck:checked', rows).length > 0) {
                    $('#cekbrgedit').prop('disabled', false);
                    $.each($(".nosericheck:checked", rows), function() {
                        // Get the row index
                        var rowIndex = $('#noseri_selesai_proses_table').DataTable().row($(this)
                            .closest(
                                'tr')).index();
                        // Get the data object for the row
                        var dataObject = $('#noseri_selesai_proses_table').DataTable().row(rowIndex)
                            .data();
                        if (checkedArySelesaiProses.findIndex(x => x.id === dataObject.id) === -1) {
                            checkedArySelesaiProses.push(dataObject);
                        } else {
                            checkedArySelesaiProses.splice(checkedArySelesaiProses.findIndex(x => x
                                .id ===
                                dataObject.id), 1);
                        }
                    });
                } else if ($('.nosericheck:checked', rows).length <= 0) {
                    $('#cekbrgedit').prop('disabled', true);
                }
            });

            $('#showtable').on('click', '.noserishow', function() {
                var data = $(this).attr('data-id');
                var datacount = $(this).attr('data-count');
                if (datacount == 0) {
                    // $('.sericheckbox').addClass("hide");
                    // $('.createmodal').addClass("hide");
                    // $('.editmodal').removeClass("hide");
                    // $('#noseri_belum_proses_table').DataTable().column(0).visible(false);
                } else {
                    // $('.createmodal').removeClass("hide");
                    // $('.editmodal').addClass("hide");
                    // $('#noseri_belum_proses_table').DataTable().column(0).visible(true);
                }
                $('#showtable').find('tr').removeClass('bgcolor');
                $(this).closest('tr').addClass('bgcolor');
                $('#noseri').removeClass('hide');
                $('#noseri_belum_proses_table').DataTable().ajax.url('/api/dc/so/detail/seri/' + data +
                    '/belum').load();
                $('#noseri_selesai_proses_table').DataTable().ajax.url('/api/dc/so/detail/seri/' + data +
                    '/selesai').load();
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
                    beforeSend: function() {
                        $('#btnsimpan').attr('disabled', true);
                        $('#loader').show();
                    },
                    success: function(response) {
                        //   alert(response);
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan Penambahan Data COO',
                                'success'
                            ).then(function() {
                                location.reload();
                            });
                            $('#cekbrg').attr("disabled", true);
                            $("#editmodal").modal('hide');
                            //$('#noseri_belum_proses_table').DataTable().ajax.reload();

                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan Penambahan Data COO',
                                'error'
                            );
                        }
                    },
                    complete: function() {
                        $('#btnsimpan').attr('disabled', false);
                        $('#loader').hide();
                    },
                    error: function(xhr, status, error) {
                        alert($('#form-create-coo').serialize());
                    }
                });
                return false;
            });

            $(document).on('submit', '#form-update-coo', function(e) {
                e.preventDefault();
                var action = $(this).attr('action');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: action,
                    data: $('#form-update-coo').serialize(),
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(response) {
                        if (response['data'] == "success") {
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan Penambahan Data Pengujian',
                                'success'
                            ).then(function() {
                                location.reload();
                            });
                            $("#editmodal").modal('hide');
                            $('#cekbrg').attr("disabled", true);
                            $('#noseri_belum_proses_table').DataTable().ajax.reload();
                            $('#noseri_selesai_proses_table').DataTable().ajax.reload();

                        } else if (response['data'] == "error") {
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan Penambahan Data Pengujian',
                                'error'
                            );
                        }
                    },
                    complete: function() {
                        $('#loader').hide();
                    },
                    error: function(xhr, status, error) {
                        alert($('#form-update-coo').serialize());
                    }
                });
                return false;
            });

            // $(document).on('click', '.createmodal', function(event) {
            //     event.preventDefault();
            //     var href = $(this).attr('data-attr');
            //     var id = $(this).data('id');
            //     $.ajax({
            //         url: "/dc/coo/create/" + id,
            //         beforeSend: function() {
            //             $('#loader').show();
            //         },
            //         // return the result
            //         success: function(result) {
            //             $('#createmodal').modal("show");
            //             $('#create').html(result).show();
            //             $('.bulan').select2({
            //                 placeholder: 'Pilih Bulan',
            //                 allowClear: true
            //             });
            //             // $("#editform").attr("action", href);
            //         },
            //         complete: function() {
            //             $('#loader').hide();
            //         },
            //         error: function(jqXHR, testStatus, error) {
            //             console.log(error);
            //             alert("Page " + href + " cannot open. Error:" + error);
            //             $('#loader').hide();
            //         },
            //         timeout: 8000
            //     })
            // });

            $(document).on('click', '.createmodal', function(event) {
                event.preventDefault();
                // select data datatable with class tr bgcolor
                var dataTableOld = $('#showtable').dataTable();
                var rows = dataTableOld.$('tr.bgcolor').closest('tr');
                var data = dataTableOld.fnGetData(rows[0]);

                let idSeri = []
                $.each(checkedAryBelumProses, function(key, value) {
                    idSeri.push(value.id);
                });
                $('#createmodal').modal("show");
                $('#create').html(`
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Info</h6>
                            </div>
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-5">
                                        <div><small class="text-muted">Nama Produk</small></div>
                                        <div><b>
                                                ${data?.nama_paket}
                                            </b></div>
                                    </div>
                                    <div class="col-4">
                                        <div><small class="text-muted">No AKD</small></div>
                                        <div><b>

                                                ${data?.no_akd == null || data?.no_akd == '' ? '-' : data?.no_akd}
                                            </b></div>
                                    </div>
                                    <div class="col-3">
                                        <div><small class="text-muted">Jumlah</small></div>
                                        <div><b>
                                            ${checkedAryBelumProses.length}
                                            </b></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-6">
                        <form id="form-create-coo" method="POST">
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id" value="${idSeri}">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Tambah</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-horizontal">
                                        <div class="form-group">
                                            <label for="" class="col-form-label">Diketahui Oleh</label>
                                            <div class="col-form-label d-flex justify-content-between">
                                                <div class="form-check form-check-inline ">
                                                    <input class="form-check-input" type="radio" name="diketahui" id="diketahui1" value="spa" />
                                                    <label class="form-check-label" for="diketahui1">PT Sinko Prima Alloy</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diketahui" id="diketahui1" value="emiindo" />
                                                    <label class="form-check-label" for="diketahui1">PT. EMIINDO Jaya Bersama</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="diketahui" id="diketahui2" value="custom" />
                                                    <label class="form-check-label" for="diketahui2">Custom</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row hide" id="nama_label">
                                            {{-- <div class="card" style="box-shadow:none;">
                                                <div class="card-body" style="background-color: #17a2b8">
                                                    <div class="row"> --}}
                                                        <div class="form-group col-6">
                                                            <label for="" class="col-form-label" style="text-align:right;">Nama</label>
                                                            <input type="text" class="form-control col-form-label" id="nama" name="nama">
                                                        </div>
                                                        <div class="form-group col-6">
                                                            <label for="" class="col-form-label" style="text-align:right;">Jabatan</label>
                                                            <input type="text" class="form-control col-form-label" id="jabatan" name="jabatan">
                                                        </div>
                                                    {{-- </div> --}}
                                                {{-- </div>
                                            </div> --}}
                                            {{-- <label for="" class="col-5 col-form-label" style="text-align:right;">Nama</label>
                                            <div class="col-5">
                                                <input type="text" class="form-control col-form-label" id="nama" name="nama">
                                            </div> --}}
                                        </div>
                                        {{-- <div class="form-group row hide" id="jabatan_label">
                                            <label for="" class="col-5 col-form-label" style="text-align:right;">Jabatan</label>
                                            <div class="col-5">
                                                <input type="text" class="form-control col-form-label" id="jabatan" name="jabatan">
                                            </div>
                                        </div> --}}
                                        <div class="form-group" for="tgl_kirim">
                                            <label for="" class="col-form-label" style="text-align:right;">Tgl Kirim</label>
                                            <input type="date" class="form-control col-form-label col-lg-4 col-md-6" name="tgl_kirim">
                                            <div class="feedback" id="msgpart_id">
                                                <small class="text-muted">*Boleh dikosongi jika tidak ada</small>
                                            </div>
                                        </div>
                                        {{-- <div class="form-group row" id="tgl_kirim">
                                            <label for="" class="col-5 col-form-label" style="text-align:right;">Tgl Kirim</label>
                                            <div class="col-5">
                                                <input type="date" class="form-control col-form-label" name="tgl_kirim">
                                                <div class="feedback" id="msgpart_id">
                                                    <small class="text-muted">*Boleh dikosongi jika tidak ada</small>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="form-group" for="keterangan">
                                            <label for="" class="col-form-label">Tanda Terima</label>
                                            <textarea class="form-control col-form-label" name="keterangan"></textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-footer">
                                    <span>
                                        <button class="btn btn-danger float-left" data-dismiss="modal">Batal</button>
                                    </span>
                                    <span>
                                        <button type="submit" class="btn btn-info float-right disabled" id="btnsimpan">Simpan</button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-5 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">List No Seri</h6>
                            </div>
                            <div class="card-body overflowy">
                                <div class="form-group">
                                    <div class="table-responsive">
                                        <table class="table" style="width: 100%; text-align:center;" id="listnoseribelum">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No Seri</th>
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
                </div>`).show();

                $('#listnoseribelum').dataTable({
                    data: checkedAryBelumProses,
                    columns: [{
                            data: 'no',
                            className: 'nowrap-text align-center',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'noseri',
                        }
                    ]
                })

                // send data /api/dc/so/store from form
                $(document).on('click', '#btnsimpan', function(event) {
                    event.preventDefault();
                    $.ajax({
                        url: "/api/dc/so/store",
                        beforeSend: function() {
                            $('#loader').show();
                        },
                        type: "POST",
                        data: $('#form-create-coo').serialize(),
                        success: function(result) {
                            console.log(result);
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan Penambahan Data COO',
                                'success'
                            ).then(function() {
                                location.reload();
                            });
                        },
                        complete: function() {
                            $('#loader').hide();
                        },
                        error: function(jqXHR, testStatus, error) {
                            console.log(error);
                            // alert("Page " + href + " cannot open. Error:" + error);
                            $('#loader').hide();
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan Penambahan Data COO',
                                'error'
                            );

                        },
                        timeout: 8000
                    })
                });

            });

            $(document).on('click', '.editmodal', function(event) {
                event.preventDefault();
                var dataTableOld = $('#showtable').dataTable();
                var rows = dataTableOld.$('tr.bgcolor').closest('tr');
                var data = dataTableOld.fnGetData(rows[0]);

                let idSeriSelesai = []
                $.each(checkedArySelesaiProses, function(key, value) {
                    idSeriSelesai.push(value.id);
                });

                $('#editmodal').modal("show");
                $('#edit').html(`<div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Info</h6>
                            </div>
                            <div class="card-body">
                                <div class="row d-flex justify-content-center">
                                    <div class="col-5">
                                        <div><small class="text-muted">Nama Produk</small></div>
                                        <div><b>${data?.nama_paket}</b></div>
                                    </div>
                                    <div class="col-4">
                                        <div><small class="text-muted">No AKD</small></div>
                                        <div><b>
                                                ${data?.no_akd == null || data?.no_akd == '' ? '-' : data?.no_akd}
                                            </b></div>
                                    </div>
                                    <div class="col-3">
                                        <div><small class="text-muted">Jumlah</small></div>
                                        <div><b>
                                            ${checkedArySelesaiProses.length}
                                            </b></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-6">
                        <form id="form-update-coo">
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="id" value="${idSeriSelesai}">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Ubah</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-horizontal">
                                        {{-- <div class="form-group row hide" id="jabatan_label">
                                            <label for="" class="col-5 col-form-label" style="text-align:right;">Jabatan</label>
                                            <div class="col-5">
                                                <input type="text" class="form-control col-form-label" id="jabatan" name="jabatan">
                                            </div>
                                        </div> --}}
                                        <div class="form-group" for="tgl_kirim">
                                            <label for="" class="col-form-label" style="text-align:right;">Tgl Kirim</label>
                                            <input type="date" class="form-control col-form-label col-lg-4 col-md-6" name="edit_tgl_kirim">
                                            <div class="feedback" id="msgpart_id">
                                                <small class="text-muted">*Boleh dikosongi jika tidak ada</small>
                                            </div>
                                        </div>
                                        {{-- <div class="form-group row" id="tgl_kirim">
                                            <label for="" class="col-5 col-form-label" style="text-align:right;">Tgl Kirim</label>
                                            <div class="col-5">
                                                <input type="date" class="form-control col-form-label" name="tgl_kirim">
                                                <div class="feedback" id="msgpart_id">
                                                    <small class="text-muted">*Boleh dikosongi jika tidak ada</small>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="form-group" for="keterangan">
                                            <label for="" class="col-form-label">Tanda Terima</label>
                                            <textarea class="form-control col-form-label" name="edit_keterangan"></textarea>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-footer">
                                    <span>
                                        <button class="btn btn-danger float-left" data-dismiss="modal">Batal</button>
                                    </span>
                                    <span>
                                        <button type="submit" class="btn btn-warning float-right" id="btnsimpanupdate">Simpan</button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-5 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">List No Seri</h6>
                            </div>
                            <div class="card-body overflowy">
                                <div class="form-group">
                                    <div class="table-responsive">
                                        <table class="table" style="width: 100%; text-align:center;" id="listnoseriselesai">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>No Seri</th>
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
                </div>`).show();


                $('#listnoseriselesai').dataTable({
                    data: checkedArySelesaiProses,
                    columns: [{
                            data: 'no',
                            className: 'nowrap-text align-center',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'noseri',
                        }
                    ]
                })

                // send data action="/api/dc/so/update"
                $(document).on('click', '#btnsimpanupdate', function(event) {
                    event.preventDefault();
                    $.ajax({
                        url: "/api/dc/so/update",
                        beforeSend: function() {
                            $('#loader').show();
                        },
                        type: "POST",
                        data: $('#form-update-coo').serialize(),
                        success: function(result) {
                            console.log(result);
                            swal.fire(
                                'Berhasil',
                                'Berhasil melakukan Perubahan Data COO',
                                'success'
                            ).then(function() {
                                location.reload();
                            });
                        },
                        complete: function() {
                            $('#loader').hide();
                        },
                        error: function(jqXHR, testStatus, error) {
                            console.log(error);
                            // alert("Page " + href + " cannot open. Error:" + error);
                            $('#loader').hide();
                            swal.fire(
                                'Gagal',
                                'Gagal melakukan Perubahan Data COO',
                                'error'
                            );

                        },
                        timeout: 8000
                    })
                });
            });

            $(document).on('click', '.tglkirim_modal', function(event) {
                var id = $(this).data('id');

                $.ajax({
                    url: "/dc/coo/edit_tglkirim/" + id,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    // return the result
                    success: function(result) {
                        $('#tglkirim_modal').modal("show");
                        $('#tglkirim_edit').html(result).show();
                        //  alert('response);

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
                if ($(this).val() != "" && (($('input[type="radio"][name="diketahui"]:checked').val() ==
                        "spa") || ($('#jabatan').val() != "" && $('#nama').val() != ""))) {
                    $('#btnsimpan').removeClass('disabled');
                } else {
                    $('#btnsimpan').addClass('disabled');
                }
            });

            $(document).on('change', 'input[type="radio"][name="diketahui"]', function() {
                $('#jabatan').val("");
                $('#nama').val("");
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
                    if ($('select[name="bulan"]').val() != "" && $('#jabatan').val() != "" && $('#nama')
                        .val() != "") {
                        $('#btnsimpan').removeClass('disabled');
                    } else {
                        $('#btnsimpan').addClass('disabled');
                    }
                }
            });

            $(document).on('keyup change', 'input[name="nama"]', function() {
                if ($(this).val() != "" && $('#jabatan').val() != "" && $('select[name="bulan"]').val() !=
                    "") {
                    $('#btnsimpan').removeClass('disabled');
                } else {
                    $('#btnsimpan').addClass('disabled');
                }
            });

            $(document).on('keyup change', 'input[name="jabatan"]', function() {
                if ($(this).val() != "" && $('#nama').val() != "" && $('select[name="bulan"]').val() !=
                    "") {
                    $('#btnsimpan').removeClass('disabled');
                } else {
                    $('#btnsimpan').addClass('disabled');
                }
            });

            $(document).on('click', '.buttonShowModalCOO', function(e) {
                e.preventDefault();

                var id = $(this).data('id');
                var value = $(this).data('value');
                var jenis = $(this).data('jenis');
                var stamp = $(this).data('stamp');
                //cek_rw
                var produk = $(this).data('produk');


                // implement to button cetak
                $('.cetakNoSeriCOO').data('id', id)
                $('.cetakNoSeriCOO').data('value', value)
                $('.cetakNoSeriCOO').data('jenis', jenis)
                $('.cetakNoSeriCOO').data('stamp', stamp)

                $('.tableNoSeriCetakCOO').DataTable()

                // create datatable with data
                $('.tableNoSeriCetakCOO').DataTable({
                    destroy: true,
                    ajax: {
                    'type': 'POST',
                    'datatype': 'JSON',
                    'url': '/api/dc/so/detail/seri/' + id + '/selesai',
                    'headers': {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                },
                    autoWidth: false,
                    columns: [{
                            data: 'id',
                            render: function(data, type, row) {
                                return `<input type="checkbox" name="" id="">`
                            }
                        },
                        {
                            data: 'noseri'
                        }
                    ]
                })
                $('#modalCetakCOO').modal('show');
            })

            $('#checkall').click(function() {
                if ($(this).is(':checked')) {
                    $('.tableNoSeriCetakCOO tbody tr td input[type="checkbox"]').prop('checked', true)
                } else {
                    $('.tableNoSeriCetakCOO tbody tr td input[type="checkbox"]').prop('checked', false)
                }
            })

            $(document).on('click', '.cetakNoSeriCOO', function(e) {
                e.preventDefault()

                var data = []

                var table = $('.tableNoSeriCetakCOO').DataTable()

                // get all checked checkbox id and push to array
                $('.tableNoSeriCetakCOO tbody tr td input[type="checkbox"]:checked').each(function() {
                    var row = table.row($(this).parents('tr'));
                    var rowData = row.data();
                    data.push(rowData.id);
                });

                console.log(data)

                var id = $(this).data('id');
                var value = $(this).data('value');
                var jenis = $(this).data('jenis');
                var stamp = $(this).data('stamp');

                // open link in new tab
                window.open(`/dc/coo/rework/pdf?id=${data}&produk=${id}&penjualan=${value}&jenis=${jenis}&stamp=${stamp}`)

            })

        })
    </script>

@endsection
